<?php
session_start();
date_default_timezone_set("Asia/Jakarta");
if (!isset($_SESSION["role"])){
    header('Location: login.php');
    exit();
}
require "../db/dbconnect.php";
require "../function/timeDifference.php";

if(isset($_GET["id"])){
    if ($_GET["id"]){
        $datas = showUser($_GET["id"]);
        $data = $datas->fetch(PDO::FETCH_ASSOC);
        if (!$data){
            header('Location: login.php');
        }
    } else {
            header('Location: login.php');
    }
} else {
    header('Location: login.php');   
}
$followers = countFollowers($_GET["id"]);
$following = countFollowing($_GET["id"]);
$postsCount = countPosts($_GET["id"]);
$posts = showPosts($_GET["id"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | <?= $data["role"] == "admin" ? "Admin" : $data["nama"] ?></title>
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar dark">
        <div class="navbar-nav">
            <a href="beranda.php">
                <div class="brand">
                    <span class="playball">Sharing Badokan <?= $_SESSION["role"] == "admin" ? "| Admin" : "" ?></span>
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
                <?= $_SESSION["role"] == "admin" ? "<a href='datamember.php'>Members</a>" : ""  ?>
                <a href="../function/logout.php">Log Out</a>
            </div>
        </div>
    </nav>

    <!-- Cover -->
    <section class="cover">
        <div class="background">

        </div>
    </section>

    <!-- Profile -->
    <section class="profile">
        <div class="container">
            <div class="profile-box">
                <div class="img-box">
                    <?= $data["gambar"] == NULL ? "<img src='../assets/img/profile.jpeg'" : '<img src="data:image/jpeg;base64,'.base64_encode( $data['gambar'] ) ?>
                    " alt="profile" class="profile-img">

                </div>
                <div class="info-box">
                    <div class="row">
                        <div class="col">
                            <span class="poppins username"><?= $data["username"]?></span>
                        </div>
                        <?php if($_SESSION['id_user'] == $_GET['id'] ) : ?>
                        <div class="col">
                            <a href="editProfile.php" class="edit poppins dark">Edit
                                Profile</a>
                        </div>
                        <?php else : ?>
                        <?php if(followCheck($_SESSION["id_user"], $_GET["id"])) : ?>
                        <div class="col <?= $_SESSION["role"] == "admin" ? "hidden" : "" ?>">
                            <a href="../function/unfollow.php?id=<?= $_GET["id"] ?>"
                                class="edit poppins dark">Unfollow</a>
                        </div>
                        <?php else : ?>
                        <div class="col <?= $_SESSION["role"] == "admin" ? "hidden" : "" ?>">
                            <a href="../function/follow.php?id=<?= $_GET["id"] ?>" class="edit poppins dark">follow</a>
                        </div>
                        <?php endif ?>
                        <?php endif ?>

                    </div>
                    <div class="row poly">
                        <div class="col">
                            <span> <b><?= $postsCount["count"] ?></b> Posts</span>
                        </div>
                        <a <?= $_SESSION['id_user'] == $_GET['id'] ? "href='followers.php'" : "" ?> class="follow">
                            <div class="col">
                                <span> <b><?= $followers["count"] ?></b> Followers</span>
                            </div>
                        </a>
                        <a <?= $_SESSION['id_user'] == $_GET['id'] ? "href='following.php'" : "" ?> class="follow">
                            <div class="col">
                                <span> <b><?= $following["count"] ?></b> Following</span>
                            </div>
                        </a>
                    </div>
                    <div class="row">
                        <span class="poly name"> <b><?= $data["nama"] ?></b>
                        </span>
                    </div>
                </div>
            </div>
            <div class="line">
            </div>
        </div>
    </section>

    <!-- Posts -->
    <section class="posts">
        <div class="container">
            <?php if($data["role"] == "admin") : ?>
            <div class="content admin poppins">
                Admin can't create a post
            </div>
            <?php else : ?>
            <?php if(!followCheck($_SESSION["id_user"], $_GET["id"]) && ($_SESSION["id_user"] != $_GET["id"] ) && ($_SESSION["role"]) != "admin" ) : ?>
            <div class="content admin poppins">
                Follow person to see Posts
            </div>
            <?php else : ?>
            <?php foreach($posts as $post) : ?>
            <div class="content" id="post-<?= $post["id_post"] ?>">
                <div class="row dark">
                    <div class="img-box">
                        <?= $data["gambar"] == NULL ? "<img src='../assets/img/profile.jpeg'" : '<img src="data:image/jpeg;base64,'.base64_encode( $data['gambar'] ) ?>
                        " alt="profile" class="profile-img">
                    </div>
                    <div class="name-box">
                        <span class="poppins name"><?= $data["nama"] ?></span>
                        <span class="poly time">
                            <?= timeDiff($post["post_date"]) ?>
                        </span>
                    </div>
                </div>
                <div class="row post-thumbnail dark">
                    <a href="#gambar-<?= $post["id_post"] ?>">
                        <?= '<img src="data:image/jpeg;base64,'.base64_encode( $post['gambar'] ) ?> "
                        alt="post"class="post-img">
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
                    </span>
                </div>
            </div>
            <?php endforeach ?>
            <?php endif ?>
            <?php endif ?>
        </div>
    </section>

    <a href="posting.php?previous=profile">
        <div
            class="new-post dark <?= $_SESSION["role"] == "admin" ? "hidden" : ($_SESSION['id_user'] == $_GET['id'] ? "" : "hidden") ?>">
            <span>+</span>
        </div>
    </a>

</body>

</html>