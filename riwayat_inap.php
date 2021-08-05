<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  $page1 = "riwayatinap";
  $page = "Riwayat Rawat Inap";
  session_start();
  include 'auth/connect.php';
  include "part/head.php";
  include "part_func/tgl_ind.php";
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
                    <h4><?php echo "Catatan ".$page." Pasien"; ?></h4>
                    <div class="card-header-action">
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped" id="table-1">
                        <thead>
                          <tr>
                            <th>Nama Pasien</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Keluar</th>
                            <th>Biaya</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $sql = mysqli_query($conn, "SELECT * FROM riwayat_rawatinap");
                          $i = 0;
                          while ($row = mysqli_fetch_array($sql)) {
                            $defpasien = $row['id_pasien'];
                          ?>
                            <tr>
                              <td><?php
                                  $sqlnama = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$defpasien'");
                                  $namapasien = mysqli_fetch_array($sqlnama);
                                  echo '<b>Sdr. ' . ucwords($namapasien["nama_pasien"]) . '</b>';
                                  ?>
                              </td>
                              <td><?php echo tgl_indo($row['tgl_masuk']); ?></td>
                              <td><?php echo tgl_indo($row['tgl_keluar']); ?></td>
                              <td>Rp. <?php echo number_format($row['biaya'], 0, ".", "."); ?></td>
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