<?php
///////////////////////////////////////////////////////////////////////////////////////
//
// DESCRIPTIONG : This script is used to reset forgotten passowrd.
//
//
// WHO           WHEN            WHAT 
//               06-16-15        Created.
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
///////////////////////////////////////////////////////////////////////////////////////
session_start();
require_once '../common/dependency.php';

vConnectDB( "baboom" );

global $mysqli;

/*****************************************************************************/
/*   SCRIPT BEGINS HERE                                                      */
/*****************************************************************************/

$q = $_REQUEST["q"];

$InputArray = explode( "|", $q );
$Temp_PW = $InputArray[0];
$New_PW1 = $InputArray[1];
$New_PW2 = $InputArray[2];
$token = $InputArray[3];

if( DEBUG )
{
	var_dump( $_SESSION );
	var_dump( $q );
}

if( Token::check( "OWNER_RESET_PW_FORM", $token ) )
{
	if( $New_PW1 == $New_PW2 )
	{
		if( iCheckLegitPassword( $New_PW1 ) == true )
		{
			if( isset( $_SESSION['email']) === true )
			{
				
				$Temp_PW = trim( $Temp_PW );
				$Temp_PW = mysqli_real_escape_string( $mysqli, $Temp_PW );
				
				$New_PW1 = trim( $New_PW1 );
				$New_PW1 = mysqli_real_escape_string( $mysqli, $New_PW1 );
				
				$New_PW2 = trim( $New_PW2 );
				$New_PW2 = mysqli_real_escape_string( $mysqli, $New_PW2 );
		
				$SafeEmail = $_SESSION['email'];
				$SafeEmail = mysqli_real_escape_string( $mysqli, $SafeEmail );
				//unset( $_SESSION['email'] );
				
				$QueryResultSet = "SELECT count(*) AS row_exists, salt, password, id, email_active, email_code, password_recover
								   FROM login_table
								   WHERE email_address = '$SafeEmail'";
					
				//echo "$QueryResultSet<br>";
				
				$objGetResult = $mysqli->query( $QueryResultSet );
				
				//var_dump($objGetResult);
				
				#remember that even if there is no single row has returned, it will return an object which is non-zero.  
				if( $objGetResult ) 
				{
					$anArray = $objGetResult->fetch_array( MYSQLI_ASSOC );
					$salt = stripslashes($anArray['salt']);
					$password = stripslashes($anArray['password']);
					$numof_rows = stripslashes($anArray['row_exists']);

					if( $numof_rows == 1 )
					{
						$hash = getHash( $Temp_PW, $salt );

						#Check if the hash value matches the hash value from the table. 
						if( $hash === $password )
						{		
							$salt = salt();
					
							$hash = getHash( $New_PW1, $salt );
								
							$mysqli->autocommit( FALSE );
							$UpdateSQL = "UPDATE `login_table`
										  SET `password_recover` = 0, 
											  `password` = '$hash',
											  `salt` = '$salt'
										  WHERE email_address = '$SafeEmail'"; 

							echo "You have successfully resetted your password.";
	//if( DEBUG )
	//{
					//echo $UpdateSQL;
	//}				
							if( !$mysqli->query( $UpdateSQL) )
							{
									echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
							}
						        if( !$mysqli->commit())
        						{
                						$mysqli->rollback();
        						}


						}
						else
						{
								echo "Temp password doesn't match. Please enter correct temp password.";
						}	
					}
					else
					{
						echo "Please enter correct temporary password.";
					}
					$objGetResult->free_result();
				}
			}
			else
			{
				echo "_SESSION['email'] is not set.";
			}
		}
		else
		{
			echo "Invalid password";
		}
	}
	else
	{
		echo "Passwords do not match.";
	}
}
else
{
	echo "Token does not match.";
}
?>
