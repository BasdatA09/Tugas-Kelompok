<?php
session_start();
require_once 'database.php';
if(!isset($_SESSION['role'])){
	header('location: login.php');
}


$connection = new database();
$conn = $connection->connectDB();

$query = 'SELECT * from dosen ';
$hasil = $conn->prepare($query);
$hasil->execute();
$dosens = $hasil->fetchAll(PDO::FETCH_ASSOC);

$query = 'SELECT * from mahasiswa ';
$hasil = $conn->prepare($query);
$hasil->execute();
$mahasiswas = $hasil->fetchAll(PDO::FETCH_ASSOC);

$query = 'SELECT * from jenismks ';
$hasil = $conn->prepare($query);
$hasil->execute();
$jenis_mkss = $hasil->fetchAll(PDO::FETCH_ASSOC);

$query = 'SELECT * from term ';
$hasil = $conn->prepare($query);
$hasil->execute();
$terms = $hasil->fetchAll(PDO::FETCH_ASSOC);
$termsarr = array();
//$termsarr[] = array();

foreach($terms as$key=>$data) {
	if ($data['semester'] == 1) {
		$termsarr[] = array('1' => '<td> Gasal <br>' . $data['tahun'] . '/' . ($data['tahun'] + 1) . '</td>', '2' => '1,' . $data['tahun']);
	} elseif ($data['semester'] == 2) {
		$termsarr[] = array('1' => '<td> Genap <br>' . ($data['tahun']-1) . '/' . $data['tahun'] . '</td>', '2' => '2,' . $data['tahun']);
	} else {
		$termsarr[] = array('1' => '<td> Pendek <br>' . ($data['tahun']-1) . '/' . $data['tahun'] . '</td>', '2' => '3,' . $data['tahun']);
	}
}

function toDropDown($arr, $val, $name, $default, $label, $postname)
{
	$select = '<select id="' . $label . '" class="form-control" name="' . $postname . '" required>
                <option value="">Pilih ' . $default . '</option>';

	foreach ($arr as $key => $value) {
		$select .= "<option value='" . $value[$val] . "'>" . $value[$name] . "</option>";
	}
	$select .= "</select>";
	return $select;
}
?>



<!DOCTYPE html>
<html>
	<head>
		<title>SiSidang</title>
		<link rel="stylesheet" href="assets/css/vendor.css" />
		<link rel="stylesheet" href="assets/css/app.css" />
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="row addModule">
			<div class="small-12 columns">
				<h1 class="subtitle">Tambah Data MKS</h1>
			</div>
			<form action="server_tambah_mks.php" method="post">
				<div class="small-12 columns">
					<label>Term</label>
					<?php echo toDropDown($termsarr,"2","1","Pilih Term","","term") ?>
				</div>
				<div class="small-12 columns">
					<label>Jenis MKS</label>
					<?php echo toDropDown($jenis_mkss,"id","namamks","Pilih Jenis Mks","","jmks") ?>

				</div>
				<div class="small-12 columns">
					<label>Mahasiswa</label>
					<?php echo toDropDown($mahasiswas,"npm","nama","Pilih Mahasiswa","","mahasiswa") ?>

				</div>
				<div class="small-12 columns">
					<label>Judul MKS</label>
					<input type="text" placeholder="Judul MKS" name="judul_mks"/>
				</div>

				<div class="small-12 columns">
					<label>Pembimbing 1</label>
					<?php echo toDropDown($dosens,"nip","nama","Pilih pembimbing","","pembimbing[]") ?>
				</div>

				<div class="small-12 columns">
					<label>Pembimbing 2</label>
					<?php echo toDropDown($dosens,"nip","nama","Pilih pembimbing","","pembimbing[]") ?>
				</div>

				<div class="small-12 columns">
					<label>Pembimbing 3</label>
					<?php echo toDropDown($dosens,"nip","nama","Pilih pembimbing","","pembimbing[]") ?>
				</div>

				<div id="penguji">
				</div>
				<div class="small-12 columns">
					<button type="button" class="tambahEntity">Tambah penguji</button>
				</div>
				<div class="small-6 columns">
					<button type="submit" class="saveButton">Simpan</button>
				</div>
				<div class="small-6 columns">
					<button type="button" class="cancelButton">Batal</button>
				</div>
			</form>
		</div>
		<?php include 'js.php' ?>
		<script>
			$(document).ready(function () {
				var counter = 0;

				addPenguji();

				$(".tambahEntity").click(function(){

					addPenguji();
				});

				function addPenguji() {
					var dosens = <?php echo json_encode($dosens); ?>

						console.log(dosens);
					counter = counter + 1;

					var res = "<div class='small-12 columns'>";

					res = res+ "<label>Penguji "+counter+"</label>";

					res = res+ "<select name='penguji[]'>";

					for(var i = 0; i<dosens.length;i++){
						res =res+ "<option value='"+dosens[i].nip+"'>"+dosens[i].nama+ "</option>";
					}

					res+"</select>";


					$("#penguji").append(res);
				}

				$(".cancelButton").click(function () {
					window.location.href ="home.php";
				});
			});
		</script>
		<?php include 'footer.php' ?>

	</body>
</html>
