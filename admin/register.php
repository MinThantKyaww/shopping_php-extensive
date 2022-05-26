<?php
session_start();
require 'config.php';
require 'commmon.php';

if (!empty($_POST)) {
    $username=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    if ($username=='' || $email=='' || $password=='') {
        echo "<script>alert('fill the form data')</script>";
    }
    else {
        $sql="SELECT COUNT(email) AS num FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email',$email);
        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['num']>0) {
            echo "<script>alert('This user already exict,try again!')</script>";
        }
        else {
            $passwordHash = password_hash($password,PASSWORD_BCRYPT);

            $sql = "INSERT INTO users(name,email,password,role) VALUES(:name,:email,:password,:role)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name',$username);
            $stmt->bindValue(':email',$email);
            $stmt->bindValue(':password',$passwordHash);
            $stmt->bindValue(':role',1);

            $result = $stmt->execute();

            if ($result) {
                echo "<script>alert('Thanks for ur registeration');window.location.href='index.php';</script>";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>registeration</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Register</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg"></p>

      <form action="register.php" method="post">
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
          <div class="col-6">
            <a class="btn btn-warning btn-block" href="login.php">Log in</a>
          </div>
          <!-- /.col -->
        </div>
      </form>


      <!-- /.social-auth-links -->



    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
