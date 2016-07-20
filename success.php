<?php 
session_start();

require_once("connection.php");

date_default_timezone_set('America/Los_Angeles');
?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Wall Assignment</title> 	
 	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css">	 -->
 	<link rel="stylesheet" type="text/css" href="skeleton.css">
 	<link rel="stylesheet" type="text/css" href="style.css">
 </head>
 <body>
	<div id="header" class="row">
		<div class="nine columns">
			<h4>The Great Wall of Chand</h4>
		</div>
		<div class="one columns">
			Welcome <?php echo $_SESSION['first_name'];?>!
		</div>
		<div class="one columns">
			<a class='button button-default' href='process.php'>LOG OFF</a>
		</div>
	</div>

	<!-- Main content -->
 	<div class="container main">

 		<!-- If any errors, show in this div -->
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

 		<!-- Post a message form -->
 		<form action="process.php" method="post">
 			<!-- action -->
 			<input type="hidden" name="action" value="post_message">

 			<label for="message">Post a message</label>
 			<textarea class="u-full-width" id="message" name="message"></textarea>
 			<input type="submit" id="post_message" class="button button-primary" name="post_message" value="Post a message">
 		</form>		

 	</div>

 	<!-- Messages -->
 	<div class="container">
 		<?php 
 		$query = "SELECT users.first_name,users.last_name, DATE_FORMAT(messages.created_at,'%M %D %Y') AS created_at, messages.message, messages.id, messages.user_id FROM messages LEFT JOIN users on users.id = messages.user_id ORDER BY messages.created_at DESC";

 		$messages = fetch_all($query);

 		foreach ($messages as $message) { 
 			echo "<div class='message_row'><div class='row name_date'>".$message['first_name']." ". $message['last_name']." - " . $message['created_at'] . "</div>";
 			echo "<div class='row message'>" . $message['message'];

 			// If message was written by current user, allow them to delete it
 			if($_SESSION['user_id'] == $message['user_id']){ ?>
 				<!-- echo "<p>it's you</p>"; -->
 			
 				<form class="delete_form" action="process.php" method="post">
		 			<!-- action -->
		 			<input type="hidden" name="action" value="delete_message">
		 			<input type="hidden" name="message_id" value="<?php echo $message['id']?>">

		 			<input type="submit" class="delete_message button button-default" value="Delete message">
	 			</form>	

 			<?php } ?>

 			</div> <!-- Close message div -->

 			<!-- comments -->
 			<div class="comments">
 				<?php 

 				$query = "SELECT users.first_name,users.last_name, comment, date_format(comments.created_at, '%M %D %Y') as created_at, comments.id, comments.user_id as user_id, comments.created_at as time1, comments.created_at + INTERVAL 30 MINUTE AS time2 FROM comments LEFT JOIN users ON users.id = comments.user_id WHERE message_id ='".$message['id']. "' ORDER BY comments.created_at DESC";

 				$comments = fetch_all($query);

 				foreach ($comments as $comment){
 					
 					echo "<div class='comment_row'><div class='row name_date'>".$comment['first_name']." ". $comment['last_name']." - " . $comment['created_at'] . "</div>";
 					echo "<div class='row comment'>" . $comment['comment'] . "</div>"; 

 					// If comment was by current user, allow them to delete it - ONLY ALLOW COMMENT DELETE BUTTON IF COMMENT WAS MADE IN THE LAST 30 MINUTES
 					$date_now = date('Y-m-d H:i:s'); 					

 					if(($_SESSION['user_id'] == $comment['user_id']) && ($date_now < $comment['time2'])){  ?>
						<form class="row comment delete_form" action="process.php" method="post">
				 			<!-- action -->
				 			<input type="hidden" name="action" value="delete_comment">
				 			<input type="hidden" name="comment_id" value="<?php echo $comment['id']?>">
				 			<input type="submit" class="delete_comment button button-default" value="Delete comment">
			 			</form>		
 					<?php } ?>
 					</div>

 				<?php  } ?>
 				
 			</div>
 			<!-- end comments -->

 			<!-- Post a comment to the message -->
	 		<form class="comment_form" action="process.php" method="post">
	 			<!-- action -->
	 			<input type="hidden" name="action" value="post_comment">
	 			<input type="hidden" name="message_id" value="<?php echo $message['id']?>">

	 			<label for="comment">Post a comment</label>
	 			<textarea class="u-full-width" id="comment" name="comment"></textarea>
	 			<input type="submit" id="post_comment" class="button button-primary" value="Post a comment">
	 		</form>	

 			<?php }	?>
 	</div>
 </body>
 </html>