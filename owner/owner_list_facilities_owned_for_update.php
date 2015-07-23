<?php
session_start();
###############################################################################3
#
# FILENAME:  owner_list_facilities_owned_for_update.php
#
# DESCRIPTION: This  script is called from the menu itme labeled 
#              "Update/Manage your facility".
# 
#              A facility owner logs in with username and password.
#              This will display all the information pertinent to the
#              facilties such as available beds, address ....
#
#              If th facility ownere wants to update any information,
#              the user can update the info and press a submit button.
#
#              Once it is submitted, it calls UpdateFacilityInfo.php to
#              update the table.
#
# DATABASE used: login_table
#
# WHO        WHEN          WHAT
#            Dec 10, 2014  This prototype code allows a facility owner to 
#                          update the facility info such as facility name, 
#                           available beds.
# 	     Jan 6, 2015   The change was made to handle many to many DB 
#                          design.  
#            Jun 20,2015   Migrated to AWS.
#
#
#
###############################################################################3

require_once '../common/dependency.php';

vConnectDB( "baboom" );

//
// DESCRIPTION :  Display all the facility numbers and its corresponding owners ( primary, secondary, administrators )
//                owned by the current owner who has logged in .
//
// WHO         WHEN          WHAT
//             06-22-15      Displayes  basic facility info.
//
//
function vDisplayALLOwnership( $arrayFacilitiesNumbers_PO_Hashed_Array )
{
	global $mysqli;
	global $display_block;
	
	//$arrayFacilityList = implode(',', $arrayFacilitiesNumbers);

	$display_block = "";
	
	
	foreach( $arrayFacilitiesNumbers_PO_Hashed_Array as $FacilityNumber => $Primary_Owner )
	{
		$display_block .= "<br/>";
		$display_block .= "<br/>";
		$display_block .= "<br/>";
		$display_block .= "<li>$FacilityNumber</li>";
		#echo "$FacilityNumber  ==>  $Primary_Owner<br>";
		
		$FacilityNumber = trim( $FacilityNumber );

		$sGetAllFacilityInfo = "SELECT facility_name, address, city, zipcode, state
					   FROM rcfe 
					   WHERE facility_number = '$FacilityNumber'";

		$objGetAllFacilityInfo = $mysqli->query( $sGetAllFacilityInfo );
		if( $objGetAllFacilityInfo )
		{
			$AFacility = $objGetAllFacilityInfo->fetch_array( MYSQLI_ASSOC );

			$FacilityName = stripslashes($AFacility['facility_name']);
			$Address = stripslashes($AFacility['address']);
			$City = stripslashes($AFacility['city']);
			$Zipcode = stripslashes($AFacility['zipcode']);
			$State = stripslashes($AFacility['state']);

			$display_block .= "<li>$FacilityName</li>";
			$display_block .= "<li>$Address</li>";
			$display_block .= "<li>$City, $Zipcode $State</li>";
			$objGetAllFacilityInfo->free_result();
		}

		// 
		// Get all the owners of a particular facility.
		// There is one primary owner but there can be multiple secondary owners and 
		// multiple administrators.
		//

		$sGetAllOwnership = "select login_table.user_id as user_id, login_table.type as type, login_table.id as id
				     from login_table  
					JOIN facility_owner ON facility_owner.owner_id = login_table.id 
					JOIN rcfe ON rcfe.id = facility_owner.facility_id 
				     where rcfe.facility_number = $FacilityNumber";					   

		#echo "$sGetAllOwnership<br>";

		$objGetAllOwnership = $mysqli->query( $sGetAllOwnership );
		if( $objGetAllOwnership )
		{
			while( $AnOwnerArray = $objGetAllOwnership->fetch_array( MYSQLI_ASSOC ) )
			{
				$UserID = stripslashes($AnOwnerArray['user_id']);
				$Type = stripslashes($AnOwnerArray['type']);
				$Owner_Admin_Index = stripslashes($AnOwnerArray['id']);
				
				#echo "Owner_Admin_Index : $Owner_Admin_Index <br>";
				if( $Type == 'O' )
				{
					if( $Primary_Owner == $Owner_Admin_Index )
						$Position = "( Primary Owner)";
					else
						$Position = "( Secondary Owner)";
				}
				else	
					$Position = "(Administrator)";
				$display_block .= "<li>$UserID   $Position</li>";
			}
			$objGetAllOwnership->free_result();
		}
		else
		{
			#echo "No owner";
		}

		//$display_block .= "<a href=owner_update_facility_front_end.php?facility_name=$urlcode_facility_name&address=$urlcode_address&facility_num=$urlcode_facility&city=$urlcode_city&state=$urlcode_state&zipcode=$urlcode_zipcode>Claim This Facility</a></ul>";
		$urlcode_facility_number = urlencode( $FacilityNumber );
		$display_block .= "<a href=owner_update_facility_front_end.php?facility_number=$urlcode_facility_number>&nbsp&nbsp&nbsp Update this Facility</a></ul>";
	}
}

	
	/////////////////////////////////////////////////////////////////////////////////
	//
	//
	//     B  E  G  I   N
	//
	//
	/////////////////////////////////////////////////////////////////////////////////

if( DEBUG )
{
	var_dump($_POST);
}
	if( isset( $_SESSION['id'] ) === true )
	{
		$IndexID = $_SESSION['id'];

		//
		// List all the facilities that are owned by an owner who is currently logged in.
		// Only the priary owner is alloowed to update the information about the
		// fadility.
		//
		$sGetAllFacilitiesOwned = "SELECT rcfe.facility_number AS facility_num, rcfe.primary_owner AS primary_owner
					   FROM rcfe 
							JOIN facility_owner ON facility_owner.facility_id = rcfe.id
							JOIN login_table ON login_table.id = facility_owner.owner_id 
					   WHERE login_table.id = '$IndexID'";
							   
		#echo $sGetAllFacilitiesOwned;
		
		$objGetAllFacilitiesOwned = $mysqli->query( $sGetAllFacilitiesOwned );
		if( $objGetAllFacilitiesOwned )
		{
			//
			// Initializes hash array.
			// The hash is the facility number.
			// The value is the priamry owner's ID.
			// 
			$arrayFacilities_primary_owner_HA = array();

			while( $AUserIDArray = $objGetAllFacilitiesOwned->fetch_array( MYSQLI_ASSOC ) )
			{
				$AFacilityNum = stripslashes($AUserIDArray['facility_num']);
				$APriamryNum = stripslashes($AUserIDArray['primary_owner']);
				#array_push( $arrayFacilities_owned, $AFacilityNum );
				$arrayFacilities_primary_owner_HA[(string)$AFacilityNum] = (string)$APriamryNum;
			}
			vDisplayALLOwnership( $arrayFacilities_primary_owner_HA );
			$objGetAllFacilitiesOwned->free_result();
		}

		//$mysqli->close();
	}
	else
	{
		popup( "_SESSION values for id is missing", OWNER_SEARCH_PAGE);
	}
?>


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
</html>
