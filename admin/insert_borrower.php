<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $business_name = $_POST['business_name'];
    $unique_number = $_POST['unique_number'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $total_paid = $_POST['total_paid'];
    $open_loans_balance = $_POST['open_loans_balance'];
    $status = $_POST['status'];

    $sql = "INSERT INTO borrowers (full_name, business_name, unique_number, mobile, email, total_paid, open_loans_balance, status)
            VALUES ('$full_name', '$business_name', '$unique_number', '$mobile', '$email', '$total_paid', '$open_loans_balance', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "New borrower added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
