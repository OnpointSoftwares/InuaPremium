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
include 'includes/header.php'; ?>
    <div class="sidebar">
        <?php include '../includes/sidebar.php'; ?>
    </div>
    <?php
    $loanProducts = getLoanProducts();
    ?>
    <main class="main">
        <section class="section">
            <div class="container">
<div class="container mt-5">
               <form action="submit_loan_application.php" method="POST">
                <!-- Loan Product -->
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
                    <input type="number" class="form-control" id="principal" name="principal_amount" required>
                </div>
                <div class="form-group">
                    <label for="loanReleaseDate">Loan Release Date</label>
                    <input type="date" class="form-control" id="loanReleaseDate" name="loan_release_date" required>
                </div>
                <div class="form-group">
                    <label for="interest">Interest</label>
                    <input type="number" class="form-control" id="interest" name="interest_amount" required>
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
                    <label for="loanInterestPercentage">Loan Interest %</label>
                    <input type="number" class="form-control" id="loanInterestPercentage" name="loan_interest_percentage" step="0.01">
                </div>
                <div class="form-group">
                    <label for="loanDuration">Loan Duration (months)</label>
                    <input type="number" class="form-control" id="loanDuration" name="loan_duration" required>
                </div>
                <div class="form-group">
                    <label for="repaymentCycle">Repayment Cycle</label>
                    <select class="form-control" id="repaymentCycle" name="repayment_cycle" required>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="annually">Annually</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="numberOfRepayments">Number of Repayments</label>
                    <input type="number" class="form-control" id="numberOfRepayments" name="number_of_repayments" required>
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
                    <label for="loanStatus">Loan Status</label>
                    <select class="form-control" id="loanStatus" name="loan_status" required>
                        <option value="open">Open</option>
                        <!-- Add more options if needed -->
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Submit Loan Application</button>
                </div>
            </form>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
