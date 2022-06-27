<?php
session_start();
require 'koneksi.php';
if(!isset($_SESSION['is_login'])) {
    header('location: login.php');
    die;
}
$myData = $db->query("SELECT * FROM users WHERE username = '{$_SESSION['username']}';")->fetch_assoc(); 
require 'header.phtml';
?>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php require 'sidebar.phtml'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dasbor Utama</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dasbor Utama</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php require 'box.phtml'; ?>

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Pemesanan Sepatu Baru</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                    if(isset($_POST['id_pemesan']) && isset($_POST['tanggal']) && isset($_POST['id_drone'])) {
                        $id_pemesan = $db->real_escape_string((int)$_POST['id_pemesan']);
                        $tanggal = $db->real_escape_string(trim($_POST['tanggal']));
                        $id_drone = $db->real_escape_string((int)$_POST['id_drone']);

                        if(!$id_pemesan || !$tanggal || !$id_drone) {
                            echo "<div class='alert alert-info'>Anda harus memasukkan data dengan benar.</div>".getRedirect(3000, "./pemesanan.php");
                        } else {
                            $no_transaksi = "DRNKU-".rand(11111111, 99999999);
                            $cekPemesan = $db->query("SELECT * FROM pemesan WHERE id = '$id_pemesan';");
                            if($cekPemesan->num_rows > 0) {
                                $dataPemesan = $cekPemesan->fetch_assoc();

                                $cekDrone = $db->query("SELECT * FROM drone WHERE id = '$id_drone';");
                                if($cekDrone->num_rows > 0) {
                                    $dataDrone = $cekDrone->fetch_assoc();

                                    $cekTransaksi = $db->query("SELECT * FROM transaksi WHERE no_transaksi = '$no_transaksi';");
                                    if($cekTransaksi->num_rows < 1) {
                                        $total_bayar = $dataDrone['harga_sewa'];
                                        $tgl = date("Y-m-d H:i:s", strtotime($tanggal));
                                        $insert = $db->query("INSERT INTO transaksi VALUES(NULL, '$no_transaksi', '{$myData['id']}', '$id_pemesan', '$total_bayar', '$id_drone', 'Masih Disewa', 'Belum Terbayar', '$tgl');");
                                        if($insert) {
                                            echo "<div class='alert alert-success'>Sukses, transaksi anda telah berhasil !<br>Nama : {$dataPemesan['nama']}<br>Sewa : {$dataDrone['nama']}<br>Tanggal : {$tanggal}<br>No Transaksi : $no_transaksi<br><br>Catat ini dan bayar langsung agar bisa diambil barang tersebut.</div>".getRedirect(4500, "./pemesanan.php");
                                        } else {
                                            echo "<div class='alert alert-danger'>Gagal utuk transaksi.</div>".getRedirect(3000, "./pemesanan.php");
                                        }
                                    } else {
                                        echo "<div class='alert alert-info'>Transaksi tidak boleh sama alias no transaksi sudah ada.</div>".getRedirect(3000, "./pemesanan.php");
                                    }
                                } else {
                                    echo "<div class='alert alert-info'>Drone tidak terdaftar.</div>".getRedirect(3000, "./pemesanan.php");
                                }
                            } else {
                                echo "<div class='alert alert-info'>Pemesan tidak terdaftar.</div>".getRedirect(3000, "./pemesanan.php");
                            }
                        }
                    }
                } else {
                    echo "<div class='alert alert-info'>Informasi :<br>Sebelum menambahkan pemesanan, harap tambahan terlebih dahulu pemesan di menu Data Pemesan</div>";
                }
                ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Nama Pemesan</label>
                        <select name="id_pemesan" class="form-control">
                            <option value="-">Pilih salah satu</option>
                            <?php
                            $cekPemesan = $db->query("SELECT * FROM pemesan");
                            while($fetchPemesan = $cekPemesan->fetch_assoc()) {
                                echo "<option value='{$fetchPemesan['id']}'>{$fetchPemesan['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pemesanan</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" name="tanggal">
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sepatu</label>
                        <select name="id_drone" class="form-control">
                            <option value="-">Pilih salah satu</option>
                            <?php
                            $cekDrone = $db->query("SELECT * FROM drone");
                            while($fetchDrone = $cekDrone->fetch_assoc()) {
                                echo "<option value='{$fetchDrone['id']}'>{$fetchDrone['nama']} - Stok {$fetchDrone['stok']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-info">Tambahkan</button> <button type="reset" class="btn btn-danger">Reset</button>
                    </div> 
                </form>
              </div>
              <!-- ./card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.1.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->
<?php require_once 'footer.phtml'; ?>