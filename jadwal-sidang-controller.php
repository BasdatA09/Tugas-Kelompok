<?php 
require_once 'database.php';

$connection = new database();
$conn = $connection->connectDB();
$npm = $_GET['idMhs'];
$query_mks = "SELECT * from sisidang.mata_kuliah_spesial where npm ='".$npm."'";
$crawl = $conn->prepare($query_mks);
$crawl->execute();
$mks = $crawl->fetchAll(PDO::FETCH_ASSOC);

$select = "<label>MKS</label>";
$select .= "<select id='mks' class='form-control' name='mks' required>";
foreach ($mks as $key => $value) {
	$select .= "<option value='" . $value['idmks'] . "'>" . $value['judul'];
}
echo $select;
?>