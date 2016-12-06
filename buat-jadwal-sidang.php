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



$connection = new database();
$conn = $connection->connectDB();
$query_mahasiswa = "SELECT * from sisidang.mahasiswa m";



$query_ruangan = "SELECT  * from sisidang.ruangan r";
$query_dosen = "SELECT * from sisidang.dosen d";

$crawl = $conn->prepare($query_mahasiswa);
$crawl->execute();
$mahasiswa = $crawl->fetchAll(PDO::FETCH_ASSOC);


$crawl = $conn->prepare($query_ruangan);
$crawl->execute();
$ruangan = $crawl->fetchAll(PDO::FETCH_ASSOC);

$crawl = $conn->prepare($query_dosen);
$crawl->execute(); 
$dosen = $crawl->fetchAll(PDO::FETCH_ASSOC);


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
    <?php include_once 'header.php';?>
    <div class="row addModule">
        <div class="small-12 columns">
            <h1 class="subtitle">Tambah Jadwal Sidang</h1>
        </div>
        <form action="server-buat-jadwal-sidang.php" method="post">
            <div class="small-12 columns">
                <label>Mahasiswa</label>
                <?php echo toDropDown($mahasiswa,"npm","nama","Pilih Mahasiswa","change","mahasiswa") ?>
            </div>
            <div id="mksdiv" class="small-12 columns">
                <label>MKS</label>

            </div>
            <div class="small-12 columns">
                <label>Tanggal</label>
                <input type="date" placeholder="yyyy-mm-dd" name="tanggal" required="" />
            </div>
            <div class="small-12 columns">
                <label>Jam Mulai</label>
                <input type="text" placeholder="00:00:00" name="jam_mulai" required="" />
            </div>
            <div class="small-12 columns">
                <label>Jam Selesai</label>
                <input type="text" placeholder="00:00:00" name="jam_selesai" required="" />
            </div>

            <div class="small-12 columns">
                <label>Ruangan</label>
                <?php echo toDropDown($ruangan,"idruangan","namaruangan","Pilih Ruangan","","ruangan") ?>

            </div>
            <div class="small-12 columns">
                <label>Penguji 1</label>
                <?php echo toDropDown($dosen,"nip","nama","penguji","","penguji[]") ?>
            </div>
            <div class="small-12 columns">
                <label>Pengumpulan Hard Copy</label>
                <input type="checkbox" name="pengumpulan"/>
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
            var counter = 1;

            addPenguji();

            $(".tambahEntity").click(function(){
                addPenguji();
            });

            function addPenguji() {
                var dosens = <?php echo json_encode($dosen); ?>


                counter = counter + 1;

                var res = "<div class='small-12 columns'>";

                res = res+ "<label>Penguji "+counter+"</label>";

                res = res+ "<select name='penguji[]'>"+ "<option value='Pilih Penguji'</option>";

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
    <script>
        jQuery(document).ready(function($) {
            $('#change').change(function(event) {
                /* Act on the event */
                var id = this.value;

                $.ajax({
                    url: 'jadwal-sidang-controller.php',
                    type: 'GET',
                    data: {idMhs: id},
                    success: function(data) {
                        $('#mksdiv').html(data);
                    }
                })
            });
        });
    </script>
    <?php include_once 'footer.php' ?>
    <?php include_once 'js.php' ?>
</body>
</html>