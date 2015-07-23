<?php
################################################################
#
#  DESCRIPION:  This script is used to recover forgotten password.
#               This script is called for AJAX subroutine from
#               owner_recover_password_front_end.php.
#
#               This script sneds an email to a user who forgot
#               his/her password.  In the email, it has a temporary
#               password.  
#
#               This script updates the login_table the status of
#               the password such that when the user logs in, the web
#               will automatically directs the user to reset the
#               password.
#
#
#  WHO       WHAT        WHEN
#            06-12-15    Created.
#            06-16-15    Implemented CSFR.
#
#
#
#
# 
#
#
################################################################
session_start();
require_once '../common/dependency.php';


vConnectDB( "baboom" );

function iCheckIfOwnerEmailExists($SafeEmail )
{
	global $mysqli;
	
	$QueryResultSet = "SELECT count(*) AS row_exists 
			   FROM login_table
			   WHERE email_address = '$SafeEmail' and email_active = 1";

	
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

		if( $numof_rows == 1 )
		{
			$Email_Exists = 1;
		}
		else
		{
			$Email_Exists = 0;
		}
		
		$objGetResult->free_result();
	}
	return $Email_Exists;
}



	/*****************************************************************************/
        /*   SCRIPT BEGINS HERE                                                      */
	/*****************************************************************************/

	$q = $_REQUEST["q"];

	$InputArray = explode( "|", $q );
	$owner_email = $InputArray[0];
	$token = $InputArray[1];
	
	$SafeEmail = mysqli_real_escape_string( $mysqli, $owner_email );
	$iOwnerExists = iCheckIfOwnerEmailExists( $SafeEmail );

	if( Token::check( "OWNER_RECOVER_PW_FORM", $token ) )
	{
		if( $iOwnerExists == 1)
		{
			// This assignment is used at owner_reset_password.php.
			$_SESSION['email'] = $SafeEmail;
			
			// Generates  a temporary password.
			$Temp_PW = substr( md5( rand( 999, 999999 ) ), 0, 8 );
			
			//Send temporary password via email.
			SendTemporaryPWNotice( $SafeEmail, $Temp_PW );

			
			//Obtain a encryption and save it in the DB.
			$salt = salt();
					
			#Hash a string that is comprised of password and salt and save it as a password.
			#This will create a second level of security.
			$hash = getHash( $Temp_PW, $salt );

			// Update password_recover flag to 1.  This tells that the user is going thru password recover phase.
			$mysqli->autocommit( FALSE );
			$UpdateSQL = "UPDATE `login_table`
				      SET `password_recover` = 1,
					  `salt` = '$salt', 
					  `password` = '$hash'
				      WHERE `email_address` = '$SafeEmail'";
							  
			echo "You will receive a temporary password via email.";
if( DEBUG )
{
	  echo $UpdateSQL;
}				
			if( !$mysqli->query( $UpdateSQL) )
			{
				echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
		        if( !$mysqli->commit())
        		{
                		$mysqli->rollback();
        		}

			//popup ( "You will receive a temporary password via email. Please login using this temporary password.", OWNER_LOGIN_PAGE );

		}
		else
		{
			echo "Please register first!";
		}
	}
	else
	{
		echo "Please refresh your browser by pressing F5 and try again. ( ERROR: Token  doesn't match! )";
	}
?>
