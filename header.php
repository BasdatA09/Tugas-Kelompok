<?php
session_start();
$role = $_SESSION['role'];
?>
<nav>
	<div class="row">
		<div class="small-12 medium-4 columns">
			<a class="logo" href="home.php"><span>Si</span>Sidang</a>
		</div>
		<div class="small-12 medium-8 columns text-right">
			<div class="burger" id="burger">
				<div class="burgerContent" id="burgerContent">
					<a class="navlink" href="tambah-mks.php">Tambah Peserta Mata Kuliah Spesial (MKS)</a>
                    <?php
                        if($role[0] === 'admin'){
                            echo '<a class="navlink" href="tambah-jadwal-sidang.php">Buat Jadwal Sidang MKS</a>';
                        }

                        if($role[0] === 'admin' || $role[0] === 'dosen')
                        {
                            echo '<a class="navlink" href="tambah-jadwal-non-sidang.php">Buat Jadwal Non-sidang Dosen</a>';
                            echo '<a class="navlink" href="mata-kuliah-spesial.php">Lihat Daftar MKS</a>';
                            echo '<a class="navlink" href="izinkan-sidang.php">Izinkan Sidang</a>';
                        }
					?>

					<a class="navlink" href="lihat-jadwal-sidang.php">Lihat Jadwal Sidang</a>


                    <a id="logout" class="navlink" >Logout</a>
                    <form method="post" action="auth.php">
                        <input type="hidden" name="command" value="logout">
                        <button id="logout-button" class="hidden" type="submit">
                    </form>
				</div>
			</div>
		</div>
	</div>
</nav>