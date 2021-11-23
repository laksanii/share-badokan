<?php
require "../db/dbconnect.php";
require "../function/validate.php";
session_start();
if ($_SESSION["id_user"]){
    $datas = showUser($_SESSION["id_user"]);
    $data = $datas->fetch(PDO::FETCH_ASSOC);
    if (!$data){
        header('Location: index.php');
    }
}

$errnama = $errusername = $erremail = $errno_telp = $errpassword = $err_newpassword = $err_newconfpassword = $errimg = "" ;
$valnama = $valusername = $valemail = $valno_telp = "";
$update = "";

if (isset($_POST["edit"]))
{
    $valid = 0;
    $passvalid = 0;
    // Validasi Nama
    $nama = validName($_POST["nama"]);
    if(!$nama[1]){ 
        $errnama = $nama[0];
        $valnama = "";
    } else {
        $valid++;
        $errnama = $nama[0];
        $valnama = $_POST["nama"];
    }
    
    // Validasi username
    $username = validUsername($_POST["username"], $data["username"]);
    if(!$username[1]){
        $errusername = $username[0];
        $valusername = "";
    } else {
        $valid++;
        $errusername = $username[0];
        $valusername = $_POST["username"];
    }

    // Validasi  No Telpon
    $no_telpon = validNoTelp($_POST["no_telp"]);
    if(!$no_telpon[1]){
        $errno_telp = $no_telpon[0];
        $valno_telp = "";
    } else {
        $valid++;
        $errno_telp = $no_telpon[0];
        $valno_telp = $_POST["no_telp"];
    }

    
    // Check old password
    
    if(!checkPassword($_SESSION["id_user"], $_POST["password"])){
        $errpassword = "Password salah";
    } else {
        $valid++;
        $errpassword = "";
    }
    
    // Validasi New Password
    $password = validPassword($_POST["newPassword"]);
    if (!$password[1]){
        $err_newpassword = $password[0];
    } else {
        $passvalid++;
        $err_newpassword = $password[0];
    }


    
    // Validasi confirm Password
    if($_POST["confNewPassword"] == ""){
        $err_newconfpassword = "";
    } elseif($_POST["confNewPassword"] != $_POST["newPassword"]){
        $err_newconfpassword = "Konfirmasi password tidak sama";
    } else {
        $passvalid++;
        $err_newconfpassword = "";
    }

    // Validasi Gambar;
    $image = "";
    if($_FILES["profileimg"]["size"] == 0){
        $image = "";
        $valid++;
        $errimg = "";
    } elseif( !getimagesize($_FILES["profileimg"]["tmp_name"]) ){
        $errimg = "File harus berupa gambar";
    } else {
        $image = file_get_contents($_FILES["profileimg"]["tmp_name"]);
        $valid++;
        $errimg = "";
    }

    if($valid == 5){
        if($_POST["newPassword"] == ""){
            if (updateProfile($_SESSION["id_user"], strtolower($_POST["nama"]), strtolower($_POST["username"]), $_POST["no_telp"], $image)){
                $update = "Update profile berhasil";
            } else {
                $update = "Update profile gagal";
            }
        } else {
            if($passvalid == 2){
                if(updatePassword($_SESSION["id_user"], $_POST["newPassword"])){
                    updateProfile($_SESSION["id_user"], strtolower($_POST["nama"]), strtolower($_POST["username"]), $_POST["no_telp"], $image);
                        $update = "Data dan Password berhasil diubah";
                } else {
                    $update = "Update profile dan Password gagal";
                }
            } else {
                $update = "Update gagal";
            }
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | <?= $data["nama"] ?></title>
    <link rel="stylesheet" href="../assets/css/editProfile.css">
</head>

<body>
    <div class="container base">
        <div class="header dark">
            <h3 class="playball">Edit Profile</h3>
            <a href="profile.php?id=<?= $_SESSION['id_user'] ?>">
                <div class="btn-back">
                    <span>&#9668;</span>
                </div>
            </a>
        </div>
        <form action="editProfile.php" method="POST" enctype="multipart/form-data">
            <div class="field poppins upload-img">
                <div class="img-box">
                    <?= $data["gambar"] == NULL ? "<img src='../assets/img/profile.jpeg'" : '<img src="data:image/jpeg;base64,'.base64_encode( $data['gambar'] ) ?>
                    " alt="profile">
                </div>
                <div class="label">
                    <span>Change Picture</span>
                    <input type="file" class="poppins" name="profileimg" id="profileimg"
                        accept="image/png, image/jpg, image/jpeg">
                </div>
                <span class="error"><?= $errimg ?></span>
            </div>
            <div class="field poppins">
                <label for="nama">Name</label>
                <input type="text" class="soft" name="nama" id="nama"
                    value="<?= isset($_POST["edit"]) ? $valnama : $data["nama"] ?>">
                <span class="error"><?= $errnama ?></span>
            </div>
            <div class="field poppins">
                <label for="username">Username</label>
                <input type="text" class="soft" name="username" id="username"
                    value="<?= isset($_POST["edit"]) ? $valusername : $data["username"] ?>">
                <span class="error"> <?= $errusername ?> </span>
            </div>
            <div class="field poppins">
                <label for="email">Email</label>
                <input type="text" class="soft" name="email" id="email" disabled value="<?= $data["email"] ?>">
            </div>
            <div class="field poppins">
                <label for="nohp">No Handphone</label>
                <input type="text" class="soft" name="no_telp" id="no_telp"
                    value="<?= isset($_POST["edit"]) ? $valno_telp : $data["no_telp"] ?>">
                <span class="error"><?= $errno_telp ?></span>
            </div>
            <div class="field poppins">
                <label for="password">Old Password</label>
                <input type="password" class="soft" name="password" id="password">
                <span class="error"><?= $errpassword ?></span>
            </div>
            <div class="field poppins">
                <label for="newPassword">New Password</label>
                <input type="Password" class="soft" name="newPassword" id="newPassword"
                    placeholder="Don't Fill if dont want to change password">
                <span class="error"><?= $err_newpassword ?></span>
            </div>
            <div class="field poppins">
                <label for="confNewPassword">Confirm New Password</label>
                <input type="password" class="soft" name="confNewPassword" id="confNewPassword">
                <span class="error"><?= $err_newconfpassword ?></span>
                <span class="error"> <?= $update ?></span>
            </div>
            <div class="field submit poppins">
                <input type="submit" class="soft" name="edit" value="confirm">
            </div>
        </form>
    </div>
</body>

</html>