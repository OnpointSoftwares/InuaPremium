<?php
require_once('TCPDF/tcpdf.php'); // Include the TCPDF library

include 'db.php';

// Function to get loans
function getLoans() {
    global $conn;
    $loans = array();

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

    $result = $conn->query($sql);

    if ($result === FALSE) {
        echo "Error: " . $conn->error;
        return $loans;
    }

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $loans[] = $row;
        }
    } else {
        echo "No records found.";
    }

    return $loans;
}

$loans = getLoans();

class PDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 15, 'Loan Applications Report', 0, 1, 'C');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Create a new PDF document
$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Loan Applications Report');
$pdf->SetSubject('Report');
$pdf->SetKeywords('TCPDF, PDF, report, loan');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

$html = '<table border="1" cellspacing="3" cellpadding="4">
            <thead>
                <tr>
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

if (count($loans) > 0) {
    foreach ($loans as $loan) {
        $html .= "<tr>
                    <td>{$loan['id']}</td>
                    <td>{$loan['borrower_name']}</td>
                    <td>{$loan['loan_product_name']}</td>
                    <td>{$loan['principal']}</td>
                    <td>{$loan['interest']}</td>
                    <td>{$loan['interest_method']}</td>
                    <td>{$loan['loan_interest']}</td>
                    <td>{$loan['loan_duration']}</td>
                    <td>{$loan['repayment_cycle']}</td>
                    <td>{$loan['number_of_repayments']}</td>
                    <td>{$loan['processing_fee']}</td>
                    <td>{$loan['registration_fee']}</td>
                    <td>{$loan['total_amount']}</td>
                    <td>{$loan['loan_release_date']}</td>
                    <td>{$loan['loan_status']}</td>
                </tr>";
    }
} else {
    $html .= "<tr><td colspan='15'>No loans found</td></tr>";
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->lastPage();

// Close and output PDF document
$pdf->Output('loan_applications_report.pdf', 'I');
?>
