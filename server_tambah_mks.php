<?php
session_start();
require_once 'database.php';

$connection = new database();
$conn = $connection->connectDB();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$term = explode(',', $_POST['term']);
$semester = $term[0];
$tahun = $term[1];

$queryid = 'select idmks from mata_kuliah_spesial order by idmks desc limit 1';
$hasil = $conn->prepare($queryid);
$hasil->execute();
$lastidmks = $hasil->fetchAll(PDO::FETCH_ASSOC);
$lastidmks = $lastidmks['0']['idmks'];

$query = 'INSERT INTO sisidang.mata_kuliah_spesial (idmks, npm, tahun, semester, judul, issiapsidang, pengumpulanhardcopy, ijinmajusidang, idjenismks) 
          VALUES (:idmks, :npm, :tahun, :semester, :judul_mks, FALSE, FALSE, FALSE, :jmks)';
$hasil = $conn->prepare($query);
$hasil->execute(array(':idmks'=>($lastidmks+1), ':npm'=>$_POST['mahasiswa'], ':tahun'=>$tahun, ':semester'=>$semester, ':judul_mks'=>$_POST['judul_mks'], ':jmks'=>$_POST['jmks']));



for ($i = 0; $i < 3; $i++) {
    $query1 = 'INSERT INTO sisidang.dosen_pembimbing (idmks, nipdosenpembimbing) VALUES (:idmks, :nipdosenpembimbing)';
    $hasil = $conn->prepare($query1);
    $hasil->execute(array(':idmks'=>($lastidmks+1), ':nipdosenpembimbing'=>$_POST['pembimbing'][$i]));

}

for ($i = 0; $i < sizeof($_POST['penguji']); $i++) {
    $query2 = 'INSERT INTO sisidang.dosen_penguji (idmks, nipdosenpenguji) VALUES (:idmks, :nipdosenpenguji)';
    $hasil = $conn->prepare($query2);
    $hasil->execute(array(':idmks'=>($lastidmks+1), ':nipdosenpenguji'=>$_POST['penguji'][$i]));
    $insertDosenPenguji = $hasil->fetchAll(PDO::FETCH_ASSOC);

}


//
//
//$_POST["judul_mks"];
//$_POST["pembimbing"];
//$_POST["penguji"];
//$_POST["mahasiswa"];
//$_POST["term"];
//$_POST["jmks"];

?>