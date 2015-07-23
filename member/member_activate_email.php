<?php
session_start();
#########################################################
# FILE: member_activate_email.php
#
# DESCRIPTION : This is the final stage of the registration for an member.
#               This script responds when an owner click to the hyperlink
#               that is embedded in the email.
#
# WHO      WHEN     WHAT
#--------------------------------
#          06-18-15 Copied from owner_activate_email.php
#
#
#
#
#
#########################################################
require_once '../common/dependency.php';

vConnectDB( "baboom" );

if( isset( $_GET['email'], $_GET['email_code'] ) === true )
{
	global $mysqli;
	
	$email = trim( $_GET['email'] );
	$email_code = trim( $_GET['email_code'] );
if(DEBUG)
{
	echo "email = " . $email . "<br>";
	echo "email_code = " . $email_code . "<br>";

}
	$email = mysqli_real_escape_string( $mysqli, $email );
	$email_code = mysqli_real_escape_string( $mysqli, $email_code );
	
	$QueryResultSet = "SELECT count(*) AS row_exists, email_active
					   FROM client_login_table
					   WHERE email_address = '$email' and email_code = '$email_code'";
if(DEBUG)
{
	echo $QueryResultSet;
}	
	$objGetResult = $mysqli->query( $QueryResultSet );

	if( $objGetResult ) #remember that even if there is no single row has returned, it will return an object which is non-zero.  
	{
		$anArray = $objGetResult->fetch_array( MYSQLI_ASSOC );
		
		$numof_rows = stripslashes($anArray['row_exists']);
		$email_active = stripslashes($anArray['email_active']);
		
if( DEBUG )
{
		echo "From iCheckIfOwnerEmailExists():Numof_rows: $numof_rows<br>";
}
		if( $numof_rows == 1 )
		{
			if( $email_active == 1 )
			{
				popup( "This email is already activated.", OWNER_LOGIN_PAGE );
				//echo "This email is already activated<br>";
			}
			else
			{
				$mysqli->autocommit( FALSE );

				$UpdateSQL = "UPDATE `client_login_table`
				              SET `email_active` = 1
				              WHERE `email_address` = '$email' and 
							        `email_code` = '$email_code'"; 
if( DEBUG )
{
				echo $UpdateSQL;
}				
				if( !$mysqli->query( $UpdateSQL) )
				{
					echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
					//die();
				}

		        	if( !$mysqli->commit())
        			{
                			$mysqli->rollback();
        			}


				popup( "Congratulations. You have successfully completed the registration.  You can now proceed with login.", MEMBER_LOGIN_PAGE );
			}
		}
		else
		{
if( DEBUG )
{
			echo "I am here.  Where is my popup<br>";
}
			// Don't put single quote between double quotes.
			popup( "Either email or activation code do not match.", MEMBER_LOGIN_PAGE );

		}
		
	}
	$objGetResult->free_result();
	$mysqli->close();
}
else
{
	popup( "Data is missing. (Error in GET inputs).", MEMBER_LOGIN_PAGE );
	//echo "Error in GET inputs<br>";
}
?>
