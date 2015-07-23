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
//         This will be a great historcal data.
//
//  DESCRIPTION:  THis script updates a data of a facility.
//
//  WHO      WHEN         WHAT
//                        Created
//
//
//
//
//
//
//
require_once '../common/dependency.php';

vConnectDB( 'baboom' );

global $mysqli;

////////////////////////////////////////////////////////////////////////////////////////
//
//       GENERAL INFORMATION  PROVIDED                 
//
////////////////////////////////////////////////////////////////////////////////////////
$facility_number= $_GET['license_number'];


////////////////////////////////////////////////////////////////////////////////////////
//
//       UPDATE ACCOMODATION  PROVIDED                 
//
////////////////////////////////////////////////////////////////////////////////////////
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

	$objCheckIfARowExists1 = $mysqli->query( $sCheckIfARowExists1 );# or die( $mysqli->erro );
	
	if( $objCheckIfARowExists1 )
	{
		$anArray = $objCheckIfARowExists1->fetch_array( MYSQLI_ASSOC );
		$A_RowExists1 = stripslashes($anArray['row_exists']);			

		$objCheckIfARowExists1->free_result();
	}
	

	//If such  row exists,  UPDATE takes place.
	if( $A_RowExists1 == 1 )
	{
		//Below line caused warning that I need to set the local time zone ...
		//$CurrTime = date( 'Y-m-d H-i-s');

		$mysqli->autocommit( FALSE );

		$sUpdateFacilityAccomodations = "UPDATE `accomodations_provided`
			    	    SET `A1`= '$A1',
			    	        `A2`= '$A2',
			    	        `A3`= '$A3',
			    	        `A4`= '$A4',
			    	        `A5`= '$A5',
			    	        `A6`= '$A6',
			    	        `A7`= '$A7',
			    	        `A8`= '$A8',
			    	        `A9`= '$A9',
					`updated_time` = now() 
			       	    WHERE facility_number = $facility_number";
				    
if( DEBUG )
{
		echo $sUpdateFacilityAccomodations . "</br>";
}
		$mysqli->query($sUpdateFacilityAccomodations);# or die($mysqli->error);


	        if( !$mysqli->commit())
        	{
                	$mysqli->rollback();
        	}


	}
	//If such row does not exists, INSERT takes place.
	else
	{
		$CurrTime = date( 'Y-m-d H-i-s');

		$mysqli->autocommit( FALSE );	

		$sInsertFacility = "INSERT INTO  accomodations_provided 
			    	    VALUES
			    	    ( $facility_number, now(), $A1, $A2, $A3, $A4, $A5, $A6, $A7, $A8, $A9 )";
if( DEBUG )
{
		echo $sInsertFacility . "</br>";
}
		$mysqli->query($sInsertFacility);# or die($mysqli->error);
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
////////////////////////////////////////////////////////////////////////////////////////
//
//       UPDATE SERVICE  PROVIDED                 
//
////////////////////////////////////////////////////////////////////////////////////////

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

	$objCheckIfARowExists2 = $mysqli->query( $sCheckIfARowExists2 ); # or die( $mysqli->error );
	
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
		$mysqli->autocommit( FALSE );
		$sUpdateFacilityService = "UPDATE `service_provided`
			    	    SET `S1`= '$S1',
			    	        `S2`= '$S2',
			    	        `S3`= '$S3',
			    	        `S4`= '$S4',
			    	        `S5`= '$S5',
			    	        `S6`= '$S6',
			    	        `S7`= '$S7',
			    	        `S8`= '$S8',
			    	        `S9`= '$S9',
			    	        `S10`= '$S10',
					`updated_time` = now() 
				    WHERE facility_number = $facility_number"; 
if( DEBUG )
{
		echo $sUpdateFacilityService . "</br>";
}
		$mysqli->query($sUpdateFacilityService);# or die($mysqli->error);
		if( !$mysqli->commit())
        	{
                	$mysqli->rollback();
        	}

	}
	//
	//If such row does not exists, INSERT takes place.
	//
	else
	{
		$mysqli->autocommit( FALSE );

		$sInsertFacilityService = "INSERT INTO  service_provided 
			    	    VALUES
			    	    ( $facility_number, now(), $S1, $S2, $S3, $S4, $S5, $S6, $S7, $S8, $S9, $S10, $S11, $S12, $S13, $S14, $S15, $S16, $S17 )";

if( DEBUG )
{		
	echo $sInsertFacilityService . "</br>";
}
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

////////////////////////////////////////////////////////////////////////////////////////
//
//       UPDATE PRICE  PROVIDED                 
//
////////////////////////////////////////////////////////////////////////////////////////

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

	$objCheckIfARowExists3 = $mysqli->query( $sCheckIfARowExists3 ); # or die( $mysqli->error );
	
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

		$mysqli->autocommit( FALSE );

		$sUpdateFacilityPrice_Special_Available = "UPDATE `facility_price`
			    	    SET `shared_min_price`= '$SharedMin',
			    	        `shared_max_price`= '$SharedMax',
			    	        `semi_private_min_price`= '$SemiMin',
			    	        `semi_private_max_price`= '$SemiMax',
			    	        `private_min_price`= '$PrivateMin',
			    	        `private_max_price`= '$PrivateMax',
			    	        `baboom_special`= '$DiscountValue',
			    	        `shared_available`= '$avail_shared',
			    	        `semi_priv_available`= '$avail_semi',
			    	        `priv_available`= '$avail_private',
					`updated_time` = now() 
				    WHERE facility_number = $facility_number"; 
if( DEBUG )
{
		echo $sUpdateFacilityPrice_Special_Available . "</br>";
}
		$mysqli->query($sUpdateFacilityPrice_Special_Available); # or die($mysqli->error);
	        if( !$mysqli->commit())
        	{
                	$mysqli->rollback();
        	}
	}
	//
	//If such row does not exists, INSERT takes place.
	//
	else
	{
		$mysqli->autocommit( FALSE );
		$sUpdateFacilityPrice_Special_Available = "INSERT INTO  facility_price 
			    	    VALUES
			    	    ( $facility_number, now(), $SharedMin, $SharedMax, $SemiMin, $SemiMax, $PrivateMin, $PrivateMax, $DiscountValue, $avail_shared, $avail_semi, $avail_private )";

if( DEBUG )
{
		echo $sUpdateFacilityPrice_Special_Available . "</br>";
}
		$mysqli->query($sUpdateFacilityPrice_Special_Available); # or die($mysqli->error);
	        if( !$mysqli->commit())
        	{
                	$mysqli->rollback();
        	}


	}

?>
