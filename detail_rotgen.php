<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $idnama = $_POST['id'];
  $page1 = "detrot";
  $page = "Foto Rotgen Pasien : " . $idnama;
  session_start();
  include 'auth/connect.php';
  include "part/head.php";
  $cek = mysqli_query($conn, "SELECT * FROM pasien WHERE nama_pasien='$idnama'");
  $pasien = mysqli_fetch_array($cek);
  $idid = $pasien['id'];
  $idpenyakit = $_POST['idriwayat'];

  $sqlimg = mysqli_query($conn, "SELECT * FROM foto_rotgen WHERE id_pasien='$idid' AND id_penyakit='$idpenyakit'");
  $penyakit = mysqli_query($conn, "SELECT * FROM riwayat_penyakit WHERE id_pasien='$idid' AND id='$idpenyakit'");
  $echopen = mysqli_fetch_array($penyakit);
  ?>
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      <?php
      include 'part/navbar.php';
      include 'part/sidebar.php';
      include 'part_func/umur.php';
      include 'part_func/tgl_ind.php';
      ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Foto Rotgen Pasien</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="pasien.php">Foto Rotgen</a></div>
              <div class="breadcrumb-item">Foto Rotgen : <?php echo ucwords($idnama); ?></div>
            </div>
          </div>

          <div class="section-body">
            <?php include 'part/info_pasien.php'; ?>

            <div class="section-body">
              <div class="row">
                <div class="col-12 col-sm-6 col-lg-12">
                  <div class="card">
                    <div class="card-header">
                      <h4>Info Pasien</h4>
                      <div class="card-header-action">
                        <form method="POST" action="print.php" target="_blank">
                          <input type="hidden" name="id" value="<?php echo $idnama; ?>">
                          <input type="hidden" name="idfoto" value="<?php echo $idpenyakit; ?>">
                          <?php
                          $cekrekam = mysqli_num_rows($rekam);
                          if ($cekrekam == 0) {
                            echo '';
                          } else {
                            echo '<button type="submit" class="btn btn-primary" name="print_foto">Print Foto</button> &emsp;';
                          } ?>
                          <a href="rawat_jalan.php" class="btn btn-primary">Rawat Jalan</a>
                        </form>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="gallery">
                        <table class="table table-striped table-sm">
                          <tbody>
                            <tr>
                              <th scope="row">Nama Lengkap</th>
                              <td> : <?php echo ucwords($idnama); ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Tanggal Lahir</th>
                              <td> : <?php echo tgl_indo($pasien['tgl_lahir']); ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Tinggi Bandan</th>
                              <td> : <?php echo $pasien['tinggi_badan'] . " cm"; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Berat Badan</th>
                              <td> : <?php echo $pasien['berat_badan'] . " kg"; ?></td>
                            </tr>
                            <tr>
                              <th scope="row">Alamat</th>
                              <td> : <?php echo $pasien['alamat']; ?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <h4>Semua Foto Rotgen Penyakit : <?php echo $echopen['penyakit']; ?></h4>
                    </div>
                    <div class="card-body">
                      <div class="gallery gallery-md">
                        <?php
                        if (mysqli_num_rows($sqlimg) == "0") {
                          echo 'Tidak ada data';
                        } else {
                          while ($img = mysqli_fetch_array($sqlimg)) {
                            echo '<div class="gallery-item" data-image="' . $img['directory'] . '" data-title="Penyakit : ' . $echopen['penyakit'] . ' (' . tgl_indo($echopen['tgl']) . ')"></div>';
                          }
                        } ?>
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
  <?php include "part/all-js.php"; ?>
</body>

</html>