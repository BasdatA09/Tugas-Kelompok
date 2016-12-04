<?php
session_start();
if(!isset($_SESSION['role'])){
    header('location: login.php');
}

if($_SESSION['role'][0] != 'mahasiswa'){
    echo '400 Bad Request';
    die();
}
$npm = $_SESSION['role'][1];
require_once 'database.php';

function get_content($npm)
{
    $connection = new database();
    $conn = $connection->connectDB();

    try
    {
        $query = "SELECT MKS.idmks , MKS.pengumpulanhardcopy , MKS.ijinmajusidang , MKS.Judul as judul_mks, JS.Tanggal as tgl , JS.jam_mulai as jam_mulai , JS.jam_selesai as jam_selesai , r.namaruangan as nama_ruangan  FROM sisidang.Mata_Kuliah_Spesial mks , sisidang.Jenismks J , sisidang.Jadwal_sidang js , sisidang.ruangan r where mks.npm =:npm and mks.idjenismks = j.ID and js.idmks = mks.idmks and js.idruangan = r.idruangan and mks.issiapsidang = true limit 1";
        $hasil = $conn->prepare($query);
        $hasil->execute(array(':npm' => $npm));
        while($hasil_row = $hasil->fetch(PDO::FETCH_ASSOC))
        {
            echo '
			<table>
				<tbody>
					<tr>
							<th>Judul Tugas Akhir</th>
							<td>'.$hasil_row['judul_mks'].'</td>
						</tr>
						<tr>
							<th>Jadwal Sidang</th>
							<td>'.$hasil_row['tgl'].'</td>
						</tr>
						<tr>
							<th>Waktu Sidang</th>
							<td>'.$hasil_row['jam_mulai'].'-'.$hasil_row['jam_selesai'].' @ '.$hasil_row['nama_ruangan'].'</td>
						</tr>
						<tr>
							<th>Dosen Pembimbing</th>
							';
            $mks_id = $hasil_row['idmks'];
            $query2 = "Select d.nama from sisidang.dosen_pembimbing dp , sisidang.dosen d where dp.nipdosenpembimbing = d.nip and dp.idmks = $mks_id";
            $hasil2 = $conn->prepare($query2);
            $hasil2->execute();
            echo '<td>';
            $i = $hasil->rowCount();
            while($hasil2_row = $hasil2->fetch(PDO::FETCH_ASSOC))
            {
                --$i;
                if($i < 0)
                {
                    echo $hasil2_row['nama'];
                } else {
                    echo $hasil2_row['nama'] . ", ";
                }

            }

            echo "<strong> Status: </strong>";

            if($hasil_row['ijinmajusidang'] == true)
            {
                echo "Izin Maju Sidang, ";
            }
            if($hasil_row['pengumpulanhardcopy'] == true)
            {
                echo "Kumpul Hard Copy";
            }
            echo '</td>		
					</tr>
						<tr>
							<th>Dosen Penguji</th>
				';
            $query3 = "Select d.nama from sisidang.dosen_penguji dp , sisidang.dosen d where dp.nipdosenpenguji = d.nip and dp.idmks = $mks_id";
            $hasil3 = $conn->prepare($query3);
            $hasil3->execute();
            echo '<td><ul>';
            while ($hasil3_row = $hasil3->fetch(PDO::FETCH_ASSOC))
            {
                echo '<li>'.$hasil3_row['nama'].'</li>';
            }
            echo '
						</ul></td>
						</tr>
						</tbody>
					</table>';
        }
    } catch (PDOException $e){
        echo $e->getMessage();
    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SiSidang</title>
    <link rel="stylesheet" href="assets/css/vendor.css" />
    <link rel="stylesheet" href="assets/css/app.css" />
    <link rel="stylesheet" href="assets/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="assets/css/fullcalendar.min.css"/>
    <link rel="stylesheet" href="assets/css/fullcalendar.print.css" rel='stylesheet' media='print' />
</head>
<body>
<?php include_once 'header.php';?>
<div class="small-12 columns adminHome">
    <h1 class="subtitle">Mahasiswa</h1>
    <div class="row expanded">
        <div class="small-12 columns">
            <h1 class="subtitle">Daftar Jadwal Sidang (mahasiswa)</h1>
            <?php get_content($npm) ?>
        </div>
    </div>
</div>
</div>
<?php include_once 'footer.php' ?>
<?php include_once 'js.php' ?>
</body>
</html>