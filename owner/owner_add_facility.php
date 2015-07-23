<?php
session_start();

//
//  DATE;  06-26-15
//  NOTE:  For the future enhancement, we don't want to lose the history of updates.
//         Whenever there is a update, create a trigger such that current  row
//         in accomodation_provided gets inserted into accomodation_provided_history.
//
//  NOTE:  Time stamp needs to be localized.  It needs more research.
//         
//         Same goes for other tables.
//
//
//         This will be a great history data.
//
//
//  DESCRIPTION : This script adds a new facility by a registered owner.
//
//  WHO         WHEN           WHAT
//              06-27-15       Created.
//              07-08-25       Added rollback() in case insert transaction doesn't go thru.
//
//
//
//
require_once '../common/dependency.php';

vConnectDB( 'baboom' );

global $mysqli;


// 
// DESCRIPTION: This function assigns the primary ownership of the facility.
// Borrowed code from owner_claim_facility.php
//
function vUpdatePrimaryOwnership( $SafeFacilityNumber, $Owner_Index )
{ 
	global $mysqli;

	$mysqli->autocommit(FALSE);
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

//
// Inserts a new row into the table `facility_owner` which coordinates many to many 
// relationship between rcfe and owner table.
//
// Borrowed code from owner_claim_facility.php
//
function iInsertIntoFacility_Owner_table( $Facility_Index, $Owner_Index )
{
	global $mysqli;
	
	$mysqli->autocommit( FALSE );

	$InsertCommand = "INSERT INTO facility_owner 
			  values ( $Facility_Index, $Owner_Index, now())";
	
	
	$add_post_res = $mysqli->query($InsertCommand);
	
	if( !$mysqli->commit())
	{
		$mysqli->rollback();
	}

	#or die($mysqli->error);
	
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



// Make sure all the inputs from the Genera tab  is not emptty.
// Make sure all the inputs are valid.
if( empty( $_GET['facility_name'] ) || empty( $_GET['facility_address'] ) || empty( $_GET['facility_city'] ) || empty( $_GET['facility_state'] ) || empty( $_GET['facility_zipcode'] ) || empty( $_GET['facility_phone'] ) || empty( $_GET['licensee_name'] ) || empty( $_GET['license_number'] ) || empty( $_GET['license_status'] ) || empty( $_GET['facility_type'] ) || empty( $_GET['facility_cap'] )  )
{
		$SafeFacilityName = mysqli_real_escape_string( $mysqli, $_GET['facility_name'] );
		$SafeFacilityAddress =   mysqli_real_escape_string( $mysqli, $_GET['facility_address'] );
		$SafeFacilityCity =  mysqli_real_escape_string( $mysqli, $_GET['facility_city'] );
		$SafeFacilityState = mysqli_real_escape_string( $mysqli, $_GET['facility_state'] );
		$SafeFacilityZipcode = mysqli_real_escape_string( $mysqli, $_GET['facility_zipcode'] );
		$SafeFacilityPhone = mysqli_real_escape_string( $mysqli, $_GET['facility_phone'] );
		$SafeLicenseName = mysqli_real_escape_string( $mysqli, $_GET['licensee_name'] );
		$SafeLicenseNumber = mysqli_real_escape_string( $mysqli, $_GET['license_number'] );
		$SafeLicenseStatus = mysqli_real_escape_string( $mysqli, $_GET['license_status'] );
		$SafeFacilityType = mysqli_real_escape_string( $mysqli, $_GET['facility_type'] );
		$SafeFacilityCap = mysqli_real_escape_string( $mysqli, $_GET['facility_cap'] );

if( DEBUG )
{
	echo "FacilityName =  $SafeFacilityName </br>";
	echo "Address =	$SafeFacilityAddress </br>";
	echo "City = $SafeFacilityCity </br>";
	echo "State =	$SafeFacilityState</br>";
	echo "Zipcode = $SafeFacilityZipcode</br>";
	echo "Phone = 	$SafeFacilityPhone</br>";
	echo "LicenseName = $SafeLicenseName</br>";
	echo "		$SafeLicenseNumber</br>";
	echo "	$SafeLicenseStatus</br>";
	echo "$SafeFacilityType</br>";
	echo "$SafeFacilityCap</br>";


}


	popup( "Please fill in all the inputs", HOME_PAGE );
}
else
{
	// Validate each text input.


	// Check if the facility number exists.
	$facility_number= $_GET['license_number'];

	// Check to see if this row already exists.
	$sCheckIfARowExists1 = "SELECT 1 AS row_exists
			       FROM rcfe
			       WHERE facility_number = $facility_number";
	

	echo $sCheckIfARowExists1;

	$objCheckIfARowExists1 = $mysqli->query( $sCheckIfARowExists1 ) or die( $mysqli->error );
	echo $sCheckIfARowExists1;
	//$objCheckIfARowExists1 = $mysqli->query( $sCheckIfARowExists1 ) or echo "{$mysqli->error}";
	
	if( $objCheckIfARowExists1 )
	{
		$anArray = $objCheckIfARowExists1->fetch_array( MYSQLI_ASSOC );
		$A_RowExists1 = stripslashes($anArray['row_exists']);			

		$objCheckIfARowExists1->free_result();
	}
	else
	{
		echo "query returned nothing";
	}
	
	echo "A = " . $A_RowExists1;
	//If such  row exists,  show an alert.
	if( $A_RowExists1 == 1 )
	{
		echo "B = " . $A_RowExists1;
		popup( "This facility number already exists.  Please use update to modify information. Thanks.", HOME_PAGE );
	}
	else
	{
		// Sanitize  all the inputs.
		$SafeFacilityNumber = mysqli_real_escape_string( $mysqli, $_GET['license_number'] );
		$SafeFacilityName = mysqli_real_escape_string( $mysqli, $_GET['facility_name'] );
		$SafeFacilityAddress =   mysqli_real_escape_string( $mysqli, $_GET['facility_address'] );
		$SafeFacilityCity =  mysqli_real_escape_string( $mysqli, $_GET['facility_city'] );
		$SafeFacilityState = mysqli_real_escape_string( $mysqli, $_GET['facility_state'] );
		$SafeFacilityZipcode = mysqli_real_escape_string( $mysqli, $_GET['facility_zipcode'] );
		$SafeFacilityPhone = mysqli_real_escape_string( $mysqli, $_GET['facility_phone'] );
		$SafeLicenseName = mysqli_real_escape_string( $mysqli, $_GET['licensee_name'] );
		$SafeLicenseNumber = mysqli_real_escape_string( $mysqli, $_GET['license_number'] );
		$SafeLicenseStatus = mysqli_real_escape_string( $mysqli, $_GET['license_status'] );
		$SafeFacilityType = mysqli_real_escape_string( $mysqli, $_GET['facility_type'] );
		$SafeFacilityCap = mysqli_real_escape_string( $mysqli, $_GET['facility_cap'] );



		//If such row does not exists, INSERT takes place.

		$mysqli->autocommit(FALSE);
		$sInsertQuery = "INSERT INTO rcfe
			        ( id, facility_type, facility_number, facility_name, license_status, licensee, telephone, address, city, state, zipcode, facility_cap )
				VALUES
			 	( NULL, '$SafeFacilityType', $SafeFacilityNumber, '$SafeFacilityName', '$SafeLicenseStatus', '$SafeLicenseName', '$SafeFacilityPhone', '$SafeFacilityAddress', '$SafeFacilityCity', '$SafeFacilityState', '$SafeFacilityZipcode', $SafeFacilityCap )";

if(DEBUG)
{
		echo $sInsertQuery;
}
		$AddAFacility = $mysqli->query($sInsertQuery);  # or die($mysqli->error);

		if( !$mysqli->commit())
		{
			$mysqli->rollback();
		}



		//Update facilicty_owner table.
		$Facility_Index = iCheckIfARowExistsFromRCFE( $SafeFacilityNumber, $SafeFacilityAddress, $SafeFacilityZipcode, $SafeFacilityCity, $PrimaryOwner );


		if( $Facility_Index )
		{
if(DEBUG)
{
			echo "Such a facility exists<br>";
}
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

						// popup( $CongratMsg,  OWNER_SEARCH_PAGE  );
				}
				else
				{
					popup( "You already have claimed this facility in the past.", OWNER_SEARCH_PAGE );

				}
		   	}
		}
		else
		{
			popup( "Sorry, facility number does not match with the address.", OWNER_SEARCH_PAGE );
		}


		//Continue with the rest of the insert.
		vInsertAccomodations( $SafeFacilityNumber );

		vInsertServices( $SafeFacilityNumber );

		vInsertPrice_Discount_Availability($SafeFacilityNumber );

		$CongratMsg = "Congratulations! You have successfully claimed this facility as an owner.";

		popup( $CongratMsg,  OWNER_SEARCH_PAGE  );

	}
}

////////////////////////////////////////////////////////////////////////////////////////
//
//       INSERT  ACCOMODATION  PROVIDED                 
//
////////////////////////////////////////////////////////////////////////////////////////
function vInsertAccomodations( $facility_number )
{
	global $mysqli;
	if( isset( $_GET['accomodation'] ) )
	{
		//
		//$Accomodations receives an array of  that looks like ([0]=>1 [1]=>2 [2]=>3 ).
		//The above array means that check box with values #1, #2 and #3 have been checked.
		//
		$Accomodations = $_GET['accomodation'];

if( DEBUG )
{
		var_dump( $Accomodations );
		print_r( $Accomodations );
}

		// Initialize each item to 0.
		$A1 = "0";
		$A2 = "0";
		$A3 = "0";
		$A4 = "0";
		$A5 = "0";
		$A6 = "0";
		$A7 = "0";
		$A8 = "0";
		$A9 = "0";

		// Reassign $A? variables to '1'.
		foreach ( $Accomodations as $accomo )
		{
			${ "A" . $accomo } = '1';
			echo $accomo . "</br>";
			echo ${ "A" . $accomo } . "</br>";
		}

		// Check to see if this row already exists.
		$sCheckIfARowExists1 = "SELECT 1 AS row_exists
				       FROM accomodations_provided
				       WHERE facility_number = $facility_number";

		$objCheckIfARowExists1 = $mysqli->query( $sCheckIfARowExists1 ) or die( $mysqli->erro );
		
		if( $objCheckIfARowExists1 )
		{
			$anArray = $objCheckIfARowExists1->fetch_array( MYSQLI_ASSOC );
			$A_RowExists1 = stripslashes($anArray['row_exists']);			

			$objCheckIfARowExists1->free_result();
		}
		

		//If such  row exists,  UPDATE takes place.
		if( $A_RowExists1 == 1 )
		{
			popup( "This facility number already exists.  Please use 'Update' menu item to update your facility.  Thanks.", HOME_PAGE );
		}
		//If such row does not exists, INSERT takes place.
		else
		{
			$mysqli->autocommit(FALSE);

			//$CurrTime = date( 'Y-m-d H-i-s');
			$sInsertFacility = "INSERT INTO  accomodations_provided 
					    VALUES
			    	    ( $facility_number, $A1, $A2, $A3, $A4, $A5, $A6, $A7, $A8, $A9, now() )";
if(DEBUG)
{
			echo $sInsertFacility . "</br>";
}
			$mysqli->query($sInsertFacility); #or die($mysqli->error);
			if( !$mysqli->commit())
			{
				$mysqli->rollback();
			}
		}
	}
	else
	{
		echo "You did not choose an accomodation.";
	}
}
////////////////////////////////////////////////////////////////////////////////////////
//
//       UPDATE SERVICE  PROVIDED                 
//
////////////////////////////////////////////////////////////////////////////////////////
function vInsertServices( $facility_number )
{
	global $mysqli;
	if( isset( $_GET['service'] ) )
	{
		//
		//$Accomodations receives an array of  that looks like ([0]=>1 [1]=>2 [2]=>3 ).
		//The above array means that check box with values #1, #2 and #3 have been checked.
		//
		$Services = $_GET['service'];

	if( DEBUG )
	{
		var_dump( $Services );
		print_r( $Services );
	}

		// Initialize each item to 0.
		$S1 = "0";
		$S2 = "0";
		$S3 = "0";
		$S4 = "0";
		$S5 = "0";
		$S6 = "0";
		$S7 = "0";
		$S8 = "0";
		$S9 = "0";
		$S10 = "0";
		$S11 = "0";
		$S12 = "0";
		$S13 = "0";
		$S14 = "0";
		$S15 = "0";
		$S16 = "0";
		$S17 = "0";

		// Reassign $A? variables to '1'.
		foreach ( $Services as $service )
		{
			${ "S" . $service } = '1';
			echo $service . "</br>";
			echo ${ "S" . $service } . "</br>";
		}

		// Check to see if this row already exists.
		$sCheckIfARowExists2 = "SELECT 1 AS row_exists
				       FROM service_provided
				       WHERE facility_number = $facility_number";

		$objCheckIfARowExists2 = $mysqli->query( $sCheckIfARowExists2 ) or die( $mysqli->error );
		
		if( $objCheckIfARowExists2 )
		{
			$anArray = $objCheckIfARowExists2->fetch_array( MYSQLI_ASSOC );
			$A_RowExists2 = stripslashes($anArray['row_exists']);			

			$objCheckIfARowExists2->free_result();
		}
		

		//
		//If such  row exists,  UPDATE takes place...
		//
		if( $A_RowExists2 == 1 )
		{
			//$CurrTime = date( 'Y-m-d H-i-s');
			popup( "This facility number already exists.  Please use 'Update' menu item to update your facility.  Thanks.", HOME_PAGE );

		}
		//
		//If such row does not exists, INSERT takes place.
		//
		else
		{
			$mysqli->autocommit(FALSE);
			$sInsertFacilityService = "INSERT INTO  service_provided 
					    VALUES
					    ( $facility_number, now(), $S1, $S2, $S3, $S4, $S5, $S6, $S7, $S8, $S9, $S10, $S11, $S12, $S13, $S14, $S15, $S16, $S17 )";

			echo $sInsertFacilityService . "</br>";

			$mysqli->query($sInsertFacilityService) or die($mysqli->error);
			if( !$mysqli->commit())
			{
				$mysqli->rollback();
			}
		}
	}
	else
	{
		echo "You did not choose an service.";
	}
}

////////////////////////////////////////////////////////////////////////////////////////
//
//       UPDATE PRICE  PROVIDED                 
//
////////////////////////////////////////////////////////////////////////////////////////
function vInsertPrice_Discount_Availability( $facility_number )
{
	global $mysqli;
	// Initialize all variables.
	$SharedMin = 0;
	$SharedMax = 0; 
	$SemiMin = 0; 
	$SemiMax = 0; 
	$PrivateMin = 0; 
	$PrivateMax = 0; 
	$DiscountValue = 0;

	$avail_private = 0; 
	$avail_semi = 0; 
	$avail_shared = 0; 

	$avail_private = $_GET['avail_private'];
	$avail_semi = $_GET['avail_semi'];
	$avail_shared = $_GET['avail_shared'];

	echo $SharedMin . "</br>";
	echo $SharedMax . "</br>";
	echo $SemiMin . "</br>";
	echo $SemiMax . "</br>";
	echo $PrivateMin . "</br>";
	echo $PrivateMax . "</br>";
	echo "DiscountValue = $DiscountValue";

	echo $avail_private . "</br>";
	echo $avail_semi . "</br>";
	echo $avail_shared . "</br>";


	echo "---------------------------------------</br>";
	if( isset( $_GET['shared_min'] ) )
	{
		$SharedMin = $_GET['shared_min'];
		echo $SharedMin . "</br>";
	}
	if( isset( $_GET['shared_max'] ) )
	{
		$SharedMax = $_GET['shared_max'];
		echo $SharedMax . "</br>";
	}
	if( isset( $_GET['semi_min'] ) )
	{
		$SemiMin = $_GET['semi_min'];
		echo $SemiMin . "</br>";
	}
	if( isset( $_GET['semi_max'] ) )
	{
		$SemiMax = $_GET['semi_max'];
		echo $SemiMax . "</br>";
	}
	if( isset( $_GET['private_min'] ) )
	{
		$PrivateMin = $_GET['private_min'];
		echo $PrivateMin . "</br>";
	}
	if( isset( $_GET['private_max'] ) )
	{
		$PrivateMax = $_GET['private_max'];
		echo $PrivateMax . "</br>";
	}

	if( isset( $_GET['discount'] ) )
	{
		$DiscountValue = $_GET['discount'];
		echo "DiscountValue = $DiscountValue";
	}
	else
	{
		echo "You did not choose discount amount <br/>";
	}


	echo "</br>";
	if( isset( $_GET['avail_shared'] ) )
	{
		$AvailShared = $_GET['avail_shared'];
		echo $AvailShared . "</br>";
	}
	if( isset( $_GET['avail_semi'] ) )
	{
		$AvailSemi = $_GET['avail_semi'];
		echo $AvailSemi . "</br>";
	}
	if( isset( $_GET['avail_private'] ) )
	{
		$AvailPrivate = $_GET['avail_private'];
		echo $AvailPrivate . "</br>";
	}
		// Check to see if this row already exists.
		$sCheckIfARowExists3 = "SELECT 1 AS row_exists
				       FROM facility_price
				       WHERE facility_number = $facility_number";

		$objCheckIfARowExists3 = $mysqli->query( $sCheckIfARowExists3 ) or die( $mysqli->error );
		
		if( $objCheckIfARowExists3 )
		{
			$anArray = $objCheckIfARowExists3->fetch_array( MYSQLI_ASSOC );
			$A_RowExists3 = stripslashes($anArray['row_exists']);			

			$objCheckIfARowExists3->free_result();
		}
		

		//
		//If such  row exists,  UPDATE takes place...
		//
		if( $A_RowExists3 == 1 )
		{
			//$CurrTime = date( 'Y-m-d H-i-s');

			popup( "This facility number already exists.  Please use 'Update' menu item to update your facility.  Thanks.", HOME_PAGE );
		}
		//
		//If such row does not exists, INSERT takes place.
		//
		else
		{
			$mysqli->autocommit(FALSE);
			$sUpdateFacilityPrice_Special_Available = "INSERT INTO  facility_price 
					    			   VALUES
			    	    				   ( $facility_number, now(), $SharedMin, $SharedMax, $SemiMin, $SemiMax, $PrivateMin, $PrivateMax, $DiscountValue, $avail_shared, $avail_semi, $avail_private )";

if( DEBUG )
{
			echo $sUpdateFacilityPrice_Special_Available . "</br>";
}
			$mysqli->query($sUpdateFacilityPrice_Special_Available);# or die($mysqli->error);
			if( !$mysqli->commit())
			{
				$mysqli->rollback();
			}
		}
}
?>
