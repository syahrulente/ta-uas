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
                <h5 class="card-title">Dasbor</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                Selamat datang di Toko Sepatu Stuff Good!
              </div>
              <!-- ./card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Pencarian Sepatu</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body align-center">
                <form action="" method="GET">
                  Nama Sepatu : <input type="text" name="kata" class="form-control" placeholder="Masukkan nama sepatu" value="<?php echo (isset($_GET['kata']) ? $_GET['kata'] : ""); ?>" /><br/><button class="btn btn-primary btn-flat btn-md">Cari</button>
                </form>
              </div>
              <!-- ./card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">List Sepatu</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body align-center">
                <div class="row">
                  <?php
                  $i = 1;
                  $batas = 3;
                  $halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
                  $halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;

                  $previous = $halaman - 1;
                  $next = $halaman + 1;

                  $data = $db->query("SELECT * FROM drone ".(isset($_GET['kata']) ? "WHERE nama LIKE '%{$_GET['kata']}%'" : ""));
                  $jumlah_data = $data->num_rows;
                  $total_halaman = ceil($jumlah_data / $batas);
                  $data_pegawai = $db->query("select * from drone ".(isset($_GET['kata']) ? "WHERE nama LIKE '%{$_GET['kata']}%'" : "")." limit $halaman_awal, $batas");
                  $nomor = $halaman_awal+1;
                  while($f = $data_pegawai->fetch_assoc()) {
                      echo '<div class="col-md-4 mb-5">';
                      $deskripsi = substr($f['deskripsi'], 0, 100);
                      $harga_sewa = rupiah($f['harga_sewa']);
                      echo "<img src='{$f['lokasi_foto']}' class='img-circle' width='100' height='100'/><br/><br/>{$f['nama']} | {$harga_sewa}<br/><small>{$deskripsi}</small><br/><br/><a href='./pemesanan.php' class='btn btn-sm btn-flat btn-primary'>Pilih</a>";
                      $i++;
                      echo '</div>';
                  }
                  ?>
                </div>
                <ul class="pagination pagination-sm m-0">
                  <li class="page-item">
                    <a class="page-link" <?php if($halaman > 1){ echo "href='?halaman=$previous'"; } ?>>Previous</a>
				          </li>
                  <?php
                  for($x=1;$x<=$total_halaman;$x++){
                    echo '<li class="page-item"><a class="page-link" href="?halaman='.$x.'">'.$x.'</a></li>';
                  }
                  ?>
                  <li class="page-item">
                    <a class="page-link" <?php if($halaman < $total_halaman) { echo "href='?halaman=$next'"; } ?>>Next</a>
                  </li>
                </ul>
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