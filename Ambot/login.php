<!-- this is for the log in -->
<?php
include_once 'components/connect.php';
?>
<?php

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit_login'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);
   
   if($select_user->rowCount() > 0){
     setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:home.php');
   }else{
      $message[] = 'incorrect email or password!';
   }

}
?>

<!-- this is for the sign up -->
<?php
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit_signup'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   
   if($select_user->rowCount() > 0){
      $message[] = 'email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm passowrd not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
         $insert_user->execute([$id, $name, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         
         $verify_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
         $verify_user->execute([$email, $pass]);
         $row = $verify_user->fetch(PDO::FETCH_ASSOC);
         
         if($verify_user->rowCount() > 0){
            setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
            header('location:home.php');
         }
      }
   }

}

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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/login_register1.css">



</head>

<body>

</head>
<div class="container" id="container">
	<div class="form-container sign-up-container">
    
		<form action="" method="post" enctype="multipart/form-data">
			<h1>Create Account</h1>
            <span class= "span1" style="margin-bottom: 10px;">Create your PTCI account and begin your academic journey today!</span>
			<input type="text" name="name" placeholder="Enter your Name" maxlength="50" required >
            <input type="email" name="email" placeholder="Enter your Email" maxlength="100" required >
			<input type="password" name="pass" placeholder="Enter your Password" maxlength="20" required >
            <input type="password" name="cpass" placeholder="Confirm your Password" maxlength="20" required >
            <span  class="profile_pic">Please select your photo <span>*</span></span>
            <input type="file" name="image" accept="image/*" required >
			<button style="margin-top: 5px;" type ="submit" name="submit_signup" value="Register Now">Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="" method="post" enctype="multipart/form-data" class="login">
			<h1>Welcome!</h1>
			<span>Log in using your email and password.</span>
			<input type="email" name="email" placeholder="Enter your Email" maxlength="100" required >
            <input type="password" name="pass" placeholder="Enter your Password" maxlength="20" required >
			<!-- <button><input type="submit" name="submit" value="login now"></button> -->
             <br>
            <button type="submit" name="submit_login" value="login now" >Login Now</button>
            <p class="link">Are you an Instructor? <a href="admin/Login.php" style="color: blue;">Login here</a></p>
            
		</form> 
	</div>
	<div class="overlay-container">
   
		<div class="overlay">
              <div class="back_ptci-logo_right"></div>
              <div class="back_ptci-logo_left"></div>
			<div class="overlay-panel overlay-left">
				<h1>Welcome!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Log In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hello, Student!</h1>
				<p>Don't have an account yet? Sign up now! and start journey with us</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>
<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>

<footer class="footer1">
   &copy; copyright @ <?= date('Y'); ?> by Palawan Technological College Inc. | all rights reserved!

</footer>

<!-- custom js file link  -->

   
</body>
</html>