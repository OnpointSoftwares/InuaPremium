<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin Dashboard - Microfinance</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <style>
        :root {
            --background-color: #ffffff;
            --default-color: #212529;
            --heading-color: #32353a;
            --accent-color: #e84545;
            --surface-color: #ffffff;
            --contrast-color: #ffffff;
            --nav-color: #3a3939;
            --nav-hover-color: #e84545;
            --nav-mobile-background-color: #ffffff;
            --nav-dropdown-background-color: #ffffff;
            --nav-dropdown-color: #3a3939;
            --nav-dropdown-hover-color: #e84545;
        }

        body {
            background-color: var(--background-color);
            color: var(--default-color);
            font-family: 'Open Sans', sans-serif;
            margin: 0;
        }

        .header {
            background-color: var(--accent-color);
            color: var(--contrast-color);
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo h1 {
            color: var(--contrast-color);
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
            color: var(--contrast-color);
            text-decoration: none;
        }

        .header .navmenu ul li a.active, .header .navmenu ul li a:hover {
            color: var(--nav-hover-color);
        }

        .sidebar {
            background-color: var(--nav-mobile-background-color);
            color: var(--nav-color);
            padding: 20px;
            width: 250px;
            position: fixed;
            height: 100%;
            overflow: auto;
        }

        .sidebar .nav-item .nav-link {
            color: var(--nav-color);
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }

        .sidebar .nav-item .nav-link.active, .sidebar .nav-item .nav-link:hover {
            color: var(--nav-hover-color);
        }

        .main {
            margin-left: 270px;
            padding: 20px;
        }

        .dashboard-metrics {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .metric {
            background-color: var(--surface-color);
            border: 1px solid var(--default-color);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            flex: 1;
            margin: 0 10px;
        }

        .metric h2 {
            margin: 0;
            font-size: 2em;
        }

        .metric p {
            margin: 5px 0 0;
        }
        #admin-dashboard{
            float:right;
        }
    </style>

    <!-- Favicons -->
    <link href="/assets/img/logo.png" rel="icon">
    <link href="/assets/img/logo.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Montserrat&family=Poppins&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php
include '../includes/functions.php';
include 'includes/header.php';
include '../includes/sidebar.php';
?>
<main class="main">
        <section id="admin-dashboard" class="admin-dashboard section">
            <div class="container">
    <h3>Settings</h3>
    <ul>
        <li><a href="">Account Settings</a></li>
        <li><a href="">Custom Fields</a></li>
        <li><a href="">API Settings</a></li>
        <li><a href="">Webform builder and API</a></li>
    </ul>
    <h3>Manage Staff</h3>
    <ul>
    <li><a href="staff.php">Staff</a></li>
    <li><a href="">Staff role and permission</a></li>
    <li><a href="">Staff email notifications</a></li>
    <li><a href="">Audit management</a></li>
    </ul>
    <h3>Loan</h3>
    <ul>
        <li><a href="">Loan products</a></li>
        <li><a href="">Loan disbursed by</a></li>
        <li><a href="">Loan penalty settings</a></li>
        <li><a href="">Loan fees</a></li>
        <li><a href="">Loan repayment cycle</a></li>
        <li><a href="">Loan reminder settings</a></li>
        <li><a href="">Loan templates:applications/agreements</a></li>
        <li><a href="">Bulk update loan schedules</a></li>
        <li><a href="">Buld update loans with automated payments</a></li>
        <li><a href="">Bulk Loans with extend after maturity</a></li>
        <li><a href="">Manage loan status and approval</a></li>
        <li><a href="">Adjust Interest rates and in middle</a></li>
        <li><a href="">Send OTP to borrowers before disbursement</a></li>
    </ul>
    <h3>Manage Branch</h3>
    <ul>
    <li><a href="">Branches</a></li>
    <li><a href="">Branch holidays</a></li>
    </ul>
    <h3>Borrowers</h3>
    <li><a href="">Download statement/schedule</a></li>
    <li><a href="">Format Borrowers reports</a></li>
    <li><a href="">Rename Borrowers reports</a></li>
    <li><a href="">Manage Credit officers</a></li>
    <li><a href="">Invite Borrowers Settings</a></li>
    <li><a href="">Buld Update Borrowers with loan officers</a></li>
    <li><a href="">Bulk move Borrowers to another Branch</a></li>
    <li><a href="">Rename Borrowers Collection Sheets headings</a></li>
    <h3>Repayments</h3>
    <ul>
<li><a href="">Loans repayment methods</a></li>
<li><a href="">Manage Collectors</a></li>
    </ul>
    <h3>Collateral</h3>
    <ul>
        <li><a href="">Collateral Types</a></li>
    </ul>
    <h3>Payroll</h3>
    <ul><li>
    <a href="">Payroll templates</a>
    </li></ul>
    <h3>Bulk Upload</h3>
    <ul>
        <li><a href="">Upload Borrowers From csv file</a></li>
        <li><a href="">Upload Loans From csv file</a></li>
        <li><a href="">Upload repayments from csv file</a></li>
        <li><a href="">Upload Expenses from csv file</a></li>
        <li><a href="">Upload other income from csv file</a></li>
        <li><a href="">Upload savings account from csv file</a></li>
        <li><a href="">Upload savings transactions from csv file</a></li>
        <li><a href=""></a>Upload Loans Schedule From csv file</li>
        <li><a href="">Upload Inter-Bank Transfer from csv file</a></li>
    </ul>
    <h3>Other Income</h3>
    <ul>
        <li><a href="">Other income types</a></li>
    </ul>
    <h3>Expenses</h3>
    <ul>
        <li><a href="">Expense Types</a></li>
    </ul>
    <h3>Asset Management</h3>
    <ul>
        <li><a href="">Asset Managment Types</a></li>
    </ul>
    <h3>SMS Settings</h3>
    <ul>
        <li><a href="">SMS credits</a></li>
        <li><a href="">Sender Id</a></li>
        <li><a href="">SMS Templates</a></li>
        <li><a href="">Auto Send SMS</a></li>
        <li><a href="">Collection Sheets SMS Templates</a></li>
        <li><a href="">SMS Logs</a></li>
    </ul>
    <h3>Email Settings</h3>
    <ul>
        <li><a href="">Email Accounts</a></li>
        <li><a href="">Email Templates</a></li>
        <li><a href="">Auto Send Emails</a></li>
        <li><a href="">Collection Sheets Email Templates</a></li>
        <li><a href="">Email Logs</a></li>
    </ul>
    <h3>Savings</h3>
    <ul>
        <li><a href="">Savings Products</a></li>
        <li><a href="">Savings fee</a></li>
        <li><a href="">Savings Transactions</a></li>
    </ul>
    </div>
</section>
    </main>
    </body>
    </html>