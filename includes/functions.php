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
function add_user($name, $email, $password, $role,$loanOfficer) {
    if($role=="loanOfficer")
    {
        $conn = db_connect();
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
    }
   else{
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role,loanOfficer) VALUES (?, ?, ?, ?,?)");
    return $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role,$loanOfficer]);
   }
}
function login($email, $password) {
    $pdo=db_connect();
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $role=$user['role'];
        return '1,$role'; // Success
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
    $stmt = $conn->query("SELECT SUM(amount) as total_amount FROM loans WHERE status = 'Disbursed' AND due_date < NOW()");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_amount'];
}
// Portfolio at risk calculation for loan officer
function calculate_portfolio_at_risk_for_user($loanOfficer) {
    $conn = db_connect();
    $stmt = $conn->query("SELECT SUM(amount) as total_amount FROM loans WHERE status = 'Disbursed' AND loan_officer='$loanOfficer' AND due_date < NOW()");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_amount'];
}
?>
