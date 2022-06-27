<?php
date_default_timezone_set("Asia/Jakarta");
$db = new mysqli("localhost","id18797954_root","cJ?rK%P0B+%Q|!<r","id18797954_galang");

// Check connection
if($db->connect_errno) {
  echo "Failed to connect to MySQL: " . $db->connect_error;
  exit();
}

function getRedirect($delay, $to) {
  return '<script>setTimeout(() => { window.location.href = "'.$to.'"; }, '.$delay.');</script>';
}

function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}
?> 