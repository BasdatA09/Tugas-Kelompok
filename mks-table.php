<?php
session_start();
require_once 'database.php';

function get_table()
{
	$connection = new database();
	$conn = $connection->connectDB();

	try {
		$query = 'SELECT mks.idmks, mks.judul, mhs.nama, mks.tahun, mks.semester, jmks.namamks, 
						 mks.ijinmajusidang, mks.pengumpulanhardcopy, mks.issiapsidang
				  FROM mahasiswa mhs NATURAL JOIN mata_kuliah_spesial mks JOIN jenismks jmks ON mks.idjenismks = jmks.id
				  ORDER BY mks.idmks;';

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
				echo '<td> Genap <br>' . $hasil_row['tahun'] . '/' . ($hasil_row['tahun']-1) . '</td>';
			} else {
				echo '<td> Pendek <br>' . $hasil_row['tahun'] . '/' . ($hasil_row['tahun']-1) . '</td>';
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
	<button class="addScheduleButton" id="admAddScheduleButton" href="tambah-mks.php">Tambah</button>
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
				get_table();
			?>

		<script>
			$(document).ready(function() {

				$('#daftarMKS').DataTable();
			} );
		</script>

		</tbody>
	</table>
</div>
