<?php
//session_start();

// Database connection
function db_connect() {
    $host = 'localhost';
    $db = 'microfinance';
    $user = 'root';
    $pass = '';
    try {
        return new PDO("mysql:host=$host;dbname=$db", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }
}

// User management functions
function add_user($name, $email, $password, $role, $area,$phone) {
    $conn = db_connect();
    if ($role == "loanOfficer") {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role_id, area,phone) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role, $area,$phone]);
    }
}

function login($email, $password, $role) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND role = :role");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $email;
        return '1,' . $role; // Success
    } else {
        return '0'; // Failure
    }
}

function update_user($id, $name, $email, $password) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
    return $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $id]);
}

function delete_user($id) {
    $conn = db_connect();
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}

// Loan management functions
function apply_loan($client_id, $amount, $term, $interest_rate) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO loans (client_id, amount, term, interest_rate, status) VALUES (?, ?, ?, ?, 'Pending')");
    return $stmt->execute([$client_id, $amount, $term, $interest_rate]);
}

function approve_loan($loan_id) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE loans SET status = 'Approved' WHERE id = ?");
    return $stmt->execute([$loan_id]);
}

function reject_loan($loan_id) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE loans SET status = 'Rejected' WHERE id = ?");
    return $stmt->execute([$loan_id]);
}

function disburse_loan($loan_id) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE loans SET status = 'Disbursed' WHERE id = ?");
    return $stmt->execute([$loan_id]);
}

function track_repayment($loan_id, $amount) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO repayments (loan_id, amount) VALUES (?, ?)");
    return $stmt->execute([$loan_id, $amount]);
}

// Portfolio at risk calculation
function calculate_portfolio_at_risk() {
    $conn = db_connect();
    // Assumes 'due_date' and 'amount' columns exist in 'loans' table
    $stmt = $conn->query("SELECT SUM(amount) AS at_risk_amount FROM loans WHERE status = 'Disbursed' AND due_date < NOW()");
    $at_risk_amount = $stmt->fetchColumn();

    $stmt = $conn->query("SELECT SUM(amount) AS total_amount FROM loans WHERE status = 'Disbursed'");
    $total_amount = $stmt->fetchColumn();

    if ($total_amount > 0) {
        return ($at_risk_amount / $total_amount) * 100;
    }
    return 0;
}

// Portfolio at risk calculation for loan officer
function calculate_portfolio_at_risk_for_user($loanOfficer) {
    $conn = db_connect();
    // Assumes 'due_date', 'amount', and 'loan_officer' columns exist in 'loans' table
    $stmt = $conn->prepare("SELECT SUM(amount) AS at_risk_amount FROM loans WHERE status = 'Disbursed' AND loan_officer = ? AND due_date < NOW()");
    $stmt->execute([$loanOfficer]);
    $at_risk_amount = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT SUM(amount) AS total_amount FROM loans WHERE status = 'Disbursed' AND loan_officer = ?");
    $stmt->execute([$loanOfficer]);
    $total_amount = $stmt->fetchColumn();

    if ($total_amount > 0) {
        return ($at_risk_amount / $total_amount) * 100;
    }
    return 0;
}

function logout() {
    session_destroy();
}

// Placeholder functions for demo purposes
function getTotalAreas() {
    // Fetch total areas from the database
    return 50; // Example value
}

function getTotalDisbursedLoans() {
    // Fetch total disbursed loans from the database
    return 1000000; // Example value
}

function getPortfolioAtRisk() {
    // Fetch portfolio at risk from the database
    return 20000; // Example value
}

function getDisbursedLoans() {
    // Example of a database query to fetch disbursed loans
    $query = "SELECT loan_id, borrower_name, amount, disbursement_date, status FROM disbursed_loans";
    $pdo = db_connect();

    try {
        $stmt = $pdo->query($query);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}

function getOutstandingBalance() {
    $query = "SELECT loan_id, borrower_name, amount, due_date, status FROM outstanding_balance";
    $pdo = db_connect();

    try {
        $stmt = $pdo->query($query);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
    // Portfolio at risk calculation
function calculate_portfolio_at_risk($days_overdue = 30) {
    $conn = db_connect();

    // Calculate the sum of overdue loans
    $stmt = $conn->prepare("
        SELECT SUM(amount) AS at_risk_amount
        FROM loans
        WHERE status = 'Disbursed' AND due_date < DATE_SUB(NOW(), INTERVAL ? DAY)
    ");
    $stmt->execute([$days_overdue]);
    $at_risk_amount = $stmt->fetchColumn();

    // Calculate the total disbursed loan amount
    $stmt = $conn->query("
        SELECT SUM(amount) AS total_amount
        FROM loans
        WHERE status = 'Disbursed'
    ");
    $total_amount = $stmt->fetchColumn();

    if ($total_amount > 0) {
        return ($at_risk_amount / $total_amount) * 100;
    }
    return 0;
}

// Portfolio at risk calculation for loan officer
function calculate_portfolio_at_risk_for_user($loanOfficer, $days_overdue = 30) {
    $conn = db_connect();

    // Calculate the sum of overdue loans for a specific loan officer
    $stmt = $conn->prepare("
        SELECT SUM(amount) AS at_risk_amount
        FROM loans
        WHERE status = 'Disbursed' AND loan_officer = ? AND due_date < DATE_SUB(NOW(), INTERVAL ? DAY)
    ");
    $stmt->execute([$loanOfficer, $days_overdue]);
    $at_risk_amount = $stmt->fetchColumn();

    // Calculate the total disbursed loan amount for a specific loan officer
    $stmt = $conn->prepare("
        SELECT SUM(amount) AS total_amount
        FROM loans
        WHERE status = 'Disbursed' AND loan_officer = ?
    ");
    $stmt->execute([$loanOfficer]);
    $total_amount = $stmt->fetchColumn();

    if ($total_amount > 0) {
        return ($at_risk_amount / $total_amount) * 100;
    }
    return 0;
}

}
include 'config.php';

function getUserRole($userId) {
    global $conn;
    $sql = "SELECT role_id FROM users WHERE id = $userId";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['role_id'];
}

function getNavigationItems($roleId) {
    global $conn;
    $sql = "SELECT ni.id, ni.title, ni.url, ni.icon, ni.parent_id 
            FROM navigation_items ni
            JOIN navigation_item_roles nir ON ni.id = nir.navigation_item_id
            WHERE nir.role_id = $roleId";
    $result = $conn->query($sql);
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}

?>
