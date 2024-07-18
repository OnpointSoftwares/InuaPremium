<?php
include_once("../includes/functions.php");

// Example function to fetch outstanding balances (replace with your actual implementation)

// Get the list of outstanding balances
$outstandingBalances = getOutstandingBalance();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Outstanding Balances - Inua Premium Services</title>
    <link href="/assets/img/logo.png" rel="icon">
    <link href="/assets/img/logo.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Montserrat&family=Poppins&display=swap" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* Additional or overridden styles specific to this page */
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
    </style>
</head>
<body class="admin-page">
    <!-- Header -->
    <?php
    include("includes/header.php");
    include("../includes/sidebar.php");
    ?>
    <!-- Sidebar -->

    <!-- Main Content -->
    <main class="main">
        <section id="outstanding-balance" class="outstanding-balance section">
            <div class="container">
                <h1>Outstanding Balances</h1>
                <div class="table-container">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Outstanding Amount</th>
                                <th>Last Payment Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($outstandingBalances)): ?>
                                <?php foreach ($outstandingBalances as $balance): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($balance['borrower_name']); ?></td>
                                        <td><?php echo htmlspecialchars($balance['amount']); ?></td>
                                        <td><?php echo htmlspecialchars($balance['due_date']); ?></td>
                                        <td><?php echo htmlspecialchars($balance['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No outstanding balances found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>
</html>
