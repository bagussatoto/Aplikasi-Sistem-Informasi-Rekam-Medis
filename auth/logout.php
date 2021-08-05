<?php
	session_start();
	unset($_SESSION['id_pegawai']);
	session_destroy();
	header('location:index.php');
?>