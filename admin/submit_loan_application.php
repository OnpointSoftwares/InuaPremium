<?php
include 'includes/db_connect.php';
// Get form data

// Trial loan details
$loan_product = "Personal Loan";
$business_loan = "";
$borrower = "John Doe";
$loan_number = "LN12345";
$custom_loan_number = "CLN123";
$principal_amount = 5000.00;
$disbursed_by = "Cash";
$loan_release_date = "2024-08-01";
$interest_amount = 500.00;
$interest_method = "flat_rate";
$loan_interest_percentage = 10.00;
$loan_duration = 12; // Duration in months
$repayment_cycle = "monthly";
$number_of_repayments = 12;
$automated_payments = 1;
$extend_loan_after_maturity = 0;
$processing_fee = 2.00;
$registration_fee = 1.00;
$loan_status = "open";
$guarantors = "John Smith, Jane Doe";
$loan_title = "John's Personal Loan";
$description = "Loan for personal expenses.";
$accounting_account = "cash";
/*$loan_product = $_POST['loan_product'];
$business_loan = $_POST['business_loan'];
$borrower = $_POST['borrower'];
$loan_number = $_POST['loan_number'];
$custom_loan_number = $_POST['custom_loan_number'];
$principal_amount = $_POST['principal_amount'];
$disbursed_by = $_POST['disbursed_by'];
$loan_release_date = $_POST['loan_release_date'];
$interest_amount = $_POST['interest_amount'];
$interest_method = $_POST['interest_method'];
$loan_interest_percentage = $_POST['loan_interest_percentage'];
$loan_duration = $_POST['loan_duration'];
$repayment_cycle = $_POST['repayment_cycle'];
$number_of_repayments = $_POST['number_of_repayments'];
$automated_payments = isset($_POST['automated_payments']) ? 1 : 0;
$extend_loan_after_maturity = isset($_POST['extend_loan_after_maturity']) ? 1 : 0;
$processing_fee = $_POST['processing_fee'];
$registration_fee = $_POST['registration_fee'];
$loan_status = $_POST['loan_status'];
$guarantors = isset($_POST['guarantors']) ? implode(',', $_POST['guarantors']) : '';
$loan_title = $_POST['loan_title'];
$description = $_POST['description'];
$accounting_account = $_POST['accounting_account'];*/

// Insert loan details into the database
$sql = "INSERT INTO loan (loan_product, business_loan, borrower, loan_number, custom_loan_number, principal_amount, disbursed_by, loan_release_date, interest_amount, interest_method, loan_interest_percentage, loan_duration, repayment_cycle, number_of_repayments, automated_payments, extend_loan_after_maturity, processing_fee, registration_fee, loan_status, guarantors, loan_title, description, accounting_account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssisssssiiiddsss", $loan_product, $business_loan, $borrower, $loan_number, $custom_loan_number, $principal_amount, $disbursed_by, $loan_release_date, $interest_amount, $interest_method, $loan_interest_percentage, $loan_duration, $repayment_cycle, $number_of_repayments, $automated_payments, $extend_loan_after_maturity, $processing_fee, $registration_fee, $loan_status, $guarantors, $loan_title, $description, $accounting_account);

if ($stmt->execute()) {
    $loan_id = $stmt->insert_id;
    // Generate repayment schedule
    generateRepaymentSchedule($conn, $loan_id, $principal_amount, $interest_amount, $repayment_cycle, $number_of_repayments, $loan_release_date);
    echo "Loan application submitted successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

function generateRepaymentSchedule($conn, $loan_id, $principal_amount, $interest_amount, $repayment_cycle, $number_of_repayments, $loan_release_date) {
    $start_date = new DateTime($loan_release_date);
    $schedule = [];

    // Calculate each repayment date based on the repayment cycle
    for ($i = 1; $i <= $number_of_repayments; $i++) {
        $schedule_date = $start_date->modify('+' . $repayment_cycle)->format('Y-m-d');
        $repayment_amount = calculateRepaymentAmount($principal_amount, $interest_amount, $number_of_repayments);

        $sql = "INSERT INTO repayments (loan_id, repayment_date, amount) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isd", $loan_id, $schedule_date, $repayment_amount);
        $stmt->execute();
        $stmt->close();
    }
}

function calculateRepaymentAmount($principal_amount, $interest_amount, $number_of_repayments) {
    // Simple example: evenly distributed repayments
    $total_amount = $principal_amount + $interest_amount;
    return $total_amount / $number_of_repayments;
}
?>
