<?php
session_start();
require("../db/dbconnect.php");
if(!isset($_SESSION["role"])){
    header("Location: ../view/login.php");
    exit();
}

unfollow($_SESSION["id_user"], $_GET["id"]);
header("Location: ../view/profile.php?id=".$_GET['id']);
exit();