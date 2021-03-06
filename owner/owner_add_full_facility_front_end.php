<?php
session_start();
#######################################################
#  FILE : owner_add_facility_front_end.php 
#
#  DESCRIPTION: This page is a front end to adding  
#               a new facility.
#
#   WHO        WHEN         WHAT
#   ------     ---------    ----------
#   Bac        06-27-15     Created.
#######################################################
require_once '../common/dependency.php';
vConnectDB( "baboom" );

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
	<h1><center><?php echo "Add your new facility"; ?></center></h1>

        <div class="container-fluid">
        <form action="owner_add_facility.php" method="get" autocomplete="on">
          <div class="form-group">
          <h3><center>Fill in the required "*General Admin" tab then optional info in other tabs before click <br>
              <b><input type="submit" value="Save Only"></b> or <b><input type="submit" formaction="owner_add_facility.php" value="Save & Exit"></b> 
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
		      <dt>Name:</dt><dd><input type="text" name="facility_name" required></dd>
		      <dt>Address:</dt><dd><input type="text" name="facility_address" required></dd>
		      <dt>City:</dt><dd><input type="text" name="facility_city" required></dd>
                      <dt>State:</dt><dd><input type="text" name="facility_state" required></dd>
                      <dt>Zipcode:</dt><dd><input type="text" name="facility_zipcode" required></dd>
		      <dt>Phone:</dt><dd><input type="tel" name="facility_phone" required></dd>
		      <dt>_____________________________</dt><dd></dd>
		      <dt>Licensee Name:</dt><dd><input type="text" name="licensee_name" required></dd>
		      <dt>License Number:</dt><dd><input type="text" name="license_number" required></dd>
		      <dt>License Status:</dt><dd><input type="text" name="license_status"required></dd>
		      <dt>Facility Type:</dt><dd><input type="text" name="facility_type" required></dd>
		      <dt>Facility Capacity:</dt><dd><input tytpe="text" name="facility_cap" required></dd>
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
            <!--<input type="checkbox" name="private" value="yes"> Private Room  <br>
            <input type="checkbox" name="semi-private" value="yes"> Semi-Private Room <br>
            <input type="checkbox" name="shared" value="yes"> Shared Room <br>
            <input type="checkbox" name="hospital" value="yes"> Near by hospitals and/or medical facilities <br>
            <input type="checkbox" name="mall" value="yes"> Near by shopping malls <br>
            <input type="checkbox" name="secure-quiet" value="yes"> Secure and quiet neighborhood <br>
            <input type="checkbox" name="light-spacious" value="yes"> Light and spacious rooms <br>
            <input type="checkbox" name="alarm-access" value="yes"> Fire Alarms & Wheelchair Access <br>
            <input type="checkbox" name="hospice" value="yes"> Hospice room available <br>

		-->

            <input type="checkbox" name="accomodation[]" id="accomodation" value="1" > Private Room  <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="2" > Semi-Private Room <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="3" > Shared Room <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="4" > Near by hospitals and/or medical facilities <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="5" > Near by shopping malls <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="6" > Secure and quiet neighborhood <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="7" > Light and spacious rooms <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="8" > Fire Alarms & Wheelchair Access <br>
            <input type="checkbox" name="accomodation[]" id="accomodation" value="9" > Hospice room available <br>
            </div>
            </div>
	  </div>
	  <div id="bbtab-3" style="color:blue;">
            <div class="container-fluid">
            <div class="form-group">
            Included amenities & services - <br>
  <!--          <input type="checkbox" name="meals" value="yes"> Well balanced & nutritious meals and snacks <br>
            <input type="checkbox" name="medication" value="yes"> Medication service <br>
            <input type="checkbox" name="emergency" value="yes"> Emergency response calls <br>
            <input type="checkbox" name="housekeeping" value="yes"> Housekeeping & laundry services <br>
            <input type="checkbox" name="bedding" value="yes"> Bedding service <br>
            <input type="checkbox" name="grooming" value="yes"> Grooming service <br>
            <input type="checkbox" name="bathing" value="yes"> Bathing service <br>
            <input type="checkbox" name="social-events" value="yes"> Social events (i.e. birthday & holiday parties) <br>
            <br>
            Fee based services - <br>
            <input type="checkbox" name="continence" value="yes"> Affordable continence service <br>
            <input type="checkbox" name="sepcial-diet" value="yes"> Special diet <br>
           
-->



            <input type="checkbox" name="service[]" id="service" value="1"> Well balanced & nutritious meals and snacks <br>
            <input type="checkbox" name="service[]" id="service" value="2"> Medication service <br>
            <input type="checkbox" name="service[]" id="service" value="3"> Emergency response calls <br>
            <input type="checkbox" name="service[]" id="service" value="4"> Housekeeping & laundry services <br>
            <input type="checkbox" name="service[]" id="service" value="5"> Bedding service <br>
            <input type="checkbox" name="service[]" id="service" value="6"> Grooming service <br>
            <input type="checkbox" name="service[]" id="service" value="7"> Bathing service <br>
            <input type="checkbox" name="service[]" id="service" value="8"> Social events (i.e. birthday & holiday parties) <br>
            <br>
            Fee based services - <br>
            <input type="checkbox" name="service[]" id="service" value="9"> Affordable continence service <br>
            <input type="checkbox" name="service[]" id="service" value="10"> Special diet <br>

 
            <p><center><br><br><b>HOSPICE & DEMENTIA RESIDENTS ARE WELCOME</b></center></p>
            </div>
            </div>
	  </div>
	  <div id="bbtab-4" style="color:blue;">
	    <div class="container-fluid">
	      <div class="row">
		 <div class="col-md-4">
		   <dl>
		     <dt>Shared:</dt><dd>From<input type="number" name="shared_min" min="1000" max="7000"><br>__To<input type="number" name="shared_max" min="1500" max="10000"></dd>
		     <dt>Semi-private:</dt><dd>From<input type="number" name="semi_min" min="1500" max="8000"><br>__To<input type="number" name="semi_max" min="2000" max="10000"></dd>
		     <dt>Private:</dt><dd>From<input type="number" name="private_min" min="2000" max="9000"><br>__To<input type="number" name="private_max" min="2500" max="10000"></dd>
		     <dt>______________________________</dt><dd><br></dd>
		     <dt>Select the 2nd month promotional discount:</dt>
                       <dd>
                         <input type="radio" name="discount" value="500"> $500.00 <br>
                         <input type="radio" name="discount" value="1000"> $1000.00 <br>
                         <input type="radio" name="discount" value="1500"> $1500.00 <br>
                       </dd> 
		     <dt>______________________________</dt><dd><br></dd>
		     <dt>Available Room(s):</dt><dd>
                                                  <input type="number" name="avail_shared" min="0" max="1000">Shared<br>
                                                  <input type="number" name="avail_semi" min="0" max="1000">Semi-private<br>
                                                  <input type="number" name="avail_private" min="0" max="1000">Private<br>`
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
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBaebVX2ufevnAzjEFJyi9Y0TIGgWOgtWk&q=<?php echo $address ?>&zoom=15">
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
