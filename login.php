<?php
session_start();

require_once 'auth.php';
require_once 'database.php';


?>



<!DOCTYPE html>
<html>
<head>
    <title>SiSidang</title>
    <link rel="stylesheet" href="assets/css/vendor.css" />
    <link rel="stylesheet" href="assets/css/app.css" />
</head>
<body>
<div class="row indexPage">
    <div class="small-12 columns">
        <div class="hero">
            <div class="row expanded">
                <div class="small-12 medium-4 columns">
                    <h1 class="logo"><span>Si</span>Sidang</h1>
                </div>
                <div class="small-12 medium-offset-2 medium-6 columns right-panel">
                    <form class="row expanded" method="post" action="auth.php">
                        <div class="small-12 medium-5 columns">
                            <input type="text" name="username" placeholder="Username anda" />
                        </div>
                        <div class="small-12 medium-5 columns">
                            <input type="password" name="pass" placeholder="Password anda" />
                        </div>
                        <div class="small-12 medium-2 columns">
                            <input type="submit" name="command" value="login"/>
                        </div>
                    </form>

                </div>
                <div class="small-12 medium-offset-2 medium-6 columns right-panel">
                    <p><?php if(isset($_SESSION['error'])) {
                            echo '<script>window.alert("' . $_SESSION['error'] . '");</script>';
                        }
                        unset($_SESSION['error']); ?></p>
                </div>

            </div>
        </div>
    </div>
</div>
<?php include_once 'footer.php' ?>
<?php include 'js.php' ?>
</body>
</html>