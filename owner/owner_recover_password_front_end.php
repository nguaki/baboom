<?php
	session_start();
	require_once "../common/dependency.php";
?>
<!DOCTYPE html>
<!---------------------------------------------------------------->
<!- FILE: owner_recover_password_front_end.php                    ->
<!-                                                               ->
<!- DESCRIPTION: THis page is displayed when an owner pushes      ->
<!-              "Forgot Password" in the owner login/registration->
<!-              page.                                            ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-WHO      WHEN          WHAT                                    ->
<!------    -------       -------------------------------------   ->
<!-         06-10-15      Copied and modified from owner_login_   ->
<!-                       register.php.                           ->
<!-                                                               ->
<!---------------------------------------------------------------->
<html lang="en" ng-app>
<head>
  <meta charset="utf-8">
  <title>4BABOOM.COM - Owner Login or Register</title>
  <script>
        function validateLoginEmail(emailField)
        {
                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

                if (reg.test(emailField.value) == false) 
                {
                        document.getElementById("owner_email").style.borderColor = "#E34234";
                        document.getElementById("owner_email_error").innerHTML="this is invalid email.";
                        return false;
                }
                else
                {
                        document.getElementById("owner_email").style.borderColor = "#CCC";
                        document.getElementById("owner_email_error").innerHTML="";
                        return true;
                }
        }
  </script>
  <script>
        function recover_password() 
        {
                //alert( "hello" );
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() 
                {
                        <!--This is where the returned string gets received -->
                        <!--from the php backend and sets the value in the  -->
                        <!-- placeholder in the html.                       -->
                        <!--Not sure what these state and status mean.      -->
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                        {
                                document.getElementById("login_message").innerHTML = xmlhttp.responseText;
			 	if( xmlhttp.responseText == "You will receive a temporary password via email." )
				{
					// Changes to a new web location.
					window.location = ('http://54.67.1.138/owner/owner_login_register.php');	
					// Give a promt to a user.
					alert( "You will receive a temporary password via email.  Please login with the new temporary password." );
				}

                         }
                 }
                 <!--Send the request to the backend php script.  -->
                 var email = document.getElementById("owner_email").value;
                 var token = document.getElementById("owner_token").value;
		 var clean_token = encodeURIComponent(token);

                 str = email + "|" + clean_token;    <!-- 03-15-15 pass token -->
                 //alert( str );
                 xmlhttp.open("POST", "owner_recover_password.php?q="+str, true);
                 xmlhttp.send();
        }
</script>

  <?php include( '../common/header.php' );?>
</head>

<body>
        <div class="headercontainer">
                <?php include( '../common/owner_navigation.php' );?>
        </div>
<!-- Owner Login or Register Start -->

<!-- Login Form -->

<div class="container">
  <h3><span class="glyphicon glyphicon-heart"></span> Lets recover your password. -</h3>
  <br>
  <br>

<h4><span class="glyphicon glyphicon-log-in"></span><b> REGISTERED OWNER - </b>Please enter your e-mail ...<h4>
<form ng-submit='registerUser()' name="owner_recover_pw_form" id="owner_recover_pw_form"  method="post" class="form-horizontal" onsubmit="recover_password()" novalidate>
  <div class="form-group">
    <label for="owner-id" class="col-sm-2 control-label">E-Mail:</label>
    <div class="col-sm-4">
      <input type="email" ng-model="user.email" class="form-control" id="owner_email" name="owner_email" onblur="validateLoginEmail(this);" placeholder="email@abc.com" required>
	<span style="color:red;" id="owner_email_error" ></span>
    </div>

  </div>
      <input type="hidden" name="token" id="owner_token" value="<?php echo Token::generate( "OWNER_RECOVER_PW_FORM" ); ?>" > <!-- CSFR defense -->

  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button ng-disabled="owner_recover_pw_form.$invalid" type="submit" class="btn btn-primary">Submit</button>
      <span id="login_message"></span>
    </div>
  </div>
</form>
</div>



<!-- Owner Login or Register End -->
 <?php include ( '../common/owner_trailer' ); ?>
 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular.min.js"></script>

</body>
</html>
