<?php
// Database connection
function db_connect() {
    $host = 'localhost';
    $db = 'microfinance';
    $user = 'root';
    $pass = '';
    try {
        return new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

// User management functions
function add_user($name, $email, $password, $role) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
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
    $stmt = $conn->query("SELECT SUM(amount) as total_amount FROM loans WHERE status = 'Disbursed' AND due_date < NOW()");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_amount'];
}
?>
