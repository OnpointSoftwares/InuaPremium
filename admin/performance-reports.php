<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="/assets/img/logo.png" rel="icon">
    <link href="/assets/img/logo.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Montserrat&family=Poppins&display=swap" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            font-family: 'Open Sans', sans-serif;
            margin: 0;
        }

        .header {
            background-color: #e84545;
            color: #ffffff;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }

        .header .navmenu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .header .navmenu ul li {
            margin-right: 20px;
        }

        .header .navmenu ul li a {
            color: #ffffff;
            text-decoration: none;
        }

        .header .navmenu ul li a.active, .header .navmenu ul li a:hover {
            color: #e84545;
        }

        .sidebar {
            background-color: #ffffff;
            color: #3a3939;
            padding: 20px;
            width: 250px;
            position: fixed;
            height: 100%;
            overflow: auto;
        }

        .sidebar .nav-item .nav-link {
            color: #3a3939;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }

        .sidebar .nav-item .nav-link.active, .sidebar .nav-item .nav-link:hover {
            color: #e84545;
        }

        .main {
            margin-left: 270px;
            padding: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        .container h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .table thead th {
            background-color: #f5f5f5;
        }

        .overdue {
            background-color: #f8d7da;
        }

        table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: #fff;
        }

        table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        table tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        table tbody td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <?php 
    include '../includes/functions.php';
    include 'includes/header.php'; 
    ?>
    <div class="sidebar">
        <?php include '../includes/sidebar.php'; ?>
    </div>
    <?php
    include 'db.php';

    // Function to update loan status
    function updateLoanStatus($loan_id, $status) {
        global $conn;
        $sql = "UPDATE loan_applications SET loan_status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $loan_id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Check for approve or deny actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $loan_id = $_POST['loan_id'];
        $action = $_POST['action'];
        $status = ($action === 'approve') ? 'approved' : 'denied';

        if (updateLoanStatus($loan_id, $status)) {
            echo "<div class='alert alert-success'>Loan $action successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to $action the loan.</div>";
        }
    }

    // Function to get loans
    function getLoans() {
        global $conn;
        $loans = array();

        $sql = "SELECT 
                    l.id, 
                    b.full_name AS borrower_name, 
                    p.name AS loan_product_name, 
                    l.principal, 
                    l.interest, 
                    l.interest_method, 
                    l.loan_interest, 
                    l.loan_duration, 
                    l.repayment_cycle, 
                    l.number_of_repayments, 
                    l.processing_fee, 
                    l.registration_fee, 
                    l.total_amount, 
                    l.loan_release_date,
                    l.loan_status
                FROM loan_applications l 
                INNER JOIN borrowers b ON l.borrower = b.id 
                INNER JOIN loan_products p ON l.loan_product = p.id";

        $result = $conn->query($sql);

        if ($result === FALSE) {
            echo "Error: " . $conn->error;
            return $loans;
        }

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $loans[] = $row;
            }
        } else {
            echo "No records found.";
        }

        return $loans;
    }
    
    $loans = getLoans();

    $sql_due = "SELECT 
                borrowers.full_name AS borrower_name, 
                loan_applications.loan_product, 
                SUM(repayments.amount) AS total_amount_due, 
                repayments.repayment_date
            FROM 
                repayments
            INNER JOIN 
                loan_applications ON repayments.loan_id = loan_applications.id
            INNER JOIN 
                borrowers ON loan_applications.borrower = borrowers.id
            WHERE 
                repayments.repayment_date >= CURDATE()
            GROUP BY 
                repayments.loan_id, 
                borrowers.full_name, 
                loan_applications.loan_product, 
                repayments.repayment_date";
    $result_due = $conn->query($sql_due);

    $sql_overdue = "SELECT 
                        borrowers.full_name AS borrower_name, 
                        loan_applications.loan_product, 
                        repayments.amount, 
                        repayments.repayment_date
                    FROM 
                        repayments
                    INNER JOIN 
                        loan_applications ON repayments.loan_id = loan_applications.id
                    INNER JOIN 
                        borrowers ON loan_applications.borrower= borrowers.id
                    WHERE 
                        repayments.repayment_date < CURDATE()";

    $result_overdue = $conn->query($sql_overdue);
    ?>
    <main class="main">
        <section class="section">
            <div class="container">
                <h1>Reports</h1>
                
                <form method="post" action="export_reports.php">
                    <button type="submit" name="export_loans" class="btn btn-primary mb-3">Export Loans to PDF</button>
                    <button type="submit" name="export_due" class="btn btn-primary mb-3">Export Due Repayments to PDF</button>
                    <button type="submit" name="export_overdue" class="btn btn-primary mb-3">Export Overdue Repayments to PDF</button>
                </form>
                
                <h2>Loan Applications</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Borrower</th>
                            <th>Loan Product</th>
                            <th>Principal</th>
                            <th>Interest</th>
                            <th>Interest Method</th>
                            <th>Loan Interest %</th>
                            <th>Duration (months)</th>
                            <th>Repayment Cycle</th>
                            <th>Number of Repayments</th>
                            <th>Processing Fee %</th>
                            <th>Registration Fee %</th>
                            <th>Total Amount</th>
                            <th>Loan Release Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($loans) > 0) {
                            foreach ($loans as $loan) {
                                echo "<tr>
                                    <td>{$loan['id']}</td>
                                    <td>{$loan['borrower_name']}</td>
                                    <td>{$loan['loan_product_name']}</td>
                                    <td>{$loan['principal']}</td>
                                    <td>{$loan['interest']}</td>
                                    <td>{$loan['interest_method']}</td>
                                    <td>{$loan['loan_interest']}</td>
                                    <td>{$loan['loan_duration']}</td>
                                    <td>{$loan['repayment_cycle']}</td>
                                    <td>{$loan['number_of_repayments']}</td>
                                    <td>{$loan['processing_fee']}</td>
                                    <td>{$loan['registration_fee']}</td>
                                    <td>{$loan['total_amount']}</td>
                                    <td>{$loan['loan_release_date']}</td>
                                    <td>{$loan['loan_status']}</td>
                                    <td>
                                        <form method='post' style='display:inline'>
                                            <input type='hidden' name='loan_id' value='{$loan['id']}'>
                                            <input type='hidden' name='action' value='approve'>
                                            <button type='submit' class='btn btn-success btn-sm'>Approve</button>
                                        </form>
                                        <form method='post' style='display:inline'>
                                            <input type='hidden' name='loan_id' value='{$loan['id']}'>
                                            <input type='hidden' name='action' value='deny'>
                                            <button type='submit' class='btn btn-danger btn-sm'>Deny</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='16'>No loan applications found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="container mt-4">
                <h2>Due Repayments</h2>
                <div class="table-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Borrower Name</th>
                                <th>Loan Product</th>
                                <th>Total Amount Due</th>
                                <th>Repayment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_due && $result_due->num_rows > 0) {
                                while ($row_due = $result_due->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row_due['borrower_name']}</td>
                                        <td>{$row_due['loan_product']}</td>
                                        <td>{$row_due['total_amount_due']}</td>
                                        <td>{$row_due['repayment_date']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No due repayments found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="container mt-4">
                <h2>Overdue Repayments</h2>
                <div class="table-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Borrower Name</th>
                                <th>Loan Product</th>
                                <th>Amount</th>
                                <th>Repayment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_overdue && $result_overdue->num_rows > 0) {
                                while ($row_overdue = $result_overdue->fetch_assoc()) {
                                    echo "<tr class='overdue'>
                                        <td>{$row_overdue['borrower_name']}</td>
                                        <td>{$row_overdue['loan_product']}</td>
                                        <td>{$row_overdue['amount']}</td>
                                        <td>{$row_overdue['repayment_date']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No overdue repayments found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
