<!DOCTYPE html>
<!----------------------------------------------------------------->
<!- FILE:                                                         ->
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
<!-         01-22-15      Link to register/login for client.      ->
<!-         06-11-15      Modified member_login_register.php.     ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!----------------------------------------------------------------->

<html lang="en">
<head>
  <title>4BABOOM.COM - FREE Senior Online Community</title>
  <meta charset="utf-8">
   <link rel="stylesheet" href="css/client.css">
   <?php include( 'common/header.php' );?>
</head>

<body>
  <!-- <div class='headercontainer'> -->
  <div>
     <?php include( 'common/general_navigation.php' );?>
  </div>
<!-- <br><br><br><br><br><br><br><br><br>; --> <!-- need it for first lines that gets cut off by the navigation bar. -->  

<!-- Introduction -->
<div class="container"> 
  <h3><span class="glyphicon glyphicon-home"></span> FREE SENIOR ONLINE COMMUNITY</h3>
</div>

<!-- Getting Started Guide for Client Search-->
<div class="container">
  <h4><span class="glyphicon glyphicon-hand-right"></span><strong> Getting Started ... </strong><p>If this is your first time visiting 4BABOOM.COM then please follow the steps below otherwise you can go directly to where you want to go via the above navigation bar... <br><center>A WARM & SINCERE WELCOME TO OUR COMMUNITY</center></p></h4>

<div class="row">
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
      <img src="/img/1.jpg" class="img-circle" alt="SENIOR-1">
      <div class="caption">
        <font color="#8B008B">
        <p><b>Senior or Relative</b></p>
        <h4>Step 1 - Login or Register</h4>
        <p>You can skip this step and continue to search for facility as guest. However if you register and login then you will get more benefits such as email communication, search history and potential discount up to $1000 ...</p>
        <p><a href="/member/member_login_register.php" class="btn btn-primary" role="button">Member Login/Register</a></p>
        </font>
      </div> 
    </div>
  </div>

  <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
      <img src="/img/2.jpg" class="img-circle" alt="SENIOR-2">
      <div class="caption">
        <font color="#8B008B">
        <p><b>Senior or Relative</b></p>
        <h4>Step 2 - Facility Search</h4>
        <p>You can go directly to this search by city and/or zipcode as guest without notification ...</p>
        <p><a href="/member/member_search.php" class="btn btn-primary" role="button">Search</a></p>
        </font>
      </div> 
    </div>
  </div>

  <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
      <img src="/img/3.jpg" class="img-circle" alt="SENIOR-1">
      <div class="caption">
        <font color="#008000">
        <p><b>Facility Owner</b></p>
	<h4>Must be registered user</h4>
        <p>You will need to register and login to take over the ownership of maximun 5 listed facilities. As registered owners then you can also update or add any new facilities that are not currently listed on our facility directory ...</p>
        <p><a href="/owner/owner_login_register.php" class="btn btn-primary" role="button">Owner Login/Register</a></p>
        <font>
      </div> 
    </div>
  </div>

</div>
</div>

<!-- Home Page End -->
  <?php include( 'common/general_trailer' );?>
</body>
</html>
