<?php
session_start();
if(!isset($_SESSION['role'])){
    header('location: login.php');
}

$role = $_SESSION['role'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>SiSidang</title>
    <link rel="stylesheet" href="assets/css/vendor.css" />
    <link rel="stylesheet" href="assets/css/app.css" />
    <link rel="stylesheet" href="assets/css/jquery.dataTables.min.css"/>
</head>
<body>
<?php include_once 'header.php';?>
<div class="row homePage">
    <?php
        if($role[0] === 'admin'){
            include_once 'home-admin.php';
        } elseif($role[0] === 'dosen'){
            include_once 'home-dosen.php';
        } elseif($role[0] === 'mahasiswa'){
            include_once 'home-mahasiswa.php';

        }
    ?>
</div>
<?php include_once 'footer.php' ?>
<?php include_once 'js.php' ?>
</body>
</html>