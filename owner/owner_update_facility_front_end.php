<?php
session_start();
#######################################################
#
#  FILE : owner_update_facility_front_end.php 
#
#  DESCRIPTION: This page will allow owner to add or update
#               a facility.
#
#   WHO        WHEN         WHAT
#   ------     ---------    ----------
#   Bac        06-11-15     Created.
#   James      06-21-15     Filled in text inputs from database.
#                           Made the facility number field readonly. 
#
#
#######################################################
require_once '../common/dependency.php';
vConnectDB( "baboom" );

global $mysqli;

////////////////////////////////////////////////////////////////////////////////////////////////
//
//   F I L L       I N    General INFO tab section
//
////////////////////////////////////////////////////////////////////////////////////////////////
$facility_number = urldecode( $_GET['facility_number']);


$sGetAllFacilityInfo = "SELECT facility_name, address, city, zipcode, state, telephone, licensee, license_status, facility_type, facility_cap 
			FROM rcfe
		        WHERE facility_number = '$facility_number'";

$objGetAllFacilityInfo = $mysqli->query( $sGetAllFacilityInfo );

if( $objGetAllFacilityInfo )
{
	$AFacility = $objGetAllFacilityInfo->fetch_array( MYSQLI_ASSOC );

	$FacilityName = stripslashes($AFacility['facility_name']);
	$Address = stripslashes($AFacility['address']);
	$City = stripslashes($AFacility['city']);
	$Zipcode = stripslashes($AFacility['zipcode']);
	$State = stripslashes($AFacility['state']);
	$Telephone = stripslashes($AFacility['telephone']);
	$Licensee = stripslashes($AFacility['licensee']);
	$LicenseeStatus = stripslashes($AFacility['license_status']);
	$FacilityType = stripslashes($AFacility['facility_type']);
	$FacilityCap = stripslashes($AFacility['facility_cap']);

	$FullAddress = $Address . " " . $City . "," . $Zipcode . " " . $State;
	echo $FullAddress;

	$objGetAllFacilityInfo->free_result();
}
else
{
	popup( "No facility information", OWNER_SEARCH_PAGE );
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
	}
////////////////////////////////////////////////////////////////////////////////////////
//
//       LOAD PRICES, DISCOUNT, AVAILABLES PROVIDED                 
//
////////////////////////////////////////////////////////////////////////////////////////
$sGetAllFacilityPriceInfo = "SELECT shared_min_price, shared_max_price, semi_private_min_price, semi_private_max_price, private_min_price, private_max_price, baboom_special, shared_available, semi_priv_available, priv_available 
			FROM facility_price
		        WHERE facility_number = '$facility_number'";

$objGetAllFacilityPriceInfo = $mysqli->query( $sGetAllFacilityPriceInfo );

if( $objGetAllFacilityPriceInfo )
{
	$AFacilityPriceInfo = $objGetAllFacilityPriceInfo->fetch_array( MYSQLI_ASSOC );

	$shared_min_price = stripslashes($AFacilityPriceInfo['shared_min_price']);
	$shared_max_price = stripslashes($AFacilityPriceInfo['shared_max_price']);
	$semi_private_min_price = stripslashes($AFacilityPriceInfo['semi_private_min_price']);
	$semi_private_max_price = stripslashes($AFacilityPriceInfo['semi_private_max_price']);
	$private_min_price = stripslashes($AFacilityPriceInfo['private_min_price']);
	$private_max_price = stripslashes($AFacilityPriceInfo['private_max_price']);
	$baboom_special = stripslashes($AFacilityPriceInfo['baboom_special']);
	$shared_available = stripslashes($AFacilityPriceInfo['shared_available']);
	$semi_priv_available = stripslashes($AFacilityPriceInfo['semi_priv_available']);
	$priv_available = stripslashes($AFacilityPriceInfo['priv_available']);


	$objGetAllFacilityPriceInfo->free_result();
}
else
{
	popup( "No facility information", OWNER_SEARCH_PAGE );
}
	
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
		<?php include( '../common/owner_navigation.php' );?> 
	</div>
	<!-- Need these break lines to prevent the navigation bar hiding the top lines. -->

<!-- Facility Detail -->
	<h1><center><?php echo "Update your new facility"; ?></center></h1>

        <div class="container-fluid">
        <form action="owner_update_facility.php" method="get" autocomplete="on">
          <div class="form-group">
          <h3><center>Fill in the required "*General Admin" tab then optional info in other tabs before click <br>
              <b><input type="submit" value="Save Only"></b> or <b><input type="submit" formaction="owner_update_facility.php" value="Save & Exit"></b> 
              </center>
          </h3>
          </div>

	<div id="tabs">
	  <ul>
	    <li><a href="#bbtab-1"><span>*General Admin</span></a></li>
	    <li><a href="#bbtab-2"><span>Accommodations</span></a></li>
	    <li><a href="#bbtab-3"><span>Amenities & Services</span></a></li>
	    <li><a href="#bbtab-4"><span>Pricing</span></a></li>
	    <li><a href="#bbtab-5"><span>Map & Direction</span></a></li>
	  </ul>
	  <div id="bbtab-1">
	    <div class="container-fluid">
            <div class="form-group">

	      <div class="row">
		 <div class="col-md-4">
		   <img src="https://maps.googleapis.com/maps/api/streetview?size=300x300&location=0+dummy+ca">
		 </div>

                 <!-- form action="Add_new_facility.php" method="get" autocomplete="on" -->
		 <div class="col-md-8" style="color:blue;background-color:lightgray;">
		    <dl>
		      <dt>Name:</dt><dd><input type="text" name="facility_name" value="<?php echo $FacilityName ?>" required></dd>
		      <dt>Address:</dt><dd><input type="text" name="facility_address" value="<?php echo $Address ?>"required></dd>
		      <dt>City:</dt><dd><input type="text" name="facility_city" value="<?php echo $City ?>"required></dd>
                      <dt>State:</dt><dd><input type="text" name="facility_state" value="<?php echo $State ?>" required></dd>
                      <dt>Zipcode:</dt><dd><input type="text" name="facility_zipcode" value="<?php echo $Zipcode ?>" required></dd>
		      <dt>Phone:</dt><dd><input type="tel" name="facility_phone" value="<?php echo $Telephone ?>" required></dd>
		      <dt>_____________________________</dt><dd></dd>
		      <dt>Licensee Name:</dt><dd><input type="text" name="licensee_name" value="<?php echo $Licensee ?>" required></dd>
		      <dt>License Number:</dt><dd><input type="text" name="license_number" readonly="readonly" value="<?php echo $facility_number ?>" required></dd>
		      <dt>License Status:</dt><dd><input type="text" name="license_status" value="<?php echo $LicenseeStatus ?>" required></dd>
		      <dt>Facility Type:</dt><dd><input type="text" name="facility_type" value="<?php echo $FacilityType ?>" required></dd>
		      <dt>Facility Capacity:</dt><dd><input tytpe="text" name="facility_cap" value="<?php echo $FacilityCap ?>" required></dd>
                      <dt>_____________________________</dt><dd></dd>
		    </dl>
		 </div>
	      </div>

            </div>
	    </div>
	  </div>
	  <div id="bbtab-2" style="color:blue;">
            <div class="container-fluid">
            <div class="form-group">
            We operate a senior board & care facility that <b>our residents are treated like our extended family with utmost respect.</b><br>
            There are comfortable and affordable shared room as well as semi-private and private rooms for more needed quiet & privacy.</p>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="1" <?php if( $A1 == '1' ) echo "Checked='Checked'";?>> Private Room  <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="2" <?php if( $A2 == '1' ) echo "Checked='Checked'";?>> Semi-Private Room <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="3" <?php if( $A3 == '1' ) echo "Checked='Checked'";?>> Shared Room <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="4" <?php if( $A4 == '1' ) echo "Checked='Checked'";?>> Near by hospitals and/or medical facilities <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="5" <?php if( $A5 == '1' ) echo "Checked='Checked'";?>> Near by shopping malls <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="6" <?php if( $A6 == '1' ) echo "Checked='Checked'";?>> Secure and quiet neighborhood <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="7" <?php if( $A7 == '1' ) echo "Checked='Checked'";?>> Light and spacious rooms <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="8" <?php if( $A8 == '1' ) echo "Checked='Checked'";?>> Fire Alarms & Wheelchair Access <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="9" <?php if( $A9 == '1' ) echo "Checked='Checked'";?>> Hospice room available <br>
            </div>
            </div>
	  </div>
	  <div id="bbtab-3" style="color:blue;">
            <div class="container-fluid">
            <div class="form-group">
            Included amenities & services - <br>
            <input type="checkbox" name="service[]" id="service" value="1" <?php if( $S1 == '1' ) echo "Checked='Checked'";?>> Well balanced & nutritious meals and snacks <br>
            <input type="checkbox" name="service[]" id="service" value="2" <?php if( $S2 == '1' ) echo "Checked='Checked'";?>> Medication service <br>
            <input type="checkbox" name="service[]" id="service" value="3" <?php if( $S3 == '1' ) echo "Checked='Checked'";?>> Emergency response calls <br>
            <input type="checkbox" name="service[]" id="service" value="4" <?php if( $S4 == '1' ) echo "Checked='Checked'";?>> Housekeeping & laundry services <br>
            <input type="checkbox" name="service[]" id="service" value="5" <?php if( $S5 == '1' ) echo "Checked='Checked'";?>> Bedding service <br>
            <input type="checkbox" name="service[]" id="service" value="6" <?php if( $S6 == '1' ) echo "Checked='Checked'";?>> Grooming service <br>
            <input type="checkbox" name="service[]" id="service" value="7" <?php if( $S7 == '1' ) echo "Checked='Checked'";?>> Bathing service <br>
            <input type="checkbox" name="service[]" id="service" value="8" <?php if( $S8 == '1' ) echo "Checked='Checked'";?>> Social events (i.e. birthday & holiday parties) <br>
            <br>
            Fee based services - <br>
            <input type="checkbox" name="service[]" id="service" value="9" <?php if( $S9 == '1' ) echo "Checked='Checked'";?>> Affordable continence service <br>
            <input type="checkbox" name="service[]" id="service" value="10" <?php if( $S10 == '1' ) echo "Checked='Checked'";?>> Special diet <br>
            
            <p><center><br><br><b>HOSPICE & DEMENTIA RESIDENTS ARE WELCOME</b></center></p>
            </div>
            </div>
	  </div>
	  <div id="bbtab-4" style="color:blue;">
	    <div class="container-fluid">
	      <div class="row">
		 <div class="col-md-4">
		   <dl>
		     <dt>Shared:</dt><dd>From<input type="number" name="shared_min" min="1000" max="7000" value=<?php echo $shared_min_price; ?>><br>__To<input type="number" name="shared_max" min="1500" max="10000" value=<?php echo $shared_max_price; ?>></dd>
		     <dt>Semi-private:</dt><dd>From<input type="number" name="semi_min" min="1500" max="8000" value=<?php echo $semi_private_min_price;?>><br><br>__To<input type="number" name="semi_max" min="2000" max="10000" value=<?php echo $semi_private_max_price;?>></dd>
		     <dt>Private:</dt><dd>From<input type="number" name="private_min" min="2000" max="9000" value=<?php echo $private_min_price;?>><br>__To<input type="number" name="private_max" min="2500" max="10000" value=<?php echo $private_max_price;?>></dd>
		     <dt>______________________________</dt><dd><br></dd>
		     <dt>Select the 2nd month promotional discount:</dt>
                       <dd>
                         <input type="radio" name="discount" id="discount" value="500" <?php if( $baboom_special == 500 ) echo "Checked='Checked'";?>> $500.00 <br>
                         <input type="radio" name="discount" id="discount" value="1000" <?php if( $baboom_special == 1000 ) echo "Checked='Checked'";?>>  $1000.00 <br>
                         <input type="radio" name="discount" id="discount" value="1500" <?php if( $baboom_special == 1500 ) echo "Checked='Checked'";?>> $1500.00 <br>
                       </dd> 
		     <dt>______________________________</dt><dd><br></dd>
		     <dt>Available Room(s):</dt><dd>
                                                  <input type="number" name="avail_shared" min="0" max="1000" value=<?php echo $shared_available;?>><>Shared<br>
                                                  <input type="number" name="avail_semi" min="0" max="1000" value=<?php echo $semi_priv_available;?>><>Semi-private<br>
                                                  <input type="number" name="avail_private" min="0" max="1000" value=<?php echo $priv_available;?>><>Private<br>`
                                                </dd>
		   </dl>
         </div>
         <div class="col-md-8" style="background-color:lightgray;">
            <p>Each facility will be required to offer the 2nd month promotional discount of $500.00 or $1000.00 or $1500.00 for all registered members of 4baboom.com. If it is not selected then we will default it to $500.00</p>
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
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBaebVX2ufevnAzjEFJyi9Y0TIGgWOgtWk&q=<?php echo $FullAddress; ?>&zoom=15">
            IFRAME is not supported
          </iframe>
        </div> 
      </div>
    </div>
  </div>
</div> <!-- tabs end --> 

</form>
</div> <!-- tabs container end -->

<script>
  $( "#tabs" ).tabs({ 
     // event: "mouseover" 
  });
</script>
	<?php include ( '../common/googleMap_marker.txt' ); ?>
	<?php include ( '../common/owner_trailer' ); ?>
</body>
</html>
