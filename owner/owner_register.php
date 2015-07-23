<?php
session_start();
#########################################################
# FILE: owner_register.php
#
# DESCRIPTION : This is the back end code when owner/admin
#               registers for the first time.
#
# WHO      WHEN     WHAT
#--------------------------------
#          01-08-15 Sets global session variable for user = owner.
#          01-22-15 Moved to Linux and changed accordingly.
#          03-15-15 Added salt and hash.
#          06-10-15 Added AJAX code.
#                   This script is called from a Javascript subroutine
#                   which resides in owner_login_register.php.
#          06-16-15 Implemented CSFR.
#
#########################################################

require_once '../common/dependency.php';

vConnectDB( "baboom" );
		
function iCheckIfOwnerEmailExists($SafeEmail)
{
	global $mysqli;
	
	$QueryResultSet = "SELECT count(*) AS row_exists
					   FROM login_table
					   WHERE email_address = '$SafeEmail'";
	$objGetResult = $mysqli->query( $QueryResultSet );
	
	if( $objGetResult ) #remember that even if there is no single row has returned, it will return an object which is non-zero.  
	{
		$anArray = $objGetResult->fetch_array( MYSQLI_ASSOC );
		
		$numof_rows = stripslashes($anArray['row_exists']);
if(DEBUG)
{		
	var_dump($objGetResult);
	echo "$QueryResultSet<br>";
	echo "From iCheckIfOwnerEmailExists():Numof_rows: $numof_rows<br>";
}	
		if( $numof_rows >= 1 )
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

///////////////////////////////////////////////////////////////////////////////////////////////
//
//  WHO       WHEN        WHAT
//            06-11-15    Added email_code.
//            06-16-15    Added email activation.
//
///////////////////////////////////////////////////////////////////////////////////////////////
function vInsertIntoOwnerLoginTable( $SafeFirstName, $SafeLastName, $SafeEmail, $SafePWD )
{
	global $mysqli;
	
	$UserID = $SafeFirstName . $SafeLastName;
	
	$iOwnerExists = iCheckIfOwnerEmailExists($SafeEmail);
	
	#if this is the first claim.
	if( $iOwnerExists == 0 )
	{
		 
		#Obtain a cryption and save it in the DB.
		$salt = salt();

		#Hash a string that is comprised of password and a salt.
		#Save it as a password.  This will create a second level of security.
		$hash = getHash( $SafePWD, $salt );


		# The folloing is for email activation of validation.
		$email_code = md5( $SafeEmail + microtime() );

if( DEBUG )
{
	echo "salt =" .  $salt . "<br>";

	echo "SafePWD =" . $SafePWD . "<br>";
	echo "hash =" . $hash . "<br>";

}
		#user_id is also email address.
		$mysqli->autocommit( FALSE );

		$InsertCommand = "INSERT INTO 
                                  login_table ( id, user_id, salt, password, email_address, email_code, type )
				  values ( NULL, '".$SafeEmail."', '".$salt."', '".$hash."', '".$SafeEmail."', '".$email_code."', 'O' )";
		
		$add_post_res = $mysqli->query($InsertCommand); # or die($mysqli->error);
	        if( !$mysqli->commit())
        	{
                	$mysqli->rollback();
        	}


		SendActivateEmailNotice( $SafeEmail, $email_code );


		echo "Please activate your email to complete the registration.  Please respond to your email. Thanks.";


	}
	else
	//if this person is already registered.
	{
		/*popup( "You have already registere!", OWNER_LOGIN_PAGE ); */
		echo "You have already registered!";
	}
	
}


        /**********************************************************************/
        /*    B E G I N                                                       */
        /**********************************************************************/

	$q = $_REQUEST["q"];

	$InputArray = explode( "|", $q );

	$owner_email = $InputArray[0];
	$owner_pwd = $InputArray[1];
	$owner_first_name = $InputArray[2];
	$owner_last_name = $InputArray[3];
	$token = $InputArray[4];

if( DEBUG )
{
 	var_dump( $InputArray );
	echo "TOKEN = $token<br>";
	echo "SESSION[token] =" . $_SESSION['token'] . "<br>";
}	
	
	$SafeFirstName = mysqli_real_escape_string( $mysqli, $owner_first_name);
	$SafeLastName = mysqli_real_escape_string( $mysqli, $owner_last_name);
	$SafeEmail = mysqli_real_escape_string( $mysqli, $owner_email);
	$SafePWD = mysqli_real_escape_string( $mysqli, $owner_pwd);

        if( Token::check( "OWNER_REGISTER_FORM", $token ))
        {
		if( iCheckLegitPassword( $SafePWD ) == false )
		{
			echo "Invalid password";
		}	
		else
		{
			vInsertIntoOwnerLoginTable( $SafeFirstName, $SafeLastName, $SafeEmail, $SafePWD );
		}
	}
	else
	{
		echo "Token doesn't match.";
	}
?>
