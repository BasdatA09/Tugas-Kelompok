<?php
session_start();
if(!isset($_SESSION['role'])){
    header('location: login.php');
}

$role = $_SESSION['role'];

?>

<?php
if($role[0] === 'admin'){
    include_once 'lihat-jadwal-sidang-admin.php';
} elseif($role[0] === 'dosen'){
    include_once 'lihat-jadwal-sidang-dosen.php';
} elseif($role[0] === 'mahasiswa'){
    include_once 'lihat-jadwal-sidang-mahasiswa.php';

}
?>
