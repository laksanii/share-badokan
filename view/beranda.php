<?php
session_start(); //Inisialisasi session
date_default_timezone_set("Asia/Jakarta"); //setting default timezone Jakarta

// Mengecek apakah sudah login atau belum dengan cara mengecek variabel $_session indeks role apakah sudah terisi.
if (!isset($_SESSION["role"])){
    header('Location: login.php'); //Jika belum login maka akan diarahkan ke halaman login
    exit(); // Keluar dari halaman saat ini (beranda)
}
require "../db/dbconnect.php"; // Koneksi ke file database dan fungsi untuk akses database
require "../function/timeDifference.php"; // Koneksi ke file function timeDifference.php untuk menghitung selisih waktu

if($_SESSION["role"] == "member"){ // Untuk mengecek apakah role user yang sedang mengakses halaman ini member atau bukan.
    if(!isset($_GET["kategori"])){
        $posts = showAllPostsFollowing($_SESSION["id_user"]); // Jika iya maka hanya akan mengambil data post dari member yang sudah difollow saja dan postnya sendiri yang disimpan di variabel $posts. Data postingan diambil dengan menggunakan fungsi showAllPostsFollowing() yang disimpan di file dbconnect.php
        $selected = false;
    } else {
        $posts = showPostByCategory($_SESSION["id_user"], $_GET["kategori"]);
        $selected = true;
    }
} else {
    if(!isset($_GET["kategori"])){
        $posts = showAllMemberPosts(); // Jika role user bukan member akan mengambil data seluruh postingan semua member yang disimpan di variabel $posts. Data postingan diambil dengan menggunakan fungsi showAllMemberPosts() yang disimpan di file dbconnect.php
        $selected = false;
    } else {
        $posts = showAllPostByCategory($_GET["kategori"]);
        $selected = true;
    }
}

$categories = selectCategories();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharing Badokan <?= $_SESSION["role"] == "admin" ? "| Admin" : "" ?></title>
    <!-- Ternary if untuk menampilkan teks Admin pada title halaman jika yang sedang login adalah admin  -->
    <link rel="stylesheet" href="../assets/css/beranda.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar dark">
        <div class="navbar-nav">
            <a href="beranda.php">
                <div class="brand">
                    <span class="playball">Sharing Badokan <?= $_SESSION["role"] == "admin" ? "| Admin" : "" ?></span>
                    <!-- Ternary if untuk menampilkan teks Admin pada nama brand pada pojok kiri atas -->
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
                <!-- Mengirim id_user dari user ke halaman profile menggunakan metode GET -->
                <?= $_SESSION["role"] == "admin" ? "<a href='datamember.php'>Members</a>" : ""  ?>
                <!-- Ternary if untuk memberi navigasi baru bernama Memebers jika role user yang login adalah admin -->
                <a href="../function/logout.php">Log Out</a>
            </div>
        </div>
    </nav>

    <!-- Kategori -->
    <div class="categories">
        <a href="beranda.php">
            <div class="category<?= !$selected ? "-active" : "" ?> poppins">
                <span>All</span>
            </div>
        </a>
        <?php foreach($categories as $categori) : ?>
        <a href="beranda.php?kategori=<?= $categori["id_kategori"] ?>">
            <div
                class="category<?= !$selected || $_GET["kategori"] != $categori["id_kategori"] ? " " : "-active" ?> poppins">
                <span><?= $categori["nama_kategori"] ?></span>
            </div>
        </a>
        <?php endforeach ?>
    </div>

    <!-- Posts -->
    <section class="posts">
        <div class="container">
            <?php foreach($posts as $post) : ?>
            <!-- foreach variabel posts sebagai variabel post -->
            <div class="content" id="post-<?= $post["id_post"] ?>">
                <div class="row dark">
                    <div class="img-box">
                        <?= $post["profile"] == NULL ? "<img src='../assets/img/profile.jpeg'" : '<img src="data:image/jpeg;base64,'.base64_encode( $post['profile'] ) ?>
                        " alt="profile" class="profile-img">
                        <!-- ternary if jika pada variabel post indeks 'profile' bernilai NULL maka akan menampilkan foto profile default dan jika tidak akan menampilkan foto dari database yang diencode terlebih dahulu karena data berupa data binary -->
                    </div>
                    <div class="name-box">
                        <span class="poppins name"><a
                                href="profile.php?id=<?= $post["id_user"] ?>"><?= $post["nama"] ?></a></span>
                        <span class="poly time">
                            <?= timeDiff($post["post_date"]) ?>
                            <!-- menghitung selisih waktu postingan yang disimpan di variabel post indeks post_date dengan menggunakan fungsi timeDiff yang disimpan pada file timeDifference.php -->
                        </span>
                    </div>
                </div>
                <div class="row post-thumbnail dark">
                    <a href="#gambar-<?= $post["id_post"] ?>">
                        <?= '<img src="data:image/jpeg;base64,'.base64_encode( $post['gambar'] )?> "
                        alt="post"class="post-img">
                        <!-- Menampilkan gambar yang disimpan pada variabel pst indeks 'gambar' dengan cara di encode terlebih dahulu karena data berupa data binary -->
                    </a>
                </div>
                <div class="overlay" id="gambar-<?= $post["id_post"] ?>">
                    <div class="box-img">
                        <a href="#post-<?= $post["id_post"] ?>" class="close">CLOSE</a>
                        <?= '<img src="data:image/jpeg;base64,'.base64_encode( $post['gambar'] ) ?> "
                        alt="post"class="post-img">
                    </div>
                </div>
                <div class="row dark">
                    <span class="post-caption poly">
                        <?= $post["caption"] ?>
                        <!-- Menampilkan caption postingan yang disimpan pada variable post indeks 'caption' -->
                    </span>
                </div>
            </div>

            <?php endforeach ?>
            <!-- akhir dari foreach -->
        </div>
    </section>

    <a href="posting.php?previous=profile">
        <div class="new-post dark <?= $_SESSION["role"] == "admin" ? "hidden" : "" ?>">
            <!-- ternary if jika role user yang sedang mengakses halaman adalah admin maka akan menambahkan class hidden pada attribut div dan jika bukan admin tidak akan menambahkan apa apa. -->
            <span>+</span>
        </div>
    </a>
</body>

</html>