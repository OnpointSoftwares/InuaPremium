<?php
session_start();
include_once("../includes/functions.php");

if (isset($_GET['logout'])) {
    logout();
    echo "<script>location.replace('../index.html');</script>";
    exit();
}

$totalAreas = getTotalAreas();
$totalDisbursedLoans = getTotalDisbursedLoans();
$portfolioAtRisk =calculate_portfolio_at_risk($days_overdue = 30, $loanOfficer = null);
?>
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

<body class="admin-page">

    <!-- End Header -->
<?php
include("includes/header.php");
?>
    <!-- ======= Sidebar ======= -->
   <?php
   include("../includes/sidebar.php");

   ?>
    <!-- ======= Main ======= -->
    <main class="main">
        <section id="admin-dashboard" class="admin-dashboard section">
            <div class="container">
                <h1>Admin Dashboard</h1>
                <div class="dashboard-metrics">
                    <div class="metric">
                        <h2><?php echo $totalAreas; ?></h2>
                        <p>Total Areas</p>
                    </div>
                    <div class="metric">
                        <h2><?php echo $totalDisbursedLoans; ?></h2>
                        <p>Total Disbursed Loans</p>
                    </div>
                    <div class="metric">
                        <h2><?php echo $portfolioAtRisk; ?></h2>
                        <p>Portfolio at Risk</p>
                    </div>
                </div>
            </div>
        </section><!-- End Admin Dashboard Section -->
    </main><!-- End Main -->

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>
