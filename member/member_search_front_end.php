<?php
	session_start();
?>
<!DOCTYPE html>

<!----------------------------------------------------------------->
<!- FILE:  member_search_front_end.php                            ->
<!-                                                               ->
<!-                                                               ->
<!-DESCRIPTION:                                                   ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
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
<!-         01-20-15      Moved to Linux.                         ->
<!-                       Added Ajax.                             ->
<!-         01-22-15      Added member_trailer                    ->
<!-         06-26-15      Changed the script name.                ->
<!-                                                               ->
<!----------------------------------------------------------------->
<html lang="en">
<head>
  <title>4BABOOM.COM - Member Facility Search</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <style>
    body {
      color: #0000FF;
      background-image: url("/img/sample1.jpg");
    }
  </style>
	<?php include( '../common/header.php' );?> <!--worked -->
</head>
<body>

<div>
       <?php include( '../common/member_navigation.php' );?>
</div>

<div class="container"> 
  <h3><span class="glyphicon glyphicon-search"> SEARCH OPTIONS -</h3>
  <p>- Search by City - Leave Zipcode blank then enter City as "Irvine" or Cities with comma as "Irvine, Tustin ..."</p> 
  <p>- Or search by Zipcode - Leave City/Cities blank then enter the 5 digits zipcode as "92618".</p>
  <p>- Search by City and Zipcode then enter them both.</p>

	<?php unset($_SESSION['cities']);  unset($_SESSION['zipcode']); ?>
<form class="form-horizontal" method="get" action="member_facility_search.php">
  <div class="form-group">
    <label for="city-ajax" class="col-sm-2 control-label">City/Cities:</label>
    <div class="col-sm-3">
      <input type="text" autocomplete="off" class="form-control" id="city-ajax" name="cities" list="city-datalist" placeholder="Irvine or Irvine, Tustin">
      <datalist id="city-datalist"></datalist>
    </div>
  </div>
  <div class="form-group">
    <label for="zipcode-ajax" class="col-sm-2 control-label">and/or Zipcode:</label>
    <div class="col-sm-3">
      <input type="text" autocomplete="off" class="form-control" id="zipcode-ajax" name="zipcode" list="zipcode-datalist" placeholder="Zipcode - Only required 5 digits">
      <datalist id="zipcode-datalist"></datalist>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
<p>Suggestion: <span id="txtHint"></span></p>
</div>

<!-- Facility Search Page End -->
<?php include( '../common/member_trailer' );?> 
<script src="combine_auto_complete.js"></script>
<!--<script src="../common/jQuery.js"></script> 
<script src="zipcode_auto_complete.js"></script>
<script src="city_auto_complete.js"></script>-->
</body>
</html>
