<?php 

session_start();
require_once("connection.php");
// var_dump($_POST);
// var_dump($_SESSION);

// Register
if(isset($_POST['action']) && $_POST['action'] == 'register'){
	register_user($_POST); // call register user function
}
// Login
elseif(isset($_POST['action']) && $_POST['action'] == 'login'){		
	login_user($_POST);
}
// Post a message
elseif(isset($_POST['action']) && $_POST['action'] == 'post_message'){	
	post_message($_POST); 
}
// Post a comment
elseif(isset($_POST['action']) && $_POST['action'] == 'post_comment'){	
	post_comment($_POST); 	
}
// Delete message
elseif(isset($_POST['action']) && $_POST['action'] == 'delete_message'){	
	delete_message($_POST);	
} 
// Delete comment
elseif(isset($_POST['action']) && $_POST['action'] == 'delete_comment'){	
	delete_comment($_POST);
} 
else{ //malicious navifation to process.php OR Someone is trying to log off!
	session_destroy();
	header("Location: index.php");
	die();
}

function register_user($post){
	// Validations
	$_SESSION['errors'] = array();

	if(empty($post['first_name'])){
		$_SESSION['errors'][] = "First name can't be blank!";
	}
	if(empty($post['last_name'])){
		$_SESSION['errors'][] = "Last name can't be blank!";
	}
	if(empty($post['password'])){
		$_SESSION['errors'][] = "Password field is required!";
	}
	if($post['password'] !== $post['confirm_password']){
		$_SESSION['errors'][] = "Passwords must match!";
	}
	if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
		$_SESSION['errors'][] = "Please use a valid email address!";		
	}
	// end of validations check

	if(count($_SESSION['errors']) >= 1){
		header("Location: index.php");
		die();
	}else{
		// Prevent mysql injections
		$first_name = escape_this_string($post['first_name']);
		$last_name = escape_this_string($post['last_name']);
		$email = escape_this_string($post['email']);
		$password = md5(escape_this_string($post['password']));

		$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES('{$first_name}','{$last_name}','{$email}','{$password}',NOW(), NOW())";

		run_mysql_query($query);

		$_SESSION['success_message'] = "User successfully created!";

		//Send back to main page
		header("Location: index.php");
		die();
	}
}

function login_user($post){
	// Prevent mysql injections
	$email = escape_this_string($post['email']);
	$password = md5(escape_this_string($post['password']));

	$query = "SELECT * FROM users where users.password = '{$password}' AND users.email = '{$email}'";

	$user = fetch_record($query);

	if(count($user) > 0){
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['first_name'] = $user['first_name'];
		$_SESSION['logged_in'] = true;
		header("Location: success.php");
		die();
	}else{
		$_SESSION['errors'][] = "Please make sure your email and password are correct.";
		header("Location: index.php");
		die();
	}
}

function post_message($post){
	// Validations
	$_SESSION['errors'] = array();

	if(empty($post['message'])){
		$_SESSION['errors'][] = "Message can't be blank!";
	}
	// end of validations check

	if(count($_SESSION['errors']) > 0){
		header("Location: success.php");
		die();
	}else{

		// Prevent mysql injections
		$message = escape_this_string($post['message']);

		$query = "INSERT INTO messages (message, user_id, created_at, updated_at) VALUES ('{$message}','{$_SESSION['user_id']}', NOW(), NOW())";

		run_mysql_query($query);

		$_SESSION['success_message'] = "Message successfully posted!";
		header("Location: success.php");
		die();
	}
}

function post_comment($post){

	// Validations
	$_SESSION['errors'] = array();

	if(empty($post['comment'])){
		$_SESSION['errors'][] = "Comment can't be blank!";
	}
	// end of validations check

	if(count($_SESSION['errors']) > 0){
		header("Location: success.php");
		die();
	}else{

		$comment = escape_this_string($post['comment']);
		// Add comment
		$query = "INSERT INTO comments (comment, user_id, created_at, updated_at, message_id) VALUES ('{$comment}','{$_SESSION['user_id']}', NOW(), NOW(), '{$post['message_id']}')";

		run_mysql_query($query);

		$_SESSION['success_message'] = "Comment successfully posted!";
		header("Location: success.php");
		die();
	}
}

function delete_message($post){
	$message_id = escape_this_string($post['message_id']);

	// If user wants to delete the message, the comments have to be deleted first:

	$query = "DELETE FROM comments WHERE message_id='{$message_id}'";
	run_mysql_query($query);

	// Now delete message
	$query = "DELETE FROM messages WHERE id='{$message_id}'";
	run_mysql_query($query);

	$_SESSION['success_message'] = "Message successfully deleted!";
	header("Location: success.php");
	die();
}

function delete_comment($post){
	$comment_id = escape_this_string($post['comment_id']);

	// delete the comment
	$query = "DELETE FROM comments WHERE id='{$comment_id}'";
	run_mysql_query($query);

	$_SESSION['success_message'] = "Comment successfully deleted!";
	header("Location: success.php");
	die();
}
?>