<?php
// Include the database connection
include '../includes/functions.php'; // Make sure this file contains the db_connect function

// Function to get all branches from the database

// Fetch branches
$branches = getBranches();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branches</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Branches</h1>
    <a href="index.php">Back to Admin</a>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Loan Conditions</th>
                <th>Branch Capital (KSh)</th>
                <th>Created</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($branches)): ?>
                <?php foreach ($branches as $branch): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($branch['name']); ?></td>
                        <td><?php echo htmlspecialchars($branch['phone']); ?></td>
                        <td>
                            Min Loan Amount: <?php echo htmlspecialchars($branch['min_loan_amount']); ?><br>
                            Max Loan Amount: <?php echo htmlspecialchars($branch['max_loan_amount']); ?><br>
                            Min Interest Rate: <?php echo htmlspecialchars($branch['min_interest_rate']); ?><br>
                            Max Interest Rate: <?php echo htmlspecialchars($branch['max_interest_rate']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($branch['branch_capital']); ?></td>
                        <td><?php echo htmlspecialchars($branch['created']); ?></td>
                        <td><?php echo htmlspecialchars($branch['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No branches found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
