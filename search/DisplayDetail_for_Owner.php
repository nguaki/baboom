<?php
#######################################################
#  FILE : DisplayDetail.php
#
#  DESCRIPTION: This script loads up images of the facility
#               and anything pertains to the facility.
#
#               The images are stored at Apache img directory.
#               The images will be separated by the state.
#               
#               The directory will look like this:
#                        img\CA\
#                        img\WA\
#                          ...
#                        img\TX\
#
#
#               The image file names will be something like
#                          CA_xxxxxxxxxx_y.file_format
#
#                              xxxxxxxxxx is the facility number.
#                              y is the index of the image.
#                              Each facility can have up to 9 pictures.
#                              file format is jpg, png, gif, ...
#
#               This script is called from the hyperlink from the list generated
#               from Find_facility_main.php.
#
#               The hyperlink will pass in 2 parameters: state and the facility number.
#
#   WHO        WHEN         WHAT
#   ------     ---------    ----------
#   James      12-31-14     Created.
#              01-01-15     removed dependency.php from local directory.
#              01-08-15     Identifies primary vs. secondary owner.
#              01-22-15     If not logged in, cannot see the detailed info.
#######################################################
#require_once 'C:\\xampp\\htdocs\\xampp\\my_exercises\\senior_site_project\\common\\dependency.php';
require_once '../common/dependency.php';

vConnectDB( "baboom" );
session_start();


$facility_number = urldecode($_GET['facility_num']);
$state = urldecode($_GET['state']);
$facility_name = urldecode($_GET['facility_name']);
$facility_address = urldecode($_GET['address']);
$city = urldecode($_GET['city']);
$zipcode = urldecode($_GET['zipcode']);

if (DEBUG)
{	
	echo "hello from DisplayDetail.php";
	echo $_GET['facility_num'];
	echo $_GET['state'];
	echo "Hello from DispDetailInfo()<br>";
	var_dump($_GET);
	echo "facility = $facility_number<br>";
	echo "state = $state<br)>";
	echo "<h1>" . var_dump( $_SESSION ) . "</h1>";
	exit;
}


#####
# This will create a path like C:\xampp\htdocs\img\CA\.
# This will also create file name like CA_1233445_1.*.
# This will take care of all image formats.
#####
$fullpath = ABSOLUTE_IMAGES_PATH . "$state/";  
$full_filename = $state . "_" . $facility_number . "*";

$display_block= "";

if (DEBUG)
{
	echo "<br>";
	echo $fullpath;
	echo "$full_filename<br>";
}

#####
# Check if such file exists. Example: C:\xampp\htdocs\img\CA\CA_1234567_1.*.
#####
$filenames = glob( $fullpath . $full_filename );
shuffle( $filenames );

#Proceed only if files exists in a directory.
if( count($filenames) > 0 )
{
	/*Need to input a logic when there is more than 8 images, 
	  we only have a room for 8images. */
	if( count( $filenames ) >= 7 )
	{
	
	}
	
	#Go thru each file.
	foreach( $filenames as $name )
	{
		 # This will get just the file name without the path.
		 # Example: CA_1234567_1.jpg.
		 #$stack = explode( "\\", $name  );
		 $stack = explode( "/", $name  );
		 
		 #The last element will be the filename.
		 $JustFileName = array_pop( $stack );

if (DEBUG)
{	 
	echo "image file name =$JustFileName<br>";	 
}
		#Displays the image.
		#@echo '<img src="' . APACHE_IMAGES_DIRECTORY . "$state/$JustFileName" . '"/>';
		/* Need to insert the logic to center the images. */
		#$display_block .=  "<img src=\"" . APACHE_IMAGES_DIRECTORY . "$state/$JustFileName\"" . " width=200 height=200 />";
		#$display_block .=  "<img src=\"" . ABSOLUTE_IMAGES_PATH . "$state/$JustFileName\"" . " width=200 height=200 />";
		$display_block .=  "<img src=\"../img/$state/$JustFileName\"" . " width=200 height=200 />";
	}
}

# This is the query to get the management staff for this particular facility.  
# Management staff refers to primary, secondary and administrator for this facility.
$sGetAllOwnership = "select login_table.user_id as user_id, login_table.type as type, login_table.id as id, rcfe.primary_owner
					 from login_table  
						JOIN facility_owner ON facility_owner.owner_id = login_table.id 
						JOIN rcfe ON rcfe.id = facility_owner.facility_id 
					 where rcfe.facility_number = $facility_number";					   
#echo $sGetAllOwnership;
$objGetAllOwnership = $mysqli->query( $sGetAllOwnership );
#echo "<h1>Hello </h1>";
#var_dump( $objGetAllOwnership );

if( $objGetAllOwnership )
{
	$OwnersEmailArray = array();
	$Owner_Adm_Block = "";
	
	while( $AnOwnerArray = $objGetAllOwnership->fetch_array( MYSQLI_ASSOC ) )
	{
		$UserID = stripslashes($AnOwnerArray['user_id']);
		$Type = stripslashes($AnOwnerArray['type']);
		$Owner_Admin_Index = stripslashes($AnOwnerArray['id']);
		$PrimaryOwner = stripslashes($AnOwnerArray['primary_owner']);
		
		#echo "Owner_Admin_Index : $Owner_Admin_Index <br>";
		if( $Type == 'O' )
		{
			$Position = "(Owner)";
			if( $PrimaryOwner == $Owner_Admin_Index )
				$Position = "(Primary Owner)";
			else
				/* $Position = "( Secondary Owner)";*/
				 $Position = "(Owner)";
		}
		else	
			$Position = "(Adm)";
		#echo "<h2>$UserID   $Position</h2>";
		$Owner_Adm_Block .= "<h4>$UserID   $Position</h4>";
		array_push( $OwnersEmailArray, $UserID );
	}
	$objGetAllOwnership->free_result();
}
else
{
	echo "<h1>No owner</h1>";
}

/*******
# Get the elders that a client has referred so far.
$ClientIndex = $_SESSION['id'];

$sGetAllReferralElders = "SELECT client_login_table.email_address as CLIENT_NAME, 
								 needy.id AS id,
								 needy.first_name AS FIRST_NAME,
								 needy.last_name AS LAST_NAME,
								 needy.special_needs AS SPECIAL_NEED
						  FROM needy 
									JOIN client_needy ON client_needy.needy_id = needy.id 
									JOIN client_login_table ON client_login_table.id = client_needy.client_id 
						  WHERE client_login_table.id = $ClientIndex";
												   
#echo $sGetAllReferralElders;
	
$objGetAllReferralElders = $mysqli->query( $sGetAllReferralElders );
#$display_block = "";

*****/
$Claim_block = "";
/*
if( $objGetAllReferralElders )
{
	
*/
	/*	<p><label for=\"sel_id\">Position</label>	
		<select id=\"sel_id\" name=\"owner_or_admin\" onchange=\"showUser(this.value)\" />
		<option value=\"\">-- Select One --</option>
		<option name=\"owner_or_admin\" value=\"O\">Owner</option>
		<option name=\"owner_or_admin\" value=\"A\">Administrator</option>"; */
/****
	#echo "<br>";
	#var_dump( $OwnersEmailArray );
	$Emails = "";
	#echo "SIZE of array = " . sizeof($OwnersEmailArray);
	for( $i = 0; $i<sizeof($OwnersEmailArray); $i++)
	{
		$Emails .= $OwnersEmailArray[$i] . ",";
	}
	#echo "EMAILS = $Emails<br>";
	$owner_block = "$Emails<br>";

	while( $ElderFullNameArray = $objGetAllReferralElders->fetch_array( MYSQLI_ASSOC ) )
	{
		$FirstName = stripslashes($ElderFullNameArray['FIRST_NAME']);
		$LastName = stripslashes($ElderFullNameArray['LAST_NAME']);
		$id = stripslashes($ElderFullNameArray['id']);
		$SpecialNeed = stripslashes($ElderFullNameArray['SPECIAL_NEED']);
		
		$MultipleValues = $id . "|" . $SpecialNeed . "|" . $FirstName . "|" . $LastName;
					
		$Referral_block .= "<option value=\"" .$MultipleValues . "\">" .$FirstName. " " . $LastName. "</option>";
		
	}
	$Referral_block .= "
		    <input type=\"hidden\" size=\"25\" id=\"emails\" name=\"emails\" value=\"" . $Emails . "\">
****/


/*	$_POST['firstname'] = "abc"; 
	$_POST['lastname'] = "xyz"; 
	$_POST['city']  = $city;
	$_POST['state'] = $state;
	$_POST['facility_number']  = $facility_number;  **/

	$OwnerIndex = $_SESSION['id'];
	$sGetEmailAddress = "SELECT email_address from login_table where id = $OwnerIndex";
												   
	
	$objGetEmailAddress = $mysqli->query( $sGetEmailAddress );
	if( $objGetEmailAddress )
	{
		$EmailAddress = $objGetEmailAddress->fetch_array( MYSQLI_ASSOC );
		$Email = stripslashes($EmailAddress['email_address']);
	}
	$_POST['email'] = $Email;
	$Claim_block .= "<h3>Please choose one<h3>";
	$Claim_block .= "
			<form method =\"post\" action=\"../owner/Claim_A_Facility.php\">
			<input type=\"owner_or_admin\" name=\"owner_or_admin\" value=\"O\">; 
		    	<input type=\"hidden\" size=\"25\" id=\"firstname\" name=\"firstname\" value=\"abc\">
		    	<input type=\"hidden\" size=\"25\" id=\"lastname\" name=\"lastname\" value=\"xyz\">
		    	<input type=\"hidden\" size=\"25\" id=\"city\" name=\"city\" value=\"" . $city . "\">
		    	<input type=\"hidden\" size=\"25\" id=\"zipcode\" name=\"zipcode\" value=\"" . $zipcode . "\">
		    	<input type=\"hidden\" size=\"25\" id=\"emails\" name=\"email\" value=\"" . $Email . "\">
		    	<input type=\"hidden\" size=\"25\" id=\"facility_number\" name=\"facility_number\" value=\"" . $facility_number . "\">
			<div class=\"submitWrapper\">
			<button type=\"submit\" name=\"submit\" value=\"view\">View Selected Entry\"></button>
			</div>
			</form>";

$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<link rel="stylesheet" href="css/5div.css">
	<!--<link rel="stylesheet" href="..\css\client.css">-->
	<?php include( '../common/header.php' );?> <!--worked -->
</head>
<body>
	<div class='headercontainer'>
		<?php include( '../common/owner_navigation.php' );?> 
	</div>
	<br>   <!-- Need these break lines to prevent the navigation bar hiding the top lines. -->
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<h1><?php echo $facility_name; ?></h1>
	<h2><?php echo "$facility_address $city, $state $zipcode<br>"; ?></h2>
	<div class='ALL'>
		<div class="TOP">
			<h2>images</h2>
			<?php echo $display_block; ?>
		</div>
		<div class="BOTTOM_LEFT">
			<h2>Owner & Administrators</h2>
			<?php echo $Owner_Adm_Block; ?>
		</div>
		<div class="BOTTOM_MIDDLE">
			<h2>Amenities</h2>
			<h3>Quartet night</h3>
			<h3>Comedy night</h3>
			<h3>Pho Night</h3>
		</div>
		<div class="BOTTOM_RIGHT">
			<h2>SPECIAL</h2>
			<h3>$500 rebate</h3>
			<h3>$300 referral</h3>
		</div>
		<div class="BOTTOM">
			<h2>Claim this facility</h2>
			<?php echo $Claim_block; ?>
		</div>
	</div>
	<?php include ( '../common/owner_trailer' ); ?>
</body>
</html>
