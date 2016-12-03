<!DOCTYPE html>
<html>
	<head>
		<title>SiSidang</title>
		<link rel="stylesheet" href="assets/css/vendor.css" />
		<link rel="stylesheet" href="assets/css/app.css" />
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="row addModule">
			<div class="small-12 columns">
				<h1 class="subtitle">Tambah Data MKS</h1>
			</div>
			<div class="small-12 columns">
				<label>Term</label>
				<select>
					<option>
						Term
					</option>
				</select>
			</div>
			<div class="small-12 columns">
				<label>Jenis MKS</label>
				<select>
					<option>
						Skripsi
					</option>
				</select>
			</div>
			<div class="small-12 columns">
				<label>Mahasiswa</label>
				<select>
					<option>
						Andi
					</option>
				</select>
			</div>
			<div class="small-12 columns">
				<label>Judul MKS</label>
				<input type="text" placeholder="Judul MKS" />
			</div>
			<div class="small-12 columns">
				<label>Pembimbing 1</label>
				<select>
					<option>
						Andi
					</option>
				</select>
			</div>
			<div class="small-12 columns">
				<label>Pembimbing 2</label>
				<select>
					<option>
						Ani
					</option>
				</select>
			</div>
			<div class="small-12 columns">
				<label>Pembimbing 3</label>
				<select>
					<option>
						Adi
					</option>
				</select>
			</div>
			<div class="small-12 columns">
				<label>Penguji</label>
				<select>
					<option>
						Ai
					</option>
				</select>
			</div>
			<div class="small-12 columns">
				<button class="tambahEntity">Tambah penguji</button>
			</div>
			<div class="small-6 columns">
				<button class="saveButton">Simpan</button>
			</div>
			<div class="small-6 columns">
				<button class="cancelButton">Batal</button>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<?php include 'js.php' ?>
	</body>
</html>