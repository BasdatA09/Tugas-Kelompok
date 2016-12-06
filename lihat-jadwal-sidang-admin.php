<?php
session_start();
if(!isset($_SESSION['role'])){
    header('location: login.php');
}

if($_SESSION['role'][0] != 'admin'){
    echo '400 Bad Request';
    die();
}

if(isset($_POST["sort"])){
    $_SESSION["mks_order"] = $_POST["sort"];
    header("Location : lihat-jadwal-sidang.php");
}

require_once 'database.php';

function get_table($order)
{

    $display = '';
    $connection = new database();
    $conn = $connection->connectDB();
    try{
        $query = "SELECT js.idjadwal as idjadwal, mks.idmks as id_mks , J.NamaMKS as nama_mks , M.Nama as nama_mhs , MKS.Judul as judul_mks , JS.tanggal as tgl , JS.jam_mulai as jam_mulai , JS.jam_selesai as jam_selesai , r.namaruangan as nama_ruangan from sisidang.mata_kuliah_spesial mks inner join sisidang.mahasiswa m on mks.npm = m.npm inner join sisidang.jadwal_sidang js on js.idmks = mks.idmks inner join sisidang.jenismks j on j.id = mks.idjenismks inner join sisidang.ruangan r on js.idruangan = r.idruangan and mks.issiapsidang = true order by $order";
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
            $display .= '<td><form action="ubah-jadwal-sidang.php" method="post" accept-charset="utf-8">
                    <input type="hidden" name = "idjadwal" value = "'.$hasil_row['idjadwal'].'">
                    <input type="submit" value = "edit"></form><td>';
            $display .=  '</tr>';
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
    <title>SiSidang</title>
      <?php include_once 'favicon.php'; ?>
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
                <div class="row expanded hidden">
                    <div class="small-12 medium-4 columns">
                        <button id="admHomeSidangButton" class="active">Jenis Sidang</button>
                    </div>
                    <div class="small-12 medium-4 columns">
                        <button id="admHomeMhsButton">Mahasiswa</button>
                    </div>
                    <div class="small-12 columns" id="admHomeSidangContent">
                        <form class="row expanded">
                            <div class="small-12 columns">
                                <label>Term Sidang</label>
                                <select>
                                    <option>Term</option>
                                </select>
                            </div>
                            <div class="small-12 columns">
                                <label>Jenis Sidang</label>
                                <select>
                                    <option>Jenis</option>
                                </select>
                            </div>
                            <div class="small-12 columns">
                                <input type="submit" value="cari" />
                            </div>
                        </form>
                    </div>
                    <div class="small-12 columns" id="admHomeMhsContent" style="display: none;">
                        <form class="row expanded">
                            <div class="small-12 columns">
                                <label>Term Mahasiswa</label>
                                <select>
                                    <option>Term</option>
                                </select>
                            </div>
                            <div class="small-12 columns">
                                <label>Jenis Sidang Mahasiswa</label>
                                <select>
                                    <option>Jenis</option>
                                </select>
                            </div>
                            <div class="small-12 columns">
                                <input type="submit" value="cari" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Schedule Table Module -->
            <div class="small-12 columns">
                <h1 class="subtitle">Daftar Jadwal Sidang</h1>
                <button class="addScheduleButton" id="admAddScheduleButton">Tambah</button>
                <form class="sort-form" action="lihat-jadwal-sidang-admin.php" method="post">
                    Sort by:
                    <select name='sort' onchange='if(this.value != 0) {this.form.submit();}'>
                        <option value="js.tanggal , js.jam_mulai ,js.jam_selesai asc" <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "js.tanggal , js.jam_mulai ,js.jam_selesai asc" )echo "selected='selected'";?>>Default</option>
                        <option value="j.namamks asc" <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "j.namamks asc" )echo "selected='selected'";?>>Jenis MKS</option>
                        <option value="js.jam_mulai ,js.jam_selesai asc" <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "js.jam_mulai ,js.jam_selesai asc" )echo "selected='selected'";?>>Waktu</option>
                        <option value="m.nama asc"  <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "m.nama asc" )echo "selected='selected'";?>>Mahasiswa</option>
                    </select>
                </form>
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
                    /*
                    if ($_SERVER['REQUEST_METHOD'] === 'POST')
                    {
                        if (!empty($_POST['command']) && $_POST['command'] === 'jenis_sidang')
                        {
                            get_table('j.namamks asc');
                        } elseif(!empty($_POST['command']) && $_POST['command'] === 'mahasiswa')
                        {
                            get_table('m.nama asc');
                        } elseif(!empty($_POST['command']) && $_POST['command'] === 'waktu')
                        {
                            get_table('(js.jam_mulai ,js.jam_selesai) asc');
                        }
                    } else {
                        get_table('(js.tanggal , js.jam_mulai ,js.jam_selesai) asc');
                    }
                    */
                        $sort = "js.tanggal , js.jam_mulai ,js.jam_selesai asc";
                        if(isset($_SESSION["mks_order"])){
                            $sort=$_SESSION["mks_order"];
                        }
                            echo get_table($sort);


                    ?>
                    </tbody>
                </table>
            </div>
<!--            <form method="post" action="lihat-jadwal-sidang-admin.php">-->
<!--                <input type="hidden" name="command" value="jenis_sidang">-->
<!--                <button type="submit" class="hidden" id="sort_js">Kece</button>-->
<!--            </form>-->
<!--            <form method="post" action="lihat-jadwal-sidang-admin.php">-->
<!--                <input type="hidden" name="command" value="mahasiswa">-->
<!--                <button type="submit" class="hidden" id="sort_mhs">Kece</button>-->
<!--            </form>-->
<!--            <form method="post" action="lihat-jadwal-sidang-admin.php">-->
<!--                <input type="hidden" name="command" value="waktu">-->
<!--                <button type="submit" class="hidden" id="sort_waktu">Kece</button>-->
<!--            </form>-->
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