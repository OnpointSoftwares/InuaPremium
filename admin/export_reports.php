<?php
require_once('TCPDF/tcpdf.php');
include 'db.php';

class PDF extends TCPDF {
    public $logo;

    public function __construct($logo) {
        parent::__construct();
        $this->logo = $logo;
    }

    public function Header() {
        if ($this->logo) {
            $this->Image('@' . $this->logo, 10, 10, 30, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 20, 'Microfinance Reports', 0, 1, 'C');
        $this->Ln(10); // Space after the header
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

function getLogo() {
    global $conn;
    $sql = "SELECT logo FROM settings LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['logo'];
    }
    return null;
}

function fetchLoans() {
    global $conn;
    $sql = "SELECT 
                l.id, 
                b.full_name AS borrower_name, 
                p.name AS loan_product_name, 
                l.principal, 
                l.interest, 
                l.interest_method, 
                l.loan_interest, 
                l.loan_duration, 
                l.repayment_cycle, 
                l.number_of_repayments, 
                l.processing_fee, 
                l.registration_fee, 
                l.total_amount, 
                l.loan_release_date,
                l.loan_status
            FROM loan_applications l 
            INNER JOIN borrowers b ON l.borrower = b.id 
            INNER JOIN loan_products p ON l.loan_product = p.id";

    return $conn->query($sql);
}

function fetchDueRepayments() {
    global $conn;
    $sql = "SELECT 
                borrowers.full_name AS borrower_name, 
                loan_applications.loan_product, 
                SUM(repayments.amount) AS total_amount_due, 
                repayments.repayment_date
            FROM 
                repayments
            INNER JOIN 
                loan_applications ON repayments.loan_id = loan_applications.id
            INNER JOIN 
                borrowers ON loan_applications.borrower = borrowers.id
            WHERE 
                repayments.repayment_date >= CURDATE()
            GROUP BY 
                repayments.loan_id, 
                borrowers.full_name, 
                loan_applications.loan_product, 
                repayments.repayment_date";

    return $conn->query($sql);
}

function fetchOverdueRepayments() {
    global $conn;
    $sql = "SELECT 
                borrowers.full_name AS borrower_name, 
                loan_applications.loan_product, 
                repayments.amount, 
                repayments.repayment_date
            FROM 
                repayments
            INNER JOIN 
                loan_applications ON repayments.loan_id = loan_applications.id
            INNER JOIN 
                borrowers ON loan_applications.borrower = borrowers.id
            WHERE 
                repayments.repayment_date < CURDATE()";

    return $conn->query($sql);
}

function generateTable($result) {
    $table = '<table border="1" cellpadding="3" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background-color: #f2f2f2; text-align: center;">
                        <th>ID</th>
                        <th>Borrower</th>
                        <th>Loan Product</th>
                        <th>Principal</th>
                        <th>Interest</th>
                        <th>Interest Method</th>
                        <th>Loan Interest %</th>
                        <th>Duration (months)</th>
                        <th>Repayment Cycle</th>
                        <th>Number of Repayments</th>
                        <th>Processing Fee %</th>
                        <th>Registration Fee %</th>
                        <th>Total Amount</th>
                        <th>Loan Release Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';
    while ($row = $result->fetch_assoc()) {
        $table .= '<tr style="text-align: center;">
                    <td>' . $row['id'] . '</td>
                    <td>' . $row['borrower_name'] . '</td>
                    <td>' . $row['loan_product_name'] . '</td>
                    <td>' . $row['principal'] . '</td>
                    <td>' . $row['interest'] . '</td>
                    <td>' . $row['interest_method'] . '</td>
                    <td>' . $row['loan_interest'] . '</td>
                    <td>' . $row['loan_duration'] . '</td>
                    <td>' . $row['repayment_cycle'] . '</td>
                    <td>' . $row['number_of_repayments'] . '</td>
                    <td>' . $row['processing_fee'] . '</td>
                    <td>' . $row['registration_fee'] . '</td>
                    <td>' . $row['total_amount'] . '</td>
                    <td>' . $row['loan_release_date'] . '</td>
                    <td>' . $row['loan_status'] . '</td>
                </tr>';
    }
    $table .= '</tbody></table>';
    return $table;
}

$logo = getLogo();

if (isset($_POST['export_loans'])) {
    $result = fetchLoans();
    $pdf = new PDF($logo);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    $table = generateTable($result);
    $pdf->writeHTML($table, true, false, false, false, '');
    $pdf->Output('loan_applications.pdf', 'I');
}

if (isset($_POST['export_due'])) {
    $result = fetchDueRepayments();
    $pdf = new PDF($logo);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    $table = generateTable($result);
    $pdf->writeHTML($table, true, false, false, false, '');
    $pdf->Output('due_repayments.pdf', 'I');
}

if (isset($_POST['export_overdue'])) {
    $result = fetchOverdueRepayments();
    $pdf = new PDF($logo);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    $table = generateTable($result);
    $pdf->writeHTML($table, true, false, false, false, '');
    $pdf->Output('overdue_repayments.pdf', 'I');
}
?>
