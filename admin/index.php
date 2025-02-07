<?php
    include 'db.php';

    // Get the number of overdue days from the request or default to 30
    $days = isset($_GET['days']) ? (int)$_GET['days'] : 30;

    // Query to get overdue repayments
    $sql_overdue = "SELECT 
                        borrowers.full_name AS borrower_name, 
                        loan_applications.loan_product, 
                        repayments.amount, 
                        repayments.repayment_date,repayments.paid
                    FROM 
                        repayments
                    INNER JOIN 
                        loan_applications ON repayments.loan_id = loan_applications.id
                    INNER JOIN 
                        borrowers ON loan_applications.borrower = borrowers.id
                    WHERE 
                        repayments.repayment_date < CURDATE() 
                        AND DATEDIFF(CURDATE(), repayments.repayment_date) > $days";

    $result_overdue = $conn->query($sql_overdue);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Microfinance</title>
    <link href="/assets/img/logo.png" rel="icon">
    <link href="/assets/img/logo.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Montserrat&family=Poppins&display=swap" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-metrics {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .metric {
            background-color: #ffffff;
            border: 1px solid #212529;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            flex: 1;
            margin: 0 10px;
        }
        .chart-container {
            width: 80%;
            margin: auto;
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
    <main class="main">
    <div class="container mt-5">
        <h1 class="text-center">Admin Dashboard</h1>
        
        <div class="dashboard-metrics">
            <div class="metric">
                <h2>KSH <?php echo number_format($total_overdue_amount, 2); ?></h2>
                <p>Total Areas</p>
            </div>
            <div class="metric">
                <h2>KSH <?php echo number_format($total_loan_amount, 2); ?></h2>
                <p>Total Disbursed Loans</p>
            </div>
            <div class="metric">
                <h2><?php echo number_format($par, 2); ?>%</h2>
                <p>Portfolio At Risk</p>
            </div>
        </div>

        <div class="chart-container mt-5">
            <canvas id="loanChart"></canvas>
        </div>
    </div>
    </main>

    <script>
        const ctx = document.getElementById('loanChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Disbursed Loans', 'Total Overdue', 'Portfolio At Risk'],
                datasets: [{
                    label: 'Financial Overview',
                    data: [<?php echo $total_loan_amount; ?>, <?php echo $total_overdue_amount; ?>, <?php echo $par; ?>],
                    backgroundColor: ['blue', 'red', 'orange']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>