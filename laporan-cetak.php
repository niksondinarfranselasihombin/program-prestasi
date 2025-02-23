<?php
require('includes/fpdf/fpdf.php');

class PDF extends FPDF{
	
	function PDF($orientation='P', $unit='mm', $size='LEGAL'){
	    $this->FPDF($orientation,$unit,$size);
	}
		
		function Header(){
		$this->Image('assets/images/back2.jpg',10,10,30,30);
	    $this->SetFont('Times','B',14);
	    $this->Cell(100);
	    $this->Cell(0,10,'LAPORAN',0,0,'L');
		$this->Cell(-40,25,'SPK PEMILIHAN SISWA BERPRESTASI',0,0,'R');
		$this->Cell(5,40,'SMP Swasta Kita Membangun Yadika Tahuan Ganda ',0,0,'R');
		$this->Cell(-40,50,'Tahun Pelajaran 2023/2024',0,0,'R');
		$this->SetLineWidth(1);
		$this->Line(10,42,200,42);
		$this->SetLineWidth(0);
		$this->Line(10,43,200,43);
		$this->Ln(35);
		
	}
		function Footer(){
	    $this->SetY(-15);
	    $this->SetFont('Times','',8);
	    $this->Cell(0,10,$this->PageNo(),0,0,'R');	
			
		
	}

}

include "includes/config.php";
session_start();
if(!isset($_SESSION['nama_lengkap'])){
	echo "<script>location.href='index.php'</script>";
}
$config = new Config();
$db = $config->getConnection();

include 'includes/alternatif.inc.php';
$pro1 = new Alternatif($db);
$stmt1 = $pro1->readAll();
$stmt1y = $pro1->readAll();
include 'includes/kriteria.inc.php';
$pro2 = new Kriteria($db);
$stmt2 = $pro2->readAll();
$stmt2y = $pro2->readAll();
include 'includes/ranking.inc.php';
$pro = new Ranking($db);
$stmt = $pro->readKhusus();
$stmty = $pro->readKhusus();
$count = $pro->countAll();
$stmtx1 = $pro->readBob();
$stmtx2 = $pro->readBob();
$stmtx1y = $pro->readBob();
$stmtx2y = $pro->readBob();

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Times','B',14);
$pdf->Cell(40,10,'Skor Dan Bobot Alternatif Kriteria',0,0,'L');
$pdf->ln();
$pdf->SetFont('Times','',5);
$pdf->Cell(70,7,'Kriteria/Alternatif',1,0,'L');

while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
	$pdf->Cell(30,7,$row2['nama_kriteria'],1,0,'L');
}

$pdf->ln();
$pdf->SetFont('Times','',8);//ISI

while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
	$pdf->Cell(70,7,$row1['nik'],1,0,'L');
	$aj= $row1['id_alternatif'];
	$stmt21 = $pro2->readAll();
	while ($row21 = $stmt21->fetch(PDO::FETCH_ASSOC)){
		$bj= $row21['id_kriteria'];
		$stmtr = $pro->readR($aj,$bj);
		while ($rowr = $stmtr->fetch(PDO::FETCH_ASSOC)){
			$pdf->Cell(30,7,number_format($rowr['skor_alt_kri'], 2, '.', ','),1,0,'C');
		}
	}
	$pdf->ln();
}
$pdf->Cell(70,7,'Bobot',1,0,'C');
while ($rowx1 = $stmtx1->fetch(PDO::FETCH_ASSOC)){
	$pdf->Cell(30,7,number_format($rowx1['bobot_kriteria'], 2, '.', ','),1,0,'C');
}
$pdf->ln();

$pdf->SetFont('Times','B',14);
$pdf->Cell(35,10,'Hasil Perangkingan',0,0,'L');
$pdf->ln();

$pdf->SetFont('Times','',4);
$pdf->Cell(70,7,'Kriteria/Alternatif',1,0,'C');
while ($row2y = $stmt2y->fetch(PDO::FETCH_ASSOC)){
	$pdf->Cell(25,7,$row2y['nama_kriteria'],1,0,'L');
}
$pdf->Cell(25,7,'Hasil',1,0,'L');

$pdf->ln();
$pdf->SetFont('Times','',8);

while ($row1 = $stmt1y->fetch(PDO::FETCH_ASSOC)){
	$pdf->Cell(70,7,$row1['nik'],1,0,'L');
	$a1= $row1['id_alternatif'];
	$stmt21 = $pro2->readAll();
	while ($row21 = $stmt21->fetch(PDO::FETCH_ASSOC)){
		$b2= $row21['id_kriteria'];
		$stmtr = $pro->readR($a1,$b2);
		while ($rowr = $stmtr->fetch(PDO::FETCH_ASSOC)){
			$xab = $rowr['skor_alt_kri']*$row21['bobot_kriteria'];
			$pdf->Cell(25,7,number_format($xab, 2, '.', ','),1,0,'L');
		}
	}
	$stmthasil = $pro->readHasil2($a1);
	$hasil = $stmthasil->fetch(PDO::FETCH_ASSOC);
	$pdf->Cell(25,7,number_format($hasil['hasil_akhir'], 4, '.', ','),1,0,'L');
	$pdf->ln();
}
$pdf->ln();


$pdf->SetFont('Times','',8);
date_default_timezone_set("Asia/Jakarta");
$pdf->Cell(0,30,'Labuhan Batu Utara,  ' .date( 'd-m-Y'),0,0,'R');
$pdf->Cell(0,40,'Kepala Sekolah',0,10,'R');
$pdf->Cell(0,20,'Drs. Adusep Sihombing',0,10,'R');



$pdf->Output();

?>
