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
                <h5 class="card-title">Daftar Pemesanan</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="width: 150px">No Transaksi</th>
                                <th style="width: 300px">Jumlah Sepatu</th>
                                <th style="width: 200px">Total Bayar</th>
                                <th style="width: 150px">Status Barang</th>
                                <th style="width: 170px">Status Pembayaran</th>
                                <th style="width: 100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $cex = $db->query("SELECT transaksi.id as id, transaksi.no_transaksi as no_transaksi, drone.nama as nama, transaksi.total_bayar as total_bayar, transaksi.status_barang as status_barang, transaksi.status_pembayaran as status_pembayaran FROM transaksi JOIN users ON users.id = transaksi.id_user JOIN pemesan ON pemesan.id = transaksi.id_pemesan JOIN drone ON drone.id = transaksi.id_drone");
                            // $xfzxf = $cex->fetch_assoc();
                            // print_r($xfzxf);die;
                            while($data_cex = $cex->fetch_assoc()) {
                                echo "<tr> <td>$i.</td> <td>{$data_cex['no_transaksi']}</td> <td>{$data_cex['nama']}</td> <td>{$data_cex['total_bayar']}</td> <td>{$data_cex['status_barang']}</td> <td>{$data_cex['status_pembayaran']}</td> <td><a href='?edit={$data_cex['id']}' class='btn btn-danger'><i class='fas fa-edit'></i></a></td> </tr>";
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
              </div>
              <!-- ./card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <?php if(isset($_GET['edit'])) {
            $id = $db->real_escape_string((int)$_GET['edit']);
            $datad = $db->query("SELECT * FROM transaksi WHERE id = '$id';")->fetch_assoc(); 
        ?>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Edit Pemesanan | No Transaksi : <?php echo $datad['no_transaksi']; ?></h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php 
                if($_SERVER['REQUEST_METHOD']  == "POST") {
                    if(isset($_POST['status_pembayaran']) && isset($_POST['status_barang'])) {
                        $status_pembayaran = $db->real_escape_string(trim($_POST['status_pembayaran']));
                        $status_barang = $db->real_escape_string(trim($_POST['status_barang']));
                        
                        $update = $db->query("UPDATE transaksi SET status_pembayaran = '$status_pembayaran', status_barang = '$status_barang' WHERE id = '$id';");
                        if($update) {
                            echo "<div class='alert alert-success'>Sukses, Data telah diubah !.</div>".getRedirect(3000, "./daftar_pemesanan.php");
                        } else {
                            echo "<div class='alert alert-danger'>Gagal, data gagal diubah !.</div>".getRedirect(3000, "./daftar_pemesanan.php");
                        }

                    }
                }
                ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Status Pembayaran</label>
                        <select name="status_pembayaran" class="form-control">
                            <option value="Belum Terbayar" <?php if($datad['status_pembayaran'] == "Belum Terbayar") { echo "selected"; } ?>>Belum Terbayar</option>
                            <option value="Terbayar" <?php if($datad['status_pembayaran'] == "Terbayar") { echo "selected"; } ?>>Terbayar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status Barang</label>
                        <select name="status_barang" class="form-control">
                            <option value="Masih Disewa" <?php if($datad['status_barang'] == "Masih Disewa") { echo "selected"; } ?>>Sedang Terkirim</option>
                            <option value="Dikembalikan" <?php if($datad['status_barang'] == "Dikembalikan") { echo "selected"; } ?>>Sudah Terkirim</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-info">Ubah</button> <button type="reset" class="btn btn-danger">Reset</button>
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
        <?php } ?>

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