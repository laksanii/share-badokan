  <?php
    session_start();
    require "../db/dbconnect.php";
    if (!isset($_SESSION["role"])){
      header("Location: login.php");
      exit();
    }
    if(isset($_POST['submit'])){
        try
    {
        $dbc = new PDO('mysql:host=localhost;dbname=socmed','root','');
        $statement = $dbc->prepare("INSERT INTO posts (id_user, gambar, caption, id_kategori) 
                                    VALUES (:id_user, :gambar, :caption, :id_kategori)");

                                    
        // ini blob
        $image   = file_get_contents($_FILES['gambar']['tmp_name']);

        //mengambil id_user dari session yang login
        
        $ini = $_SESSION['id_user'];

        $statement->bindValue(':id_user', $ini);
        $statement->bindValue(':gambar', $image);
        $statement->bindValue(':caption', $_POST['caption']);
        $statement->bindValue(":id_kategori", $_POST["kategori"]);
        $statement->execute();
        header("Location: beranda.php");
        exit();
    }
    catch (PDOException $err)
    {
        echo $err->getMessage();
    }   
    }

    $categories = selectCategories();
?>


  <!DOCTYPE html>
  <html>

  <head>
      <meta charset="utf-8">
      <title>Halaman posting</title>
      <link rel="stylesheet" href="../assets/css/post.css" media="screen" title="no title">
  </head>

  <body>
      <!-- content isi -->
      <form action="posting.php" name="myForm" method="POST" enctype=multipart/form-data>
          <div class="login">
              <div class="header">
                  <h2>Create New Post</h2>
                  <a href="profile.php?id=<?= $_SESSION['id_user'] ?>">
                      <div class="btn-back">
                          <span>&#9668;</span>
                      </div>
                  </a>
              </div>
              <div class="box-login">
                  <label for="gambar" id="lab">Gambar</label>
                  <input type="file" name="gambar" id="gambar" accept="image/png, image/jpg, image/jpeg">
              </div>
              <div class="box-login">
                  <label for="caption" id="lab">Caption</label>
                  <textarea type="text" name="caption" id="caption"></textarea>
              </div>
              <div class="kategori">
                  <label for="kategori" id="lab">kategori</label>
                  <select name="kategori" id="kategori">
                      <?php foreach($categories as $cateogry) : ?>
                      <option value="<?= $cateogry["id_kategori"] ?>"><?= $cateogry["nama_kategori"] ?></option>
                      <?php endforeach ?>
                  </select>
              </div>
              <div class="tombol">
                  <input type="submit" value="Submit" name="submit" class="btn-login">
                  <input type="reset" value="Reset" name="reset" class="btn-login">
              </div>
          </div>
      </form>
  </body>

  </html>