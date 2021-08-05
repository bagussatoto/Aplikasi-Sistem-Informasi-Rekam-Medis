<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	$page1 = "ruang";
	$page = "Status Ruangan Rawat Inap";
	session_start();
	include 'auth/connect.php';
	include "part/head.php";
	include "part_func/tgl_ind.php";

	if (isset($_POST['submit'])) {
		$id = $_POST['id'];
		$nama = $_POST['nama'];
		$harga = $_POST['harga'];
		$stat = $_POST['status'];


		$up2 = mysqli_query($conn, "UPDATE ruang_inap SET nama_ruang='$nama', status='$stat', biaya='$harga' WHERE id='$id'");
		echo '<script>
				setTimeout(function() {
					swal({
					title: "Data Diubah",
					text: "Data Ruangan berhasil diubah!",
					icon: "success"
					});
					}, 500);
				</script>';
	}

	if (isset($_POST['submit2'])) {
		$nama = $_POST['nama'];
		$harga = $_POST['harga'];

		$cekuser = mysqli_query($conn, "SELECT * FROM ruang_inap WHERE nama_ruang='$nama'");
		$baris = mysqli_num_rows($cekuser);
		if ($baris >= 1) {
			echo '<script>
				setTimeout(function() {
					swal({
						title: "Nama ruangan sudah digunakan",
						text: "Nama ruangan sudah digunakan, gunakan nama lain!",
						icon: "error"
						});
					}, 500);
			</script>';
		} else {
			$add = mysqli_query($conn, "INSERT INTO ruang_inap (nama_ruang, status, biaya) VALUES ('$nama', '0', '$harga')");
			echo '<script>
				setTimeout(function() {
					swal({
						title: "Berhasil!",
						text: "Ruangan baru telah ditambahkan!",
						icon: "success"
						});
					}, 500);
			</script>';
		}
	}
	?>
</head>

<body>
	<div id="app">
		<div class="main-wrapper main-wrapper-1">
			<div class="navbar-bg"></div>

			<?php
			include 'part/navbar.php';
			include 'part/sidebar.php';
			?>

			<!-- Main Content -->
			<div class="main-content">
				<section class="section">
					<div class="section-header">
						<h1>Detail Ruangan Rawat Inap</h1>
					</div>
					<div class="section-body">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4><?php echo $page; ?></h4>
										<div class="card-header-action">
											<a href="#" class="btn btn-primary" data-target="#addUser" data-toggle="modal">Tambah Ruangan</a>
										</div>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table table-striped" id="table-1">
												<thead>
													<tr>
														<th class="text-center">#</th>
														<th>Nama Ruangan</th>
														<th>Dipakai Sejak</th>
														<th>Dipakai Oleh</th>
														<th>Status</th>
														<th>Harga per hari</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$sql = mysqli_query($conn, "SELECT * FROM ruang_inap");
													$i = 0;
													while ($row = mysqli_fetch_array($sql)) {
														$defpasien = $row['id_pasien'];
														$i++;
													?>
														<tr>
															<td><?php echo $i; ?></td>
															<th><?php echo ucwords($row['nama_ruang']); ?></th>
															<td><?php if ($row['tgl_masuk'] == "") {
																		echo 'Belum digunakan';
																	} else {
																		echo tgl_indo($row['tgl_masuk']);
																	} ?></td>
															<td><?php
																	if ($defpasien == '') {
																		echo 'Belum ada pasien';
																	} else {
																		$sqlnama = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$defpasien'");
																		$namapasien = mysqli_fetch_array($sqlnama);
																		echo '<b>Sdr. ' . ucwords($namapasien["nama_pasien"]) . '</b>';
																	} ?></td>
															<td><?php
																	if ($row["status"] == "0") {
																		echo '<div class="badge badge-pill badge-success mb-1">';
																		echo '<i class="ion-checkmark-round"></i> Tersedia';
																	} elseif ($row["status"] == "1") {
																		echo '<div class="badge badge-pill badge-danger mb-1">';
																		echo '<i class="ion-close"></i> Dipakai';
																	} else {
																		echo '<div class="badge badge-pill badge-warning mb-1">';
																		echo '<i class="ion-gear-b"></i>  Dalam Perbaikan';
																	} ?>
										</div>
										</td>
										<td>Rp. <?php echo number_format($row['biaya'], 0, ".", "."); ?></td>
										<td>
											<?php if ($row['status'] == '1') { ?>
												<span data-toggle="tooltip" title="Status masih dipakai, Data tidak dapat diedit">
													<a class="btn btn-primary disabled btn-action mr-1"><i class="fas fa-pencil-alt"></i></a>
												</span>
												<span data-toggle="tooltip" title="Status masih dipakai, Data tidak dapat dihapus">
													<a class="btn btn-danger disabled btn-action mr-1"><i class="fas fa-trash"></i></a>
												</span>
												<a data-toggle="tooltip" title="Konfirmasi pasien keluar" class="btn btn-warning btn-action mr-1" data-confirm="Pasien Keluar|Apakah benar pasien yang bernama <b><?php echo ucwords($namapasien["nama_pasien"]) ?></b> akan keluar?" data-confirm-yes="location.reload(); window.open('updateriwayat.php?id=<?php echo $defpasien; ?>', '_blank');"><i class="ion-log-out"></i></a>
											<?php } else { ?>
												<span data-target="#editRuang" data-toggle="modal" data-id="<?php echo $row['id']; ?>" data-nama="<?php echo $row['nama_ruang']; ?>" data-harga="<?php echo $row['biaya']; ?>">
													<a class="btn btn-primary btn-action mr-1" title="Edit" data-toggle="tooltip"><i class="fas fa-pencil-alt"></i></a>
												</span>
												<a class="btn btn-danger btn-action mr-1" data-toggle="tooltip" title="Hapus" data-confirm="Hapus Data|Apakah anda ingin menghapus data ini?" data-confirm-yes="window.location.href = 'auth/delete.php?type=ruang_inap&id=<?php echo $row['id']; ?>'" ;><i class="fas fa-trash"></i></a>
												<span data-target="#editRuang" data-toggle="modal">
													<a data-toggle="tooltip" title="Pasien masuk" class="btn btn-success btn-action" href="rawat_jalan.php"><i class="ion-log-in"></i></a>
												</span>
											<?php } ?>
										</td>
										</tr>
									<?php } ?>
									</tbody>
									</table>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
			</section>
		</div>

		<div class="modal fade" tabindex="-1" role="dialog" id="addUser">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Tambah Ruangan</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" method="POST" class="needs-validation" novalidate="">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Nama Ruangan</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="nama" required="">
									<div class="invalid-feedback">
										Mohon data diisi!
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Harga</label>
								<div class="input-group col-sm-9">
									<div class="input-group-prepend">
										<div class="input-group-text">
											Rp
										</div>
									</div>
									<input type="number" class="form-control currency" name="harga" required="">
									<div class="invalid-feedback">
										Mohon data diisi!
									</div>
								</div>
							</div>
					</div>
					<div class="modal-footer bg-whitesmoke br">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" name="submit2">Tambah</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" tabindex="-1" role="dialog" id="editRuang">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Edit Data</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" method="POST" class="needs-validation" novalidate="">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Nama Ruangan</label>
								<div class="col-sm-9">
									<input type="hidden" class="form-control" name="id" required="" id="getId">
									<input type="text" class="form-control" name="nama" required="" id="getNama">
									<div class="invalid-feedback">
										Mohon data diisi!
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Harga</label>
								<div class="input-group col-sm-9">
									<div class="input-group-prepend">
										<div class="input-group-text">
											Rp
										</div>
									</div>
									<input type="number" class="form-control currency" name="harga" id="getHarga" required="">
								</div>
							</div>
							<div class="form-group">
								<label>Status Ruangan</label>
								<select class="form-control selectric" name="status">
									<option value="">Terserdia</option>
									<option value="2">Dalam Perbaikan</option>
								</select>
							</div>
					</div>
					<div class="modal-footer bg-whitesmoke br">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" name="submit">Edit</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php include 'part/footer.php'; ?>
	</div>
	</div>
	<?php include "part/all-js.php"; ?>

	<script>
		$('#editRuang').on('show.bs.modal', function(event) {
			var button = $(event.relatedTarget)
			var nama = button.data('nama')
			var id = button.data('id')
			var harga = button.data('harga')
			var modal = $(this)
			modal.find('#getId').val(id)
			modal.find('#getNama').val(nama)
			modal.find('#getHarga').val(harga)
		})
	</script>
</body>

</html>