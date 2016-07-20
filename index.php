<?php 
/* Sharol Chand, 4/11/2016, Coding Dojo -PHP - The Wall
Assignment: The Wall
OptionalDeadline: Monday of Week 2Difficulty Level: AdvancedEstimated Time: 4-12 hrs
Create a wall/forum page where users will be able the post a message and see the message displayed by other users. Store the messages in a table called 'messages' and retrieve the messages from the database. Have a Login and Registration page. After logging in, the user is directed to The Wall.

Download the handout for the wireframe/ERD */
session_start();
include_once("connection.php");

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Login or Register</title> 	
 	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css">	 -->
 	<link rel="stylesheet" type="text/css" href="skeleton.css">
 	<link rel="stylesheet" href="style.css">
 </head>
 <body>
 	<div class="container main">
 		<div>
 			<?php  
			if(isset($_SESSION['errors'])){
				foreach ($_SESSION['errors'] as $error) {
					echo "<p class='error'>{$error}</p>";
					unset($_SESSION['errors']);
				}
			}

			if(isset($_SESSION['success_message'])){
				echo "<p class='success'>{$_SESSION['success_message']}";
				unset($_SESSION['success_message']);
			}
			 ?>
 		</div>
 		<!-- Register  form-->
 		<h2>Register</h2>
		<form action="process.php" method="post">
			<!-- action -->
			<input type="hidden" name="action" value="register">

			<!-- First name -->
			<div class="row">
				<div class="three columns">
					<label for="first_name">First name:</label>
					
				</div>
				<div class="nine columns">
					<input class="" name="first_name" type="text" id="first_name" placeholder="First name">
				</div>				
			</div>

			<!-- Last name -->
			<div class="row">
				<div class="three columns">
					<label for="last_name">Last name:</label>
					
				</div>
				<div class="nine columns">
					<input class="" name="last_name" type="text" id="last_name" placeholder="Last name">
				</div>				
			</div>

			<!-- Email -->
			<div class="row">
				<div class="three columns">
					<label for="email">Email address:</label>
					
				</div>
				<div class="nine columns">
					<input class="" name="email" type="text" id="email" placeholder="Email address">
				</div>				
			</div>

			<!-- Password -->
			<div class="row">
				<div class="three columns">
					<label for="password">Password:</label>
					
				</div>
				<div class="nine columns">
					<input class="" name="password" type="password" id="password" placeholder="Password">
				</div>				
			</div>

			<!-- Confirm Password -->
			<div class="row">
				<div class="three columns">
					<label for="confirm_password">Confirm Password:</label>
					
				</div>
				<div class="nine columns">
					<input class="" name="confirm_password" type="password" id="confirm_password" placeholder="Confirm Password">
				</div>				
			</div>

			<!-- Submit -->
			<div class="row">
				<input type="submit" class="button-primary btn" value="Register">
			</div>
		</form> <!-- End register form -->

		<!-- Login  form-->
 		<h2>Login</h2>
		<form action="process.php" method="post">
			<!-- action -->
			<input type="hidden" name="action" value="login">
			<!-- Email address -->
			<div class="row">
				<div class="three columns">
					<label for="email">Email address:</label>
					
				</div>
				<div class="nine columns">
					<input class="" name="email" type="text" id="email" placeholder="Email address">
				</div>				
			</div>

			<!-- Password -->
			<div class="row">
				<div class="three columns">
					<label for="password">Password:</label>
					
				</div>
				<div class="nine columns">
					<input class="" name="password" type="password" id="password" placeholder="Password">
				</div>				
			</div>		

			<!-- Submit -->
			<div class="row">
				<input type="submit" class="button-primary btn" value="Login">
			</div>	
		</form> <!-- End login form -->
 	</div>
 </body>
 </html>