<?php
session_start();
require_once 'database.php';
if(!isset($_SESSION['role'])){
	header('location: login.php');
}	




$connection = new database();
$conn = $connection->connectDB();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$queryid = 'select idjadwal from sisidang.jadwal_sidang order by idjadwal desc limit 1';
$hasil = $conn->prepare($queryid);
$hasil->execute();
$idjadwal = $hasil->fetchAll(PDO::FETCH_ASSOC);
$idjadwal = $idjadwal['0']['idjadwal'];

$check_query = "SELECT * FROM sisidang.ruangan";
$hasil = $conn->prepare($check_query);
$hasil->execute();
$ruangan = $hasil->fetchAll(PDO::FETCH_ASSOC);

$isError = False;

foreach ($ruangan as $ruang) {

	if($_POST['ruangan'] == $ruang['idruangan']){


		$check_query2 = "SELECT * FROM sisidang.jadwal_sidang WHERE idruangan = ".$ruang['idruangan']."";
		$hasil = $conn->prepare($check_query2);
		$hasil->execute();
		$jadwal = $hasil->fetchAll(PDO::FETCH_ASSOC);
		foreach ($jadwal as $value) {
			if($value['tanggal'] = $_POST['tanggal']) {
				$jam_mulai1 = strtotime($value['jam_mulai']);
				$jam_selesai1 = strtotime($value['jam_selesai']);

				if ( ($jam_mulai1 < strtotime($_POST['jam_mulai']) AND $jam_selesai1 > strtotime($_POST['jam_mulai'])) OR ($jam_mulai1 < strtotime($_POST['jam_selesai']) AND $jam_selesai1 > strtotime($_POST['jam_selesai']))) {
					$isError = True;
				}

				if ($jam_mulai1 > strtotime($_POST['jam_mulai']) AND $jam_selesai1 < strtotime($_POST['jam_selesai'] )) {
					$isError = True;
				}
			}
		}
	}
}

if($isError) {
	$_SESSION['error'] = 'Waktu yang anda masukkan bentrok';
	header("Location: buat-jadwal-sidang.php");
} else {
	$query = 'INSERT INTO sisidang.jadwal_sidang (idjadwal, idmks, tanggal, jam_mulai,jam_selesai, idruangan) 
	          VALUES (:idjadwal, :idmks, :tanggal, :jam_mulai, :jam_selesai, :idruangan)';
	$hasil = $conn->prepare($query);
	$hasil->execute(array(':idjadwal'=>($idjadwal+1), ':idmks'=>$_POST['mks'], ':tanggal'=>$_POST['tanggal'], ':jam_mulai'=>$_POST['jam_mulai'], ':jam_selesai'=>$_POST['jam_selesai'], ':idruangan'=>$_POST['ruangan']));

	for ($i = 0; $i < sizeof($_POST['penguji']); $i++) {
	    $query2 = 'INSERT INTO sisidang.dosen_penguji (idmks, nipdosenpenguji) VALUES (:idmks, :nipdosenpenguji)';
	    $hasil = $conn->prepare($query2);
	    $hasil->execute(array(':idmks'=>$_POST['mks'], ':nipdosenpenguji'=>$_POST['penguji'][$i]));
	    $insertDosenPenguji = $hasil->fetchAll(PDO::FETCH_ASSOC);

	}
	if (isset($_POST['pengumpulan'])) {
		$query3 = 'UPDATE sisidang.mata_kuliah_spesial SET pengumpulanhardcopy = TRUE WHERE idmks = :idmks';	
		$hasil = $conn->prepare($query3);
	    $hasil->execute(array(':idmks'=>$_POST['mks']));	
	    $updatemks =  $hasil->fetchAll(PDO::FETCH_ASSOC);

	}

	header("Location: home.php");
}

?>