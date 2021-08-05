<?php
$page = "Detail Pembayaran Rawat Inap";
include "part/head.php";
include 'part_func/tgl_ind.php';
include 'auth/connect.php';

$id = $_GET['id'];
$datenow = date('Y-m-d');
$cek = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$id'");
$pasien = mysqli_fetch_array($cek);

$sql = mysqli_query($conn, "SELECT * FROM ruang_inap WHERE id_pasien='$id'");
$getdata = mysqli_fetch_array($sql);
$tglmasuk = $getdata['tgl_masuk'];
$biaya = $getdata['biaya'];
$idruang = $getdata['id'];

$date1 = date_create($tglmasuk);
$date2 = date_create($datenow);
$diff = date_diff($date1, $date2);
$hitunghari = $diff->format("%a");

$lay = $hitunghari * $biaya;
?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4><?php echo $page; ?></h4>
      </div>
      <div class="card-body">
        <div class="gallery">
          <table class="table table-striped table-sm">
            <tbody>
              <tr>
                <th scope="row">Nama Lengkap</th>
                <td> : <?php echo ucwords($pasien['nama_pasien']); ?></td>
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
                <th scope="row">Alamat Pasien</th>
                <td> : <?php echo $pasien['alamat']; ?></td>
              </tr>
              <tr>
                <th scope="row">Tanggal Masuk</th>
                <td> : <?php echo tgl_indo($tglmasuk); ?></td>
              </tr>
              <tr>
                <th scope="row">Tanggal Keluar</th>
                <td> : <?php echo tgl_indo($datenow); ?></td>
              </tr>
              <tr>
                <th scope="row">Biaya Kamar per Hari</th>
                <td> : <?php echo "Rp. " . number_format($biaya, 0, ".", ".") . " x " . $hitunghari . "Hari"; ?></td>
              </tr>
              <tr>
                <th scope="row">Total Biaya yang harus dibayar</th>
                <th> : <?php echo "Rp. " . number_format($lay, 0, ".", "."); ?></th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  window.print();
</script>

<?php
$riwayat = mysqli_query($conn, "INSERT INTO riwayat_rawatinap (id_pasien, tgl_masuk, tgl_keluar, biaya) VALUES ('$id', '$tglmasuk', '$datenow', '$biaya')");
$ruangan = mysqli_query($conn, "UPDATE ruang_inap SET id_pasien = NULL, tgl_masuk = NULL, status='0' WHERE id='$idruang'");
?>