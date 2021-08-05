<h2 class="section-title"><?php echo ucwords($idnama); ?> (<?php echo umur($pasien['tgl_lahir']); ?>) </h2>
<p class="section-lead">
  <?php
  $rekam = mysqli_query($conn, "SELECT * FROM riwayat_penyakit WHERE id_pasien='$idid'");
  $cekrekam = mysqli_num_rows($rekam);
  if ($cekrekam == 0) {
    echo 'Pasien belum memiliki catatan medis';
  } else {
    echo 'Pasien memiliki ' . $cekrekam . ' catatan medis';
  }
  ?>
</p>