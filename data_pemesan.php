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
            <?php
            if(isset($_GET['hapus'])) {
                $id = $db->real_escape_string((int)$_GET['hapus']);

                $cek = $db->query("SELECT * FROM pemesan WHERE id = '$id';");
                if($cek->num_rows > 0) {
                    $delete = $db->query("DELETE FROM pemesan WHERE id = '$id';");
                    if($delete) {
                        echo '<div class="alert alert-success">Pemesan selesai dihapus.</div>'.getRedirect(2000, './data_pemesan.php');
                    } else {
                        echo '<div class="alert alert-danger">Pemesan gagal dihapus.</div>'.getRedirect(2000, './data_pemesan.php');
                    }
                } else {
                    echo '<div class="alert alert-info">Pemesan tidak ditemukan.</div>'.getRedirect(2000, './data_pemesan.php');
                }
            }
            ?>
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Daftar Pemesan</h5>

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
                                <th style="width: 50px">Nama</th>
                                <th style="width: 70px">No KTP</th>
                                <th style="width: 30px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr><td>A</td><td>A</td><td>A</td><td>A</td></tr> -->
                            <?php
                            $i = 1;
                            $x = $db->query("SELECT * FROM pemesan");
                            while($f = $x->fetch_assoc()) {
                                $no_ktp = substr($f['no_ktp'], 0, 10);
                                echo "<tr> <td>$i.</td> <td>{$f['nama']}</td> <td>{$no_ktp}*******</td> <td><a href='data_pemesan.php?edit={$f['id']}' class='btn btn-info btn-sm'><i class='fas fa-edit'></i></a> <a href='data_pemesan.php?hapus={$f['id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a></td></tr>";
                                $i++;
                            }
                            ?>
                            <!--  -->
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
        </div>
        <div class="col-md-12">
            <?php if(isset($_GET['edit'])) {
                $id = $db->real_escape_string((int)$_GET['edit']);
                $cekx = $db->query("SELECT * FROM pemesan WHERE id = '$id';");
                if($cekx->num_rows < 1) {
                    echo '<div class="alert alert-danger">Maaf, anda harus menginputkan data yang benar.</div>'.getRedirect(2500, './data_pemesan.php');
                } else {
                    $datacekx = $cekx->fetch_assoc(); ?>
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Edit Pemesan</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                    <?php 
                    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['edit'])) {
                        if(isset($_POST['nama_pemesan']) && isset($_POST['nomor_ktp_pemesan'])) {
                            $nama_pemesan = $db->real_escape_string(trim($_POST['nama_pemesan']));
                            $nomor_ktp_pemesan = $db->real_escape_string((int)$_POST['nomor_ktp_pemesan']);
                            
                            if(!$nama_pemesan || !$nomor_ktp_pemesan) {
                                echo "<div class='alert alert-info'>Anda harus memasukkan data dengan benar.</div>".getRedirect(2000, './data_pemesan.php');
                            } else {
                                $cek = $db->query("SELECT * FROM pemesan WHERE id = '$id';");
                                if($cek->num_rows < 0) {
                                    echo "<div class='alert alert-info'>Pemesan tidak ada.</div>".getRedirect(2000, './data_pemesan.php');
                                } else {
                                    $cekxdata = $cek->fetch_assoc();
                                    $update = $db->query("UPDATE pemesan SET nama = '$nama_pemesan', no_ktp = '$nomor_ktp_pemesan' WHERE id = '$id';");
                                    if($update) {
                                        echo '<div class="alert alert-success">Pemesan selesai diubah.</div>'.getRedirect(2000, './data_pemesan.php');
                                    } else {
                                        echo '<div class="alert alert-danger">Pemesan gagal diubah.</div>'.getRedirect(2000, './data_pemesan.php');
                                    }
                                }
                            }
                        } else {
                        }
                    }
                    ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>Nama Pemesan</label>
                            <input type="text" name="nama_pemesan" class="form-control form-control-border" placeholder="Masukkan nama pemesan" value="<?php echo $datacekx['nama']; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Nomor KTP Pemesan</label>
                            <input type="number" name="nomor_ktp_pemesan" class="form-control form-control-border" placeholder="Ex. 3200005xxxx" value="<?php echo $datacekx['no_ktp']; ?>" />
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info">Ubah</button> <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </form>
              </div>
              <!-- ./card-body -->
            </div>
            <!-- /.card -->
            <?php } } else { ?>
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Tambah Pemesan</h5>

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
                        if(isset($_POST['nama_pemesan']) && isset($_POST['nomor_ktp_pemesan'])) {
                            $nama_pemesan = $db->real_escape_string(trim($_POST['nama_pemesan']));
                            $nomor_ktp_pemesan = $db->real_escape_string((int)$_POST['nomor_ktp_pemesan']);
                            
                            if(!$nama_pemesan || !$nomor_ktp_pemesan) {
                                echo "<div class='alert alert-info'>Anda harus memasukkan data dengan benar.</div>".getRedirect(2000, './data_pemesan.php');
                            } else {
                                $cek = $db->query("SELECT * FROM pemesan WHERE nama LIKE '%$nama_pemesan%';");
                                if($cek->num_rows > 0) {
                                    echo "<div class='alert alert-info'>Pemesan sudah ada.</div>".getRedirect(2000, './data_pemesan.php');
                                } else {
                                    $insert = $db->query("INSERT INTO pemesan VALUES(NULL, '$nama_pemesan', '$nomor_ktp_pemesan');");
                                    if($insert) {
                                        echo '<div class="alert alert-success">Pemesan berhasil ditambahkan.</div>'.getRedirect(2000, './data_pemesan.php');
                                    }
                                }
                            }
                        }
                    }
                    ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>Nama Pemesan</label>
                            <input type="text" name="nama_pemesan" class="form-control form-control-border" placeholder="Masukkan nama pemesan" />
                        </div>
                        <div class="form-group">
                            <label>Nomor KTP Pemesan</label>
                            <input type="number" name="nomor_ktp_pemesan" class="form-control form-control-border" placeholder="Ex. 3200005xxxx" />
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info">Tambahkan</button> <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </form>
              </div>
              <!-- ./card-body -->
            </div>
            <!-- /.card -->
            <?php } ?>
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