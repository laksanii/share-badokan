<?php
	session_start();
	require '../db/dbconnect.php';

	if(isset($_SESSION["role"]) && $_SESSION["role"] != "admin"){
		header('Location: login.php'); //Jika belum login maka akan diarahkan ke halaman login
    	exit(); 
	}
	
	$role = !isset($_SESSION["role"])  ? "member" : "admin"; 
	$_SESSION["inpute"] = "";
	
	$sql = "SELECT * FROM user";
	$stm = $dbc->prepare($sql);
	$stm->execute();
	if(isset($_POST['submit'])){
		$nama = $_POST['nama'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		$nomer = $_POST['nomer'];
		
		$namaVali = FALSE;
		$usernameVali = FALSE;
		$emailVali = FALSE;
		$passwordVali = FALSE;
		$nomerVali = FALSE;
		
		// var_dump($_POST);
		// die;
		
		//nama validasi huruf
		$valinama  = preg_match('/^[a-zA-Z ]*$/',$nama);
		if($nama == "") {
			$_SESSION['error_nama'] = '~Nama Harus Di Isi~';
		}elseif(!$valinama) {
			$_SESSION['error_nama'] = '~Nama Hanya Boleh Huruf dan Spasi~';
		}elseif(strlen($nama) < 4 || strlen($nama) > 30 ) {
			$_SESSION['error_nama'] = '~Nama Harus Lebih Dari 3 Huruf dan Kurang Dari 31 Huruf~';
		}else{
			$namaVali = TRUE;
		}
				
			
		//username validasi harus hanya huruf dan angka
		$sql_username = "SELECT username FROM user WHERE username = :username";
		$stm2 = $dbc->prepare($sql_username);
		$stm2->bindValue(':username', strtolower($_POST['username']));
		$stm2->execute();
		$row = $stm2->fetch(PDO::FETCH_ASSOC);

		if($username == "") {
		$_SESSION['error_username'] = '~Username Harus Di Isi~';		
		}elseif(!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
			$_SESSION['error_username'] = '~Username Harus Berupa Huruf/Angka Tanpa Spasi~';
		}elseif(strlen($username) < 6 || strlen($username) > 16 ) {
			$_SESSION['error_username'] = '~Username Harus Lebih Dari 5 Karakter dan Kurang Dari 17 Karakter~';
		}elseif($stm2->rowCount() != 0){		  
			$_SESSION['error_username'] = '~Username Telah Terpakai';
		}else{
			$usernameVali = TRUE;
		}
	
		
		//validasi email
		$sql_email = "SELECT email FROM user WHERE email = :email";
		$stm3 = $dbc->prepare($sql_email);
		$stm3->bindValue(':email', strtolower($_POST['email']));
		$stm3->execute();
		$row = $stm3->fetch(PDO::FETCH_ASSOC);
		
		if($email == "") {
		$_SESSION['error_email'] = '~Email Harus Di Isi~';	
		}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['error_email'] = '~Email Tidak Valid~';
		}elseif($stm3->rowCount() != 0){		  
			$_SESSION['error_email'] = '~Email Telah Terpakai';
		}else{
			$emailVali = TRUE;	
		}
				
				
		//validasi nomer
		if($nomer == "") {
			$_SESSION['error_nomer'] = '~Nomer Harus Di Isi~';
		}elseif(!is_numeric($nomer)) {
		$_SESSION['error_nomer'] = '~Inputan Harus Berupa Angka~';
		}elseif(strlen($nomer) < 9 || strlen($nomer) > 13 ) {
			$_SESSION['error_nomer'] = "~Nomer Harus Lebih Dari 8 dan Kurang Dari 14 Angka~";
		}else{
			$nomerVali = TRUE;
		}
				
		
		//validasi password 
		$uppercase = preg_match('@[A-Z]@', $password1);
		$lowercase = preg_match('@[a-z]@', $password1);
		$number    = preg_match('@[0-9]@', $password1);		
		if($password1 == "") {
			$_SESSION['error_password1'] = '~Password Harus Di Isi~';
		}elseif($password2 == "") {
			$_SESSION['error_password2'] = '~Confirmasi Password Harus Di Isi~';	
		}elseif(strlen($password1) < 8 || strlen($password1) > 18 ){
			$_SESSION['error_password1'] = '~Password Harus Lebih Dari 7 dan kurang dari 19~';
		}elseif(!$uppercase || !$lowercase || !$number ) {
			$_SESSION['error_password1'] = '~Harus Memiliki Huruf Besar, Kecil dan Angka~';
		}elseif($password1 !== $password2) {	
			$_SESSION['error_password2'] = '~Password Tidak Sama~';
		}else{
			$passwordVali = TRUE;
		}
			
			
		//input ke db
		if ($namaVali && $usernameVali && $nomerVali && $emailVali && $passwordVali) {
				$sql_insert = "INSERT INTO user (username, nama, email, password, no_telp, role) VALUES(:username, :nama, :email, SHA2(:password, 0), :nomer, :role)";
				$stm4 = $dbc->prepare($sql_insert);
				$stm4->bindValue(':username', strtolower($_POST['username']));
				$stm4->bindValue(':nama', strtolower($_POST['nama']));
				$stm4->bindValue(':email', strtolower($_POST['email']));
				$stm4->bindValue(':password', $_POST['password1']);
				$stm4->bindValue(':nomer', $_POST['nomer']);
				$stm4->bindValue(':nomer', $_POST['nomer']);
				$stm4->bindValue(':role', $_POST['submit']);


				if($stm4->execute()){
					$_SESSION['inpute'] = "Data berhasil disimpan";
				} else {
					$_SESSION['inpute'] = "Data gagal disimpan";
				}

			}else{
					$_SESSION['nama'] = $nama;
					$_SESSION['username'] = $username;
					$_SESSION['email'] = $email;
					$_SESSION['nomer'] = $nomer;
			}

}
		
?>
<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/login_regis.css">
</head>

<body>


    <div class="kotak_login_regis">
        <p class="tulisan_atas_kotak"><?= $role != "admin" ? '' : 'Admin' ?>Register</p>

        <form action="register.php" method="post">
            <label>Nama</label>
            <?php
							if (isset($_SESSION['error_nama'])) {
								echo $_SESSION['error_nama'];
								unset($_SESSION['error_nama']);
							}
						?>
            <input type="text" name="nama" class="form_login_regis"
                value="<?php if(isset($_SESSION['nama'])){echo $_SESSION['nama']; unset($_SESSION['nama']);} ?>"
                placeholder="Nama">

            <label>Username</label>
            <?php
							if (isset($_SESSION['error_username'])) {
								echo $_SESSION['error_username'];
								unset($_SESSION['error_username']);
							}
						?>
            <input type="text" name="username" class="form_login_regis"
                value="<?php if(isset($_SESSION['username'])){echo $_SESSION['username']; unset($_SESSION['username']);} ?>"
                placeholder="Username">

            <label>Nomer</label>
            <?php
							if (isset($_SESSION['error_nomer'])) {
								echo $_SESSION['error_nomer'];
								unset($_SESSION['error_nomer']);
							}
						?>
            <input type="text" name="nomer" class="form_login_regis"
                value="<?php if(isset($_SESSION['nomer'])){echo $_SESSION['nomer']; unset($_SESSION['nomer']);} ?>"
                placeholder="Nomer">

            <label>Email</label>
            <?php
							if (isset($_SESSION['error_email'])) {
								echo $_SESSION['error_email'];
								unset($_SESSION['error_email']);
							}
						?>
            <input type="text" name="email" class="form_login_regis"
                value="<?php if(isset($_SESSION['email'])){echo $_SESSION['email']; unset($_SESSION['email']);} ?>"
                placeholder="Email">

            <label>Password</label>
            <?php
							if (isset($_SESSION['error_password1'])) {
								echo $_SESSION['error_password1'];
								unset($_SESSION['error_password1']);
							}
						?>
            <input type="password" name="password1" class="form_login_regis" placeholder="Password">

            <label>Confirmasi Password</label>
            <?php
							if (isset($_SESSION['error_password2'])) {
								echo $_SESSION['error_password2'];
								unset($_SESSION['error_password2']);
							}
						?>

            <input type="password" name="password2" class="form_login_regis" placeholder="Confirmasi Password">
            <?php
							if (isset($_SESSION['benar'])) {
								echo $_SESSION['benar'];
								unset($_SESSION['benar']);
							}
						?>
            <button type="submit" name="submit" class="tombol_login_regis"
                value="<?= $role == "admin" ? "admin" : "member" ?>"> REGISTER </button>
            <?= $_SESSION["inpute"] ?>

            <br />
            <br />
            <center>
                <?php if($role != "admin") : ?>
                <span>
                    already have account? <a class="link" href="login.php">Login</a> now
                </span>
                <?php else :?>
                <span>
                    <a class="link" href="login.php">Login as Admin</a>
                </span>
                <?php endif ?>
            </center>

        </form>

    </div>


</body>

</html>