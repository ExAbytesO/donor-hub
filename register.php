<!--<html>
<head>
	<script type="text/javascript">

	  function checkForm(form)
	  {
		...

		if(!form.captcha.value.match(/^\d{5}$/)) {
		  alert('Please enter the CAPTCHA digits in the box provided');
		  form.captcha.focus();
		  return false;
		}
		if(form.captcha.value.match(/^\d{5}$/)) {
		  return true;
		}
	  }
		
	</script>
</head>-->
<?php
/* Registration process, inserts user info into the database 
   and sends account confirmation email message
 */

require_once("db.php");

/*if($_POST['captcha'] != $_SESSION['digit']){
	// Set session variables to be used on profile.php page
	$_SESSION['email'] = $_POST['email'];
	$_SESSION['first_name'] = $_POST['firstname'];
	$_SESSION['last_name'] = $_POST['lastname'];*/

if(!empty($_POST['password'])){
	/*if (strlen($_POST["password"]) <= '8') {
            $_SESSION['message'] = "Your Password Must Contain At Least 8 Characters!";
			header("location: error.php");
        }
        elseif(!preg_match("#[0-9]+#",$password)) {
            $_SESSION['message'] = "Your Password Must Contain At Least 1 Number!";
			header("location: error.php");
        }
        elseif(!preg_match("#[A-Z]+#",$password)) {
            $_SESSION['message'] = "Your Password Must Contain At Least 1 Capital Letter!";
			header("location: error.php");
        }
        elseif(!preg_match("#[a-z]+#",$password)) {
            $_SESSION['message'] = "Your Password Must Contain At Least 1 Lowercase Letter!";
			header("location: error.php");
        }
		elseif(empty($_POST["password"]) ){
            $_SESSION['message'] = "Please Check You've Entered Or Confirmed Your Password!";
			header("location: error.php");
        }
		if(preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/', $_POST['password'])){
			$_SESSION['message'] = "Password does not meet the requirements";
			header("location: error.php");
		}
		else{*/
			// Escape all $_POST variables to protect against SQL injections
			if($conn != NULL){
				$first_name = mysqli_escape_string($conn , $_POST['firstname']);
				$last_name = mysqli_escape_string($conn , $_POST['lastname']);
				$email = mysqli_escape_string($conn , $_POST['email']);
				$password = mysqli_escape_string($conn, password_hash($_POST['password'], PASSWORD_BCRYPT));
				$hash = mysqli_escape_string( $conn ,hash("sha256", rand(0,1000) ) );
					  
				// Check if user with that email already exists
				$result = $conn->query("SELECT * FROM users WHERE email='$email'") or die($conn->error());

				// We know user email exists if the rows returned are more than 0
				if ( $result->num_rows > 0 ) {
					
					$_SESSION['message'] = 'User with this email already exists!';
					header("location: error.php");
					
				}
				else { // Email doesn't already exist in a database, proceed...

					// active is 0 by DEFAULT (no need to include it here)
					$sql = "INSERT INTO users (first_name, last_name, email, password, hash) " 
							. "VALUES ('$first_name','$last_name','$email','$password', '$hash')";

					// Add user to the database
					if ( $conn->query($sql) ){

						$_SESSION['active'] = 0; //0 until user activates their account with verify.php
						$_SESSION['logged_in'] = true; // So we know the user has logged in
						$_SESSION['message'] =
								
								 "Confirmation link has been sent to $email, please verify
								 your account by clicking on the link in the message!";

						// Send registration confirmation link (verify.php)
						$to      = $email;
						$subject = 'Account Verification ( donorhub.com )';
						$message_body = '
						Hello '.$first_name.',

						Thank you for signing up!

						Please click this link to activate your account:

						http://localhost/DonorHub_Web/verify.php?email='.$email.'&hash='.$hash;  
						
						$from='From: donotreply@donorhub.com';
						
						mail( $to, $subject, $message_body,$from  );

						header("location: success.php"); 

					}

					else {
						$_SESSION['message'] = 'Registration failed!';
						header("location: error.php");
					}

				}
			}else{
			echo "Conn is null";}
		}

//die("Sorry, the CAPTCHA code entered was incorrect!");
$conn->close();
?>

</html>