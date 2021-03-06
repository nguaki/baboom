<?php
############################################
#
#  FILE NAME:owner_facility_search.php
#
#  DESCRIPTION: This is the search page for owners.   
#               First, gets the appropriate SQL statement.
#               Second, executes the SQL and stores the data to $display_blcok variable
#                       in a long concateation.
#               Third, this variable gets displayed in the HTML doc in the bottom.
#                
# 
# WHO      WHEN      WHAT
# ----     --------  -----------
# James    12-31-14  Inserted ConnectDB.php into dependency.php
#          01-21-15  Moved to linux from windows env.  Modified require_once directory path.
#          01-23-15  Added zipcode as a part of href.
#          06-20-15  Added comments.
#
############################################

require_once '../common/dependency.php';
vConnectDB( "baboom" );

header( 'Cache-Control: no cache' );
session_cache_limiter( 'private_no_expire' );
session_start();

################
#prevents SQL injection
#Insert a backslash or an escape character in front of characters that can
#pose SQL injection.  A character like '.
################
$SafeCity = mysqli_real_escape_string( $mysqli, $_POST['cities']);
$SafeZipCode = mysqli_real_escape_string( $mysqli, $_POST['zipcode']);

$Case = 0;

// Get appropriate SQL statement.
$Get_Detailed_info = sFindSearchCriteria1( $SafeCity, $SafeZipCode, $Case );
		
if(DEBUG)
{
	var_dump( $_POST );
	echo $_POST['cities'];
	echo $_POST['zipcode'];
	echo "Running this query <br>";
	echo "$Get_Detailed_info <br>";
}

if( $res = $mysqli->query( $Get_Detailed_info ) )
{
	// Get the right title.
	if( $Case == 1 )
	{
		$display_block = "<h1>Showing Record for " .$SafeCounty. "</h1>";
	}
	else if( $Case == 2 )
	{
		$display_block = "<h1>Showing Record for " .$SafeCity. "</h1>";
	}
	else if( $Case == 3 )
	{
		$display_block = "<h1>Showing Record for " .$SafeZipCode. "</h1>";
	}
	else if( $Case == 4 )
	{
		$display_block = "<h1>Showing Record for " .$SafeZipCode. " and " .$SafeCity. "</h1>";
	}
			
	while( $newArray= $res->fetch_array( MYSQLI_ASSOC ) )
	{
		//All data is stored into this variable in a concatenation.
		$display_block .= "<p><strong>Address:</strong><br/><ul>";
		
		$facility_type = stripslashes($newArray['facility_type']);
		$facility_number = stripslashes($newArray['facility_number']);
		$facility_name = stripslashes($newArray['facility_name']);
		$licensee = stripslashes($newArray['licensee']);
		$administrator = stripslashes($newArray['administrator']);
		$telephone = stripslashes($newArray['telephone']);
		$address = stripslashes($newArray['address']);
		$city = stripslashes($newArray['city']);
		$state = stripslashes($newArray['state']);
		$zipcode = stripslashes($newArray['zipcode']);
		$county = stripslashes($newArray['county']);
		$facility_cap = stripslashes($newArray['facility_cap']);
				
				
		$display_block .= "<li>$facility_name $facility_number $licensee</li>";
		$display_block .= "<li>$facility_type</li>";
		$display_block .= "<li>$administrator $telephone $address $city</li>";
		$display_block .= "<li>$state $zipcode $county $facility_cap</li>";
		
		// Need to preserve + & and other HTML special characters.
		$urlcode_facility = urlencode($facility_number);
		$urlcode_state = urlencode($state);
if(DEBUG)
{				
		echo $urlcode_facility;
		echo $urlcode_state;
}		
		$urlcode_facility_name = urlencode($facility_name);
		$urlcode_address = urlencode($address);
		$urlcode_city = urlencode($city);
		$urlcode_zipcode  = urlencode($zipcode);

		$display_block .= "<a href=owner_claim_facility_front_end.php?facility_name=$urlcode_facility_name&address=$urlcode_address&facility_num=$urlcode_facility&city=$urlcode_city&state=$urlcode_state&zipcode=$urlcode_zipcode>Claim This Facility</a></ul>";
	}
	$res->free_result();
	//$mysqli->close();
}	
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>4BABOOM.COM - Client Facility Search</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/client.css">
	<?php include( '../common/header.php' );?> <!--worked -->
</head>
<body>
	<div class="headercontainer">
		<?php include( '../common/owner_navigation.php' );?> 
	</div>
	<br><br>;  <!-- need it for first lines that gets cut off by the navigation bar. -->
	<?php echo $display_block; ?>
	<?php include ( '../common/owner_trailer' ); ?>
</body>
</html>
