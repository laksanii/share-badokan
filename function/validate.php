<?php
// Validate nama
function validName($nama){
    if ($nama == ""){
        $errnama = "Nama harus diisi";
        return [$errnama, false];
    } elseif (!preg_match('/^[a-zA-Z ]*$/',$nama)){
        $errnama = "Nama hanya boleh huruf dan spasi";
        return [$errnama, false];
    } elseif (strlen($nama) < 4 || strlen($nama) > 31){
        $errnama = "Nama minimal 4 karakter dan maksimal 30 karakter";
        return [$errnama, false];
    } else {
        $errnama = "";
        return [$errnama, true];
    }
}

function validUsername($username, $oldUsername){
    if ($username == ""){
        $errusername = "Username harus diisi";
        return [$errusername, false];
    } elseif (!preg_match("/^[a-zA-Z0-9_]*$/", $username)){
        $errusername = "Username harus berupa huruf, angka dan underscore (_) tanpa spasi";
        return [$errusername, false];
    } elseif (strlen($username) < 6 || strlen($username) > 16) {
        $errusername = "Username minimal 6 karakter dan maksimal 15 karakter";
        return [$errusername, false];
    } elseif(checkUsername($username) && $username != $oldUsername ){
        $errusername = "Username sudah digunakan";
        return [$errusername, false];
    } else {
        $errusername = "";
        return [$errusername, true];
    }
}

function validNoTelp($no_telpon){
    if($no_telpon == ""){
        $errno_telp = "No telpon harus diisi";
        return [$errno_telp, false];
    } elseif (!preg_match( "/^[0-9]*$/", $no_telpon)){
        $errno_telp = "No telpon harus berupa angka";
        return [$errno_telp, false];
    } elseif (strlen($no_telpon) < 8 || strlen($no_telpon) > 13){
        $errno_telp = "No telpon minimal 8 angka dan maksimal 13 angka";
        return [$errno_telp, false];
    } else {
        $errno_telp = "";
        return [$errno_telp, true];
    }
}

function validPassword($password){
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    if($password == ""){
        $errpassword = "";
        return [$errpassword, true];
    } elseif (strlen($password) < 8 || strlen($password) > 18){
        $errpassword = "Password minimal 8 karakter dan maksimal 18";
        return [$errpassword, false];
    } elseif (!$uppercase || !$lowercase || !$number){
        $errpassword = "Password harus memiliki huruf besar, kecil dan angka";
        return [$errpassword, false];
    } else {
        $errpassword = "";
        return [$errpassword, true];
    }
}