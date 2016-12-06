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

$idjadwal = $_POST['idjadwal'];

$query_jadwal = "SELECT * from sisidang.mahasiswa m, sisidang.mata_kuliah_spesial mks, sisidang.jadwal_sidang js, sisidang.ruangan r where js.idjadwal = ".$idjadwal." AND js.idmks=mks.idmks AND mks.npm = m.npm AND r.idruangan = js.idruangan";

$query_ruangan = "SELECT  * from sisidang.ruangan r";
$query_dosen = "SELECT * from sisidang.dosen d";

$crawl = $conn->prepare($query_jadwal);
$crawl->execute();
$jadwal = $crawl->fetchAll(PDO::FETCH_ASSOC);

$npmMhs = $jadwal[0]['npm'];
$mahasiswa = $jadwal[0]['nama'];   
$judul_mks = $jadwal[0]['judul'];
$tanggal  = $jadwal[0]['tanggal'];
$jam_mulai = $jadwal[0]['jam_mulai'];
$jam_selesai  = $jadwal[0]['jam_selesai'];
$namaruangan = $jadwal[0]['namaruangan'];
$idruangan = $jadwal[0]['idruangan'];
$pengumpulan = $jadwal[0]['pengumpulanhardcopy'];

$idmks = $jadwal[0]['idmks'];


$arr = [];


$query_penguji = "SELECT d.nama, d.nip from sisidang.dosen d, sisidang.dosen_penguji dp where d.nip = dp.nipdosenpenguji AND dp.idmks =".$idmks."";

$crawl = $conn->prepare($query_penguji);
$crawl->execute();
$penguji = $crawl->fetchAll(PDO::FETCH_ASSOC);


$crawl = $conn->prepare($query_ruangan);
$crawl->execute();
$ruangan = $crawl->fetchAll(PDO::FETCH_ASSOC);

$crawl = $conn->prepare($query_dosen);
$crawl->execute(); 
$dosen = $crawl->fetchAll(PDO::FETCH_ASSOC);

$counter = count($penguji);

function showPenguji($arr,$arr2) {
    $i  = 1;
    foreach ($arr as $value) {

        echo '<label>Penguji '.$i.'</label>';
        echo toDropDown($arr2,"nip","nama",$value['nama'],"","penguji[]", $value['nip']);
        $i = $i +1;
    }
}

function toDropDown($arr, $val, $name, $default, $label, $postname, $values)
{
  $select = '<select id="' . $label . '" class="form-control" name="' . $postname . '" required>
  <option value="'.$values.'">' . $default . '</option>';
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
        <form action="server-ubah-jadwal-sidang.php" method="post">
            <div class="small-12 columns">
                <label>Mahasiswa</label>
                <?php echo toDropDown($arr,"idmks","nama","$mahasiswa","change","mahasiswa", $npmMhs) ?>
            </div>
            <div id="mksdiv" class="small-12 columns">
                <label>MKS</label>
                <?php echo toDropDown($arr,"npm","mks","$judul_mks","","mks", $idmks) ?>

            </div>
            <div class="small-12 columns">
                <label>Tanggal</label>
                <input type="date" value="<?php echo $tanggal;?>" name="tanggal" required="" />
            </div>
            <div class="small-12 columns">
                <label>Jam Mulai</label>
                <input type="text" placeholder="00:00:00" value="<?php echo $jam_mulai;?>" name="jam_mulai" required="" />
            </div>
            <div class="small-12 columns">
                <label>Jam Selesai</label>
                <input type="text" placeholder="00:00:00" value="<?php echo $jam_selesai;?>" name="jam_selesai" required="" />
            </div>

            <div class="small-12 columns">
                <label>Ruangan</label>
                <?php echo toDropDown($ruangan,"idruangan","namaruangan",$namaruangan,"","ruangan", $idruangan) ?>
            </div>
            <div class="small-12 columns">
                <label>Pengumpulan Hard Copy</label>
                <input type="checkbox" name="pengumpulan" <?php if($pengumpulan) echo'checked'; ?>/>
            </div>
            <div id="penguji">
                <?php showPenguji($penguji, $dosen) ?>
            </div>
            <div class="small-12 columns">
                <input type="hidden" value="<?php echo $idjadwal ?>" name ="idjadwal">
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
            var counter = <?php echo $counter; ?>;

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