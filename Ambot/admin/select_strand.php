<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Select Strand</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <!-- <style>
      /* Center the section */
      .strand-selection {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 90vh;
        text-align: center;
        margin-top: -45px; /* Moves the section up */
        }


      .box-container {
         display: flex;
         gap: 20px;
      }

      .box {
         background: #2c3e50;
         color: white;
         padding: 20px 40px;
         border-radius: 10px;
         text-decoration: none;
         font-size: 24px;
         font-weight: bold;
         transition: 0.3s;
         box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
      }

      .box:hover {
         background: #34495e;
         transform: translateY(-5px);
      }
   </style> -->

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="strand-selection">
   <h1 class="heading">Select Strand</h1>

   <div class="box-container">
   <a href="playlists.php" class="box">
   <h3>HUMMS</h3>
   </a>
   <a href="playlists.php" class="box">
   <h3>ICT</h3>
   </a>

   </div>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>

<?php

?>

