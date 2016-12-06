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
				<h1 class="subtitle">Tambah Jadwal Non-Sidang MKS</h1>
			</div>
			<div class="small-12 columns">
				<label>Dosen</label>
				<select>
					<option>
						Andi
					</option>
				</select>
			</div>
			<div class="small-12 columns">
				<label>Tanggal Mulai</label>
				<input type="text" placeholder="Tanggal Mulai" />
			</div>
			<div class="small-12 columns">
				<label>Tanggal Selesai</label>
				<input type="text" placeholder="Tanggal Selesai" />
			</div>
			<div class="small-12 columns">
				<label>Jam Mulai</label>
				<input type="text" placeholder="Jam Mulai" />
			</div>
			<div class="small-12 columns">
				<label>Jam Selesai</label>
				<input type="text" placeholder="Jam Selesai" />
			</div>
			<div class="small-12 columns">
				<label>Kegiatan Berulang</label>
				<div class="radioButtons">
					<input type="radio" value="Harian" name="kegiatanberulang" />Harian
					<input type="radio" value="Mingguan" name="kegiatanberulang" />Mingguan
					<input type="radio" value="Bulanan" name="kegiatanberulang" />Bulanan
				</div>
			</div>
			<div class="small-12 columns">
				<label>Keterangan</label>
				<input type="text" placeholder="Keterangan" />
			</div>
			<div class="small-6 columns">
				<button class="saveButton">Simpan</button>
			</div>
			<div class="small-6 columns">
				<button class="cancelButton">Batal</button>
			</div>
			<!-- Schedule Table Module -->
			<div class="small-12 columns">
				<h1 class="subtitle">Jadwal Non Sidang Dosen (dosen)</h1>
				<button class="addScheduleButton" id="admAddScheduleButton">Tambah</button>
				<table>
					<thead>
						<tr>
							<th>Tanggal</th>
							<th>Jam</th>
							<th>Keterangan</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Tanggal</td>
							<td>Jam</td>
							<td>Keterangan</td>
							<td><button onClick="location.href = './ubah-jadwal-non-sidang.php';">Edit</button></td>
						</tr>
						<tr>
							<td>Tanggal</td>
							<td>Jam</td>
							<td>Keterangan</td>
							<td><button onClick="location.href = './ubah-jadwal-non-sidang.php';">Edit</button></td>
						</tr>
						<tr>
							<td>Tanggal</td>
							<td>Jam</td>
							<td>Keterangan</td>
							<td><button onClick="location.href = './ubah-jadwal-non-sidang.php';">Edit</button></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<?php include 'js.php' ?>
	</body>
</html>