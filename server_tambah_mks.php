<?php
session_start();
require_once 'database.php';
if(!isset($_SESSION['role'])){
    header('location: login.php');
}


$connection = new database();
$conn = $connection->connectDB();

$term = explode(',', $_POST['term']);
$semester = $term[0];
$tahun = $term[1];

$queryid = 'select idmks from mata-kuliah-spesial order by idmks desc limit 1';
$hasil = $conn->prepare($queryid);
$hasil->execute();
$lastidmks = $hasil->fetchAll(PDO::FETCH_ASSOC);

$query = 'INSERT INTO sisidang.mata-kuliah-spesial (idmks, npm, tahun, semester) VALUES (:idmks, :npm, :tahun, :semester)';
$hasil = $conn->prepare($query);
$hasil->execute(array(':idmks'=>SESUATU, ':npm'=>$_POST['mahasiswa'], ':tahun'=>$tahun, ':semester'=>$semester));
$insertMKS = $hasil->fetchAll(PDO::FETCH_ASSOC);

$query1 = 'INSERT INTO sisidang.dosenpembimbing (idmks, nipdosenpembimbing) VALUES (:idmks, :nipdosenpembimbing)';
$hasil = $conn->prepare($query1);
$hasil->execute();
$insertDosenPembimbing = $hasil->fetchAll(PDO::FETCH_ASSOC);

$query2 = 'INSERT INTO sisidang.dosenpenguji (idmks, nipdosenpenguji) VALUES (:idmks, :nipdosenpenguji)';
$hasil = $conn->prepare($query2);
$hasil->execute();
$insertDosenPenguji = $hasil->fetchAll(PDO::FETCH_ASSOC);



$_POST["judul_mks"];
$_POST["pembimbing"];
$_POST["penguji"];
$_POST["mahasiswa"];
$_POST["term"];
$_POST["jmks"];

?>