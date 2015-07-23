<?php 
	session_start();  
	require_once "../common/dependency.php";
?>
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
<!-JIC      02-12-15      Implemented Angular vaidation.          ->
<!-         03-20-15      Implemented CSRF defense.               ->
<!-         06-11-15      Cleaned up some filenames.              ->
<!-                                                               ->
<!-                                                               ->
<!----------------------------------------------------------------->
<html lang="en" ng-app>
<head>
  <meta charset="utf-8">
  <title>4BABOOM.COM - Client Login or Register</title>
  <script>
        function validateLoginEmail(emailField)
        {
                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

                if (reg.test(emailField.value) == false) 
                {
                        document.getElementById("client_email").style.borderColor = "#E34234";
                        document.getElementById("member_email_error").innerHTML="this is invalid email.";
                        return false;
                }
                else
                {
                        document.getElementById("client_email").style.borderColor = "#CCC";
                        document.getElementById("member_email_error").innerHTML="";
                        return true;
                }
        }
  </script>
  <script>
        function validateRegisterEmail(emailField)
        {
                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

                if (reg.test(emailField.value) == false) 
                {
                        document.getElementById("client_register_email").style.borderColor = "#E34234";
                        document.getElementById("member_register_email_error").innerHTML="this is invalid email.";
                        return false;
                }
                else
                {
                        document.getElementById("client_register_email").style.borderColor = "#CCC";
                        document.getElementById("member_register_email_error").innerHTML="";
                        return true;
                }
        }
  </script>

<script>
	function validatePassword( passwordField )
	{
		//var reg = /^\w+$/;
		var reg = /^[A-Za-z0-9]+$/;

		if ( reg.test(passwordField.value) == false )
		{
                        document.getElementById("client_password").style.borderColor = "#E34234";
                        document.getElementById("client_register_password_error").innerHTML="Password must contain only the letters and numbers!";
			//alert( "Password must contain only letters, number and underscores!" );
			return false;
		}
		else
		{
                        document.getElementById("client_password").style.borderColor = "#CCC";
                        document.getElementById("client_register_password_error").innerHTML="";
			return true;
		}
	}

</script>

  <script>
        function validateConfirmPassword() 
        {
                var pass1 = document.getElementById("client_pwd").value;
                var pass2 = document.getElementById("confirm_client_pwd").value;
                var ok = true;
                if (pass1 != pass2) 
                {
                        document.getElementById("member_register_password_confirm_error").innerHTML="this password does not match!";
                        //alert("Passwords Do not match");
                        document.getElementById("client_pwd").style.borderColor = "#E34234";
                        document.getElementById("confirm_client_pwd").style.borderColor = "#E34234";
                        ok = false;
                }
                else {
                        //alert("Passwords Match!!!");
                        document.getElementById("client_pwd").style.borderColor = "#CCC";
                        document.getElementById("confirm_client_pwd").style.borderColor = "#CCC";
                        document.getElementById("member_register_password_confirm_error").innerHTML="";
                }
                return ok;
        }
  </script>
<script>
        function validate_registration() 
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
                                         document.getElementById("registration_message").innerHTML = xmlhttp.responseText;
                                 }
                         }
                         <!--Send the request to the backend php script.  -->
                         var email = document.getElementById("client_register_email").value;
                         var pass1 = document.getElementById("client_pwd").value;
                         var pass2 = document.getElementById("confirm_client_pwd").value;
                         var first_name = document.getElementById("client_first").value;
                         var last_name = document.getElementById("client_last").value;
                         var token = document.getElementById("client_register_token").value;
			 var clean_token = encodeURIComponent(token);

                         str = email + "|" + pass1 + "|" + first_name + "|" + last_name + "|" + clean_token;
                         //alert( str );
                         xmlhttp.open("POST", "member_register.php?q="+str, true);
                         xmlhttp.send();
        }
</script>
<script>
        function validate_login() 
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
                                         document.getElementById("login_message").innerHTML = xmlhttp.responseText;
					 if( xmlhttp.responseText == "Welcome" )
					 {
						window.location = ('http://54.67.1.138/member/member_search_front_end.php');	
					 }

                                 }
                         }
                         <!--Send the request to the backend php script.  -->
                         var email = document.getElementById("client_email").value;
                         var pass1 = document.getElementById("client_password").value;
                         var token = document.getElementById("client_token").value;
			 var clean_token = encodeURIComponent(token);

                         str = email + "|" + pass1 + "|" + clean_token;    <!-- 03-15-15 pass token -->
                         //alert( str );
                         xmlhttp.open("POST", "member_login.php?q="+str, true);
                         xmlhttp.send();
        }
</script>
  <?php include( '../common/header.php' );?> <!--worked -->
</head>

<body>
        <div class="headercontainer">
                <?php include( '../common/member_navigation.php' );?>  
        </div>
<!-- Owner Login or Register Start -->

<!-- Login Form -->

<div class="container">
  <h3><span class="glyphicon glyphicon-heart"></span> MEMBERS -</h3>
  <p>- Must register for the first time in order to search facilities for your loved ones. Skip the login and go directly to the member register below.</p> 
  <p>- After successful registration then you can search for facilities then inquiry and receive email notifications.</p>
  <p>- As registered member then you can also add/update your dependent seniors who need your helps.</p>
  <br>
  <br>

<h4><span class="glyphicon glyphicon-log-in"></span><b> REGISTERED MEMBER - </b>Please go ahead and login ...<h4>
<form ng-submit='registerUser()' name="member_login_form" id="member_login_form" method="post" class="form-horizontal" onsubmit="validate_login()" novalidate>
  <div class="form-group">
    <label for="client_id" class="col-sm-2 control-label">Login:</label>
    <div class="col-sm-4">
      <input type="email" ng-model="user.email" class="form-control" id="client_email" name="client_email" onblur="validateLoginEmail(this);" placeholder="email@abc.com" required>
        <span style="color:red;" id="member_email_error" > </span>
    </div>
    <label for="client_password" class="col-sm-2 control-label">Password:</label>
    <div class="col-sm-4">
      <input type="password" ng-model="user.password" class="form-control" id="client_password" name="client_password" placeholder="password" ng-minlength="6" required>
        <p
                class="help-block"
                ng-show="member_login_form.client_password.$error.minlength || member_login_form.client_password.$invalid" >  <!--This text will be displayed only if password is invalid or password length is less than 6.   $invalid is a variable name and so it $error. -->
                <small style="color:green;" >password must be at least 6 characters.</small>
        </p>
    	<a href="http://54.67.1.138/member/member_recover_password_front_end.php" class="forgot-pwd" tabindex="4">Forgot your password?</a>
     </div>
  </div>
	<input type="hidden" id="client_token" name="token" value="<?php echo Token::generate( "MEMBER_LOGIN_FORM" ); ?>" > <!-- CSRF defense --> 
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button ng-disabled="member_login_form.$invalid" type="submit" class="btn btn-primary">Login</button>
        <span id="login_message"></span>
    </div>
  </div>
</form>
<br>
<br>
<font color="#008000">
<h4><span class="glyphicon glyphicon-registration-mark"></span><b> FIRST TIME MEMBER - </b>Please follow the member registration below ...<h4>
  <!--<form ng-submit='registerUser()' name="member_register_form" method="post" class="form-horizontal" action="Client_Register.php" novalidate>-->
  <form ng-submit='registerUser()' name="member_register_form" method="post" class="form-horizontal"  onsubmit="validate_registration()" novalidate>
    <div class="form-group">
      <label for="client_email" class="col-sm-2 control-label">Enter Email:</label>
      <div class="col-sm-4">
        <input ng-model="newUser.email" type="email" class="form-control" id="client_register_email" name="client_email" placeholder="email@abc.com" onblur="validateRegisterEmail(this);" required>
        <span style="color:red;" id="member_register_email_error" > </span>

      </div>
      <label for="client_pwd" class="col-sm-3 control-label">New Password:</label>
      <div class="col-sm-3">
        <input ng-model="newUser.password" type="password" class="form-control" id="client_pwd" name="client_pwd" placeholder="password" onblur="validatePassword(this);" ng-minlength="6" required>
		<span style="color:red;" id="client_register_password_error"></span>
        <p
                class="help-block"
                ng-show="member_register_form.client_pwd.$error.minlength || member_register_form.client_pwd.$invalid" >  <!--This text will be displayed only if password is invalid or password length is less than 6.   $invalid is a variable name and so it $error. -->
                <small style="color:green;" >password must be at least 6 characters.</small>
        </p>
      </div>
      <label for="client_confirm_pwd" class="col-sm-offset-6 col-sm-3 control-label">Confirm New Password:</label>
      <div class="col-sm-3">
        <input  ng-model="user_confirm.password"  type="password" class="form-control" id="confirm_client_pwd" name="confirm_client_pwd" placeholder="confirm new password" onblur="validateConfirmPassword(this);" ng-minlength="6" required>
        <span style="color:red;" id="member_register_password_confirm_error" > </span>
      </div>
    </div>
    <div class="form-group">
      <label for="client_first" class="col-sm-2 control-label">First:</label>
      <div class="col-sm-4">
        <input ng-model="user.text" type="text" class="form-control" id="client_first" name="client_first_name" placeholder="first" ng-minlength="2" required>
                <p
                        class="help-block"
                        ng-show="member_register_form.client_first_name.$error.minlength || member_register_form.client_first_name.$invalid" >  <!--This text will be displayed only if password is invalid
                        or password length is less than 6.   $invalid is a variable name and so it $error. -->
                        <small style="color:green;" >at least 2 characters please.</small>
                </p>
      </div>
      <label for="client_last" class="col-sm-2 control-label">Last:</label>
      <div class="col-sm-4">
        <input ng-model="user1.text" type="text" class="form-control" id="client_last" name="client_last_name" placeholder="last" ng-minlength="2" required>

                <p
                        class="help-block"
                        ng-show="member_register_form.client_last_name.$error.minlength || member_register_form.client_last_name.$invalid" >  <!--This text will be displayed only if password is invalid
                        or password length is less than 6.   $invalid is a variable name and so it $error. -->
                        <small style="color:green;" >at least 2 characters please.</small>
                </p>
      </div>
    </div>
	<input type="hidden" id="client_register_token" name="client_register_token" value="<?php echo Token::generate( "MEMBER_REGISTER_FORM" ); ?>" > <!-- CSRF defense --> 
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <br/>

        <label class="checkbox">
                <input type="checkbox" ng-model="newUser.terms" value="1" required>
                I accept 4baboom.com's <a target="_blank" href="/home/terms">Terms of Service</a>.
        </label>
        <br/>

        <button ng-disabled="member_register_form.$invalid" type="submit" class="btn btn-primary">Register</button>
        <span id="registration_message"></span>
      </div>
    </div>
  </form>
</font>
</div>

<!-- Owner Login or Register End -->

  <?php include( '../common/member_trailer' );?> <!--worked -->
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular.min.js"></script>
</body>
</html>
