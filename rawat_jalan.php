<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Rawat Jalan";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";

  @$nama = $_POST['nama'];
  $cek = mysqli_query($conn, "SELECT * FROM pasien WHERE nama_pasien='$nama' OR id='$nama'");
  $cekrow = mysqli_num_rows($cek);
  $tokne = mysqli_fetch_array($cek);
  $tglnow = date('Y-m-d');

  if (isset($_POST['jalan1'])) {
    if ($cekrow == 0) {
      mysqli_query($conn, "INSERT INTO pasien (nama_pasien, tinggi_badan, berat_badan) VALUES ('$nama', '0', '0')");
      echo '<script> location.reload(); </script>';
    } else {
      echo '<script>
				setTimeout(function() {
					swal({
						title: "Pasien Telah Terdaftar!",
						text: "Pasien yang bernama ' . ucwords($tokne['nama_pasien']) . ' sudah terdaftar, silahkan lanjutkan ke menu selanjutnya",
						icon: "success"
						});
					}, 500);
			</script>';
    }
  }

  if (isset($_POST['jalan2'])) {
    $namamu = $_POST['nama'];
    @$tgl = $_POST['tgl'];
    $berat = $_POST['berat'];
    $tinggi = $_POST['tinggi'];
    $alam = $_POST['alamat'];

    mysqli_query($conn, "UPDATE pasien SET alamat='$alam', tgl_lahir='$tgl', berat_badan='$berat', tinggi_badan='$tinggi' WHERE nama_pasien='$namamu'");
  }

  if (isset($_POST['jalan3'])) {
    $idpasien = $_POST['id'];
    $penyakit = $_POST['penyakit'];
    $diagnosa = $_POST['diagnosa'];
    $biaya = $_POST['biaya'];

    mysqli_query($conn, "INSERT INTO riwayat_penyakit (id_pasien, penyakit, diagnosa, tgl, id_rawatinap, biaya_pengobatan) VALUES ('$idpasien', '$penyakit', '$diagnosa', '$tglnow', '0', '$biaya')");
  }

  if (isset($_POST['submitfoto'])) {
    $idpasien = $_POST['id'];
    $penyakit = $_POST['penyakit'];
    $biaya = $_POST['biaya'];
    $cekriwayat = mysqli_query($conn, "SELECT * FROM `riwayat_penyakit` WHERE penyakit='$penyakit' AND id_pasien='$idpasien' ORDER BY id DESC LIMIT 1");
    $datapasien = mysqli_fetch_array($cekriwayat);
    $idpas = $datapasien['id_pasien'];
    $idpeny = $datapasien['id'];

    if (count($_FILES['upload']['name']) > 0) {
      for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
        $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
        if ($tmpFilePath != "") {
          $filePath = "assets/img/uploads/" . date('d-m-Y-H-i-s') . '-' . $_FILES['upload']['name'][$i];
          if (move_uploaded_file($tmpFilePath, $filePath)) {
            $split = count($_FILES['upload']['tmp_name']);
            $sql = mysqli_query($conn, "INSERT INTO foto_rotgen (id_pasien, id_penyakit, biaya, directory) VALUES ('$idpas', '$idpeny','$biaya', '$filePath')");
          }
        }
      }
    }
    echo '<script>
				setTimeout(function() {
					swal({
						title: "Foto terupload!",
						text: "' . $split . ' Foto Telah Berhasil Diupload",
						icon: "success"
						});
					}, 500);
			</script>';
  }

  if (isset($_POST['rawatinap'])) {
    $idpasien = $_POST['id'];
    $penyakit = $_POST['penyakit'];
    $idruang = $_POST['ruang'];
    $kodestatus = "tmp" . $idruang;
    $nama = $_POST['namaruang'];

    mysqli_query($conn, "UPDATE ruang_inap SET id_pasien='$idpasien', tgl_masuk='$tglnow', status='1' WHERE id='$idruang'");
    mysqli_query($conn, "UPDATE riwayat_penyakit SET id_rawatinap='$kodestatus' WHERE id_pasien='$idpasien'");
    echo '<script>
				setTimeout(function() {
					swal({
						title: "Kamar dipesan!",
						text: "Kamar ' . $nama . ' Berhasil Dipesan",
						icon: "success"
						});
					}, 500);
			</script>';
  }

  if (isset($_POST['pesanobat'])) {
    $idpasien = $_POST['id'];
    $penyakit = $_POST['penyakit'];
    $jum = $_POST['jumlah'];
    $cekriwayat = mysqli_query($conn, "SELECT * FROM `riwayat_penyakit` WHERE penyakit='$penyakit' AND id_pasien='$idpasien' ORDER BY id DESC LIMIT 1");
    $datapasien = mysqli_fetch_array($cekriwayat);
    $idpas = $datapasien['id_pasien'];
    $idpeny = $datapasien['id'];

    if (isset($_POST["obat"])) {
      foreach ($_POST['obat'] as $obat) {
        mysqli_query($conn, "INSERT INTO riwayat_obat (id_penyakit, id_pasien, id_obat, jumlah) VALUES ('$idpeny', '$idpas', '$obat', '$jum')");
        mysqli_query($conn, "UPDATE obat SET stok=(stok - $jum) WHERE id='$obat'");
      }
    }
    echo '<script>
				setTimeout(function() {
					swal({
						title: "Obat Dibeli!",
						text: "Obat berhasil dibeli",
						icon: "success"
						});
					}, 500);
			</script>';
  }

  if (isset($_POST['print'])) {
    $idpasien = $_POST['id'];
    $penyakit = $_POST['penyakit'];

    $tolologi = mysqli_query($conn, "SELECT * FROM riwayat_penyakit WHERE penyakit='$penyakit' AND id_pasien='$idpasien' ORDER BY id DESC LIMIT 1");
    $lol = mysqli_fetch_array($tolologi);
    $tolologi2 = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$idpasien'");
    $lol2 = mysqli_fetch_array($tolologi2);
    $penyyy = $lol['id'];
    $passs = $lol2['nama_pasien'];
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
            <h1><?php echo $page; ?></h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Create New App</h4>
                  </div>
                  <div class="card-body">
                    <div class="row mt-4">
                      <div class="col-12 col-lg-8 offset-lg-1">
                        <div class="wizard-steps">
                          <div class="wizard-step wizard-step-active">
                            <div class="wizard-step-icon">
                              <i class="far fa-user"></i>
                            </div>
                            <div class="wizard-step-label">
                              Identitas Pasien
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['jalan1']) || isset($_POST['jalan2']) || isset($_POST['jalan3']) || isset($_POST['submitfoto']) || isset($_POST['rawatinap']) || isset($_POST['pesanobat']) || isset($_POST['print'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-server"></i>
                            </div>
                            <div class="wizard-step-label">
                              Informasi Umum
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['jalan2']) || isset($_POST['jalan3']) || isset($_POST['submitfoto']) || isset($_POST['rawatinap']) || isset($_POST['pesanobat']) || isset($_POST['print'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="wizard-step-label">
                              Pemeriksaan
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['jalan3']) || isset($_POST['submitfoto']) || isset($_POST['rawatinap']) || isset($_POST['pesanobat']) || isset($_POST['print'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-briefcase-medical"></i>
                            </div>
                            <div class="wizard-step-label">
                              Tindakan yang dilakukan
                            </div>
                          </div>
                          <div class="wizard-step <?php echo (isset($_POST['print'])) ? "wizard-step-active" : ""; ?>">
                            <div class="wizard-step-icon">
                              <i class="fas fa-print"></i>
                            </div>
                            <div class="wizard-step-label">
                              Cetak Struk
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <form class="wizard-content mt-2 needs-validation" novalidate="" method="POST" autocomplete="off" enctype="multipart/form-data">
                      <div class="wizard-pane">
                        <?php if (empty($_POST)) { ?>

                          <!-- PART 1 -->

                          <div class="form-group row align-items-center">
                            <label class="col-md-4 text-md-right text-left">Nama Lengkap / ID</label>
                            <div class="col-lg-4 col-md-6">
                              <input id="myInput" type="text" class="form-control" name="nama" placeholder="Nama / ID Calon Pasien">
                              <div class="invalid-feedback">
                                Mohon data diisi!
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-lg-4 col-md-6 text-right">
                              <button class="btn btn-icon icon-right btn-primary" name="jalan1">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                            </div>
                          </div>
                        <?php }
                        if (isset($_POST['jalan1'])) { ?>

                          <!-- PART 2 -->

                          <div class="form-group row align-items-center">
                            <label class="col-md-4 text-md-right text-left">Nama Lengkap</label>
                            <div class="col-lg-4 col-md-6">
                              <input type="hidden" name="nama" class="form-control" required="" value="<?php echo $tokne['nama_pasien']; ?>">
                              <input type="text" class="form-control" required="" value="<?php echo $tokne['nama_pasien']; ?>" disabled>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-4 text-md-right text-left">Tanggal lahir</label>
                            <div class="col-lg-4 col-md-6">
                              <input type="text" class="form-control datepicker" name="tgl" required="" value="<?php echo $tokne['tgl_lahir']; ?>">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-4 text-md-right text-left col-form-label">Tinggi Badan</label>
                            <div class="input-group col-sm-6 col-lg-4">
                              <input type="number" class="form-control" name="tinggi" required="" value="<?php echo $tokne['tinggi_badan']; ?>">
                              <div class="invalid-feedback">
                                Mohon data diisi!
                              </div>
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  cm
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-4 text-md-right text-left col-form-label">Berat Badan</label>
                            <div class="input-group col-sm-6 col-lg-4">
                              <input type="number" class="form-control" name="berat" required="" value="<?php echo $tokne['berat_badan']; ?>">
                              <div class="invalid-feedback">
                                Mohon data diisi!
                              </div>
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  Kg
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-4 text-md-right text-left">Alamat</label>
                            <div class="col-lg-4 col-md-6">
                              <textarea type="number" class="form-control" name="alamat" required=""><?php echo $tokne['alamat']; ?></textarea>
                              <div class="invalid-feedback">
                                Mohon data diisi!
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-lg-4 col-md-6 text-right">
                              <button class="btn btn-icon icon-right btn-primary" name="jalan2">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                            </div>
                          </div>
                        <?php }
                        if (isset($_POST['jalan2'])) { ?>

                          <!-- PART 3 -->

                          <div class="card-body">
                            <div class="form-group row mb-4">
                              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Penyakit</label>
                              <div class="col-sm-12 col-md-7">
                                <input type="hidden" class="form-control" name="id" required="" value="<?php echo $tokne['id']; ?>">
                                <input type="text" class="form-control" name="penyakit" required="">
                                <div class="invalid-feedback">
                                  Mohon data diisi!
                                </div>
                              </div>
                            </div>
                            <div class="form-group row mb-4">
                              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Diagnosa</label>
                              <div class="col-sm-12 col-md-7">
                                <textarea placeholder="Wajib" class="summernote" name="diagnosa">Wajib Diisi</textarea>
                              </div>
                            </div>
                            <div class="form-group row mb-4">
                              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Biaya Pemeriksaan</label>
                              <div class="input-group col-sm-12 col-md-7">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    Rp
                                  </div>
                                </div>
                                <input type="number" class="form-control" name="biaya" required="" value="0">
                                <div class="invalid-feedback">
                                  Mohon data diisi!
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <div class="col-md-6"></div>
                              <div class="col-lg-4 col-md-6 text-right">
                                <button class="btn btn-icon icon-right btn-primary" name="jalan3">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                              </div>
                            </div>
                          <?php }
                        if (isset($_POST['jalan3']) || isset($_POST['submitfoto']) || isset($_POST['rawatinap']) || isset($_POST['pesanobat'])) { ?>

                            <!-- PART 4 -->

                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-4">
                                <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                  <li class="nav-item">
                                    <a class="nav-link active" id="home-tab4" data-toggle="tab" href="#home4" role="tab" aria-controls="home" aria-selected="true">Pemberian Obat</a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link" id="profile-tab4" data-toggle="tab" href="#profile4" role="tab" aria-controls="profile" aria-selected="false">Foto Rotgen</a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link" id="contact-tab4" data-toggle="tab" href="#contact4" role="tab" aria-controls="contact" aria-selected="false">Membutuhkan Rawat Inap</a>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-12 col-sm-12 col-md-8">
                                <div class="tab-content no-padding" id="myTab2Content">
                                  <div class="tab-pane fade show active" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                    <div class="form-group row mb-4">
                                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Obat yang dibutuhkan</label>
                                      <div class="col-sm-12 col-md-7">
                                        <input type="hidden" class="form-control" name="id" required="" value="<?php echo $idpasien; ?>">
                                        <input type="hidden" class="form-control" name="penyakit" required="" value="<?php echo $penyakit; ?>">
                                        <select class="form-control select2" name="obat[]" multiple="">
                                          <?php
                                          $obat2an = mysqli_query($conn, "SELECT * FROM obat WHERE stok >= 1");
                                          while ($obat = mysqli_fetch_array($obat2an)) {
                                            echo "<option value='" . $obat['id'] . "'>" . $obat['nama_obat'] . "</option>";
                                          }
                                          ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jumlah Obat</label>
                                      <div class="col-sm-12 col-md-7">
                                        <input type="number" class="form-control" name="jumlah" required="" value="0">
                                        <div class="invalid-feedback">
                                          Mohon data diisi!
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <div class="col-md-6"></div>
                                      <div class="col-lg-4 col-md-6 text-right">
                                        <input type="submit" class="btn btn-icon icon-right btn-primary" name="pesanobat" value="Beli Obat">
                                        <input type="submit" class="btn btn-icon icon-right btn-success" name="print" value="Selesai">
                                      </div>
                                    </div>
                                  </div>
                                  <div class="tab-pane fade" id="profile4" role="tabpanel" aria-labelledby="profile-tab4">
                                    <div class="card-body">
                                      <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pilih Foto</label>
                                        <div class="col-sm-12 col-md-7">
                                          <input type="hidden" class="form-control" name="id" required="" value="<?php echo $idpasien; ?>">
                                          <input type="hidden" class="form-control" name="penyakit" required="" value="<?php echo $penyakit; ?>">
                                          <input id='upload' class="form-control" name="upload[]" type="file" multiple="multiple" />
                                          <div class="invalid-feedback">
                                            Mohon data diisi!
                                          </div>
                                        </div>
                                      </div>
                                      <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Total Biaya</label>
                                        <div class="input-group col-sm-12 col-md-7">
                                          <div class="input-group-prepend">
                                            <div class="input-group-text">
                                              Rp
                                            </div>
                                          </div>
                                          <input type="number" class="form-control" name="biaya" required="" value="0">
                                          <div class="invalid-feedback">
                                            Mohon data diisi!
                                          </div>
                                        </div>
                                      </div>
                                      <div class="form-group row">
                                        <div class="col-md-6"></div>
                                        <div class="col-lg-4 col-md-6 text-right">
                                          <input type="submit" class="btn btn-icon icon-right btn-primary" name="submitfoto" value="Upload Foto">
                                          <input type="submit" class="btn btn-icon icon-right btn-success" name="print" value="Selesai">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="tab-pane fade" id="contact4" role="tabpanel" aria-labelledby="contact-tab4">
                                    <div class="table-responsive">
                                      <?php
                                      $kepake = mysqli_query($conn, "SELECT * FROM ruang_inap WHERE id_pasien='$idpasien'");
                                      $sql = mysqli_query($conn, "SELECT * FROM ruang_inap WHERE status='0'");
                                      if (mysqli_num_rows($sql) == 0) {
                                        echo "Kamar sudah penuh";
                                      } else {
                                        $kapakegak = mysqli_num_rows($kepake);
                                        if ($kapakegak == 0) {
                                      ?>
                                          <table class="table table-striped" id="table-1">
                                            <thead>
                                              <tr>
                                                <th>Nama Ruangan</th>
                                                <th>Harga per hari</th>
                                                <th>Action</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <?php
                                              while ($row = mysqli_fetch_array($sql)) {
                                              ?>
                                                <tr>
                                                  <th><?php echo ucwords($row['nama_ruang']); ?></th>
                                                  <td>Rp. <?php echo number_format($row['biaya'], 0, ".", "."); ?></td>
                                                  <td>
                                                    <input type="hidden" name="id" required="" value="<?php echo $idpasien; ?>">
                                                    <input type="hidden" name="namaruang" required="" value="<?php echo $row['nama_ruang']; ?>">
                                                    <input type="hidden" name="ruang" value="<?php echo $row['id'] ?>" required="">
                                                    <button class="btn btn-primary btn-action mr-1" name="rawatinap"><i class="fas fa-arrow-right"></i></button>
                                                    <input type="submit" class="btn btn-icon icon-right btn-success" name="print" value="Selesai">
                                                  <?php }
                                                  ?>
                                                  </td>
                                                </tr>
                                            </tbody>
                                          </table>
                                      <?php } else {
                                          echo 'Pasien sudah memesan kamar';
                                        }
                                      } ?>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php } ?>
                          </div>
                    </form>
                    <?php if (isset($_POST['print'])) { ?>

                      <!-- PART 5 -->
                      <div class="wizard-pane text-center">
                      <form method="POST" action="print.php" target="_blank">
                        <input type="hidden" name="id" value="<?php echo $passs; ?>">
                        <input type="hidden" name="idriwayat" value="<?php echo $penyyy; ?>">
                        <div class="btn-group">
                          <a href="rawat_jalan.php"class="btn btn-info" title="Ke Menu Utama" data-toggle="tooltip">Ke Menu Utama</a>
                          <button type="submit" class="btn btn-primary" name="printone" title="Print" data-toggle="tooltip"><i class="fas fa-print"></i> Cetak Struk Pembayaran</button>
                        </div>
                      </form>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include 'part/footer.php'; ?>
    </div>
  </div>
  <?php include "part/all-js.php";
  include "part/autocomplete.php"; ?>
</body>

</html>