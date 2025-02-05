<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- quick select section starts  -->

<section class="quick-select">

   <h1 class="heading">Quick Options</h1>

   <div class="box-container">

   <?php
if ($user_id != '') {
?>
    <div class="box">
        <h3 class="title">Likes and Comments</h3>
        <p>Total Likes: <span><?= $total_likes; ?></span></p>
        <a href="likes.php" class="inline-btn">View Likes</a>
        <p>Total Comments: <span><?= $total_comments; ?></span></p>
        <a href="comments.php" class="inline-btn">View Comments</a>
        <p>Saved Playlist: <span><?= $total_bookmarked; ?></span></p>
        <a href="bookmark.php" class="inline-btn">View Bookmark</a>
    </div>
<?php
} else {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit(); // Make sure to exit after redirecting
}
?>


      <!-- <div class="box">
         <h3 class="title">Top Strands</h3>
         <div class="flex">
            <a href="search_course.php?"><i class="fa-solid fa-users-rectangle"></i><span>HUMSS</span></a>
            <a href="#"><i class="fa-solid fa-laptop"></i><span>ICT</span></a>
            <a href="#"><i class="fa-solid fa-microchip"></i><span>TVL</span></a>
      </div> -->

      <!-- <div class="box">
         <h3 class="title">Popular Subjects</h3>
         <div class="flex">
            <a href="#"><i class="fa-solid fa-globe"></i><span>Philosophy</span></a>
            <a href="#"><i class="fa-solid fa-photo-film"></i><span>Media Information Literacy</span></a>
            <a href="#"><i class="fa-solid fa-person-running"></i><span>Physical Education</span></a>
            <a href="#"><i class="fa-solid fa-atom"></i><span>Earth Life and Science</span></a>
            <a href="#"><i class="fa-solid fa-palette"></i><span>Contemporary Art</span></a>
            <a href="#"><i class="fa-solid fa-book"></i><span>Practical Research</span></a>
         </div>
      </div> -->

      <div class="box tutor">
         <h3 class="title"></h3>
         <p style="text-align: left;"></p>
         <a href="admin/register.php" class="inline-btn"></a>
      </div>

   </div>

</section>

<!-- quick select section ends -->

<!-- courses section starts  -->

<section class="courses">

   <h1 class="heading">latest courses</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute(['active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box" >
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">view playlist</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no courses added yet!</p>';
      }
      ?>

   </div>
  

   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn">view more</a>
   </div>
   <div style="margin-bottom: 2cm;"></div>

</section>

<!-- courses section ends -->












<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>