<?php
session_start();

########################################################################################################### 
#
# FILENAME:  owner_claim_facility.php
#
# DATABASE used: login_table, rcfe, facility_owner
#
# WHO        WHEN          WHAT
# 	     01-07-15      Added logic to store primary owner to rcfe table.
#
#            01-12-15      Removed UpdateClaim() function.  We no longer carry
#                          that flag.  It becomes obsolete due to many to many
#                          relationship.
#
#            06-18-15      firstname, lastname and email address inputs are not needed.
#                          Owner ID can be retrieved from $_SESSION['id'].
#                          There is no need to query for owner ID.
#            07-08-15      Added rollback() in case insert transaction doesn't go thru.
#
#
#
########################################################################################################### 

require_once '../common/dependency.php';

vConnectDB( "baboom" );

// 
// DESCRIPTION: This function assigns the primary ownership of the facility.
//
function vUpdatePrimaryOwnership( $SafeFacilityNumber, $Owner_Index )
{ 
	global $mysqli;

	$mysqli->autocommit( FALSE );

	$UpdateCommand = "UPDATE rcfe 
			  SET primary_owner = '$Owner_Index'
			  WHERE facility_number = $SafeFacilityNumber";

if(DEBUG)
{	
	echo $UpdateCommand;
}
	$add_post_res = $mysqli->query($UpdateCommand); # or die($mysqli->error);

        if( !$mysqli->commit())
        {
                $mysqli->rollback();
        }


}


/*function vMakeSafeVariables( &$SafeAddress, &$SafeCity, &$SafeZipcode, &$SafeFacilityNumber, &$SafeOwnerOfAdmin )*/
function vMakeSafeVariables( &$SafeAddress, &$SafeCity, &$SafeZipcode, &$SafeFacilityNumber )
{
	###
	# Gets the user ID and password from the text input.
	###
	global $mysqli;
	
	$SafeEmail = mysqli_real_escape_string( $mysqli, $_SESSION['email']);
	$SafeAddress = mysqli_real_escape_string( $mysqli, $_POST['address']);
	$SafeCity = mysqli_real_escape_string( $mysqli, $_POST['city']);
	$SafeZipcode = mysqli_real_escape_string( $mysqli, $_POST['zipcode']);
	$SafeFacilityNumber = mysqli_real_escape_string( $mysqli, $_POST['facility_number']);
}

////////////////////////////////////////////////////////////////////////////////////////////////////
//
// DESCRIPTION : THis function validates if the facility number matches with zipcode and city.
//               If there is a row then the claimed owner becomes a legit ownership of the
//               facility.
//
//  WHO        WHEN        WHAT
//             06-20-15    Added $SsfeAddress as an additional check.
//
//
////////////////////////////////////////////////////////////////////////////////////////////////////
function iCheckIfARowExistsFromRCFE( $SafeFacilityNumber, $SafeAddress, $SafeZipcode, $SafeCity, &$PrimaryOwner )
{
	global $mysqli;
	
	$QueryResultSet = "SELECT id, primary_owner
			   FROM rcfe
			   WHERE facility_number = '$SafeFacilityNumber' AND
					 address = '$SafeAddress' AND
					 zipcode = '$SafeZipcode' AND 
					 city = '$SafeCity'";

	$objGetResult = $mysqli->query( $QueryResultSet );
		
if(DEBUG)
{		
	echo $QueryResultSet;
	var_dump($objGetResult);
}
	
	//
	// remember that even if there is no single row has returned, it will return an object which is non-zero.  
	//
	if( $objGetResult ) 
	{
		$anArray = $objGetResult->fetch_array( MYSQLI_ASSOC );
		
		$Facility_Index = stripslashes($anArray['id']);
		$PrimaryOwner = stripslashes($anArray['primary_owner']);
if(DEBUG)
{
		echo "Primary Owner = $PrimaryOwner <br>";
		echo "Facility_Index = $Facility_Index<br>";
}
		$objGetResult->free_result();
	}
	else
	{
		$Facility_Index = 0;

	}

	return $Facility_Index;

}

################################################################################
#
#
#  DESCRIPTION : This function plays most critical part of
#                the many to many relationship between facilities
#                and owners, specifically rcfe table and login_table.
#
#                It establishes a relationship between them.  
#                The two columns of this table 
#                are composite primary keys.
#
#  INPUT:  $Facility_Index - Unique ID of the facility table.
#          $Owner_Index - Unique ID of the owner table.
#
#  OUTPUT: SUCCESS  -  a new row has successfully inserted.
#          FAILURE  -  such a row already exists.
#
#  WHO     WHEN      WHAT
#  ----    ------   -------
#          01-07-15  Added timestamp.
#
#
#
################################################################################


// Inserts a new row into the table `facility_owner` which coordinates many to many 
// relationship between rcfe and owner table.
function iInsertIntoFacility_Owner_table( $Facility_Index, $Owner_Index )
{
	global $mysqli;

	$mysqli->autocommit( FALSE );	
	$InsertCommand = "INSERT INTO facility_owner 
			  values ( $Facility_Index, $Owner_Index, now())";
	
	
	$add_post_res = $mysqli->query($InsertCommand); #or die($mysqli->error);

        if( !$mysqli->commit())
        {
                $mysqli->rollback();
        }


	
if(DEBUG)
{		
	echo $InsertCommand;
	var_dump( $add_post_res );
}
	if( $add_post_res )
	{
if(DEBUG)
{		
		echo "Success";
}
		$status = SUCCESS;
	}
	else
	{
if(DEBUG)
{		
		echo "No Success: $mysqli->error";
}
		$status = FAILURE;
	}
	return $status;
}

/******
function iGetOwnerID( $EmailAddress )
{
	global $mysqli;
	
	$QueryResultSet = "SELECT id
			   FROM login_table
			   WHERE email_address = '$EmailAddress'";
					   
	$objGetResult = $mysqli->query( $QueryResultSet );

if(DEBUG)
{
	var_dump($objGetResult);
}	
	if( $objGetResult ) #remember that even if there is no single row has returned, it will return an object which is non-zero.  
	{
		$anArray = $objGetResult->fetch_array( MYSQLI_ASSOC );
		
		$ID = stripslashes($anArray['id']);
		$objGetResult->free_result();
	}
	else
	{
		$ID = 0;
	}
	
	return $ID;
	
}

*****/

////   MAIN PROGRAM STRTS HERE   

	//$display_block = "";
	//if(( $_POST['firstname'] != "" ) && ( $_POST['lastname'] != "" ) &&( $_POST['email'] != "" ) &&( $_POST['city'] != "" ) &&( $_POST['zipcode'] != "" ) && ( $_POST['facility_number'] != "" ) && ( $_POST['owner_or_admin'] != "" ))
if( DEBUG )
{
	var_dump( $_SESSION );
	var_dump( $_POST );
}

	if( isset( $_SESSION['email'],  $_SESSION['id'] ) === true )
	{
		//if( isset( $_POST['address'],  $_POST['city'],  $_POST['zipcode'], $_POST['facility_number'] )  === true )
		if( ( $_POST['address'] != "" ) && ( $_POST['city'] != "" ) &&( $_POST['zipcode'] != "" ) && ( $_POST['facility_number'] != "" ) )
		{
			//$display_block = "";

			vMakeSafeVariables( $SafeAddress, $SafeCity, $SafeZipcode, $SafeFacilityNumber );
			
			//
			// If such a password exists given the input from the textfield, then proceed.
			//
			$Facility_Index = iCheckIfARowExistsFromRCFE( $SafeFacilityNumber, $SafeAddress, $SafeZipcode, $SafeCity, $PrimaryOwner );
			
			
			if( $Facility_Index )
			{
if(DEBUG)
{
				echo "Such a facility exists<br>";
}
				//$Owner_Index = iGetOwnerID($SafeEmail);
				$Owner_Index = $_SESSION['id'];
					
				//
				// Retrieve Owner unique index.  
				// Proceed only if there is a valid owner unique index.
				// Owner index is an auto-increment so it starts with 0.
				//
				if( $Owner_Index != 0 )
				{
					$iInserted = iInsertIntoFacility_Owner_table( $Facility_Index, $Owner_Index );
					if( $iInserted == SUCCESS )
					{
							
						//The first one who claims the facility becomes the primary owner.
						//If the primary owner doesn't exist, store the primary owner to the table.
						if( $PrimaryOwner == 0 )
						{
							vUpdatePrimaryOwnership( $SafeFacilityNumber, $Owner_Index );
							$CongratMsg = "Congratulations! You have successfully claimed this facility as the  primary owner.";
						}
						else
							$CongratMsg = "Congratulations! You have successfully claimed this facility as the  secondary owner.";

						popup( $CongratMsg,  OWNER_SEARCH_PAGE  );
					}
					else
					{
						popup( "You already have claimed this facility in the past.", OWNER_SEARCH_PAGE );

					}
			   	}
			}
			else
			{
				#echo "Such a facility doesn't exists<br>";
				popup( "Sorry, facility number does not match with the address.", OWNER_SEARCH_PAGE );
				#$display_block = "<h1>Such a facility doesn't exists.  Please try again.</h1><br>";
			}
			
		}
		#If there is a missing field, send a messssge along with a clean form.
		else
		{
			popup( "You must fill in every field", OWNER_SEARCH_PAGE);
		}
	}
	else
	{
		popup( "_SESSION values for id or email is missing", OWNER_SEARCH_PAGE);
		
	}
	// $mysqli->close();
?>

/**
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/facility.css">
<?php include( '../common/header.php' );?> 
</head>
<body>
	<div class="headcontainer">
		<?php include( '../common/owner_navigation.php' );?> 
	</div>
	<div class="CLAIM_A_FACILITY">
		<?php echo $display_block; ?>
	</div>
	<?php include ( '../common/owner_trailer' ); ?>
</body>
</html>  ***/
