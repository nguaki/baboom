<?php
	session_start();
	require_once "../common/dependency.php";
?>
<!DOCTYPE html>
<!---------------------------------------------------------------->
<!- FILE: member_reset_password_front_end.php                     ->
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
<!-         06-19-15      Copied and modified from owner_reset_   ->
<!-                       password_front_end.php.                 ->
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
                        document.getElementById("member_reset_password_confirm_error").innerHTML="this password does not match!";
                        ok = false;
                }
                else 
		{
                        //alert("Passwords Match!!!");
                        document.getElementById("new_password1").style.borderColor = "#CCC";
                        document.getElementById("new_password2").style.borderColor = "#CCC";
                        document.getElementById("member_reset_password_confirm_error").innerHTML="";
                }
                return ok;
        }
  </script>
  <script>
        function member_reset_password() 
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
                                document.getElementById("member_reset_password_message").innerHTML = xmlhttp.responseText;
			 	if( xmlhttp.responseText == "You have successfully resetted your password." )
				{
					// Changes to a new web location.
					window.location = ('http://54.67.1.138/member/member_login_register.php');	
					// Give a promt to a user.
					alert( "Please proceed with login." );
				}

                         }
                 }
                 <!--Send the request to the backend php script.  -->
                 var temp_password = document.getElementById("temp_password").value;
                 var new_password = document.getElementById("new_password1").value;
                 var confirm_password = document.getElementById("new_password2").value;
                 var token = document.getElementById("member_token").value;
		 var clean_token = encodeURIComponent(token);

                 str = temp_password + "|" + new_password + "|" + confirm_password + "|" + clean_token;
		 //alert( str );
                 //alert( str );
                 xmlhttp.open("POST", "member_reset_password.php?q="+str, true);
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
                        document.getElementById("member_new_password_error").innerHTML="Password must contain only the letters and numbers!";
			//alert( "Password must contain only letters, number and underscores!" );
			return false;
		}
		else
		{
                        document.getElementById("new_password1").style.borderColor = "#CCC";
                        document.getElementById("member_new_password_error").innerHTML="";
			return true;
		}
	}

</script>

  <?php include( '../common/header.php' );?>
</head>

<body>
        <div class="headercontainer">
                <?php include( '../common/member_navigation.php' );?>
        </div>
<!-- Owner Login or Register Start -->

<!-- Login Form -->

  	<div class="container">
	<h3><span class="glyphicon glyphicon-heart"></span><b>Lets reset your password.-</b></h3>
  	<br>
  	<br>

	<h4><span class="glyphicon glyphicon-log-in"></span><b> </b>REGISTERED MEMBER,  Please fill in the following ...<h4>
	<form ng-submit='registerUser()' name="member_reset_password_form" class="form-horizontal" method="post" onsubmit="member_reset_password()" novalidate>
  		<div class="form-group">
    			<label for="temp_password" class="col-sm-2 control-label">temp password:</label>
    			<div class="col-sm-3">
      				<input ng-model="user.password" type="password" class="form-control" id="temp_password" name="temp_password" placeholder="password" ng-minlength="6" required>
					<p
						class="help-block"
						ng-show="member_reset_password_form.temp_password.$error.minlength || member_reset_password_form.temp_password.$invalid">
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
					<span style="color:red;" id="member_new_password_error"></span>
					<p
						class="help-block"
						ng-show="member_reset_password_form.new_password1.$error.minlength || member_reset_password_form.new_password1.$invalid">
						<small style="color:green;">password must be at least 6 characters.</small>
					</p>
    			</div>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
    
			<label for="new_password2" class="col-sm-2 control-label">confirm password:</label>
    			<div class="col-sm-3">
				<input ng-model="userpassword" type="password" class="form-control" id="new_password2" name="new_password2" placeholder="password" onblur="validateResetPassword(this);" ng-minlength="6" required>
				<span style="color:red;" id="member_reset_password_confirm_error"></span>
    			</div>
   		</div>
		<input type="hidden" name="token" id="member_token" value="<?php echo Token::generate( "MEMBER_RESET_PW_FORM" ); ?>" >
  		<div class="form-group">
			<!--The button is disabled when 2 passwords do not match.-->
  			<div class="col-sm-offset-2 col-sm-10">
                                <!-- June 15, 2014                                        -->
				<!-- The second or statement has no impact to ng-disabled -->
    				<button ng-disabled="member_reset_password_form.$invalid || member_reset_password_form.new_password2.$invalid " type="submit" class="btn btn-primary">Login</button>
    				<span id="member_reset_password_message"></span>
  			</div>
  		</div>
	</form>
</div>
<!-- Owner Login or Register End -->
 <?php include ( '../common/member_trailer' ); ?>
 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular.min.js"></script>

</body>
</html>
