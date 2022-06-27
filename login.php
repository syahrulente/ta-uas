<?php
session_start();
require 'koneksi.php';
if(isset($_SESSION['is_login'])) {
    header('location: index.php');
    die;
}

require 'header.phtml';
?>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="./" class="h1">Stuff <b>Good</b> !</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg"><?php
      if($_SERVER['REQUEST_METHOD'] == "POST") {
        if($_POST['username'] && $_POST['password']) {
          $username = $db->real_escape_string(trim($_POST['username']));
          $password = $db->real_escape_string(trim($_POST['password']));

          $cekUser = $db->query("SELECT * FROM users WHERE username = '$username';");
          if($cekUser->num_rows > 0) {
            $dataUser = $cekUser->fetch_assoc();
            if($password == $dataUser['password']) {
              $_SESSION['is_login'] = true;
              $_SESSION['username'] = $username;

              echo '<div class="alert alert-success">Sukses, anda akan dialihkan dalam 3 detik.</div><script>setTimeout(() => { window.location.href = "./index.php"; }, 3000);</script>';
            } else {
              echo '<div class="alert alert-info">Password anda salah.</div>';
            }
          } else {
            echo '<div class="alert alert-info">Username tidak terdaftar.</div>';
          }
        } else {
          echo '<div class="alert alert-info">Masukkan data yang benar.</div>';
        }
      } else {
        echo "Masuk untuk melanjutkan";
      }
      ?></p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Masukkan username anda">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi anda">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
<?php require_once 'footer.phtml'; ?>