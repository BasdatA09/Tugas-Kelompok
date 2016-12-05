<?php
session_start();
if(!isset($_SESSION['role'])){
    header('location: login.php');
}
if($_SESSION['role'][0] != 'dosen'){
    echo '400 Bad Request';
    die();
}

$nip_dosen = $_SESSION['role'][1];
require_once 'database.php';

function get_table($nip , $order)
{

	$connection = new database();
	$conn = $connection->connectDB();
	try {

	    $query = "SELECT DISTINCT mks.idmks,mks.ijinmajusidang , mks.pengumpulanhardcopy , mhs.nama, j.namamks , mks.judul , js.tanggal , js.jam_mulai , js.jam_selesai , r.namaruangan from sisidang.mata_kuliah_spesial mks inner join sisidang.mahasiswa mhs on mhs.npm =  mks.npm inner join sisidang.jadwal_sidang js on js.idmks = mks.idmks inner join sisidang.jenismks j on j.id = mks.idjenismks INNER JOIN sisidang.ruangan r  on r.idruangan = js.idruangan left outer join sisidang.dosen_pembimbing dpem on dpem.idmks = mks.idmks left outer join sisidang.dosen_penguji dp on dp.idmks = mks.idmks where dpem.nipdosenpembimbing =:nip or dp.nipdosenpenguji =:nip ORDER by $order;";
        //$query = "select distinct mks.ijinmajusidang ,mks.pengumpulanhardcopy ,mhs.nama ,j.namamks , mks.judul , js.tanggal ,js.jam_mulai ,js.jam_selesai ,dp.nipdosenpenguji ,dpem.nipdosenpembimbing ,mks.idmks ,r.namaruangan from sisidang.mata_kuliah_spesial mks inner join sisidang.mahasiswa mhs on mhs.npm = mks.npm inner join sisidang.jadwal_sidang js on js.idmks = mks.idmks inner join sisidang.jenismks j on j.id = mks.idjenismks inner join sisidang.ruangan r on r.idruangan = js.idruangan left outer join sisidang.dosen_pembimbing dpem on dpem.idmks = mks.idmks left outer join sisidang.dosen_penguji dp on dp.idmks = mks.idmks WHERE mks.issiapsidang = true and dp.nipdosenpenguji =:nip OR dpem.nipdosenpembimbing =:nip order by $order";
        /*
        $query  = "SELECT DISTINCT mks.ijinmajusidang ,mks.pengumpulanhardcopy ,mhs.nama ,j.namamks , mks.judul , js.tanggal ,js.jam_mulai ,js.jam_selesai ,dp.nipdosenpenguji ,dpem.nipdosenpembimbing ,mks.idmks ,r.namaruangan
FROM sisidang.jadwal_sidang js NATURAL JOIN sisidang.mata_kuliah_spesial mks NATURAL JOIN sisidang.mahasiswa mhs JOIN sisidang.jenismks j ON mks.idjenismks = j.id  NATURAL LEFT OUTER JOIN sisidang.dosen_pembimbing dpem NATURAL LEFT OUTER JOIN sisidang.dosen_penguji dp NATURAL JOIN sisidang.ruangan r
WHERE dp.nipdosenpenguji=:nip OR dpem.nipdosenpembimbing =:nip
ORDER BY $order";
        */

        $hasil = $conn->prepare($query);
        $hasil->execute(array(':nip' => $nip));

        while($hasil_row = $hasil->fetch(PDO::FETCH_ASSOC)) {

            echo '<tr>
                    <td>' . $hasil_row['nama'] . '</td>
                    <td>' . $hasil_row['namamks'] . '<br>
                Sebagai: ';
            $mks_id = $hasil_row['idmks'];
            $query2 = "Select count(*) as hasil from sisidang.dosen_pembimbing dp where dp.idmks = $mks_id and dp.nipdosenpembimbing =:nip";
            $hasil2 = $conn->prepare($query2);
            $hasil2->execute(array(':nip' => $nip));
            $hasil2_row = $hasil2->fetch(PDO::FETCH_ASSOC);
            if($hasil2_row['hasil'] > 0){
                echo 'Dosen Pembimbing <br>';
            }
            $query3 = "Select count(*) as hasil from sisidang.dosen_penguji dp where dp.idmks = $mks_id and dp.nipdosenpenguji =:nip ";
            $hasil3 = $conn->prepare($query3);
            $hasil3->execute(array(':nip' => $nip));
            $hasil3_row = $hasil3->fetch(PDO::FETCH_ASSOC);
            if($hasil3_row['hasil'] > 0)
            {
                echo 'Dosen Penguji';
            }

            echo '</td>
            <td>'.$hasil_row['judul'].'</td>';
            echo '
                    <td>'.$hasil_row['tanggal'].'<br>
                    '.$hasil_row['jam_mulai'].'-'.$hasil_row['jam_selesai'].'<br>
                    Ruangan: '.$hasil_row['namaruangan'].'
                    </td>
                ';

            $query4 = "select d.nip ,  d.nama as nama from sisidang.dosen_pembimbing dp , sisidang.dosen d where dp.nipdosenpembimbing = d.nip and dp.idmks = $mks_id";
            $hasil4 = $conn->prepare($query4);
            $hasil4->execute();
            echo '<td>';
            while($hasil4_row = $hasil4->fetch(PDO::FETCH_ASSOC))
            {
                if($nip === $hasil4_row['nip'])
                {

                } else {
                    echo $hasil4_row['nama'] . '<br>';
                }
            }


            echo '</td>';

            $query5 = "select d.nip ,  d.nama as nama from sisidang.dosen_penguji dp , sisidang.dosen d where dp.nipdosenpenguji = d.nip and dp.idmks = $mks_id";
            $hasil5 = $conn->prepare($query5);
            $hasil5->execute();
            echo '<td>';
            while($hasil5_row = $hasil5->fetch(PDO::FETCH_ASSOC))
            {

                if($nip === $hasil5_row['nip'])
                {

                } else {
                    echo $hasil5_row['nama'] . '<br>';
                }
            }

            echo '<td><ul>';

            if($hasil_row['ijinmajusidang'] == true)
            {
                echo '<li>Izin Masuk Sidang</li>';
            }
            if($hasil_row['pengumpulanhardcopy'] == true)
            {
                echo '<li>Kumpul Hard Copy</li>';
            }

            echo '</ul></td>';


            echo '</td>';

            if($hasil_row)

            echo '</tr>';
        }
	} catch (PDOException $e){
		echo $e->getMessage();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
  <?php include_once 'favicon.php'; ?>
    <title>SiSidang</title>
    <link rel="stylesheet" href="assets/css/vendor.css" />
    <link rel="stylesheet" href="assets/css/app.css" />
    <link rel="stylesheet" href="assets/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="assets/css/fullcalendar.min.css"/>
    <link rel="stylesheet" href="assets/css/fullcalendar.print.css" rel='stylesheet' media='print' />

</head>
<body>
<?php include_once 'header.php';?>
<div class="row homePage">
<div class="small-12 columns adminHome">
	<h1 class="subtitle">Dosen</h1>
	<div class="row expanded">
		<div class="small-12 columns">
			<h1 class="subtitle">Daftar Jadwal Sidang</h1>
			<button class="addScheduleButton" id="admAddScheduleButton">Tambah</button>
<!--            <h4>Sort By:</h4><a id="jenis_sidang">{Jenis Sidang}</a>,<a id="mahasiswa">{Mahasiswa}</a>,<a id="waktu">{Waktu}</a>-->
			<table  id="jadwal_sidang" class="display">
				<thead>
				<tr>
					<th>Mahasiswa</th>
					<th>Jenis Sidang</th>
					<th>Judul</th>
					<th>Waktu Lokasi</th>
                    <th>Pembimbing Lain</th>
					<th>Penguji Lain</th>
					<th>Status</th>
				</tr>
				</thead>
				<tbody>
				<?php
//                if ($_SERVER['REQUEST_METHOD'] === 'POST')
//                {
//                    if (!empty($_POST['command']) && $_POST['command'] === 'jenis_sidang')
//                    {
//                        get_table($nip_dosen,'j.namamks asc');
//                    } elseif(!empty($_POST['command']) && $_POST['command'] === 'mahasiswa')
//                    {
//                        get_table($nip_dosen,'mhs.nama asc');
//                    } elseif(!empty($_POST['command']) && $_POST['command'] === 'waktu')
//                    {
//                        get_table($nip_dosen,'js.jam_mulai asc,js.jam_selesai asc');
//                    }
//                } else {
                    get_table($nip_dosen,'js.tanggal asc , js.jam_mulai asc ,js.jam_selesai asc');
//                }

                ?>
				</tbody>
			</table>
		</div>
<!--        <form method="post" action="home-dosen.php">-->
<!--            <input type="hidden" name="command" value="jenis_sidang">-->
<!--            <button type="submit" class="hidden" id="sort_js">Kece</button>-->
<!--        </form>-->
<!--        <form method="post" action="home-dosen.php">-->
<!--            <input type="hidden" name="command" value="mahasiswa">-->
<!--            <button type="submit" class="hidden" id="sort_mhs">Kece</button>-->
<!--        </form>-->
<!--        <form method="post" action="home-dosen.php">-->
<!--            <input type="hidden" name="command" value="waktu">-->
<!--            <button type="submit" class="hidden" id="sort_waktu">Kece</button>-->
        </form>
		<!-- Datepicker -->
	</div>
</div>

    <div id='calendar'></div>

</div>
<?php include_once 'footer.php' ?>
<?php include_once 'js.php' ?>
<?php

function display_calendar($nip)
{
    $connection = new database();
    $conn = $connection->connectDB();
    try {
        $query_1 = "SELECT mks.idmks , mks.judul , js.tanggal , js.jam_mulai , js.jam_selesai , r.namaruangan from sisidang.mata_kuliah_spesial mks inner join sisidang.mahasiswa m on m.npm =  mks.npm inner join sisidang.jadwal_sidang js on js.idmks = mks.idmks INNER JOIN sisidang.ruangan r  on r.idruangan = js.idruangan left outer join sisidang.dosen_pembimbing dpem on dpem.idmks = mks.idmks left outer join sisidang.dosen_penguji dp on dp.idmks = mks.idmks where dpem.nipdosenpembimbing =:nip or dp.nipdosenpenguji =:nip limit 1";
        $hasil_1 = $conn->prepare($query_1);
        $hasil_1->execute(array(':nip' => $nip));
        while($hasil_1_row = $hasil_1->fetch(PDO::FETCH_ASSOC)){
            $tanggal = $hasil_1_row['tanggal'];
        }
        echo '<script type="text/javascript">
   $(document).ready(function() {
    $(\'#calendar\').fullCalendar({
        // put your options and callbacks here
        header: {
            left: \'prev,next today\',
            center: \'title\',
            right: \'month,agendaWeek,agendaDay,listWeek\'
        },
            defaultDate:\''.$tanggal.'\',
        	eventLimit: true, // allow "more" link when too many events
			events: [
      ';




        $query = "SELECT DISTINCT mks.idmks , mks.judul , js.tanggal , js.jam_mulai , js.jam_selesai , r.namaruangan from sisidang.mata_kuliah_spesial mks inner join sisidang.mahasiswa m on m.npm =  mks.npm inner join sisidang.jadwal_sidang js on js.idmks = mks.idmks INNER JOIN sisidang.ruangan r  on r.idruangan = js.idruangan left outer join sisidang.dosen_pembimbing dpem on dpem.idmks = mks.idmks left outer join sisidang.dosen_penguji dp on dp.idmks = mks.idmks where dpem.nipdosenpembimbing =:nip or dp.nipdosenpenguji =:nip;";
        $hasil = $conn->prepare($query);
        $hasil->execute(array(':nip' => $nip));

        $i = $hasil->rowCount();

        while($hasil_row = $hasil->fetch(PDO::FETCH_ASSOC))
        {
            $i--;
            if($i == 0)
            {
                echo '{
                title:\'' . $hasil_row['judul'] . ' @' . $hasil_row['namaruangan'] . '\',
                start:\'' . $hasil_row['tanggal'] . 'T' . $hasil_row['jam_mulai'] . '\',
                end:\'' . $hasil_row['tanggal'] . 'T' . $hasil_row['jam_selesai'] . '\'
                }';
            } else {
                echo '{
                title:\'' . $hasil_row['judul'] . ' @' . $hasil_row['namaruangan'] . '\',
                start:\'' . $hasil_row['tanggal'] . 'T' . $hasil_row['jam_mulai'] . '\',
                end:\'' . $hasil_row['tanggal'] . 'T' . $hasil_row['jam_selesai'] . '\'
                },';
            }
        }
        echo ' ]
            });
          });
             </script>';



    } catch (PDOException $e)
    {
        echo $e->getMessage();
    }
}
?>

<?php display_calendar($nip_dosen); ?>


</body>
</html>