<?php
session_start();
#########################################################
# FILE: member_login.php
#
# DESCRIPTION : This is the back end code when client
#               logs in.
#
# WHERE      WHEN     WHAT
#--------------------------------
#            01-08-15 Copied from Client_Register.php
#            01-10-15 Implemented JS popup.
#                     It's very important that there is no print
#                     statements in the page.  If it has a print statements
#                     the web page will come to this page before 
#                     it redirects to a new page.
#            01-22-15 Moved to Linux.
#                     Included password check.
#                     Modified redirect page.
#                     Defined IP_ADDRESS.
#            02-15-15 Implemented AJAX to replace alert messages.
#            03-15-15 Implemented salt and hash.
#            03-20-15 Implemented CSRF defense.
#########################################################

require_once '../common/dependency.php';
//require_once '/var/www/html/common/dependency.php';

vConnectDB( "baboom" );

//////////////////////////////////////////////////////////////////////////////
//
//  DESCRIPTION:  Searches for a given Email and returns the nummber of rows. 
//  OUTPUT     :  1   -  if a such email exists. 
//                0   -  if a such email doesn't exists.
//
//
//////////////////////////////////////////////////////////////////////////////
function iCheckIfClientEmailExists( $SafeEmail, $SafePWD,&$ID, &$Email_status, &$Email_code, &$Password_status )
{
        global $mysqli;
        
        $QueryResultSet = "SELECT count(*) AS row_exists, 
                                  salt,
                                  password,
                                  id,
				  email_active,
				  email_code,
				  password_recover
                           FROM client_login_table
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
                echo "From iCheckIfOwnerEmailExists():Numof_rows: $numof_rows<br>";
}

                if( $numof_rows >= 1 )
                {
                        $hash = getHash( $SafePWD, $salt );
if(DEBUG)
{
                echo "SafePWD: $SafePWD     salt: $salt<br>";
}
			if( $hash == $password )
			{
                        	$ID = stripslashes($anArray['id']);
				$Email_status = stripslashes( $anArray['email_active'] );
                                $Email_code = stripslashes( $anArray['email_code'] );
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


	/*************************************************************************************/
	// Script starts here.
	// This script is called from client_login_register.php.
	// Specifically, it is called from AJAX subroutine called validate_login().
	/*************************************************************************************/

	$q = $_REQUEST["q"];
        
        $InputArray = explode( "|", $q );
	$client_email = $InputArray[0];
        $client_password = $InputArray[1];
        $token = $InputArray[2];

        $SafeEmail = mysqli_real_escape_string( $mysqli, $client_email);
	$SafePWD = mysqli_real_escape_string( $mysqli, $client_password);

        $iClientExists = iCheckIfClientEmailExists($SafeEmail, $SafePWD, $ID, $Email_status, $email_code, $Password_status ); /* ID is a referene */

if(DEBUG)
{
	//There is no POST variables since all the values are sent via AJAX.
        //The below returns nothing.
	var_dump( $_POST );        
	print_r( $token );
}
        #if the client exists.
        if( $iClientExists == 1 )
        {
               	if( Token::check( "MEMBER_LOGIN_FORM", $token ))
		{
                       	if( $Email_status == 1 )
			{
				// Declare SESSION global.
				$_SESSION['user'] = 'member';
                       		$_SESSION['id'] = $ID;
                        
                               	if( $Password_status == 1 )
				{
					$_SSESSION['email'] = $SafeEmail;

					echo '<META HTTP-EQUIV="Refresh" Content="0;URL=member_reset_password_front_end.php">'; 
				}
				else
				{
					echo "Welcome back!";

				}
			}
			else
			{

				SendActivateEmailNoticeForMember( $SafeEmail, $email_code );

				echo "Please activate your email.  Please respond to your email. Thanks.";
			}
		}
		else
		{
			echo "Please refresh your web( press F5) and try again. (Error:Token doesn't match)";
		}
        }
        else
        {
		echo "Please register before log in.  Thank you";
        }
?>
