<?php
session_start();
require "../db/dbconnect.php";
if (!isset($_SESSION["role"])){
	  header("Location: login.php");
    exit();
}

$followers = showFollowers($_SESSION["id_user"]);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>followers</title>
    <link rel="stylesheet" href="../assets/css/followers.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar dark">
        <div class="navbar-nav">
            <a href="beranda.php">
                <div class="brand">
                    <span class="playball">Sharing Badokan </span>
                </div>
            </a>
            <div class="search-bar">
                <form action="searchResult.php">
                    <input type="text" class="poppins" name="search" id="search" placeholder="Search...">
                </form>
            </div>
            <div class="nav poppins">
                <a href="beranda.php">Beranda</a>
                <a href="profile.php?id=<?= $_SESSION["id_user"] ?>">Profile</a>
                <a href="../function/logout.php">Log Out</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section>
        <div class="container base">
            <div class="header dark">
                <span class="poly">Followers</span>
            </div>
            <div class="member-list">
                <?php foreach($followers as $follower) : ?>
                <div class="member">
                    <div class="img-box">
                        <?= $follower["gambar"] == NULL ? "<img src='../assets/img/profile.jpeg'" : '<img src="data:image/jpeg;base64,'.base64_encode( $follower['gambar'] ) ?>
                        " alt="profile"">
                    </div>
                    <div class="name-box">
                        <span class="poppins name"><?= $follower["nama"] ?></span>
                        <span class="poly username">@<?= $follower["username"] ?></span>
                    </div>
                    <div class="button poppins ">
                        <a href="profile.php?id=<?= $follower["id_user"] ?>" class="soft">Detail</a>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </section>

</body>

</html>