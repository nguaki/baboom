<?php
session_start();
#########################################################
# FILE: member_register.php 
#
# DESCRIPTION : This is the back end code when client
#               registers for the first time.
#
# WHO      WHEN     WHAT
#--------------------------------
#          01-08-15 Copied from Owner_Register.php
#Irvine    01-10-15 converted to simpler syntax insert statement.
#          01-22-15 Moved to linux.
#                   Modified echo to popup.
#                   Added logic to take care of already 
#                   registered member.
#          02-15-15 Implemented AJAX to store the status of the registration.
#                   This script is called from javascript "confirm_registration" which
#                   is triggered from submit button of a form in the client_registration.php. 
#          03-15-15 Added salt and hash.
#          07-08-15 Added rollback in case insert didn't go thru.
#########################################################

require_once '../common/dependency.php';


vConnectDB( "baboom" );
                
function iCheckIfClientEmailExists($SafeEmail)
{
        global $mysqli;
        
        $QueryResultSet = "SELECT count(*) AS row_exists
                                           FROM client_login_table
                                           WHERE email_address = '$SafeEmail'";
                
if(DEBUG)
{
        echo "$QueryResultSet<br>";
}       
        $objGetResult = $mysqli->query( $QueryResultSet );
        
if(DEBUG)
{
        var_dump($objGetResult);
}
        if( $objGetResult ) #remember that even if there is no single row has returned, it will return an object which is non-zero.  
        {
                $anArray = $objGetResult->fetch_array( MYSQLI_ASSOC );
                
                $numof_rows = stripslashes($anArray['row_exists']);
if(DEBUG)
{
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

function vInsertIntoClientLoginTable( $SafeFirstName, $SafeLastName, $SafeEmail, $SafePWD )
{
        global $mysqli;
        
        $UserID = $SafeFirstName . $SafeLastName;
        
        $iClientExists = iCheckIfClientEmailExists($SafeEmail);
        
        #if this is the first claim.
        if( $iClientExists == 0 )
        {

		$salt = salt();

		$hash = getHash( $SafePWD, $salt );

                $email_code = md5( $SafeEmail + microtime() );

		#user_id is also email address.
		$mysqli->autocommit( FALSE );

                $InsertCommand = "INSERT INTO client_login_table 
                                        ( id, first_name, last_name, email_address, email_code, salt, password )
                                  values 
                                  (NULL,'$SafeFirstName', '$SafeLastName', '$SafeEmail', '$email_code', '$salt', '$hash' )";

                
                $add_post_res = $mysqli->query($InsertCommand) or die($mysqli->error);

		if( !$mysqli->commit())
        	{
                	$mysqli->rollback();
        	}

		SendActivateEmailNotice( $SafeEmail, $email_code );
		echo "Please activate your email to complete the registration.  Please respond to your email. Thanks.";

        }
        else
        #if this person is already registered.
        {
                 /*popup('You have already registered.', "http://" . IP_ADDRESS . "/member/client_login_register.php");*/
                 echo "You have already registered";
        }
        
}

        /**********************************************************************/
        /*    B E G I N                                                       */
        /**********************************************************************/


	$q = $_REQUEST["q"];

        //echo $q;

        $InputArray = explode( "|", $q );

        $client_email = $InputArray[0];
        $client_password = $InputArray[1];
        $client_first_name = $InputArray[2];
        $client_last_name = $InputArray[3];
	$token = $InputArray[4];

        $SafeEmail = mysqli_real_escape_string( $mysqli, $client_email);
        $SafePWD = mysqli_real_escape_string( $mysqli, $client_password);
        $SafeFirstName = mysqli_real_escape_string( $mysqli, $client_first_name);
        $SafeLastName = mysqli_real_escape_string( $mysqli, $client_last_name);

        if( Token::check( "MEMBER_REGISTER_FORM", $token ))
        {
		if( iCheckLegitPassword( $SafePWD ) == false )
		{
			echo "Invalid password";
		}	
		else
		{
        		vInsertIntoClientLoginTable( $SafeFirstName, $SafeLastName, $SafeEmail, $SafePWD );
		}
	}
	else
	{
		echo "Please refresh your web( press F5) and try again. (Error:Token doesn't match)";
	}
?>
