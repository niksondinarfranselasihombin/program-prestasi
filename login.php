<?php
include_once 'includes/config.php';

$config = new Config();
$db = $config->getConnection();

if ($_POST) {
    include_once 'includes/login.inc.php';
    $login = new Login($db);
    $login->userid = $_POST['username'];
    $login->passid = md5($_POST['password']);
    if ($login->login()) {
        echo "<script>location.href='index.php'</script>";
    } else {
        $msg = "Username / Password tidak sesuai!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPK Pemilihan Siswa Berperstasi </title>
     <link href="images/favicon.ico" rel="shortcut icon" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body background="assets/images/back1.jpg">
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                    <div class="panel panel-primary login-box">
                        <div class="panel-heading"><h3 class="text-center">LOGIN USER</h3></div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Username" autofocus="on">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password"><br>
                                <p>Masukkan Username dan Password Anda</p>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary raised btn-block">Login</button>
                            <br>
                            <p class="text-center"><?php include "footer.php"; ?></p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>
	<?php if (isset($msg)): ?>
    <script type="text/javascript">
		swal({
            title: "Maaf!",
            text: "<?=$msg?>",
            type: "error",
            timer: 2000,
            confirmButtonColor: "#DD6B55"
		})
	</script>
	<?php endif; ?>
</body>
</html>
