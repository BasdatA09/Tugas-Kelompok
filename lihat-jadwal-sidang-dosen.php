<?php
session_start();
if(!isset($_SESSION['role'])){
    header('location: login.php');
}
if($_SESSION['role'][0] != 'dosen'){
    echo '400 Bad Request';
    die();
}


if(isset($_POST["sort"])){
    $_SESSION["mks_order"] = $_POST["sort"];
}


$nip_dosen = $_SESSION['role'][1];
require_once 'database.php';

function get_table($nip , $order)
{

    $display = '';
    $connection = new database();
    $conn = $connection->connectDB();
    try {
        $query = "SELECT DISTINCT mks.idmks,mks.ijinmajusidang , mks.pengumpulanhardcopy , mhs.nama, j.namamks , mks.judul , js.tanggal , js.jam_mulai , js.jam_selesai , r.namaruangan from sisidang.mata_kuliah_spesial mks inner join sisidang.mahasiswa mhs on mhs.npm =  mks.npm inner join sisidang.jadwal_sidang js on js.idmks = mks.idmks inner join sisidang.jenismks j on j.id = mks.idjenismks INNER JOIN sisidang.ruangan r  on r.idruangan = js.idruangan left outer join sisidang.dosen_pembimbing dpem on dpem.idmks = mks.idmks left outer join sisidang.dosen_penguji dp on dp.idmks = mks.idmks where dpem.nipdosenpembimbing =:nip or dp.nipdosenpenguji =:nip ORDER by $order;";
        /*
        $query  = "SELECT mks.ijinmajusidang ,mks.pengumpulanhardcopy ,mhs.nama ,j.namamks , mks.judul , js.tanggal ,js.jam_mulai ,js.jam_selesai ,dp.nipdosenpenguji ,dpem.nipdosenpembimbing ,mks.idmks ,r.namaruangan
FROM sisidang.jadwal_sidang js NATURAL JOIN sisidang.mata_kuliah_spesial mks NATURAL JOIN sisidang.mahasiswa mhs JOIN sisidang.jenismks j ON mks.idjenismks = j.id  NATURAL LEFT OUTER JOIN sisidang.dosen_pembimbing dpem NATURAL LEFT OUTER JOIN sisidang.dosen_penguji dp NATURAL JOIN sisidang.ruangan r
WHERE dp.nipdosenpenguji=:nip OR dpem.nipdosenpembimbing =:nip
ORDER BY $order";
        */
        $hasil = $conn->prepare($query);
        $hasil->execute(array(':nip' => $nip));


        while($hasil_row = $hasil->fetch(PDO::FETCH_ASSOC)) {

            $display .= '<tr>
                    <td>' . $hasil_row['nama'] . '</td>
                    <td>' . $hasil_row['namamks'] . '<br>        
                Sebagai: ';
            $mks_id = $hasil_row['idmks'];
            $query2 = "Select count(*) as hasil from sisidang.dosen_pembimbing dp where dp.idmks = $mks_id and dp.nipdosenpembimbing =:nip";
            $hasil2 = $conn->prepare($query2);
            $hasil2->execute(array(':nip' => $nip));
            $hasil2_row = $hasil2->fetch(PDO::FETCH_ASSOC);
            if($hasil2_row['hasil'] > 0){
                $display .=  'Dosen Pembimbing <br>';
            }
            $query3 = "Select count(*) as hasil from sisidang.dosen_penguji dp where dp.idmks = $mks_id and dp.nipdosenpenguji =:nip ";
            $hasil3 = $conn->prepare($query3);
            $hasil3->execute(array(':nip' => $nip));
            $hasil3_row = $hasil3->fetch(PDO::FETCH_ASSOC);
            if($hasil3_row['hasil'] > 0)
            {
                $display .=  'Dosen Penguji';
            }

            $display .=  '</td> 
            <td>'.$hasil_row['judul'].'</td>';
            $display .= '
                    <td>'.$hasil_row['tanggal'].'<br>
                    '.$hasil_row['jam_mulai'].'-'.$hasil_row['jam_selesai'].'<br>
                    Ruangan: '.$hasil_row['namaruangan'].'
                    </td>         
                ';

            $query4 = "select d.nip ,  d.nama as nama from sisidang.dosen_pembimbing dp , sisidang.dosen d where dp.nipdosenpembimbing = d.nip and dp.idmks = $mks_id";
            $hasil4 = $conn->prepare($query4);
            $hasil4->execute();
            $display .=  '<td>';
            while($hasil4_row = $hasil4->fetch(PDO::FETCH_ASSOC))
            {
                if($nip === $hasil4_row['nip'])
                {

                } else {
                    $display .=  $hasil4_row['nama'] . '<br>';
                }
            }


            $display .=  '</td>';

            $query5 = "select d.nip ,  d.nama as nama from sisidang.dosen_penguji dp , sisidang.dosen d where dp.nipdosenpenguji = d.nip and dp.idmks = $mks_id";
            $hasil5 = $conn->prepare($query5);
            $hasil5->execute();
            $display .=  '<td>';
            while($hasil5_row = $hasil5->fetch(PDO::FETCH_ASSOC))
            {

                if($nip === $hasil5_row['nip'])
                {

                } else {
                    $display .=  $hasil5_row['nama'] . '<br>';
                }
            }

            $display .=  '<td><ul>';

            if($hasil_row['ijinmajusidang'] == true)
            {
                $display .=  '<li>Izin Masuk Sidang</li>';
            }
            if($hasil_row['pengumpulanhardcopy'] == true)
            {
                $display .= '<li>Kumpul Hard Copy</li>';
            }

            $display .=  '</ul></td>';


            $display .=  '</td>';

            if($hasil_row)

                $display .=  '</tr>';
        }

        return $display;
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
<div class="row homePage">
    <div class="small-12 columns adminHome">
        <h1 class="subtitle">Dosen</h1>
        <div class="row expanded">
            <div class="small-12 columns">
                <h1 class="subtitle">Daftar Jadwal Sidang</h1>
                <button class="addScheduleButton" id="admAddScheduleButton">Tambah</button>
                <form class="sort-form" action="lihat-jadwal-sidang-dosen.php" method="post">
                    Sort by:
                    <select name='sort' onchange='if(this.value != 0) {this.form.submit();}'>
                        <option value="js.tanggal , js.jam_mulai ,js.jam_selesai asc" <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "js.tanggal , js.jam_mulai ,js.jam_selesai asc" )echo "selected='selected'";?>>Default</option>
                        <option value="j.namamks asc" <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "j.namamks asc" )echo "selected='selected'";?>>Jenis MKS</option>
                        <option value="js.jam_mulai ,js.jam_selesai asc" <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "js.jam_mulai ,js.jam_selesai asc" )echo "selected='selected'";?>>Waktu</option>
                        <option value="mhs.nama asc"  <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "mhs.nama asc" )echo "selected='selected'";?>>Mahasiswa</option>
                    </select>
                </form>
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
                    /*
                    if ($_SERVER['REQUEST_METHOD'] === 'POST')
                    {
                        if (!empty($_POST['command']) && $_POST['command'] === 'jenis_sidang')
                        {
                            get_table($nip_dosen,'j.namamks asc');
                        } elseif(!empty($_POST['command']) && $_POST['command'] === 'mahasiswa')
                        {
                            get_table($nip_dosen,'mhs.nama asc');
                        } elseif(!empty($_POST['command']) && $_POST['command'] === 'waktu')
                        {
                            get_table($nip_dosen,'(js.jam_mulai ,js.jam_selesai) asc');
                        }
                    } else {
                        get_table($nip_dosen,'(js.tanggal , js.jam_mulai ,js.jam_selesai) asc');
                    }
                    */
                    $sort = "js.tanggal , js.jam_mulai ,js.jam_selesai asc";
                    if(isset($_SESSION["mks_order"])){
                        $sort=$_SESSION["mks_order"];
                    }
                    echo get_table($nip_dosen,$sort);
                    ?>
                    </tbody>
                </table>
            </div>
            <!--            <form method="post" action="lihat-jadwal-sidang-dosen.php">-->
            <!--                <input type="hidden" name="command" value="jenis_sidang">-->
            <!--                <button type="submit" class="hidden" id="sort_js">Kece</button>-->
            <!--            </form>-->
            <!--            <form method="post" action="lihat-jadwal-sidang-dosen.php">-->
            <!--                <input type="hidden" name="command" value="mahasiswa">-->
            <!--                <button type="submit" class="hidden" id="sort_mhs">Kece</button>-->
            <!--            </form>-->
            <!--            <form method="post" action="lihat-jadwal-sidang-dosen.php">-->
            <!--                <input type="hidden" name="command" value="waktu">-->
            <!--                <button type="submit" class="hidden" id="sort_waktu">Kece</button>-->
            <!--            </form>-->
            <!-- Datepicker -->
            <div class="small-12 columns">
                <div class="row expanded">
                    <table class="hidden" >
                        <thead>
                        <tr>
                            <th>Senin</th>
                            <th>Selasa</th>
                            <th>Rabu</th>
                            <th>Kamis</th>
                            <th>Jumat</th>
                            <th>Sabtu</th>
                            <th>Minggu</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>9</td>
                            <td>10</td>
                            <td>11</td>
                            <td>12</td>
                            <td>13</td>
                            <td>14</td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>16</td>
                            <td>17</td>
                            <td>18</td>
                            <td>19</td>
                            <td>20</td>
                            <td>21</td>
                        </tr>
                        <tr>
                            <td>22</td>
                            <td>23</td>
                            <td>24</td>
                            <td>25</td>
                            <td>26</td>
                            <td>27</td>
                            <td>28</td>
                        </tr>
                        <tr>
                            <td>29</td>
                            <td>30</td>
                            <td>31</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                        </tr>
                        </tbody>
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