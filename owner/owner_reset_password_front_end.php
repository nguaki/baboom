<?php
	session_start();
	require_once "../common/dependency.php";
?>
<!DOCTYPE html>
<!---------------------------------------------------------------->
<!- FILE: owner_reset_password_front_end.php                      ->
<!-                                                               ->
<!- DESCRIPTION: THis page is for resetting the password.         ->
<!-              This is useful when a user forgets the password. ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-WHO      WHEN          WHAT                                    ->
<!------    -------       -------------------------------------   ->
<!-         06-10-15      Copied and modified from owner_login_   ->
<!-                       register.php.                           ->
<!-         06-15-15      Cannot disable the form button when     ->
<!-                       the passwords don't match.              ->
<!-                       Need more knowledge on angular form.    ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!---------------------------------------------------------------->
<html lang="en" ng-app>
<head>
  <meta charset="utf-8">
  <title>4BABOOM.COM - Owner Login or Register</title>

  <script>
        function validateResetPassword() 
        {
                var pass1 = document.getElementById("new_password1").value;
                var pass2 = document.getElementById("new_password2").value;
                var ok = true;
                if (pass1 != pass2) 
                {
                        //alert("Passwords Do not match");
                        document.getElementById("new_password1").style.borderColor = "#E34234";
                        document.getElementById("new_password2").style.borderColor = "#E34234";
                        document.getElementById("owner_reset_password_confirm_error").innerHTML="this password does not match!";
                        ok = false;
			//owner_reset_password_form.new_password2.$invalid = true;
			//console.log( owner_reset_password_form.new_password2.$invalid );
			//owner_reset_password_form..$invalid = true;
                }
                else 
		{
                        //alert("Passwords Match!!!");
                        document.getElementById("new_password1").style.borderColor = "#CCC";
                        document.getElementById("new_password2").style.borderColor = "#CCC";
                        document.getElementById("owner_reset_password_confirm_error").innerHTML="";
			//owner_reset_password_form.new_password2.$invalid = false;
			//console.log( owner_reset_password_form.new_password2.$invalid );
                }
                return ok;
        }
  </script>
  <script>
        function owner_reset_password() 
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
                                document.getElementById("owner_reset_password_message").innerHTML = xmlhttp.responseText;
			 	if( xmlhttp.responseText == "You have successfully resetted your password." )
				{
					// Changes to a new web location.
					window.location = ('http://54.67.1.138/owner/owner_login_register.php');	
					// Give a promt to a user.
					alert( "Please proceed with login." );
				}

                         }
                 }
                 <!--Send the request to the backend php script.  -->
                 var temp_password = document.getElementById("temp_password").value;
                 var new_password = document.getElementById("new_password1").value;
                 var confirm_password = document.getElementById("new_password2").value;
                 var token = document.getElementById("owner_token").value;
		 var clean_token = encodeURIComponent(token);

                 str = temp_password + "|" + new_password + "|" + confirm_password + "|" + clean_token;
		 //alert( str );
                 //alert( str );
                 xmlhttp.open("POST", "owner_reset_password.php?q="+str, true);
                 xmlhttp.send();
        }
</script>

<script>
	function validatePassword( passwordField )
	{
		//var reg = /^\w+$/;
		var reg = /^[A-Za-z0-9]+$/;

		if ( reg.test(passwordField.value) == false )
		{
                        document.getElementById("new_password1").style.borderColor = "#E34234";
                        document.getElementById("owner_new_password_error").innerHTML="Password must contain only the letters and numbers!";
			//alert( "Password must contain only letters, number and underscores!" );
			return false;
		}
		else
		{
                        document.getElementById("new_password1").style.borderColor = "#CCC";
                        document.getElementById("owner_new_password_error").innerHTML="";
			return true;
		}
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
	<h3><span class="glyphicon glyphicon-heart"></span><b>Lets reset your password.-</b></h3>
  	<br>
  	<br>

	<h4><span class="glyphicon glyphicon-log-in"></span><b> </b>REGISTERED OWNER,  Please fill in the following ...<h4>
	<form ng-submit='registerUser()' name="owner_reset_password_form" class="form-horizontal" method="post" onsubmit="owner_reset_password()" novalidate>
  		<div class="form-group">
    			<label for="temp_password" class="col-sm-2 control-label">temp password:</label>
    			<div class="col-sm-3">
      				<input ng-model="user.password" type="password" class="form-control" id="temp_password" name="temp_password" placeholder="password" ng-minlength="6" required>
					<p
						class="help-block"
						ng-show="owner_reset_password_form.temp_password.$error.minlength || owner_reset_password_form.temp_password.$invalid">
						<small style="color:green;">password must be at least 6 characters.</small>
					</p>
    			</div>
			<br>
			<br>
			<br>
			<br>
			<br>

    			<h4><label for="new_password1" class="col-sm-2 control-label">new password:</label><h4>
			<div class="col-sm-3">
      				<input ng-model="user.password2" type="password" class="form-control" id="new_password1" name="new_password1" onblur="validatePassword(this);" placeholder="password" ng-minlength="6" required>
					<span style="color:red;" id="owner_new_password_error"></span>
					<p
						class="help-block"
						ng-show="owner_reset_password_form.new_password1.$error.minlength || owner_reset_password_form.new_password1.$invalid">
						<small style="color:green;">password must be at least 6 characters.</small>
					</p>
    			</div>
			<br>
			<br>
			<br>
			<br>
    
			<label for="new_password2" class="col-sm-2 control-label">confirm password:</label>
    			<div class="col-sm-3">
				<input ng-model="userpassword" type="password" class="form-control" id="new_password2" name="new_password2" placeholder="password" onblur="validateResetPassword(this);" ng-minlength="6" required>
				<span style="color:red;" id="owner_reset_password_confirm_error"></span>
    			</div>
   		</div>
		<input type="hidden" name="token" id="owner_token" value="<?php echo Token::generate( "OWNER_RESET_PW_FORM" ); ?>" >
  		<div class="form-group">
			<!--The button is disabled when 2 passwords do not match.-->
  			<div class="col-sm-offset-2 col-sm-10">
                                <!-- June 15, 2014                                        -->
				<!-- The second or statement has no impact to ng-disabled -->
    				<button ng-disabled="owner_reset_password_form.$invalid || owner_reset_password_form.new_password2.$invalid " type="submit" class="btn btn-primary">Login</button>
    				<span id="owner_reset_password_message"></span>
  			</div>
  		</div>
	</form>
</div>
<!-- Owner Login or Register End -->
 <?php include ( '../common/owner_trailer' ); ?>
 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular.min.js"></script>

</body>
</html>
