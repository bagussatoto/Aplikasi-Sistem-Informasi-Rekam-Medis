<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page = "Data Foto Rotgen";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";
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
                    <h4>Pasien yang memiliki foto rotgen</h4>
                    <div class="card-header-action">
                      <a href="rawat_jalan.php" class="btn btn-primary">Tambah Foto Rotgen</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped" id="table-1">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th>Nama Pasien</th>
                            <th>Jumlah Foto Rotgen</th>
                            <th>Nama Penyakit</th>
                            <th>Biaya</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $sql = mysqli_query($conn, "SELECT *, COUNT(*) FROM foto_rotgen GROUP BY id_pasien, id_penyakit");
                          $i = 0;
                          while ($row = mysqli_fetch_array($sql)) {
                            $idpasien = $row['id_pasien'];
                            $idpenyakit = $row['id_penyakit'];
                            $sqlpasien = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$idpasien'");
                            $pasien = mysqli_fetch_array($sqlpasien);
                            $sqlpenyakit = mysqli_query($conn, "SELECT * FROM riwayat_penyakit WHERE id='$idpenyakit'");
                            $penyakit = mysqli_fetch_array($sqlpenyakit);
                            $idriwayat = $penyakit['id'];
                            $i++;
                          ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo ucwords($pasien['nama_pasien']); ?></td>
                              <td><?php echo $row['COUNT(*)']; ?> Foto</td>
                              <td><?php echo ucwords($penyakit['penyakit']); ?></td>
                              <td>Rp. <?php echo number_format($row['biaya'], 0, ".", "."); ?></td>
                              <td align="center">
                                <div class="btn-group">
                                  <form method="POST" action="detail_rotgen.php">
                                    <input type="hidden" name="id" value="<?php echo $pasien['nama_pasien']; ?>">
                                    <input type="hidden" name="idriwayat" value="<?php echo $idriwayat ?>">
                                    <button type="submit" class="btn btn-info" name="detail" title="Menampilkan semua foto rotgen" data-toggle="tooltip">Info Detail</button>
                                  </form>
                                  &emsp;
                                  <form method="POST" action="print.php" target="_blank">
                                    <input type="hidden" name="id" value="<?php echo $pasien['nama_pasien']; ?>">
                                    <input type="hidden" name="idfoto" value="<?php echo $idpenyakit ?>">
                                    <button type="submit" class="btn btn-primary" name="print_foto" title="Print" data-toggle="tooltip">Print</button>
                                  </form>
                                </div>
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
      <?php include 'part/footer.php'; ?>
    </div>
  </div>
  <?php include "part/all-js.php"; ?>

</body>

</html>