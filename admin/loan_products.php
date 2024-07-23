<?php 
include '../includes/functions.php';
$loanProducts=getLoanProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Loan Products</title>
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
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
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
            top: 60px; /* Height of header */
            left: 0;
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
            margin-top: 70px; /* Space for header */
        }
        .table-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #e84545;
            color: #ffffff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn-primary {
            background-color: #e84545;
            border: none;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-primary:hover {
            background-color: #d73434;
        }
        .btn-action {
            margin: 0;
            padding: 0;
            border: none;
            background: none;
            cursor: pointer;
            color: #e84545;
        }
        .btn-action:hover {
            color: #d73434;
        }
        .search-container {
            margin-bottom: 20px;
        }
        .search-container input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Header and Sidebar -->
    <div class="header">
        <div class="logo">
            <h1>Loan Management</h1>
        </div>
    </div>
<?php 
include 'includes/header.php'; ?>
    <div class="sidebar">
        <?php include '../includes/sidebar.php'; ?>
    </div>

    <main class="main">
        <div class="container">
            <div class="table-container">
                <h2>Manage Loan Products</h2>
                <div class="search-container">
                    <input type="text" placeholder="Search...">
                </div>
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addLoanProductModal">Add Loan Product</button>
                <br>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Branch Access</th>
                            <th>Penalty Settings</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            foreach($loanProducts as $product)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php  echo $product['name']; ?></td>
                                            <td><?php  echo $product['branch_access']; ?></td>
                                            <td><?php  echo $product['penalty_settings']; ?></td>
                                            <td><?php  echo $product['status']; ?></td>
                                            
                                            <td><button class="btn-action">Edit</button></td>
                                        </tr>
                                        <?php
                                    }
                        ?>
                        
                       
                         
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="addLoanProductModal" tabindex="-1" aria-labelledby="addLoanProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLoanProductModalLabel">Add Staff</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="add_staff.php" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="officerName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="officerName" name="officerName" required>
                            </div>
                            <div class="mb-3">
                                <label for="officerEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="officerEmail" name="officerEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="officerPhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="officerPhone" name="officerPhone" required>
                            </div>
                            <div class="mb-3">
                                <label for="officerPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="officerPassword" name="officerPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="areaId" class="form-label">Area</label>
                                <select class="form-select" id="areaId" name="areaId" required>
                                    <option value="">Select Area</option>
                                    <?php foreach ($areas as $area): ?>
                                        <option value="<?php echo htmlspecialchars($area['area_id']); ?>"><?php echo htmlspecialchars($area['area_id']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="roleId" class="form-label">Role</label>
                                <select class="form-select" id="roleId" name="roleId" required>
                                    <option value="">Select Role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo htmlspecialchars($role['id']); ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="addStaff">Add Staff</button>
                        </div>
                    </form>
                </div>
            </div>
    </main>
</body>
</html>
