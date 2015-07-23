<?php
session_start();
#########################################################
# FILE: owner_activate_email.php
#
# DESCRIPTION : This is the final stage of the registration for an owner.
#               This script responds when an owner click to the hyperlink
#               that is embedded in the email.
#
# WHO      WHEN     WHAT
#--------------------------------
#          03-21-15 Implemented email activation.
#          06-12-15 Transferrred from windows.
#          06-25-15 Since this is running in persistent DB,  there is no need
#                   to close DB.  Please read  php.net/manual/en/features.persistent-connections.php.
#
#
#
#
#########################################################
//require_once 'C:\\xampp\\htdocs\\xampp\\my_exercises\\senior_site_project\\common\\dependency.php';
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
					   FROM login_table
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
				$UpdateSQL = "UPDATE `login_table`
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


				popup( "Congratulations. You have successfully completed the registration.  You can now proceed with login.", OWNER_LOGIN_PAGE );
			}
		}
		else
		{
if( DEBUG )
{
			echo "I am here.  Where is my popup<br>";
}
			// Don't put single quote between double quotes.
			popup( "Either email or activation code do not match.", OWNER_LOGIN_PAGE );

		}
		$objGetResult->free_result();
	}
	//$mysqli->close();    //No need to close() each time.
}
else
{
	popup( "Error in GET inputs.", OWNER_LOGIN_PAGE );
	//echo "Error in GET inputs<br>";
}
?>
