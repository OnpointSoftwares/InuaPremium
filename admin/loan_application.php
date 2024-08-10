<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Loan Application</title>
    <link href="/assets/img/logo.png" rel="icon">
    <link href="/assets/img/logo.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Montserrat&family=Poppins&display=swap" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .custom-form-section {
            margin-bottom: 30px;
        }

        .custom-form-section h2 {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }
        body {
            background-color: #ffffff;
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

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group button {
            background-color: #e84545;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #d73434;
        }

        .section {
            padding: 20px 0;
        }

        .form-group small {
            display: block;
            margin-top: 5px;
            color: #6c757d;
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
    
    $sql = "SELECT * FROM borrowers";
    $result = $conn->query($sql);
                
    $loanProducts = getLoanProducts();
    ?>
    <main class="main">
        <section class="section">
            <div class="container">
                <form action="submit_loan_application.php" method="POST" id="loanForm">
                    <!-- Loan Product -->
                    <div class="form-group">
                        <label for="borrower">Borrower</label>
                    <select class="form-control" name="borrower" id="borrower">
                    <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo " <option value=".$row["id"].">".$row["full_name"]."</option>";
            }
        } else {
            echo "<option>No borrowers found</option>";
        }
        ?>
        </select>
    </div>
                    <div class="form-group">
                        <label for="loanProduct">Loan Product</label>
                        <select class="form-control" id="loanProduct" name="loan_product" required>
                            <?php
                            foreach ($loanProducts as $product) {
                                echo "<option value={$product['id']}>{$product['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="principal">Principal</label>
                        <input type="number" class="form-control" id="principal" name="principal" required>
                    </div>
                    <div class="form-group">
                        <label for="loanReleaseDate">Loan Release Date</label>
                        <input type="date" class="form-control" id="loanReleaseDate" name="loan_release_date" required>
                    </div>
                    <div class="form-group">
                        <label for="interest">Interest</label>
                        <input type="number" class="form-control" id="interest" name="interest" required>
                    </div>
                    <div class="form-group">
                        <label for="interestMethod">Interest Method</label>
                        <select class="form-control" id="interestMethod" name="interest_method" required>
                            <option value="flat_rate">Flat Rate</option>
                            <option value="percentage">Percentage</option>
                            <option value="fixed_amount">Fixed Amount Per Cycle</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="interestCalculation">Interest Calculation</label>
                        <select class="form-control" id="interestCalculation" name="interest_calculation" required>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="loanInterestPercentage">Loan Interest %</label>
                        <input type="number" class="form-control" id="loanInterestPercentage" name="loan_interest_percentage" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="loanDuration">Loan Duration</label>
                        <input type="number" class="form-control" id="loanDuration" name="loan_duration" required>
                        <select class="form-control mt-2" id="loanDurationUnit" name="loan_duration_unit" required>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="repaymentCycle">Repayment Cycle</label>
                        <select class="form-control" id="repaymentCycle" name="repayment_cycle" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="numberOfRepayments">Number of Repayments</label>
                        <input type="number" class="form-control" id="numberOfRepayments" name="number_of_repayments" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="processingFee">Processing Fee %</label>
                        <input type="number" class="form-control" id="processingFee" name="processing_fee" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="registrationFee">Registration Fee %</label>
                        <input type="number" class="form-control" id="registrationFee" name="registration_fee" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="totalAmount">Total Amount</label>
                        <input type="number" class="form-control" id="totalAmount" name="total_amount" readonly>
                    </div>
                    <div class="form-group">
                        <label for="repaymentAmount">Repayment Amount Per Cycle</label>
                        <input type="number" class="form-control" id="repaymentAmount" name="repayment_amount" readonly>
                    </div>
                    <div class="form-group">
                    <label for="idPhoto">Upload ID Photo</label>
                    <input type="file" class="form-control" id="idPhoto" name="photo" accept="image/*" required>
                    <small class="form-text text-muted">Please upload a clear photo of your ID.</small>
                </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    
    <script>
        document.getElementById('loanForm').addEventListener('input', calculateLoanDetails);

        function calculateLoanDetails() {
            var principal = parseFloat(document.getElementById('principal').value) || 0;
            var loanDuration = parseFloat(document.getElementById('loanDuration').value) || 0;
            var loanDurationUnit = document.getElementById('loanDurationUnit').value;
            var interest = parseFloat(document.getElementById('interest').value) || 0;
            var interestMethod = document.getElementById('interestMethod').value;
            var interestCalculation = document.getElementById('interestCalculation').value;
            var processingFee = parseFloat(document.getElementById('processingFee').value) || 0;
            var registrationFee = parseFloat(document.getElementById('registrationFee').value) || 0;
            var repaymentCycle = document.getElementById('repaymentCycle').value;
            var loanInterestPercentage = parseFloat(document.getElementById('loanInterestPercentage').value) || 0;

            var durationInWeeks = 0;

            // Convert loan duration to weeks
            switch (loanDurationUnit) {
                case 'days':
                    durationInWeeks = loanDuration / 7;
                    break;
                case 'weeks':
                    durationInWeeks = loanDuration;
                    break;
                case 'months':
                    durationInWeeks = loanDuration * 4;
                    break;
                case 'years':
                    durationInWeeks = loanDuration * 52;
                    break;
            }

            var numberOfRepayments = 0;
            switch (repaymentCycle) {
                case 'daily':
                    numberOfRepayments = durationInWeeks * 7;
                    break;
                case 'weekly':
                    numberOfRepayments = durationInWeeks;
                    break;
                case 'monthly':
                    numberOfRepayments = durationInWeeks / 4;
                    break;
                case 'yearly':
                    numberOfRepayments = durationInWeeks / 52;
                    break;
            }

            // Calculate interest based on method and cycle
            var totalInterest = 0;
            switch (interestMethod) {
                case 'flat_rate':
                    totalInterest = (principal * loanInterestPercentage * loanDuration) / 100;
                    break;
                case 'percentage':
                    switch (interestCalculation) {
                        case 'weekly':
                            totalInterest = (principal * (interest / 100)) * durationInWeeks;
                            break;
                        case 'monthly':
                            totalInterest = (principal * (interest / 100)) * (durationInWeeks / 4);
                            break;
                        case 'yearly':
                            totalInterest = (principal * (interest / 100)) * (durationInWeeks / 52);
                            break;
                    }
                    break;
                case 'fixed_amount':
                    totalInterest = interest * numberOfRepayments;
                    break;
            }

            var totalAmount = principal + totalInterest + processingFee + registrationFee;
            var repaymentAmount = totalAmount / numberOfRepayments;

            // Set the calculated values in the form
            document.getElementById('numberOfRepayments').value = numberOfRepayments.toFixed(2);
            document.getElementById('totalAmount').value = totalAmount.toFixed(2);
            document.getElementById('repaymentAmount').value = repaymentAmount.toFixed(2);
        }
    </script>
</body>
</html>
