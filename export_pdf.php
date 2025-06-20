 <?php
require('fpdf/fpdf.php');
require 'config.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// ====== Clients ======
$pdf->Cell(0, 10, 'Liste des Clients', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Nom', 1);
$pdf->Cell(50, 10, 'Email', 1);
$pdf->Cell(30, 10, 'Ville', 1);
$pdf->Cell(40, 10, 'Telephone', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$stmt = $pdo->query("SELECT id, nom, email, ville, telephone FROM clients");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(20, 10, $row['id'], 1);
    $pdf->Cell(40, 10, $row['nom'], 1);
    $pdf->Cell(50, 10, $row['email'], 1);
    $pdf->Cell(30, 10, $row['ville'], 1);
    $pdf->Cell(40, 10, $row['telephone'], 1);
    $pdf->Ln();
}

// ====== Reservations ======
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Liste des Reservations', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, 'ID', 1);
$pdf->Cell(30, 10, 'Nom Client', 1);
$pdf->Cell(40, 10, 'Email', 1);
$pdf->Cell(25, 10, 'Ville', 1);
$pdf->Cell(25, 10, 'Telephone', 1);
$pdf->Cell(30, 10, 'Modele', 1);
$pdf->Cell(25, 10, 'Date Debut', 1);
$pdf->Cell(25, 10, 'Date Fin', 1);
$pdf->Cell(20, 10, 'Statut', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$stmt = $pdo->query("SELECT * FROM reservations");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(10, 10, $row['id'], 1);
    $pdf->Cell(30, 10, $row['nom_client'], 1);
    $pdf->Cell(40, 10, $row['email'], 1);
    $pdf->Cell(25, 10, $row['ville'], 1);
    $pdf->Cell(25, 10, $row['telephone'], 1);
    $pdf->Cell(30, 10, $row['modele'], 1);
    $pdf->Cell(25, 10, $row['date_debut'], 1);
    $pdf->Cell(25, 10, $row['date_fin'], 1);
    $pdf->Cell(20, 10, $row['statut'], 1);
    $pdf->Ln();
}

// ====== Vehicules ======
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Liste des Vehicules', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(15, 10, 'ID', 1);
$pdf->Cell(70, 10, 'Modele', 1);
$pdf->Cell(30, 10, 'Annee', 1);
$pdf->Cell(30, 10, 'Prix', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$stmt = $pdo->query("SELECT id, modele, annee, prix FROM vehicules");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(15, 10, $row['id'], 1);
    $pdf->Cell(70, 10, $row['modele'], 1);
    $pdf->Cell(30, 10, $row['annee'], 1);
    $pdf->Cell(30, 10, $row['prix'], 1);
    $pdf->Ln();
}

$pdf->Output();
?>
