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

                $cek = $db->query("SELECT * FROM drone WHERE id = '$id';");
                if($cek->num_rows > 0) {
                    $delete = $db->query("DELETE FROM drone WHERE id = '$id';");
                    if($delete) {
                        echo '<div class="alert alert-success">Drone selesai dihapus.</div>'.getRedirect(2000, './drone.php');
                    } else {
                        echo '<div class="alert alert-danger">Drone gagal dihapus.</div>'.getRedirect(2000, './drone.php');
                    }
                } else {
                    echo '<div class="alert alert-info">Drone tidak ditemukan.</div>'.getRedirect(2000, './drone.php');
                }
            }
            ?>
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Daftar Sepatu</h5>

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
                                <th style="width: 100px">Foto Sepatu</th>
                                <th style="width: 300px">Nama Sepatu</th>
                                <th style="width: 200px">Harga Sepatu</th>
                                <th>Deskripsi</th>
                                <th style="width: 50px">Stok</th>
                                <th style="width: 100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $x = $db->query("SELECT * FROM drone");
                            while($f = $x->fetch_assoc()) {
                                $deskripsi = substr($f['deskripsi'], 0, 100);
                                $harga_sewa = rupiah($f['harga_sewa']);
                                echo "<tr> <td>$i.</td> <td><img src='{$f['lokasi_foto']}' class='img-circle' width='100' height='75'/></td> <td>{$f['nama']}</td> <td>{$harga_sewa}</td> <td>{$deskripsi}...</td> <td>{$f['stok']}</td> <td><a href='drone.php?edit={$f['id']}' class='btn btn-info btn-sm'><i class='fas fa-edit'></i></a> <a href='drone.php?hapus={$f['id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a></td> </tr>";
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
                $cekx = $db->query("SELECT * FROM drone WHERE id = '$id';");
                if($cekx->num_rows < 1) {
                    echo '<div class="alert alert-danger">Maaf, anda harus menginputkan data yang benar.</div>'.getRedirect(2500, './drone.php');
                } else {
                    $datacekx = $cekx->fetch_assoc(); ?>
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Edit Drone</h5>

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
                        if(isset($_POST['nama_drone']) && isset($_POST['deskripsi_drone']) && isset($_POST['stok_drone']) && isset($_POST['harga_sewa_drone'])) {
                            $nama_drone = $db->real_escape_string(trim($_POST['nama_drone']));
                            $deskripsi_drone = $db->real_escape_string(trim($_POST['deskripsi_drone']));
                            $stok_drone = $db->real_escape_string((int)$_POST['stok_drone']);
                            $harga_sewa_drone = $db->real_escape_string((int)$_POST['harga_sewa_drone']);
                            
                            if(!$nama_drone || !$deskripsi_drone || !$stok_drone || !$harga_sewa_drone) {
                                echo "<div class='alert alert-info'>Anda harus memasukkan data dengan benar.</div>".getRedirect(2000, './drone.php');
                            } else {
                                $cek = $db->query("SELECT * FROM drone WHERE id = '$id';");
                                if($cek->num_rows < 0) {
                                    echo "<div class='alert alert-info'>Drone tidak ada.</div>".getRedirect(2000, './drone.php');
                                } else {
                                    $cekxdata = $cek->fetch_assoc();
                                    $lokasi_foto = '';
                                    if(isset($_FILES['photo_drone']['error']) && $_FILES['photo_drone']['error'] > 0) {
                                        $lokasi_foto = $cekxdata['lokasi_foto'];
                                        $update = $db->query("UPDATE drone SET nama = '$nama_drone', harga_sewa = '$harga_sewa_drone', deskripsi = '$deskripsi_drone', lokasi_foto = '$lokasi_foto', stok = '$stok_drone' WHERE id = '$id';");
                                        if($update) {
                                            echo '<div class="alert alert-success">Drone berhasil diubah.</div>'.getRedirect(2000, './drone.php');
                                        } else {
                                            echo '<div class="alert alert-danger">Drone gagal diubah.</div>'.getRedirect(2000, './drone.php');
                                        }
                                    } else {
                                        @unlink($cekxdata['lokasi_foto']);
                                        $folder = './foto/';
                                        $lokasi_foto = rand(111111, 999999)."-x.";
                                        $lokasi_foto = $lokasi_foto . explode(".", $_FILES['photo_drone']['name'])[1];
                                        $tmp_name = $_FILES['photo_drone']['tmp_name'];
                                        $teruploadx = move_uploaded_file($tmp_name, $folder.$lokasi_foto);
                                        if($teruploadx) {
                                            $update = $db->query("UPDATE drone SET nama = '$nama_drone', harga_sewa = '$harga_sewa_drone', deskripsi = '$deskripsi_drone', lokasi_foto = '$lokasi_foto', stok = '$stok_drone' WHERE id = '$id';");
                                            if($update) {
                                                echo '<div class="alert alert-success">Drone berhasil diubah.</div>'.getRedirect(2000, './drone.php');
                                            } else {
                                                echo '<div class="alert alert-danger">Drone gagal diubah.</div>'.getRedirect(2000, './drone.php');
                                            }
                                        } else {
                                            echo '<div class="alert alert-warning">Foto tidak berhasil diupload. Gunakan format .png / image/png untuk memproses data.</div>'.getRedirect(2000, './drone.php');
                                        }
                                    }
                                }
                            }
                        } else {
                            print_r($_POST);
                        }
                    }
                    ?>
                    <form action="" method="POST" enctype='multipart/form-data'>
                        <div class="form-group">
                            <label>Nama Drone</label>
                            <input type="text" name="nama_drone" class="form-control form-control-border" placeholder="Masukkan nama drone" value="<?php echo $datacekx['nama'];?>" />
                        </div>
                        <div class="form-group">
                            <label>Photo Drone</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile" name="photo_drone">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Deskprisi Drone</label>
                            <textarea class="form-control" name="deskripsi_drone" placeholder="Drone ini adalah drone yang banyak disukai..."><?php echo $datacekx['deskripsi'];?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Stok Drone</label>
                            <input type="number" name="stok_drone" class="form-control form-control-border" placeholder="Ex. 1-20" value="<?php echo $datacekx['stok'];?>" />
                        </div>
                        <div class="form-group">
                            <label>Harga Sewa Drone</label>
                            <input type="number" name="harga_sewa_drone" class="form-control form-control-border" placeholder="Ex. 1000000" value="<?php echo $datacekx['harga_sewa'];?>" />
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
                <h5 class="card-title">Tambah Sepatu</h5>

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
                        if(isset($_POST['nama_drone']) && isset($_POST['deskripsi_drone']) && isset($_POST['stok_drone']) && isset($_POST['harga_sewa_drone']) && isset($_FILES['photo_drone'])) {
                            $nama_drone = $db->real_escape_string(trim($_POST['nama_drone']));
                            $deskripsi_drone = $db->real_escape_string(trim($_POST['deskripsi_drone']));
                            $stok_drone = $db->real_escape_string((int)$_POST['stok_drone']);
                            $harga_sewa_drone = $db->real_escape_string((int)$_POST['harga_sewa_drone']);
                            
                            if(!$nama_drone || !$deskripsi_drone || !$stok_drone || !$harga_sewa_drone) {
                                echo "<div class='alert alert-info'>Anda harus memasukkan data dengan benar.</div>".getRedirect(2000, './drone.php');
                            } else {
                                $cek = $db->query("SELECT * FROM drone WHERE nama LIKE '%$nama_drone%';");
                                if($cek->num_rows > 0) {
                                    echo "<div class='alert alert-info'>Drone sudah ada.</div>".getRedirect(2000, './drone.php');
                                } else {
                                    $folder = './foto/';
                                    $nama_acak = rand(111111, 999999)."-x.";
                                    $nama_acak = $nama_acak . explode(".", $_FILES['photo_drone']['name'])[1];
                                    $tmp_name = $_FILES['photo_drone']['tmp_name'];
                                    $terupload = move_uploaded_file($tmp_name, $folder.$nama_acak);
                                    if($terupload) {
                                        $insert = $db->query("INSERT INTO drone VALUES(NULL, '$nama_drone', '$harga_sewa_drone', '$deskripsi_drone', '$folder$nama_acak', '$stok_drone');");
                                        if($insert) {
                                            echo '<div class="alert alert-success">Drone berhasil ditambahkan.</div>'.getRedirect(2000, './drone.php');
                                        }
                                    } else {
                                        echo '<div class="alert alert-warning">Foto tidak berhasil diupload. Gunakan format .png / image/png untuk memproses data.</div>'.getRedirect(2000, './drone.php');
                                    }
                                }
                            }
                        }
                    }
                    ?>
                    <form action="" method="POST" enctype='multipart/form-data'>
                        <div class="form-group">
                            <label>Nama Sepatu</label>
                            <input type="text" name="nama_drone" class="form-control form-control-border" placeholder="" />
                        </div>
                        <div class="form-group">
                            <label>Photo Sepatu</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile" name="photo_drone">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Deskprisi Sepatu</label>
                            <textarea class="form-control" name="deskripsi_drone" placeholder=""></textarea>
                        </div>
                        <div class="form-group">
                            <label>Stok Sepatu</label>
                            <input type="number" name="stok_drone" class="form-control form-control-border" placeholder="Ex. 1-20" />
                        </div>
                        <div class="form-group">
                            <label>Harga Sepatu</label>
                            <input type="number" name="harga_sewa_drone" class="form-control form-control-border" placeholder="Ex. 1000000" />
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