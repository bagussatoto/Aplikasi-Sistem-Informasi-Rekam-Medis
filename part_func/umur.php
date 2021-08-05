<?php
function umur($tgl_lahir){
  $lahir = new DateTime($tgl_lahir);
  $hari_ini = new DateTime();
    
  $diff = $hari_ini->diff($lahir);
    
  echo $diff->y ." Tahun";
  if($diff->m > 0){
  echo " ". $diff->m ." Bulan";
  }
  }
?>