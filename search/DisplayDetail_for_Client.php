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
#              01-31-15     Added license status and available rooms.
#              06-22-15     Loads amenities and services providded and sets the check boxes accordingly.
#              06-27-15     Loads prices, discount and available info.  
#                           All the values are read only.
#
#
#
#######################################################

//header('Cache-Control: no cache');           // This created problem.
//session_cache_limiter('private_no_expire');
require_once '../common/dependency.php';
vConnectDB( "baboom" );

session_start();

//If not logged in, cannot see the detailed information.
//This forces people to register with us.
if (($_SESSION['user']) != 'member' ) 
{
	 popup('Please register in order to see the detail info.',  MEMBER_LOGIN_PAGE);	
}


$facility_number = urldecode($_GET['facility_num']);
$state = urldecode($_GET['state']);
$facility_name = urldecode($_GET['facility_name']);
$facility_address = urldecode($_GET['address']);
$city = urldecode($_GET['city']);
$zipcode = urldecode( $_GET['zipcode'] );
$telephone = urldecode( $_GET['telephone'] );
$licensee = urldecode( $_GET['licensee'] );
$facility_type = urldecode( $_GET['facility_type'] );
$facility_cap = urldecode( $_GET['facility_cap'] );
$license_status = urldecode( $_GET['status'] );
$shared_available = urldecode( $_GET['shared_avail'] );
$semi_private_available = urldecode( $_GET['semi_private_avail'] );
$private_available = urldecode( $_GET['private_avail'] );


////////////////////////////////////////////////////////////////////////////////////////
//
//       M   A   P
//
////////////////////////////////////////////////////////////////////////////////////////
$address = $facility_address . $city . " " . $state . " " . $zipcode;
$region = "";
$address = str_replace(" ", "+", $address);
// echo $address;
#exit;

$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");

if (DEBUG)
{
	//`var_dump( $json );
	echo "<br>";
	echo "<br>";
	echo "<br>";
}

$json = json_decode($json);

$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
$long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

if (DEBUG)
{
	//var_dump( $json );
	echo $lat . "<br>";
	echo $long . "<br>";
}


echo "<span id='storage' data_variable_one='". $lat ."' data_variable_two='". $long ."'>";
echo "</span>";
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


////////////////////////////////////////////////////////////////////////////////////////
//
//       W E L C O M E     S T A T E M E N T
//
////////////////////////////////////////////////////////////////////////////////////////
$sGetWelcomeStatement = "SELECT statement 
                         FROM welcome_statement 
			 WHERE facility_number = $facility_number";

$objGetStatement = $mysqli->query( $sGetWelcomeStatement );
$Statement = "";
 
if( $objGetStatement )
{
	$StatementArray = $objGetStatement->fetch_array( MYSQLI_ASSOC );
	$Statement = stripslashes($StatementArray['statement']);

	$objGetStatement->free_result();
}
if(DEBUG)
{
	echo $sGetWelcomeStatement;
	echo $Statement;
}

////////////////////////////////////////////////////////////////////////////////////////
//
//       LOAD ACCOMODATIONS  PROVIDED                 
//
////////////////////////////////////////////////////////////////////////////////////////

	// Initialize every variables to zero.
	$A1 = '0';
	$A2 = '0';
	$A3 = '0';
	$A4 = '0';
	$A5 = '0';
	$A6 = '0';
	$A7 = '0';
	$A8 = '0';
	$A9 = '0';
	$A10 = '0';
	$A11 = '0';


	$sAccomodationsProvided = "SELECT * 
                 		   FROM accomodations_provided 
                 		   WHERE facility_number = $facility_number";
if( DEBUG )
{
	echo $sAccomodationsProvided; 
}
	$objGetAccomodations = $mysqli->query( $sAccomodationsProvided );


	if( $objGetAccomodations )
	{
		var_dump( $objGetAccomodations );
		$AnArray = $objGetAccomodations->fetch_array( MYSQLI_ASSOC );
		//var_dump( $AnArray );
		$A1 = stripslashes($AnArray['A1']);
		$A2 = stripslashes($AnArray['A2']);
		$A3 = stripslashes($AnArray['A3']);
		$A4 = stripslashes($AnArray['A4']);
		$A5 = stripslashes($AnArray['A5']);
		$A6 = stripslashes($AnArray['A6']);
		$A7 = stripslashes($AnArray['A7']);
		$A8 = stripslashes($AnArray['A8']);
		$A9 = stripslashes($AnArray['A9']);

if( DEBUG )
{
	//for( $i = 1; $i <=9; $i ++ )
	//{
	//	echo { "$A" . $i };
	//}	

	echo "A1 = " . $A1 . " ";
	echo $A2 . " ";
	echo $A3 . " ";
	echo $A4 . " ";
	echo $A5 . " ";
	echo $A6 . " ";
	echo $A7 . " ";
	echo $A8 . " ";
	echo $A9 . " ";
}
		$objGetAccomodations->free_result();

	}
	else
	{
		echo "No results from accomodations  table </br>";
	}

////////////////////////////////////////////////////////////////////////////////////////
//
//       LOAD SERVICES PROVIDED                 
//
////////////////////////////////////////////////////////////////////////////////////////
//
// Load services available. Used xml file to reduce the DB connection.
// Defines what S1, S2, ... means from database table name service_provided.
//
////////////////////////////////////////////////////////////////////////////////////////

#$theData = simplexml_load_file("../common/services.xml");
#$i = 1;
#foreach( $theData->Service as $theService )
#{
#	$TheService = $theService->Description;
#	//echo "$TheService<br>";
#	$ServiceList[$i++]= $TheService;
#	unset($TheService);
#}

	// Initialize every variables to zero.
	$S1 = '0';
	$S2 = '0';
	$S3 = '0';
	$S4 = '0';
	$S5 = '0';
	$S6 = '0';
	$S7 = '0';
	$S8 = '0';
	$S9 = '0';
	$S10 = '0';

$sGetServices = "SELECT * 
                 FROM service_provided 
                 WHERE facility_number = $facility_number";

$objGetServices = $mysqli->query( $sGetServices );
if( $objGetServices )
{
	$AnArray = $objGetServices->fetch_array( MYSQLI_ASSOC );
	//var_dump( $AnArray );
	$S1 = stripslashes($AnArray['S1']);
	$S2 = stripslashes($AnArray['S2']);
	$S3 = stripslashes($AnArray['S3']);
	$S4 = stripslashes($AnArray['S4']);
	$S5 = stripslashes($AnArray['S5']);
	$S6 = stripslashes($AnArray['S6']);
	$S7 = stripslashes($AnArray['S7']);
	$S8 = stripslashes($AnArray['S8']);
	$S9 = stripslashes($AnArray['S9']);
	$S10 = stripslashes($AnArray['S10']);

	$objGetServices->free_result();
if( DEBUG )
{
	//for( $i = 1; $i <=9; $i ++ )
	//{
	//	echo { "$A" . $i };
	//}	

	echo "S1 = " . $S1 . " ";
	echo $S2 . " ";
	echo $S3 . " ";
	echo $S4 . " ";
	echo $S5 . " ";
	echo $S6 . " ";
	echo $S7 . " ";
	echo $S8 . " ";
	echo $S9 . " ";
	echo $S10 . " ";
}
	
}

#$available_services = array();
#$count = 0;
#
#for( $i = 1; $i <= 13;$i++ )
#{
#	//echo ${"S".$i};
#	if ( ${"S".$i} == "I" )   // Funcky way of expressing $S1, $S2, $S3 ,...
##	{
#		$available_services[$count++] = $ServiceList[$i];
#		//echo $ServiceList[$i]  . "<br>";
##	}
#}

////////////////////////////////////////////////////////////////////////////////////////
//
//       LOAD PRICE INFORMATION                 
//
////////////////////////////////////////////////////////////////////////////////////////
$sGetPrice = "select * 
              from facility_price 
              where facility_number = $facility_number 
              order by time_stamp desc limit 1";

$objGetPrice = $mysqli->query( $sGetPrice );
if( $objGetPrice )
{
	$AnArray = $objGetPrice->fetch_array( MYSQLI_ASSOC );

	$shared_min_price = stripslashes($AnArray['shared_min_price']);
	$shared_max_price = stripslashes($AnArray['shared_max_price']);
	$semi_private_min_price = stripslashes($AnArray['semi_private_min_price']);
	$semi_private_max_price = stripslashes($AnArray['semi_private_max_price']);
	$private_min_price = stripslashes($AnArray['private_min_price']);
	$private_max_price = stripslashes($AnArray['private_max_price']);
	$baboom_special = stripslashes($AnArray['baboom_special']);
	$shared_available = stripslashes($AnArray['shared_available']);
	$semi_priv_available = stripslashes($AnArray['semi_priv_available']);
	$priv_available = stripslashes($AnArray['priv_available']);

	$objGetPrice->free_result();
}


$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/5div.css">

	<?php include( '../common/header.php' );?> <!--worked -->

	 <style>
	    body {
	      color: #0000FF;
	      background-image: url("/img/sample1.jpg");
	    }
	  </style>
	  <!-- Added for tabs -->
	  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/start/jquery-ui.css">
	  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
	  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
	<div>
		<?php include( '../common/member_navigation.php' );?> 
	</div>
	<!-- Need these break lines to prevent the navigation bar hiding the top lines. -->

<!-- Facility Detail -->
	<h1><?php echo $facility_name; ?></h1>
        <h2><?php echo "$facility_address $city $state<br>"; ?></h2>

        <div class="container-fluid">
	<div id="tabs">
	  <ul>
	    <li><a href="#bbtab-1"><span>General Admin</span></a></li>
	    <li><a href="#bbtab-2"><span>Accommodations</span></a></li>
	    <li><a href="#bbtab-3"><span>Amenities & Services</span></a></li>
	    <li><a href="#bbtab-4"><span>Pricing</span></a></li>
	    <li><a href="#bbtab-5"><span>Map & Direction</span></a></li>
	  </ul>
	  <div id="bbtab-1">
	    <div class="container-fluid">
	      <div class="row">
		 <div class="col-md-4">
		   <!--<img src="https://maps.googleapis.com/maps/api/streetview?size=300x300&location=700+s+plymouth+place+anaheim+ca">-->
		   <img src="https://maps.googleapis.com/maps/api/streetview?size=300x300&location=<?php echo $address?>">
		 </div>
		 <div class="col-md-8" style="color:blue;background-color:lightgray;">
		    <dl>
		      <dt>Name:</dt><dd><?php echo $facility_name; ?></dd>
		      <dt>Address:</dt><dd><?php echo $facility_address; ?></dd>
		      <dt>City & Zipcode</dt><dd><?php echo $city . " " . $state . " " . $zipcode;?></dd>
		      <dt>Phone:</dt><dd><?php echo $telephone ?></dd>
		      <dt>_____________________________</dt><dd></dd>
		      <dt>Licensee Name:</dt><dd><?php echo $licensee ?></dd>
		      <dt>License Number:</dt><dd><?php echo $facility_number ?></dd>
		      <dt>License Status:</dt><dd><?php echo $license_status ?></dd>
		      <dt>Facility Type:</dt><dd><?php echo $facility_type?></dd>
		      <dt>Facility Capacity:</dt><dd><?php echo $facility_cap?></dd>
		      <dt style="color:darkgreen;">Vacancy Status:</dt><dd style="color:darkgreen;"><?php if(($shared_available > 0) || ($semi_private_available > 0 ) || ($private_available > 0) ){ echo "Yes"; } else {echo "No";} ?> - Please go to "Pricing" tab to inquire ...</dd>
		    </dl>
		 </div>
	      </div>
	    </div>
	  </div>
	  <div id="bbtab-2" style="color:blue;">
	    <p><?php echo $Statement ?> </p> 
	    <!--We operate a 6-bed senior board & care facility that <b>our residents are treated like our extended family with utmost respect.</b><br>
	    We are conveniently residing in city of Anaheim, California with nearby hospitals, medical facilities, shopping malls, etc.<br>
	    There are comfortable and affordable shared room as well as semi-private and private rooms for more needed quiet & privacy.</p>-->

            <div class="container-fluid">
            <div class="form-group">
            We operate a senior board & care facility that <b>our residents are treated like our extended family with utmost respect.</b><br>
            There are comfortable and affordable shared room as well as semi-private and private rooms for more needed quiet & privacy.</p>
            <!-- disabled readonly also works but the UI is not clean look. -->
	    <input type="checkbox" <?php if( $A1 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Private Room  <br>
            <input type="checkbox" <?php if( $A2 == '1' ) echo "Checked='Checked'";?> onclick="return false">  Semi-Private Room <br>
            <input type="checkbox" <?php if( $A3 == '1' ) echo "Checked='Checked'";?> onclick="return false">  Shared Room <br>
            <input type="checkbox" <?php if( $A4 == '1' ) echo "Checked='Checked'";?> onclick="return false">  Near by hospitals and/or medical facilities <br>
            <input type="checkbox" <?php if( $A5 == '1' ) echo "Checked='Checked'";?> onclick="return false">  Near by shopping malls <br>
            <input type="checkbox" <?php if( $A6 == '1' ) echo "Checked='Checked'";?> onclick="return false">  Secure and quiet neighborhood <br>
            <input type="checkbox" <?php if( $A7 == '1' ) echo "Checked='Checked'";?> onclick="return false">  Light and spacious rooms <br>
            <input type="checkbox" <?php if( $A8 == '1' ) echo "Checked='Checked'";?> onclick="return false">  Fire Alarms & Wheelchair Access <br>
            <input type="checkbox" <?php if( $A9 == '1' ) echo "Checked='Checked'";?> onclick="return false">  Hospice room available <br>
            </div>
            </div>
	  </div>
	  <div id="bbtab-3" style="color:blue;">
    <div class="container-fluid">
    <div class="form-group">
            Included amenities & services - <br>
            <input type="checkbox" <?php if( $S1 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Well balanced & nutritious meals and snacks <br>
            <input type="checkbox" <?php if( $S2 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Medication service <br>
            <input type="checkbox" <?php if( $S3 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Emergency response calls <br>
            <input type="checkbox" <?php if( $S4 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Housekeeping & laundry services <br>
            <input type="checkbox" <?php if( $S5 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Bedding service <br>
            <input type="checkbox" <?php if( $S6 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Grooming service <br>
            <input type="checkbox" <?php if( $S7 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Bathing service <br>
            <input type="checkbox" <?php if( $S8 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Social events (i.e. birthday & holiday parties) <br>
            <br>
            Fee based services - <br>
            <input type="checkbox" <?php if( $S9 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Affordable continence service <br>
            <input type="checkbox" <?php if( $S10 == '1' ) echo "Checked='Checked'";?> onclick="return false"> Special diet <br>
            
            <p><center><br><br><b>HOSPICE & DEMENTIA RESIDENTS ARE WELCOME</b></center></p>
            </div>
            </div>
	    <!--<p><center>
		    Well balanced & nutritious meals and snacks
	    <br>Grooming assistance<br>Medication assistance<br>Housekeeping & laundry<br>Holidays & birthday events
	    <br><br><b>HOSPICE & DEMENTIA RESIDENTS ARE WELCOME</b>
	    </center></p> -->
	  </div>
	  <div id="bbtab-4" style="color:blue;">
	    <div class="container-fluid">
	      <div class="row">
		 <div class="col-md-4">
		   <dl>
		     <dt>Shared:</dt><dd>From<input type="number" readonly="readonly" value=<?php echo $shared_min_price; ?>><br>__To<input type="number" readonly="readonly" value=<?php echo $shared_max_price; ?>></dd>
		     <dt>Semi-private:</dt><dd>From<input type="number" readonly="readonly" value=<?php echo $semi_private_min_price;?>><br>__To<input type="number" readonly="readonly" value=<?php echo $semi_private_max_price;?>></dd>
		     <dt>Private:</dt><dd>From<input type="number" readonly="readonly" value=<?php echo $private_min_price;?>><br>__To<input type="number" readonly="readonly" value=<?php echo $private_max_price;?>></dd>
		     <dt>______________________________</dt><dd><br></dd>
		     <dt>Select the 2nd month promotional discount:</dt>
                       <dd>
                         <input type="radio" <?php if( $baboom_special == 500 ) echo "Checked='Checked'";?> onclick="return false"> $500.00 <br>
                         <input type="radio" <?php if( $baboom_special == 1000 ) echo "Checked='Checked'";?> onclick="return false">  $1000.00 <br>
                         <input type="radio" <?php if( $baboom_special == 1500 ) echo "Checked='Checked'";?> onclick="return false"> $1500.00 <br>
                       </dd> 
		     <dt>______________________________</dt><dd><br></dd>
		     <dt>Available Room(s):</dt><dd>
                                                  <input type="number" readonly="readonly" value=<?php echo $shared_available;?>><>Shared<br>
                                                  <input type="number" readonly="readonly" value=<?php echo $semi_priv_available;?>><>Semi-private<br>
                                                  <input type="number" readonly="readonly" value=<?php echo $priv_available;?>><>Private<br>`
                                                </dd>
		   </dl>
         <div class="col-md-8" style="background-color:lightgray;">
            <p>Please click the "Room Inquiry" button below and we will immediately start the inquiry process on your behalf to this facility owner 
            regarding about the room availability ...</p>
            <p><a href="../member/facility_inquiry.php?facility_number=<?php echo $facility_number?>&facility_name=<?php echo $facility_name?>" class="btn btn-primary" role="button">Room Inquiry</a></p>
         </div>
         </div>
       </div>
     </div>
  </div>
  <div id="bbtab-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-offset-4 col-md-8" style="color:blue;background-color:lightgray;">
          <iframe
            width="100%"
            height="350"
            frameborder="0" style="border:0"
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBaebVX2ufevnAzjEFJyi9Y0TIGgWOgtWk&q=<?php echo $address ?>&zoom=15">
            IFRAME is not supported
          </iframe>
        </div> 
      </div>
    </div>
  </div>
</div> <!-- tabs end --> 
</div> <!-- tabs container end -->

<script>
  $( "#tabs" ).tabs({ 
     // event: "mouseover" 
  });
</script>
	<?php include ( '../common/googleMap_marker.txt' ); ?>
	<?php include ( '../common/member_trailer' ); ?>
</body>
</html>
