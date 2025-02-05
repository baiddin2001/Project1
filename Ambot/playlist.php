<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:home.php');
}

if(isset($_POST['save_list'])){

   if($user_id != ''){
      
      $list_id = $_POST['list_id'];
      $list_id = filter_var($list_id, FILTER_SANITIZE_STRING);

      $select_list = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
      $select_list->execute([$user_id, $list_id]);

      if($select_list->rowCount() > 0){
         $remove_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
         $remove_bookmark->execute([$user_id, $list_id]);
         $message[] = 'Playlist removed!';
      }else{
         $insert_bookmark = $conn->prepare("INSERT INTO `bookmark`(user_id, playlist_id) VALUES(?,?)");
         $insert_bookmark->execute([$user_id, $list_id]);
         $message[] = 'Playlist saved!';
      }
   }else{
      $message[] = 'Please login first!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Playlist</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="videos-container">
   <h1 class="heading">Subject Videos</h1>
   <div class="box-container">
      <?php
         $select_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND status = ? ORDER BY date DESC");
         $select_content->execute([$get_id, 'active']);
         if($select_content->rowCount() > 0){
            while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <a href="watch_video.php?get_id=<?= $fetch_content['id']; ?>" class="box">
         <i class="fas fa-play"></i>
         <img src="uploaded_files/<?= $fetch_content['thumb']; ?>" alt="">
         <h3><?= $fetch_content['title']; ?></h3>
      </a>
      <?php
            }
         }else{
            echo '<p class="empty">No videos added yet!</p>';
         }
      ?>
   </div>
</section>

<!-- Downloadable Files Section -->
<section class="files-container">
   <h1 class="heading">Downloadable Files</h1>
   <div class="box-container">
      <?php
         $select_files = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND file IS NOT NULL AND file != ''");
         $select_files->execute([$get_id]);
         if($select_files->rowCount() > 0){
            while($fetch_file = $select_files->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <div class="box">
         <i class="fas fa-file"></i>
         <h3><?= $fetch_file['title']; ?></h3>
         <a href="uploaded_files/<?= $fetch_file['file']; ?>" download class="btn">Download</a>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">No files uploaded yet!</p>';
         }
      ?>
   </div>
</section>

<style>

.files-container .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
    justify-content: center;
    align-items: center;
}

.files-container .box {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.files-container .box i {
    font-size: 30px;
    color: #333;
    margin-bottom: 5px;
}

.files-container .box h3 {
    font-size: 14px;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
    text-align: center;
}

.files-container .box .btn {
    background: #007bff;
    color: white;
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin-top: 5px;
}

.files-container .box .btn:hover {
    background: #0056b3;
}


</style>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
