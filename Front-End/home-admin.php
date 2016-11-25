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
			<table>
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
					<tr>
						<td>Jenis Sidang</td>
						<td>Mahasiswa</td>
						<td>Dosen Pembimbing</td>
						<td>Dosen Penguji</td>
						<td>Waktu dan Lokasi</td>
						<td><button onClick="location.href = './ubah-jadwal-sidang.php';">Edit</button></td>
					</tr>
					<tr>
						<td>Jenis Sidang</td>
						<td>Mahasiswa</td>
						<td>Dosen Pembimbing</td>
						<td>Dosen Penguji</td>
						<td>Waktu dan Lokasi</td>
						<td><button onClick="location.href = './ubah-jadwal-sidang.php';">Edit</button></td>
					</tr>
					<tr>
						<td>Jenis Sidang</td>
						<td>Mahasiswa</td>
						<td>Dosen Pembimbing</td>
						<td>Dosen Penguji</td>
						<td>Waktu dan Lokasi</td>
						<td><button onClick="location.href = './ubah-jadwal-sidang.php';">Edit</button></td>
					</tr>
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