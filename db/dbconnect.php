<?php

    try{
        $dbc = new PDO('mysql:host=localhost;dbname=socmed','root','');
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        echo "Failed to connect Database : ".$e->getMessage();
    }

    function showSearch($keyword) {
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM user WHERE (INSTR(nama, :keyword) > 0 OR INSTR(username, :keyword) > 0) AND role = 'member';");
        $query->bindValue(":keyword", $keyword);
        $query->execute();
        return $query;
    }

    function showUser($id) {
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM user WHERE id_user = :id;");
        $query->bindValue(":id", $id);
        $query->execute();
        return $query;
    }

    function showAllMember(){
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM user WHERE role = 'member';");
        $query->execute();
        return $query;
    }
    
    function updateProfile($id, $nama, $username, $no_telp, $image){
        global $dbc;

        if ($image == ""){
            $query = $dbc->prepare("UPDATE user SET username = :username, nama = :nama, no_telp = :no_telp WHERE id_user = :id;");
            $query->bindValue(":username", $username);
            $query->bindValue(":nama", $nama);
            $query->bindValue(":no_telp", $no_telp);
            $query->bindValue(":id", $id);
        } else {
            $query = $dbc->prepare("UPDATE user SET username = :username, nama = :nama, no_telp = :no_telp, gambar = :image WHERE id_user = :id;");
            $query->bindValue(":username", $username);
            $query->bindValue(":nama", $nama);
            $query->bindValue(":no_telp", $no_telp);
            $query->bindValue(":image", $image);
            $query->bindValue(":id", $id);
        }

        $query->execute();
        
        return $query->rowCount() > 0;
    }

    function insertUser($nama, $username, $no_telp, $email, $password){
        global $dbc;

        $query = $dbc->prepare("INSERT INTO user (nama, username, no_telp, email, password, role) VALUES (:nama, :username, :no_telp, :email, SHA2(:password, 0), 'member');");
        $query->bindValue(":nama", $nama);
        $query->bindValue("username", $username);
        $query->bindValue(":no_telp", $no_telp);
        $query->bindValue(":email", $email);
        $query->bindValue(":password", $password);
        $query->execute();

        return $query->rowCount() > 0;
    }

    function showPosts($id){
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM posts WHERE id_user = :id_user ORDER BY post_date DESC");
        $query->bindValue(":id_user", $id);
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function showAllPostsFollowing($id){
        global $dbc;

        $query = $dbc->prepare("SELECT user.nama as nama, user.gambar as profile, posting.* FROM user, 
        (SELECT posts.* FROM posts WHERE id_user IN (:id_user) OR id_user IN (SELECT id_followed FROM follows WHERE id_user = :id_user)) as posting 
        WHERE user.id_user = posting.id_user ORDER BY post_date DESC;");
        $query->bindValue(":id_user", $id);
        $query->execute();

        return $query;
    }

    function showAllMemberPosts(){
        global $dbc;

        $query = $dbc->prepare("SELECT user.nama as nama, user.gambar as profile, posts.* FROM user, posts WHERE user.id_user = posts.id_user ORDER BY post_date DESC;");
        $query->execute();

        return $query;
    }

    function showPostByCategory($id, $id_kategori){
        global $dbc;
        
        $query = $dbc->prepare("SELECT user.nama as nama, user.gambar as profile, posting.* FROM user, 
        (SELECT posts.* FROM posts WHERE id_user IN (:id_user) OR id_user IN (SELECT id_followed FROM follows WHERE id_user = :id_user)) as posting 
        WHERE user.id_user = posting.id_user AND id_kategori = :id_kategori ORDER BY post_date DESC;");
        $query->bindValue(":id_user", $id);
        $query->bindValue(":id_kategori", $id_kategori);
        $query->execute();
        
        return $query;

    }

    function showAllPostByCategory($id_kategori){
        global $dbc;

        $query = $dbc->prepare("SELECT user.nama as nama, user.gambar as profile, posts.* FROM user, posts WHERE user.id_user = posts.id_user AND posts.id_kategori = :id_kategori ORDER BY post_date DESC;");
        $query->bindValue(":id_kategori", $id_kategori);
        $query->execute();
        
        return $query;
    }
    
    function countPosts($id){
        global $dbc;

        $query = $dbc->prepare("SELECT COUNT(*) as 'count' FROM posts WHERE id_user = :id_user;");
        $query->bindValue(":id_user", $id);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    function countFollowers($id){
        global $dbc;

        $query = $dbc->prepare("SELECT COUNT(id_user) as 'count' FROM follows WHERE id_followed = :id_followed;");
        $query->bindValue(":id_followed", $id);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    function countFollowing($id){
        global $dbc;

        $query = $dbc->prepare("SELECT COUNT(id_followed) as 'count' FROM follows WHERE id_user = :id_user;");
        $query->bindValue(":id_user", $id);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    function followCheck($id_user, $id_followed){
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM follows WHERE id_user = :id_user AND id_followed = :id_followed");
        $query->bindValue(":id_user", $id_user);
        $query->bindValue(":id_followed", $id_followed);
        $query->execute();

        return $query->rowCount();
        
    }
    
        function follow($id_user, $id_followed){
            global $dbc;
    
            $query = $dbc->prepare("INSERT INTO follows VALUES (:id_user, :id_followed);");
            $query->bindValue(":id_user", $id_user);
            $query->bindValue(":id_followed", $id_followed);
            $query->execute();
    
            return $query->rowCount();
        }

    function unfollow($id_user, $id_followed){
        global $dbc;

        $query = $dbc->prepare("DELETE FROM follows WHERE id_user = :id_user AND id_followed = :id_followed;");
        $query->bindValue(":id_user", $id_user);
        $query->bindValue(":id_followed", $id_followed);
        $query->execute();

        return $query->rowCount();
    }

    function checkUsername($username){
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM user WHERE username = :username");
        $query->bindValue(":username", $username);
        $query->execute();

        return $query->rowCount();
    }

    function checkEmail($email){
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM user WHERE email = :email");
        $query->bindValue(":email", $email);
        $query->execute();

        return $query->rowCount();
    }

    function showFollowers($id_user){
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM user WHERE id_user IN (SELECT id_user FROM follows WHERE id_followed = :id_user)");
        $query->bindValue(":id_user", $id_user);
        $query->execute();

        return $query;
    }

    function showFollowing($id_user){
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM user WHERE id_user IN (SELECT id_followed FROM follows WHERE id_user = :id_user)");
        $query->bindValue(":id_user", $id_user);
        $query->execute();

        return $query;
    }

    function selectCategories(){
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM kategori");
        $query->execute();
        return $query;
    }

    function checkPassword($id, $password){
        global $dbc;

        $query = $dbc->prepare("SELECT * FROM user WHERE id_user = :id AND password = SHA2(:password, 0)");
        $query->bindValue(":id", $id);
        $query->bindValue(":password", $password);
        $query->execute();
        return $query->rowCount() > 0;
    }

    function updatePassword($id, $password){
        global $dbc;

        $query = $dbc->prepare("UPDATE user SET password = SHA2(:password, 0) WHERE id_user = :id");
        $query->bindValue(":id", $id);
        $query->bindValue(":password", $password);
        $query->execute();

        return $query->rowCount() > 0;
    }
?>