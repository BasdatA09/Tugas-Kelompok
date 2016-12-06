<?php
require_once 'database.php';
if(!isset($_SESSION['role'])){
	header('location: login.php');
}

if(isset($_POST["sort_admin"])){
	$_SESSION["mks_order_admin"] = $_POST["sort_admin"];
	header("Location : mata-kuliah-spesial.php");
}

function get_table($order)
{
	$connection = new database();
	$conn = $connection->connectDB();

	try {
		$query = 'SELECT mks.idmks, mks.judul, mhs.nama, mks.tahun, mks.semester, jmks.namamks, 
						 mks.ijinmajusidang, mks.pengumpulanhardcopy, mks.issiapsidang
				  FROM mahasiswa mhs NATURAL JOIN mata_kuliah_spesial mks JOIN jenismks jmks ON mks.idjenismks = jmks.id
				  ORDER BY '.$order;

		$hasil = $conn->prepare($query);
		$hasil->execute(array());

		while($hasil_row = $hasil->fetch(PDO::FETCH_ASSOC)) {
			echo '<tr>
                    <td>' . $hasil_row['idmks'] . '</td>
                    <td>' . $hasil_row['judul'] . '</td>        
                	<td>' . $hasil_row['nama']. '</td>';
			if ($hasil_row['semester'] == 1) {
				echo '<td> Gasal <br>' . $hasil_row['tahun'] . '/' . ($hasil_row['tahun']+1) . '</td>';
			} elseif ($hasil_row['semester'] == 2) {
				echo '<td> Genap <br>' . ($hasil_row['tahun']-1) . '/' . $hasil_row['tahun'] . '</td>';
			} else {
				echo '<td> Pendek <br>' . ($hasil_row['tahun']-1) . '/' . $hasil_row['tahun'] . '</td>';
			}

			echo '<td>' . $hasil_row['namamks'] . '</td>
				  <td><ul>';

			if ($hasil_row['ijinmajusidang']) {
				echo '<li>Izin Masuk Sidang</li>';
			}
			if ($hasil_row['pengumpulanhardcopy']) {
				echo '<li>Kumpul Hard Copy</li>';
			}
			if ($hasil_row['issiapsidang']) {
				echo '<li>Siap Sidang</li>';
			}

			echo '</ul></td></tr>';
		}

	} catch (PDOException $e){
		echo $e->getMessage();
	}
}

?>
<!-- Schedule Table Module -->
<div class="small-12 columns">
	<h1 class="subtitle">Daftar Mata Kuliah Spesial (admin)</h1>
	<button class="addScheduleButton" id="admAddScheduleButton">Tambah</button>
	<form action="mata-kuliah-spesial.php" method="post">
		Sort by:
		<select name='sort_admin' onchange='if(this.value != 0) {this.form.submit();}'>
			<option value="mhs.nama asc"  <?php if(isset($_SESSION["mks_order_admin"]) && $_SESSION["mks_order_admin"] == "mhs.nama asc" )echo "selected='selected'";?>>Mahasiswa</option>
			<option value="jmks.namamks asc" <?php if(isset($_SESSION["mks_order_admin"]) && $_SESSION["mks_order_admin"] == "jmks.namamks asc" )echo "selected='selected'";?>>Jenis MKS</option>
			<option value="mks.semester, mks.tahun asc" <?php if(isset($_SESSION["mks_order_admin"]) && $_SESSION["mks_order_admin"] == "mks.semester, mks.tahun asc" )echo "selected='selected'";?>>Term</option>
		</select>
	</form>
	<table id="daftarMKS">
		<thead>
			<tr>
				<th>ID</th>
				<th>Judul</th>
				<th>Mahasiswa</th>
				<th>Term</th>
				<th>Jenis MKS</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>

		<?php
		$sort = "mhs.nama";
		if(isset($_SESSION["mks_order_admin"])){
			$sort=$_SESSION["mks_order_admin"];
		}
		get_table($sort);
		?>
		<script>
			$(document).ready(function() {

				$('#daftarMKS').DataTable({
					ordering:false,
					paging:true,
					info:false,
					bFilter:false});
				$("#admAddScheduleButton").click(function () {
					window.location.href ="tambah-mks.php";
				});
			} );
		</script>

		</tbody>
	</table>
</div>
