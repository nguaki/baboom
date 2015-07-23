<!DOCTYPE html>
<?php session_start(); ?>
<!----------------------------------------------------------------->
<!- FILE: owner_claim_facility_front_end.php                      ->
<!-                                                               ->
<!-                                                               ->
<!-DESCRIPTION:  This is the front end used to claim the          ->
<!-              ownership  of a facility by a registered         ->
<!-              owner. Simply enter a valid license number       ->
<!-              or a facility number to claim the facility.      ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-WHO      WHEN          WHAT                                    ->
<!------    -------       -------------------------------------   ->
<!-         01-22-15      Moved to Linux and made changes         ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!----------------------------------------------------------------->

<?php
if( DEBUG )
{
	var_dump( $_GET );
}

$facility_number = urldecode($_GET['facility_num']);
$state = urldecode($_GET['state']);
$facility_name = urldecode($_GET['facility_name']);
$facility_address = urldecode($_GET['address']);
$city = urldecode($_GET['city']);
$zipcode = urldecode($_GET['zipcode']);
?>

<html>
<head>
<link rel="stylesheet" href="css/facility.css">
<?php include( '../common/header.php' );?> <!--worked -->
</head>
<body>
	<div class="headcontainer">
		<?php include( '../common/owner_navigation.php' );?> 
	</div>	
						
	<div class="mainform">
		<h2>Please fill out facility number/license number below in order to claim this facility.</h2>
		<form action="owner_claim_facility.php" method="post">
				<fieldset>
				<legend>Facility Info</legend>
					<p>
						<label for="facility_name">Facility Name:</label>
						<input type="text" size="25" id="facility_name" name="facility_name" value="<?php echo $facility_name;?>">
					</p>
					<p>
						<label for="address">Address:</label>
						<input type="text" size="25" id="address" name="address" value="<?php echo $facility_address;?>">
					</p>
					<p>
						<label for="city">City:</label>
						<input type="text" size="25" id="city" name="city" value="<?php echo $city;?>">
					</p>
					<p>
						<label for="zipcode">Zipcode:</label>
						<input type="text" size="25" id="zipcode" name="zipcode" value="<?php echo $zipcode;?>">
					</p>
					<p>
						<label for="facility_number">Facility Number:</label>
						<input type="text" size="25" id="facility_number" name="facility_number">
					</p>
					<!-- <p>
						<label for="owner_or_admin">Owner or Admin:</label>
						<input type="text" size="25" id="owner_or_admin" name="owner_or_admin">
					</p> -->
					<p>
						<div class="submitWrapper">
							<button type="submit" name="submit" value="send">Submit</button>
						</div>
					</p>
				</fieldset>		
		</form>	
	</div>
 	<?php include ( '../common/owner_trailer' ); ?>
</body>
</html>
