<?php
require "../db/dbconnect.php";
session_start();
if (!isset($_SESSION["role"])){
    header('Location: login.php');
    exit();
}
if ($_GET["search"]){
    $result = showSearch($_GET["search"]);
} else {
    header('Location: profile.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result | <?= $_GET["search"] ?> </title>
    <link rel="stylesheet" href="../assets/css/searchResult.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar dark">
        <div class="navbar-nav">
            <a href="beranda.php">
                <div class="brand">
                    <span class="playball">Sharing Badokan</span>
                </div>
            </a>
            <div class="search-bar">
                <form action="searchResult.php">
                    <input type="text" class="poppins" name="search" id="search" placeholder="Search...">
                </form>
            </div>
            <div class="nav poppins">
                <a href="beranda.php">Beranda</a>
                <a href="profile.php?id=<?= $_SESSION['id_user'] ?>">Profile</a>
                <?= $_SESSION["role"] == "admin" ? "<a href='datamember.php'>Members</a>" : ""  ?>
                <a href="../function/logout.php">Log Out</a>
            </div>
        </div>
    </nav>

    <!-- Result -->
    <section class="result">
        <?php foreach($result as $data) : ?>
        <div class="content">
            <div class="row dark">
                <div class="img-box">
                    <?= $data["gambar"] == NULL ? "<img src='../assets/img/profile.jpeg'" : '<img src="data:image/jpeg;base64,'.base64_encode( $data['gambar'] ) ?>
                    " alt="profile" class="profile-img">
                </div>
                <div class="name-box">
                    <span class="poppins name"><?= $data["nama"] ?></span>
                    <span class="poly">@<?= $data["username"] ?></span>
                </div>
                <div class="button poppins ">
                    <a href="profile.php?id=<?= $data["id_user"] ?>" class="soft">Detail</a>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </section>
</body>

</html>