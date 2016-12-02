<?php
session_start();
require_once 'database.php';


function get_table($order)
{
    $connection = new database();
    $conn = $connection->connectDB();
    try{
        $query = 'SELECT mks.idmks as id_mks, J.NamaMKS as nama_mks , M.Nama as nama_mhs, MKS.Judul as judul_mks, JS.Tanggal as tgl , JS.jam_mulai as jam_mulai , JS.jam_selesai as jam_selesai , r.namaruangan as nama_ruangan  FROM sisidang.Mata_Kuliah_Spesial mks , sisidang.Jenismks J , sisidang.Jadwal_sidang js  , sisidang.mahasiswa m , sisidang.ruangan r where m.npm = mks.npm and mks.idjenismks = j.ID and js.idmks = mks.idmks and js.idruangan = r.idruangan and mks.issiapsidang = true order by :order ';
        $hasil = $conn->prepare($query);
        $hasil->execute(array(':order'=> $order));


        while($hasil_row = $hasil->fetch(PDO::FETCH_ASSOC))
        {
            echo '<tr>
                    <td>'.$hasil_row['nama_mks'].'</td>
                    <td>'.$hasil_row['nama_mhs'].'<br>
                    Judul: '.$hasil_row['judul_mks'].'
                    </td>         
                ';
            $mks_id = $hasil_row['id_mks'];
            $query2 = "Select d.nama as nama from sisidang.dosen_pembimbing dp inner join sisidang.dosen d on d.nip = dp.nipdosenpembimbing where dp.idmks = $mks_id";
            $hasil2 = $conn->prepare($query2);
            $hasil2->execute();

            echo '<td><ul>';
                while($hasil2_row = $hasil2->fetch(PDO::FETCH_ASSOC))
                {

                    echo '<li>'.$hasil2_row['nama'].'</li>';
                }
            echo '</ul></td>';

            $query3 = "Select d.nama as nama from sisidang.dosen_penguji dp inner join sisidang.dosen d on d.nip = dp.nipdosenpenguji where dp.idmks = $mks_id";
            $hasil3 = $conn->prepare($query3);
            $hasil3->execute();
            echo '<td><ul>';
                while($hasil3_row = $hasil3->fetch(PDO::FETCH_ASSOC))
                {
                    echo '<li>'.$hasil3_row['nama'].'</li>';
                }
            echo '</ul></td>';
            echo '
                    <td>'.$hasil_row['tgl'].'<br>
                    '.$hasil_row['jam_mulai'].'-'.$hasil_row['jam_selesai'].'<br>
                    Ruangan: '.$hasil_row['nama_ruangan'].'
                    </td>         
                ';
            echo '<td><a href="ubah-jadwal-sidang.php">Edit</a></td>';
            echo '</tr>';
        }

    } catch(PDOException $e){
        echo $e->getMessage();
    }

}


?>
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
            <table  id="jadwal_sidang" class="display">
                <thead>
                <tr>
                    <th>Jenis Sidang</th>
                    <th>Mahasiswa</th>
                    <th>Dosen Pembimbing</th>
                    <th>Dosen Penguji</th>
                    <th>Waktu dan Lokasi</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                get_table('(js.tanggal , js.jam_mulai) asc');
                ?>
                </tbody>
            </table>
        </div>
        <!-- Datepicker -->
        <div class="small-12 columns">
            <h1 class="subtitle">Agenda Bulan November</h1>
            <div class="row expanded">
                <table class="datescheduler">
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