<?php
include '../includes/functions.php';
// Get form data
$borrower=$_POST['borrower'];
$loan_product = $_POST['loan_product'];
$principal = $_POST['principal'];
$loan_release_date = $_POST['loan_release_date'];
$interest = $_POST['interest'];
$interest_method = $_POST['interest_method'];
$loan_interest = $_POST['loan_interest_percentage'];
$loan_duration = $_POST['loan_duration'];
$repayment_cycle = $_POST['repayment_cycle'];
$number_of_repayments = $_POST['number_of_repayments'];
$processing_fee = $_POST['processing_fee'];
$registration_fee = $_POST['registration_fee'];
$loan_status = "pending";
$conn = db_connect();

// Calculate total interest
$total_interest = ($interest_method == 'percentage') 
    ? ($loan_interest / 100) * $principal 
    : $interest;

$total_amount = $principal + $total_interest + ($processing_fee / 100 * $principal) + ($registration_fee / 100 * $principal);

// Prepare SQL to insert loan application
$sql = "INSERT INTO loan_applications 
    (borrower,loan_product, principal, loan_release_date, interest, interest_method, loan_interest, loan_duration, repayment_cycle, number_of_repayments, processing_fee, registration_fee, loan_status, total_amount) 
    VALUES (:borrower,:loan_product, :principal, :loan_release_date, :interest, :interest_method, :loan_interest, :loan_duration, :repayment_cycle, :number_of_repayments, :processing_fee, :registration_fee, :loan_status, :total_amount)";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':borrower', $borrower, PDO::PARAM_STR);
$stmt->bindValue(':loan_product', $loan_product, PDO::PARAM_STR);
$stmt->bindValue(':principal', $principal, PDO::PARAM_STR);
$stmt->bindValue(':loan_release_date', $loan_release_date, PDO::PARAM_STR);
$stmt->bindValue(':interest', $interest, PDO::PARAM_STR);
$stmt->bindValue(':interest_method', $interest_method, PDO::PARAM_STR);
$stmt->bindValue(':loan_interest', $loan_interest, PDO::PARAM_STR);
$stmt->bindValue(':loan_duration', $loan_duration, PDO::PARAM_INT);
$stmt->bindValue(':repayment_cycle', $repayment_cycle, PDO::PARAM_STR);
$stmt->bindValue(':number_of_repayments', $number_of_repayments, PDO::PARAM_INT);
$stmt->bindValue(':processing_fee', $processing_fee, PDO::PARAM_STR);
$stmt->bindValue(':registration_fee', $registration_fee, PDO::PARAM_STR);
$stmt->bindValue(':loan_status', $loan_status, PDO::PARAM_STR);
$stmt->bindValue(':total_amount', $total_amount, PDO::PARAM_STR);

if ($stmt->execute()) {
    $loan_id = $conn->lastInsertId();
    echo "New loan application submitted successfully";

    // Generate repayment schedule
    generateRepaymentSchedule($conn, $loan_id, $principal, $total_interest, $repayment_cycle, $number_of_repayments, $loan_release_date);
} else {
    echo "Error: " . $stmt->errorInfo()[2];
}

function generateRepaymentSchedule($conn, $loan_id, $principal_amount, $interest_amount, $repayment_cycle, $number_of_repayments, $loan_release_date) {
    $start_date = new DateTime($loan_release_date);
    $schedule = [];

    // Calculate each repayment date based on the repayment cycle
    for ($i = 1; $i <= $number_of_repayments; $i++) {
        $schedule_date = clone $start_date;
        $schedule_date->modify('+' . getCycleInterval($repayment_cycle));

        $repayment_amount = calculateRepaymentAmount($principal_amount, $interest_amount, $number_of_repayments);

        $sql = "INSERT INTO repayments (loan_id, repayment_date, amount) VALUES (:loan_id, :repayment_date, :amount)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':loan_id', $loan_id, PDO::PARAM_INT);
        $stmt->bindValue(':repayment_date', $schedule_date->format('Y-m-d'), PDO::PARAM_STR);
        $stmt->bindValue(':amount', $repayment_amount, PDO::PARAM_STR);
        $stmt->execute();
    }?>
    <script>
    alert("Loan application successfull");
    location.replace('index.php');
    </script>
    <?php
}

function calculateRepaymentAmount($principal_amount, $interest_amount, $number_of_repayments) {
    // Calculate the total amount (principal + interest) and divide by the number of repayments
    $total_amount = $principal_amount + $interest_amount;
    return $total_amount / $number_of_repayments;
}

function getCycleInterval($cycle) {
    switch ($cycle) {
        case 'monthly':
            return '1 month';
        case 'quarterly':
            return '3 months';
        case 'annually':
            return '1 year';
        default:
            return '1 month'; // Default to monthly if the cycle is unknown
    }
}
?>
