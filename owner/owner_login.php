<?php
session_start();

#########################################################
# FILE: owner_login.php
#
# DESCRIPTION : This is the back end code when an owner/administrator
#               logs in.
#
# WHO      WHEN     WHAT
#--------------------------------
#          01-08-15 Copied from Log_in_a_client.php
#Irvine    01-10-15 Got rid of 'if( !$_POST )' condition
#                   since it will never be like this.
#                   
#                   Implemented popup().
#          01-22-15 Moved to Linux.
#                   Modified to Linux env.
#          03-15-15 Added salt and hash.
#          03-20-15 Implemented CSFR defense.
#          06-06-15 Implmented AJAX validation.
#          06-11-15 Implemented Password recovery.
#          06-11-15 Added code to detect if the email has not yet completed 
#                   email authentication.
#########################################################

require_once '../common/dependency.php';

vConnectDB( "baboom" );
		
function iCheckIfOwnerEmailExists( $SafeEmail, $SafePW, &$ID, &$Email_status, &$Email_code, &$Password_status )
{
	global $mysqli;
	
	$QueryResultSet = "SELECT count(*) AS row_exists, 
                                  salt, 
                                  password, 
                                  id,
				  email_active,
				  email_code,
				  password_recover
			   FROM login_table
			   WHERE email_address = '$SafeEmail'";

	
	$objGetResult = $mysqli->query( $QueryResultSet );
if(DEBUG)
{	
	echo "$QueryResultSet<br>";
	var_dump($objGetResult);
}
	if( $objGetResult ) #remember that even if there is no single row has returned, it will return an object which is non-zero.  
	{
		$anArray = $objGetResult->fetch_array( MYSQLI_ASSOC );
		
		$numof_rows = stripslashes($anArray['row_exists']);
		$salt = stripslashes($anArray['salt']);
		$password = stripslashes($anArray['password']);
		$Password_status = stripslashes( $anArray['password_recover'] );

if(DEBUG)
{	
	echo "Inside<br>";
	echo "numof_rows = $numof_rows<br>";
	echo "salt = $salt<br>";
	echo "password = $password<br>";
}
		if( $numof_rows == 1 )
		{
			$hash = getHash( $SafePW, $salt );
if(DEBUG)
{	
	echo "Inside<br>";
	echo "numof_rows = $numof_rows<br>";
	echo "salt = $salt<br>";
	echo "password = $password<br>";
	echo "SafePW = $SafePW<br>";
	echo "hash = $hash<br>";
}

			if( $hash == $password )
			{
				$ID = stripslashes($anArray['id']);
				$Email_status = stripslashes( $anArray['email_active'] );
				$Email_code = stripslashes( $anArray['email_code'] );
if(DEBUG)
{
	echo "email_code" . $Email_code . "<br>";
}

				$Email_Exists = 1;
			}
			else
			{
				$Email_Exists = 0;
			}
		}
		else
		{
			$Email_Exists = 0;
		}
		
		$objGetResult->free_result();
	}
	return $Email_Exists;
}

	/*******************************************************************************/
	/*                                                                             */
	/*             B E G I N                                                       */
	/*                                                                             */
	/*******************************************************************************/
if( DEBUG )
{
	echo "Hello from owner_login.php <br>";
}

	//
	// The parameters are coming from a Javascript using AJAX
	// All the echo statements will be displayed on the element designated by AJAX.
	//
	$q = $_REQUEST["q"];

        $InputArray = explode( "|", $q );
	$owner_email = $InputArray[0];
	$owner_password = $InputArray[1];
        $token = $InputArray[2];
	

if( DEBUG )
{
	echo "Hello from owner_login.php <br>";
	var_dump( $InputArray );
	echo "TOKEN: $token<br>";
	echo "_SESSION['owner_login_token'] = " . $_SESSION['owner_login_token'] . "<br>";
}

        //$SafeEmail = mysqli_real_escape_string( $mysqli, $owner_email);

        $SafePWD = mysqli_real_escape_string( $mysqli, $owner_password);

	$iOwnerExists = iCheckIfOwnerEmailExists($SafeEmail, $SafePWD, $ID, $Email_status, $email_code, $Password_status );
	
	#if the owner exists.
	if( Token::check( "OWNER_LOGIN_FORM", $token ))
	{
		if( $iOwnerExists == 1 )
		{
if( DEBUG )
{
	echo "TOKEN matches<br>";
}
			//If email is already activated.
			if( $Email_status == 1 )
			{
				$_SESSION['user'] = 'owner';
				$_SESSION['id'] = $ID;
                        	
				//If owner wants to reset the password.
				if( $Password_status == 1 )
				{
					
					// header() function didn't work.  header() function displayed a nested website .
					// This function worked.  I think it has to do with angularJS.  
					echo '<META HTTP-EQUIV="Refresh" Content="0;URL=owner_reset_password_front_end.php">';
				}
				else
				{
					$_SESSION['email'] = $SafeEmail;
					echo "Welcome back!";
				}
			}
			// This is the case where the email exists but the user didn't authenticate the email
			// by returning the email.  In this case, baboom will ask the user to authenticate the
			// email once again.
			else
			{
				SendActivateEmailNotice( $SafeEmail, $email_code );
				
				echo "Please activate your email.  Please respond to your email. Thanks.";
			}
		}
		else
		{
			//popup( "Please register before log in.  Thanks.", OWNER_LOGIN_PAGE );
			echo "Please register before log in.  Thanks.";
		}
	}
	else
	{
		//popup( "Token doesn't match.", OWNER_LOGIN_PAGE );
		echo "Please refresh the brower by pressing F5 ( Error: Token doesn't match.  _SESSION['owner_login_token'] = " . $_SESSION['owner_login_token'];
	}
?>
