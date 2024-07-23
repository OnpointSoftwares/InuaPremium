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
function getBranches() {
    $conn = db_connect();
    $stmt = $conn->query("SELECT * FROM branches");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// User management functions
function add_user($name, $email, $password, $role, $area = null, $phone = null) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role_id, area, phone) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role, $area, $phone]);
}

function login($email, $password, $role) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND role_id = :role");
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

function update_loan_status($loan_id, $status) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE loans SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $loan_id]);
}

function track_repayment($loan_id, $amount) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO repayments (loan_id, amount) VALUES (?, ?)");
    return $stmt->execute([$loan_id, $amount]);
}

function calculate_portfolio_at_risk($days_overdue = 30, $loanOfficer = null) {
    $conn = db_connect();
    $params = [$days_overdue];
    $loanOfficerCondition = "";

    if ($loanOfficer !== null) {
        $loanOfficerCondition = " AND loan_officer = ?";
        $params[] = $loanOfficer;
    }

    $stmt = $conn->prepare("
        SELECT SUM(amount) AS at_risk_amount
        FROM loans
        WHERE status = 'Disbursed' AND due_date < DATE_SUB(NOW(), INTERVAL ? DAY) $loanOfficerCondition
    ");
    $stmt->execute($params);
    $at_risk_amount = $stmt->fetchColumn();

    $stmt = $conn->prepare("
        SELECT SUM(amount) AS total_amount
        FROM loans
        WHERE status = 'Disbursed' $loanOfficerCondition
    ");
    $stmt->execute($loanOfficer !== null ? [$loanOfficer] : []);
    $total_amount = $stmt->fetchColumn();

    return $total_amount > 0 ? ($at_risk_amount / $total_amount) * 100 : 0;
}

function logout() {
    session_destroy();
}

// Loan Products management functions
function getLoanProducts() {
    $conn = db_connect();
    $stmt = $conn->query("SELECT * FROM loan_products");
    // Fetch all rows as an associative array
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addLoanProduct($name, $branch_access, $penalty_settings, $status) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO loan_products (name, branch_access, penalty_settings, status) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $branch_access, $penalty_settings, $status]);
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
    $pdo = db_connect();
    $query = "SELECT loan_id, borrower_name, amount, disbursement_date, status FROM disbursed_loans";
    
    try {
        $stmt = $pdo->query($query);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}

function getOutstandingBalance() {
    $pdo = db_connect();
    $query = "SELECT loan_id, borrower_name, amount, due_date, status FROM outstanding_balance";
    
    try {
        $stmt = $pdo->query($query);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}

// Role and Navigation Management
function getUserRole($userId) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT role_id FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

function getRoles() {
    $conn = db_connect();
    $stmt = $conn->query("SELECT * FROM roles");
    return $stmt->fetchAll();
}

function getRole($id) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM roles WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAreas() {
    $conn = db_connect();
    $stmt = $conn->query("SELECT * FROM areas");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLoanOfficers() {
    $conn = db_connect();
    $stmt = $conn->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNavigationItems($roleId) {
    $conn = db_connect();
    $stmt = $conn->prepare("
        SELECT ni.id, ni.title, ni.url, ni.icon, ni.parent_id 
        FROM navigation_items ni
        INNER JOIN navigation_item_roles nir ON ni.id = nir.navigation_item_id
        WHERE nir.role_id = :role_id
    ");
    $stmt->execute([':role_id' => $roleId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateSettings($settings) {
    $conn = db_connect();
    $stmt = $conn->prepare("
        UPDATE settings SET
            company_name = ?, country = ?, timezone = ?, currency = ?, currency_in_words = ?, 
            date_format = ?, decimal_separator = ?, thousand_separator = ?, monthly_loan_repayment_cycle = ?, 
            yearly_loan_repayment_cycle = ?, days_in_a_month = ?, days_in_a_year = ?, 
            business_registration_number = ?, address = ?, city = ?, province = ?, zipcode = ?
    ");
    return $stmt->execute([
        $settings['company_name'], $settings['country'], $settings['timezone'], $settings['currency'], $settings['currency_in_words'],
        $settings['date_format'], $settings['decimal_separator'], $settings['thousand_separator'], $settings['monthly_loan_repayment_cycle'],
        $settings['yearly_loan_repayment_cycle'], $settings['days_in_a_month'], $settings['days_in_a_year'],
        $settings['business_registration_number'], $settings['address'], $settings['city'], $settings['province'], $settings['zipcode']
    ]);
}

function getSettings() {
    $conn = db_connect();
    $stmt = $conn->query("SELECT * FROM settings");
    return $stmt->fetch();
}

function savelogs() {
    // Implement log saving functionality
}
?>
