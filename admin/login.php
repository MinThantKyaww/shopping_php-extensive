<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if (!empty($_POST)) {
    $email=$_POST['email'];
    $password=$_POST['password'];
    if (empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
      if (empty($_POST['email'])) {
            $emailError = 'email cannot be empty';
        }
        if (empty($_POST['password'])) {
            $passwordError = 'password cannot be empty';
        }
        if (strlen($_POST['password']) < 4) {
            $passwordError = 'password must be al least 4 characters';
        }
    } else {
      $sql="SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email',$email);
    // $stmt->bindValue(':password',$password);
    $stmt->execute();

    $user=$stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($user)) {
        echo "<script>alert('wrong user');</script>";
    }
    else{
        $passwordValid= password_verify($password,$user['password']);
        if ($passwordValid){
            if ($user['role'] == 1) {
              $_SESSION['user_name']=$user['name'];
              $_SESSION['user_id']=$user['id'];
              $_SESSION['logged_in']=time();
              $_SESSION['role']=$user['role'];
              header('location: index.php');
              exit();
            }else{
              echo "<script>alert('U are not admin');</script>";
            }
        }
    else{
        echo "<script>alert('wrong password ')</script>";
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
  <title>Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Log in</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post">
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <p style="color:red;"><?php echo empty($emailError) ? '' : $emailError;?></p>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <p style="color:red;"><?php echo empty($passwordError) ? '' : $passwordError;?></p>
        <div class="row">
          <div class="col-6">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
          <div class="col-6">
            <a class="btn btn-warning btn-block" href="register.php">Register</a>
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
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
