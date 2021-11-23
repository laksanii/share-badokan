<?php 
session_start();
require "../db/dbconnect.php";
if (!isset($_SESSION["role"])){
    header("Location: login.php");
    exit();
}

if ($_SESSION["role"] != "admin"){
    header("Location: beranda.php");
    exit();
}
$members = showAllMember();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Member</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/datamember.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar dark">
        <div class="navbar-nav">
            <a href="beranda.php">
                <div class="brand">
                    <span class="playball">Sharing Badokan | Admin</span>
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


    <div class="container">
        <div class="content base poly">
            <h1>Data Member</h1>
            <a href="register.php">
                <span class="add-admin poppins dark">Add new Admin</span>
            </a>
            <div class="tabel poppins">
                <table>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>email</th>
                        <th>No Telp</th>
                        <th>Gambar</th>
                    </tr>

                    <?php foreach($members as $member) : ?>
                    <tr>
                        <td><a href="profile.php?id=<?= $member["id_user"] ?>"><?= $member["nama"] ?></a></td>
                        <td><?= $member["username"] ?></td>
                        <td><?= $member["email"] ?></td>
                        <td><?= $member["no_telp"] ?> </td>
                        <td>
                            <?= $member["gambar"] == NULL ? "<img src='../assets/img/profile.jpeg'" : '<img src="data:image/jpeg;base64,'.base64_encode( $member['gambar'] ) ?>
                            " alt="profile" class="member-img">
                        </td>
                    </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>

    </div>

    <!-- <div class="container soft">
        <div class="konten">
            <div class="formtitle">
                <a href="#"><b>Data Member</b></a>
            </div>
            <div class="tabel">
                <table border='1'>
                    <tr>
                        <th>Nama Member</th>
                        <th>Email</th>
                        <th>No.Telp</th>
                        <th>Gambar</th>
                    </tr>
                    <?php
								include "db.php";
								$take=$conn->query("SELECT * FROM user");
								while($data=$take->fetch())
								{
									echo"<tr align='center'><td>".$data['nama']."</td>"; // mengambil data nama_member
									echo"<td>".$data['email']."</td>"; // mengambil data email
									echo"<td>".$data['no_telp']."</td>"; // mengambil data no.telp
									"<img src='data:image/jpeg;base64,".base64_encode( $data['gambar'] );
									echo"<td><img alt='' width='150' height='150' src='data:image/jpeg;base64,".base64_encode( $data['gambar'] )."'></td>";// mengambil data gambar
								}
							?>
                    </tr>
                </table>
            </div>
        </div>
    </div> -->
</body>

</html>