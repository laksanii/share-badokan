<?php
session_start();
if (isset($_SESSION["role"])){
    header('Location: login.php'); //Jika belum login maka akan diarahkan ke halaman login
    exit(); // Keluar dari halaman saat ini (beranda)
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharing Baodokan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <nav>
        <div class="container">
            <div class="brand playball">
                <span> Sharing Badokan</span>
            </div>
            <div class="btn">
                <a href="login.php" class="poly">Login</a>
                <a href="register.php" class="poly">Sign Up</a>
            </div>
        </div>
    </nav>

    <div class="content">
        <h3 class="poppins">PERSONALIZE YOUR EXPERIENCE</h3>
        <h1 class="poly">Share Your Favorite Food</h1>
    </div>

    <div class="photos">
        <div class="col">
            <img src="../assets/img/1.jpg" alt="Indonesian">
            <div class="overlay">
                <span>Indonesian</span>
            </div>
        </div>
        <div class="col">
            <img src="../assets/img/2.jpg" alt="American">
            <div class="overlay">
                <span>American</span>
            </div>
        </div>
        <div class="col">
            <img src="../assets/img/3.jpg" alt="Chinese">
            <div class="overlay">
                <span>Chinese</span>
            </div>
        </div>
        <div class="col">
            <img src="../assets/img/4.jpg" alt="Asian">
            <div class="overlay">
                <span>Asian</span>
            </div>
        </div>
        <div class="col">
            <img src="../assets/img/5.jpeg" alt="Seafood">
            <div class="overlay">
                <span>Seafood</span>
            </div>
        </div>
    </div>

</body>

</html>