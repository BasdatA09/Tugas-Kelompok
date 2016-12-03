<?php
require_once 'database.php';

if(isset($_POST["sort"])){
    $_SESSION["mks_order"] = $_POST["sort"];
    header("Location : mata-kuliah-spesial.php");
}


function get_table($nipdosen,$order)
{
    $connection = new database();
    $conn = $connection->connectDB();

    try {
        $query = 'SELECT DISTINCT mks.idmks, mks.judul, mhs.nama, mks.tahun, mks.semester, jmks.namamks,
			            mks.ijinmajusidang, mks.pengumpulanhardcopy, mks.issiapsidang
                  FROM mahasiswa mhs NATURAL JOIN mata_kuliah_spesial mks JOIN jenismks jmks ON mks.idjenismks = jmks.id,
                      dosen_pembimbing dpb, dosen_penguji dpj
                  WHERE (dpb.idmks = mks.idmks and dpb.nipdosenpembimbing = :nipdosen) OR 
                      (dpj.idmks = mks.idmks and dpj.nipdosenpenguji = :nipdosen)
                  ORDER BY '.$order;


        $hasil = $conn->prepare($query);
        $hasil->execute(array(':nipdosen'=>$nipdosen));

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
    <h1 class="subtitle">Daftar Mata Kuliah Spesial (dosen)</h1>

    <form action="mata-kuliah-spesial.php" method="post">
    <select name='sort' onchange='if(this.value != 0) {this.form.submit();}'>
        <option value="mhs.nama asc"  <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "mhs.nama asc" )echo "selected='selected'";?>>Mahasiswa</option>
        <option value="jmks.namamks asc" <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "jmks.namamks asc" )echo "selected='selected'";?>>Jenis MKS</option>
        <option value="mks.semester, mks.tahun asc" <?php if(isset($_SESSION["mks_order"]) && $_SESSION["mks_order"] == "mks.semester, mks.tahun asc" )echo "selected='selected'";?>>Term</option>
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
        if(isset($_SESSION["mks_order"])){
            $sort=$_SESSION["mks_order"];
        }
            get_table($_SESSION['role']['1'],$sort);
        ?>

        <script>
            $(document).ready(function() {

                $('#daftarMKS').DataTable({ordering:false,paging:true,info:false});
            } );
        </script>

        </tbody>
    </table>
</div>
