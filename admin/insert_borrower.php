<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loanOfficer=$_POST['loanOfficer'];
    $full_name = $_POST['full_name'];
    $business_name = $_POST['business_name'];
    $unique_number = $_POST['unique_number'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $total_paid = $_POST['total_paid'];
    $open_loans_balance = $_POST['open_loans_balance'];
    $status = $_POST['status'];

    $sql = "INSERT INTO borrowers (full_name, business_name, unique_number, mobile, email, total_paid, open_loans_balance, status,loan_officer)
            VALUES ('$full_name', '$business_name', '$unique_number', '$mobile', '$email', '$total_paid', '$open_loans_balance', '$status','$loanOfficer')";

    if ($conn->query($sql) === TRUE) {
       ?>
       <script>
           alert("New borrower added");
           location.replace("view_borrowers.php");
           </script>
       <?php
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
