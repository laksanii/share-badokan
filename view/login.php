<?php  
session_start();
require '../db/dbconnect.php';
if (isset($_SESSION["role"])){
    $id = $_SESSION["id_user"];
    header("Location: profile.php?id=$id");
}
if (isset($_POST['submit'])) {
    $usernameoremail = $_POST['usernameoremail'];
    $password = $_POST['password'];
    
    if($usernameoremail != "" && $password != "") {

        $sql_login = "SELECT * FROM user WHERE (email = :usernameoremail OR username = :usernameoremail) AND password = SHA2(:password, 0)";
        $stm2 = $dbc->prepare($sql_login);
        $stm2->bindValue(':usernameoremail', $_POST['usernameoremail']);
        $stm2->bindValue(':password', $_POST['password']);
        $stm2->execute();
        $row = $stm2->fetch(PDO::FETCH_ASSOC);
        if($stm2->rowCount() > 0) {
			$id = $row["id_user"];
            if($row['role'] == 'member') {
				$_SESSION["role"] = "member";
				$_SESSION["id_user"] = $id;
                header("location: beranda.php");
                exit();
            }
            else {
				$_SESSION["role"] = "admin";
                $_SESSION["id_user"] = $id;
                header("location: beranda.php");
                exit();
            }
        } else {
            $_SESSION['error'] = '~Data Yang di Masukkan Salah Atau Tidak Sesuai~';
        }
            
    }else {
            if($usernameoremail == "") {
			$_SESSION['error_nama'] = '~Nama Harus Di Isi~';
			}
			if($password == "") {
			$_SESSION['error_password'] = '~Password Harus Di Isi~';	
			}
				
			$_SESSION['usernameoremail'] = $usernameoremail;
        }

    
            

}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/login_regis.css">
</head>

<body>

    <div class="kotak_login_regis">
        <p class="tulisan_atas_kotak">Sharing Badokan </p>

        <form action="login.php" method="post">
            <label>Email or Username</label>
            <?php
							if (isset($_SESSION['error_nama'])) {
								echo $_SESSION['error_nama'];
								unset($_SESSION['error_nama']);
							}
						?>

            <input type="text" name="usernameoremail" class="form_login_regis"
                value="<?php if(isset($_SESSION['usernameoremail'])){echo $_SESSION['usernameoremail']; unset($_SESSION['usernameoremail']);} ?>"
                placeholder="Username or Password">

            <label>Password</label>
            <?php
							if (isset($_SESSION['error_password'])) {
								echo $_SESSION['error_password'];
								unset($_SESSION['error_password']);
							}
						?>

            <input type="password" name="password" class="form_login_regis" placeholder="Password">
            <?php
							if (isset($_SESSION['error'])) {
								echo $_SESSION['error'];
								unset($_SESSION['error']);
							}
						?>
            <input type="submit" name="submit" class="tombol_login_regis" value="LOGIN">

            <br />
            <br />
            <center>
                <span>
                    Don't have account?<a class="link" href="register.php"> Register</a> now
                </span>
            </center>
        </form>

    </div>


</body>

</html>