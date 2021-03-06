<?php
session_start();
if(!isset($_SESSION['role'])){
    header('location: login.php');
}

if($_SESSION['role'][0] != 'admin'){
    echo '400 Bad Request';
    die();
}

require_once 'database.php';

function get_table($order)
{
    $display = '';
    $connection = new database();
    $conn = $connection->connectDB();
    try{
        $query = "SELECT js.idjadwal as idjadwal , mks.idmks as id_mks , J.NamaMKS as nama_mks , M.Nama as nama_mhs , MKS.Judul as judul_mks , JS.tanggal as tgl , JS.jam_mulai as jam_mulai , JS.jam_selesai as jam_selesai , r.namaruangan as nama_ruangan from sisidang.mata_kuliah_spesial mks inner join sisidang.mahasiswa m on mks.npm = m.npm inner join sisidang.jadwal_sidang js on js.idmks = mks.idmks inner join sisidang.jenismks j on j.id = mks.idjenismks inner join sisidang.ruangan r on js.idruangan = r.idruangan and mks.issiapsidang = true order by $order";
        //$query = "SELECT mks.idmks as id_mks, J.NamaMKS as nama_mks , M.Nama as nama_mhs, MKS.Judul as judul_mks, JS.Tanggal as tgl , JS.jam_mulai as jam_mulai , JS.jam_selesai as jam_selesai , r.namaruangan as nama_ruangan  FROM sisidang.Mata_Kuliah_Spesial mks , sisidang.Jenismks J , sisidang.Jadwal_sidang js  , sisidang.mahasiswa m , sisidang.ruangan r where m.npm = mks.npm and mks.idjenismks = j.ID and js.idmks = mks.idmks and js.idruangan = r.idruangan and mks.issiapsidang = true order by $order ";
        $hasil = $conn->prepare($query);
        $hasil->execute();

        while($hasil_row = $hasil->fetch(PDO::FETCH_ASSOC))
        {
            $display .= '<tr>
                    <td>'.$hasil_row['nama_mks'].'</td>
                    <td>'.$hasil_row['nama_mhs'].'<br>
                    Judul: '.$hasil_row['judul_mks'].'
                    </td>
                ';
            $mks_id = $hasil_row['id_mks'];
            $query2 = "Select d.nama as nama from sisidang.dosen_pembimbing dp inner join sisidang.dosen d on d.nip = dp.nipdosenpembimbing where dp.idmks = $mks_id";
            $hasil2 = $conn->prepare($query2);
            $hasil2->execute();

            $display .=  '<td><ul>';
                while($hasil2_row = $hasil2->fetch(PDO::FETCH_ASSOC))
                {

                    $display .=  '<li>'.$hasil2_row['nama'].'</li>';
                }
            $display .=  '</ul></td>';

            $query3 = "Select d.nama as nama from sisidang.dosen_penguji dp inner join sisidang.dosen d on d.nip = dp.nipdosenpenguji where dp.idmks = $mks_id";
            $hasil3 = $conn->prepare($query3);
            $hasil3->execute();
            $display .=  '<td><ul>';
                while($hasil3_row = $hasil3->fetch(PDO::FETCH_ASSOC))
                {
                    $display .=  '<li>'.$hasil3_row['nama'].'</li>';
                }
            $display .=  '</ul></td>';
            $display .=  '
                    <td>'.$hasil_row['tgl'].'<br>
                    '.$hasil_row['jam_mulai'].'-'.$hasil_row['jam_selesai'].'<br>
                    Ruangan: '.$hasil_row['nama_ruangan'].'
                    </td>
                ';
            $display .=  '<td><form action="ubah-jadwal-sidang.php" method="post" accept-charset="utf-8">
                    <input type="hidden" name = "idjadwal" value = "'.$hasil_row['idjadwal'].'">
                    <input type="submit" value = "edit"></form></td>';
            $display .=  '</tr>';
        }

        return $display;


    } catch(PDOException $e){
        echo $e->getMessage();
    }

}

$connection = new database();
$conn = $connection->connectDB();

$query_term = 'SELECT * from sisidang.term ';
$hasil = $conn->prepare($query_term);
$hasil->execute();
$terms = $hasil->fetchAll(PDO::FETCH_ASSOC);
$termsarr = array();


$query_jmks = 'SELECT * from sisidang.jenismks ';
$hasil = $conn->prepare($query_jmks);
$hasil->execute();
$jenis_mkss = $hasil->fetchAll(PDO::FETCH_ASSOC);
//$termsarr[] = array();

foreach($terms as$key=>$data) {
    if ($data['semester'] == 1) {
        $termsarr[] = array('1' => '<td> Gasal <br>' . $data['tahun'] . '/' . ($data['tahun'] + 1) . '</td>', '2' => '1,' . $data['tahun']);
    } elseif ($data['semester'] == 2) {
        $termsarr[] = array('1' => '<td> Genap <br>' . ($data['tahun'] - 1) . '/' . $data['tahun'] . '</td>', '2' => '2,' . $data['tahun']);
    } else {
        $termsarr[] = array('1' => '<td> Pendek <br>' . ($data['tahun']-1) . '/' . $data['tahun'] . '</td>', '2' => '3,' . $data['tahun']);
    }
}




function displaySearchOpt($label, $val, $name, $postname,$arr)
{
    $select = '<select class="form-control" name="' . $postname . '" required>
                <option value="">' . $label . '</option>';

    foreach ($arr as $key => $value) {
        $select .= "<option value='" . $value[$val] . "'>" . $value[$name] . "</option>";
    }
    $select .= "</select>";
    return $select;
}



function get_hasil_cari()
{

    $display = '';
    $connection = new database();
    $conn = $connection->connectDB();
    $term = explode(',', $_POST['term']);
    $semester = $term[0];
    $tahun = $term[1];

    try{
        $query = "SELECT mks.idmks as id_mks , J.NamaMKS as nama_mks , M.Nama as nama_mhs , MKS.Judul as judul_mks , JS.tanggal as tgl , JS.jam_mulai as jam_mulai , JS.jam_selesai as jam_selesai , r.namaruangan as nama_ruangan from sisidang.mata_kuliah_spesial mks inner join sisidang.mahasiswa m on mks.npm = m.npm inner join sisidang.jadwal_sidang js on js.idmks = mks.idmks inner join sisidang.jenismks j on j.id = mks.idjenismks inner join sisidang.ruangan r on js.idruangan = r.idruangan and mks.issiapsidang = true and mks.tahun =:tahun and mks.semester =:semester and j.id =:namamks";
        //$query = "SELECT mks.idmks as id_mks, J.NamaMKS as nama_mks , M.Nama as nama_mhs, MKS.Judul as judul_mks, JS.Tanggal as tgl , JS.jam_mulai as jam_mulai , JS.jam_selesai as jam_selesai , r.namaruangan as nama_ruangan  FROM sisidang.Mata_Kuliah_Spesial mks , sisidang.Jenismks J , sisidang.Jadwal_sidang js  , sisidang.mahasiswa m , sisidang.ruangan r where m.npm = mks.npm and mks.idjenismks = j.ID and js.idmks = mks.idmks and js.idruangan = r.idruangan and mks.issiapsidang = true order by $order ";
        $hasil = $conn->prepare($query);
        $hasil->execute(array(':semester' => $semester ,':tahun' => $tahun , ':namamks' => $_POST['jmks']));

        while($hasil_row = $hasil->fetch(PDO::FETCH_ASSOC))
        {
            $display .= '<tr>
                    <td>'.$hasil_row['nama_mks'].'</td>
                    <td>'.$hasil_row['nama_mhs'].'<br>
                    Judul: '.$hasil_row['judul_mks'].'
                    </td>
                ';
            $mks_id = $hasil_row['id_mks'];
            $query2 = "Select d.nama as nama from sisidang.dosen_pembimbing dp inner join sisidang.dosen d on d.nip = dp.nipdosenpembimbing where dp.idmks = $mks_id";
            $hasil2 = $conn->prepare($query2);
            $hasil2->execute();

            $display .= '<td><ul>';
            while($hasil2_row = $hasil2->fetch(PDO::FETCH_ASSOC))
            {

                $display .= '<li>'.$hasil2_row['nama'].'</li>';
            }
            $display .= '</ul></td>';

            $query3 = "Select d.nama as nama from sisidang.dosen_penguji dp inner join sisidang.dosen d on d.nip = dp.nipdosenpenguji where dp.idmks = $mks_id";
            $hasil3 = $conn->prepare($query3);
            $hasil3->execute();
            $display .= '<td><ul>';
            while($hasil3_row = $hasil3->fetch(PDO::FETCH_ASSOC))
            {
                $display .= '<li>'.$hasil3_row['nama'].'</li>';
            }
            $display .= '</ul></td>';
            $display .= '
                    <td>'.$hasil_row['tgl'].'<br>
                    '.$hasil_row['jam_mulai'].'-'.$hasil_row['jam_selesai'].'<br>
                    Ruangan: '.$hasil_row['nama_ruangan'].'
                    </td>
                ';
            $display .= '<td><a href="ubah-jadwal-sidang.php">Edit</a></td>';
            $display .= '</tr>';
        }

        return $display;

    } catch(PDOException $e){
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
    <div class="row expanded">
        <h1 class="subtitle">Admin</h1>
        <!--searchmodule-->
        <div class="small-12 columns">
            <div class="row expanded">
                <div class="small-12 medium-4 columns">
                    <button id="admHomeSidangButton" class="active">Jenis Sidang</button>
                </div>
                <div class="small-12 medium-4 columns">
                    <button id="admHomeMhsButton">Mahasiswa</button>
                </div>
                <div class="small-12 columns" id="admHomeSidangContent">
                    <form method="post" action="home-admin.php" class="row expanded">
                        <div class="small-12 columns">
                            <label>Term Sidang</label>
                            <?php echo displaySearchOpt("Pilih Term","2","1","term",$termsarr); ?>
                        </div>
                        <div class="small-12 columns">
                            <label>Jenis Sidang</label>
                            <?php echo displaySearchOpt("Pilih Jenis Mks","id","namamks","jmks",$jenis_mkss); ?>
                        </div>
                        <div class="small-12 columns">
                            <input type="submit"  name="perintah" value="cari" />
                        </div>
                    </form>
                </div>
                <div class="small-12 columns" id="admHomeMhsContent" style="display: none;">
                    <form method="post" action="home-admin.php" class="row expanded">
                        <div class="small-12 columns">
                            <label>Term Mahasiswa</label>
                            <?php echo displaySearchOpt("Pilih Term","2","1","term",$termsarr); ?>
                        </div>
                        <div class="small-12 columns">
                            <label>Jenis Sidang Mahasiswa</label>
                            <?php echo displaySearchOpt("Pilih Jenis Mks","id","namamks","jmks",$jenis_mkss); ?>
                        </div>
                        <div class="small-12 columns">
                            <input type="submit" name="perintah" value="cari" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Schedule Table Module -->
        <div class="small-12 columns">
            <h1 class="subtitle">Daftar Jadwal Sidang</h1>
            <button class="addScheduleButton" id="admAddScheduleButton">Tambah</button>
<!--            <h4>Sort By:</h4><a id="jenis_sidang">{Jenis Sidang}</a>,<a id="mahasiswa">{Mahasiswa}</a>,<a id="waktu">{Waktu}</a>-->
            <table  id="jadwal_sidang" class="display">
                <thead>
                <tr>
                    <th>Jenis Sidang</th>
                    <th >Mahasiswa</th>
                    <th >Dosen Pembimbing</th>
                    <th> Dosen Penguji</th>
                    <th >Waktu dan Lokasi</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    if(isset($_POST['perintah']) && $_POST['perintah'] == 'cari')
                    {
                        echo get_hasil_cari();

                    } else {
                        echo get_table('(js.tanggal , js.jam_mulai ,js.jam_selesai) asc');
                    }
//                }

                ?>
                </tbody>
            </table>
        </div>
<!--        <form method="post" action="home-admin.php">-->
<!--            <input type="hidden" name="command" value="jenis_sidang">-->
<!--            <button type="submit" class="hidden" id="sort_js">Kece</button>-->
<!--        </form>-->
<!--        <form method="post" action="home-admin.php">-->
<!--            <input type="hidden" name="command" value="mahasiswa">-->
<!--            <button type="submit" class="hidden" id="sort_mhs">Kece</button>-->
<!--        </form>-->
<!--        <form method="post" action="home-admin.php">-->
<!--            <input type="hidden" name="command" value="waktu">-->
<!--            <button type="submit" class="hidden" id="sort_waktu">Kece</button>-->
<!--        </form>-->
        <!-- Datepicker -->
        <div class="small-12 columns">
            <div class="row expanded">
                <table class="hidden" >
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<?php include_once 'footer.php' ?>
<?php include_once 'js.php' ?>
</body>
</html>