<?php
session_start();
unset($_SESSION["role"]);
unset($_SESSION["id_user"]);
header("Location:../view/");
exit();