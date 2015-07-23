<!DOCTYPE html>

<!----------------------------------------------------------------->
<!- FILE:  owner_search.php                                      ->
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
<!-         06-29-15      Changed to owner_trailer                ->
<!-                                                               ->
<!-                                                               ->
<!----------------------------------------------------------------->
<html lang="en">
<head>
  <title>4BABOOM.COM - Member Facility Search</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <script>
	<!-- This is an implementation of AJAX.  It is called by onkeyup event which is-->
	<!-- set up in when a user inputs city name.                                   -->
	function showHint(str) 
	{
	     <!-- Question: How is it possible when no character -->
	 	<!-- has entered, this script is called?            -->
		 if (str.length == 0) 
		 { 
		 	document.getElementById("txtHint").innerHTML = "";
		 	return;
	     	 } 
		 else 
		 {
		 	var xmlhttp = new XMLHttpRequest();
		 	xmlhttp.onreadystatechange = function() 
			{
		     		<!--This is where the returned string gets received -->
				 <!--from the php backend and sets the value in the  -->
				 <!-- placeholder in the html.                       -->
			 	<!--Not sure what these state and status mean.      -->
			 	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			 	{
                 			document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
             			}
         		}
			 <!--Send the request to the backend php script.  -->
			xmlhttp.open("GET", "../common/Get_Matching_city.php?q="+str, true);
			xmlhttp.send();
     		}
	}
  </script>

  <?php include( '../common/header.php' );?>
</head>
<body>

	<div class="headercontainer">
		<?php include( '../common/owner_navigation.php' );?>
	</div>

<div class="container"> 
  <h3><span class="glyphicon glyphicon-search"> SEARCH OPTIONS -</h3>
  <p>- Search by City - Leave Zipcode blank then enter City as "Irvine" or Cities with comma as "Irvine, Tustin ..."</p> 
  <p>- Or search by Zipcode - Leave City/Cities blank then enter the 5 digits zipcode as "92618".</p>
  <p>- Search by City and Zipcode then enter them both.</p>

<form class="form-horizontal" method="post" action="owner_facility_search.php">
  <div class="form-group">
    <label for="cities" class="col-sm-2 control-label">City/Cities:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="cities" name="cities" onkeyup="showHint(this.value)" placeholder="Irvine or Irvine, Tustin">
    </div>
  </div>
  <div class="form-group">
    <label for="zipcode" class="col-sm-2 control-label">and/or Zipcode:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Zipcode - Only required 5 digits">
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

<?php include( '../common/owner_trailer' );?> 
</body>
</html>
