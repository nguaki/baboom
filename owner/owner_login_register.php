<?php
	session_start();
	require_once "../common/dependency.php";
?>
<!DOCTYPE html>
<!---------------------------------------------------------------->
<!- FILE:                                                         ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!-ESCRIPTION:                                                    ->
<!-WHO      WHEN          WHAT                                    ->
<!------    -------       -------------------------------------   ->
<!-         01-22-15      Including 3 files from common dir       >
<!-                       Added method="post"                     ->
<!-                                                               ->
<!-         03-20-15      Implemented CSFR defense.               ->
<!-         06-10-15      Implemented AJAX.                       ->
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
        function validateRegisterEmail(emailField)
        {
                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

                if (reg.test(emailField.value) == false) 
                {
                        document.getElementById("owner_register_email").style.borderColor = "#E34234";
                        document.getElementById("owner_register_email_error").innerHTML="this is invalid email.";
                        return false;
                }
                else
                {
                        document.getElementById("owner_register_email").style.borderColor = "#CCC";
                        document.getElementById("owner_register_email_error").innerHTML="";
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
                        document.getElementById("owner_pwd").style.borderColor = "#E34234";
                        document.getElementById("owner_register_password_error").innerHTML="Password must contain only the letters and numbers!";
			//alert( "Password must contain only letters, number and underscores!" );
			return false;
		}
		else
		{
                        document.getElementById("owner_pwd").style.borderColor = "#CCC";
                        document.getElementById("owner_register_password_error").innerHTML="";
			return true;
		}
	}

</script>

<script>
        function validateConfirmPassword() 
        {
                var pass1 = document.getElementById("owner_pwd").value;
                var pass2 = document.getElementById("confirm_owner_pwd").value;
                var ok = true;
                if (pass1 != pass2) 
                {
                        document.getElementById("owner_register_password_confirm_error").innerHTML="this password does not match!";
                        //alert("Passwords Do not match");
                        document.getElementById("owner_pwd").style.borderColor = "#E34234";
                        document.getElementById("confirm_owner_pwd").style.borderColor = "#E34234";
                        ok = false;
                }
                else 
		{
                        //alert("Passwords Match!!!");
                        document.getElementById("owner_pwd").style.borderColor = "#CCC";
                        document.getElementById("confirm_owner_pwd").style.borderColor = "#CCC";
                        document.getElementById("owner_register_password_confirm_error").innerHTML="";
                }
                return ok;
        }
</script>

<script>
        //<!----------------------------------------------------------------------------->
	//<!-- DESCRIPTION: This is AJAX call to  owner_login.php.                     -->
	//<!--              This AJAX is called from owner_log_in form once all the    -->
        //<!--              inputs have passed the minimum qualification.              -->
	//<!--                                                                         -->
        //<!-- WHO        WHEN        WHAT                                             -->
	//<!--            06-11-15    Added encodeURIComponent()                       -->
	//<!--                                                                         -->
        //<!----------------------------------------------------------------------------->
	function validate_login() 
        {
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function() 
                {
                        //<!--This is where the returned string gets received -->
                        //<!--from the php backend and sets the value in the  -->
                        //<!-- placeholder in the html.                       -->
                        //<!--Not sure what these state and status mean.      -->
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                        {
                                if( this.responseText !== null )
					document.getElementById("login_message").innerHTML = xmlhttp.responseText;
				//else
				//	alert( "Ajax error: No data received " );
			 	if( xmlhttp.responseText == "Welcome back!" )
				{ 
			        	alert( "Welcome back" );
					window.location = ('http://54.67.1.138/owner/owner_search_front_end.php');
				}

                         }
			 //else
			 //{
			 //	alert( "AJAX error: " + this.status );
			 //}
                 }

                 //<!--Send the request to the backend php script.  -->
                 var email = document.getElementById("owner_email").value;
                 var pass1 = document.getElementById("owner_password").value;
                 var token = document.getElementById("owner_token").value;

		 //<!-- "+ &" and other characters get lost when the data is sent to the PHP file.  -->
		 //<!-- In order to preserve these characters, the following command is a must.     -->
		 //<!-- "+" character will be preserved to "%2B".  Once the data is arrived in PHP  -->
                 //<!-- it gets translated back to "+" character.                                   -->

		 var clean_token = encodeURIComponent(token);

                 str = email + "|" + pass1 + "|" + clean_token;    //<!-- 03-15-15 pass token -->

                 //alert( str ); 
                 xmlhttp.open("POST", "owner_login.php?q="+str, true);
                 xmlhttp.send();
        }
</script>

<script>
        function validate_registration() 
        {
                 //alert( "Hello" );
                 var xmlhttp = new XMLHttpRequest();
                 xmlhttp.onreadystatechange = function() 
                 {
                          //<!--This is where the returned string gets received -->
                          //<!--from the php backend and sets the value in the  -->
                          //<!-- placeholder in the html.                       -->
                          //<!--Not sure what these state and status mean.      -->
                          if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                          {
                                   document.getElementById("registration_message").innerHTML = xmlhttp.responseText;
                          }
                  }
                  <!--Send the request to the backend php script.  -->
                  var email = document.getElementById("owner_register_email").value;
                  var pass1 = document.getElementById("owner_pwd").value;
                  var first_name = document.getElementById("owner_first_name").value;
                  var last_name = document.getElementById("owner_last_name").value;
                  var token = document.getElementById("owner_register_token").value;

		  var clean_token = encodeURIComponent(token);

                  str = email + "|" + pass1 + "|" + first_name + "|" + last_name + "|" + clean_token;
                  //alert( str );
                  xmlhttp.open("POST", "owner_register.php?q="+str, true);
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
  <h3><span class="glyphicon glyphicon-heart"></span> FACILITY OWNER -</h3>
  <p>- Must register for the first time in order to manage/update your facilities. Skip the login and go directly to the register below.</p> 
  <p>- After successful registration then you can search for facilities to take ownership or add your new facilities.</p>
  <p>- After successful login then your owned facilities will be presented and ready for you to add/update.</p>
  <br>
  <br>

<h4><span class="glyphicon glyphicon-log-in"></span><b> REGISTERED OWNER - </b>Please go ahead and login ...<h4>
<form ng-submit='registerUser()' name="owner_login_form" id="owner_login_form"  method="post" class="form-horizontal" onsubmit="validate_login()" novalidate>
<!--<form ng-submit='registerUser()' name="owner_login_form" id="owner_login_form"  method="post" class="form-horizontal" onclick="validate_login()" novalidate>-->
  <div class="form-group">
    <label for="owner-id" class="col-sm-2 control-label">Login:</label>
    <div class="col-sm-4">
      <input type="email" ng-model="user.email" class="form-control" id="owner_email" name="owner_email" onblur="validateLoginEmail(this);" placeholder="email@abc.com" required>
	<span style="color:red;" id="owner_email_error" ></span>
    </div>
    <label for="owner-password" class="col-sm-2 control-label">Password:</label>
    <div class="col-sm-4">
      <input type="password" ng-model="user.password" class="form-control" id="owner_password" name="owner_password" placeholder="password" ng-minlength="6" required>

        <p
                class="help-block"
                ng-show="owner_login_form.owner_password.$error.minlength || owner_login_form.owner_password.$invalid" >  <!--This text will be displayed only if password is invalid or password length is less than 6.   $invalid is a variable name and so it $error. -->
                <small style="color:green;" >password must be at least 6 characters.</small>
        </p>

	<a href="http://54.67.1.138/owner/owner_recover_password_front_end.php" class="forgot-pwd" tabindex="4">Forgot your password?</a>
    </div>
  </div>
      <input type="hidden" name="token" id="owner_token" value="<?php echo Token::generate( "OWNER_LOGIN_FORM" ); ?>" > <!-- CSFR defense -->
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button ng-disabled="owner_login_form.$invalid" type="submit" class="btn btn-primary">Login</button>
      <span id="login_message"></span>
    </div>
  </div>
</form>
<br>
<br>
<font color="#008000">
<h4><span class="glyphicon glyphicon-registration-mark"></span><b> FIRST TIME OWNER - </b>Please follow the owner registration below ...<h4>
  <form ng-submit='registerUer()' name="owner_register_form" class="form-horizontal" method="post" onsubmit="validate_registration()" novalidate>
    <div class="form-group">
      <label for="owner-email" class="col-sm-2 control-label">Enter Email:</label>
      <div class="col-sm-4">
        <input ng-model="newUser.email" type="email" class="form-control" id="owner_register_email" name="owner_register_email" placeholder="email@abc.com" onblur="validateRegisterEmail(this);" required>
	<span style="color:red;" id="owner_register_email_error" ></span>
      </div>
      <label for="owner-pwd" class="col-sm-3 control-label">New Password:</label>
      <div class="col-sm-3">
       <input ng-model="newUser.password" type="password" class="form-control" id="owner_pwd" name="owner_pwd" placeholder="password" onblur="validatePassword(this);" ng-minlength="6" required>
		<span style="color:red;" id="owner_register_password_error" ></span>
        <p
                class="help-block"
                ng-show="owner_register_form.owner_pwd.$error.minlength || owner_register_form.owner_pwd.$invalid" >  <!--This text will be displayed only if password is invalid or password length is less than 6.   $invalid is a variable name and so it $error. -->
                <small style="color:green;" >password must be at least 6 characters.</small>
        </p>
      </div>
      <label for="owner-confirm-pwd" class="col-sm-offset-6 col-sm-3 control-label">Confirm New Password:</label>
      <div class="col-sm-3">
        <input ng-model="user_confirm.password" type="password" class="form-control" id="confirm_owner_pwd" name="confirm_owner_pwd" placeholder="confirm new password" onblur="validateConfirmPassword(this);" ng-minlength="6" required>
        <span style="color:red;" id="owner_register_password_confirm_error"></span>
      </div>
    </div>
    <div class="form-group">
      <label for="owner-first" class="col-sm-2 control-label">First:</label>
      <div class="col-sm-4">
        <input ng-model="user.text" type="text" class="form-control" id="owner_first_name" name="owner_first_name" placeholder="first" ng-minlength="2" required>
                <p
                        class="help-block"
                        ng-show="owner_register_form.owner_first_name.$error.minlength || owner_register_form.owner_first_name.$invalid" >  <!--This text will be displayed only if password is invalid or password length is less than 6.   $invalid is a variable name and so it $error. -->
                        <small style="color:green;" >at least 2 characters please.</small>
                </p>
      </div>
      <label for="owner-last" class="col-sm-2 control-label">Last:</label>
      <div class="col-sm-4">
        <input ng-model="user1.text" type="text" class="form-control" id="owner_last_name" name="owner_last_name" placeholder="last" ng-minlength=2" required>
                <p
                        class="help-block"
                        ng-show="owner_register_form.owner_last_name.$error.minlength || owner_register_form.owner_last_name.$invalid" >  <!--This text will be displayed only if password is invalid or password length is less than 6.   $invalid is a variable name and so it $error. -->
                        <small style="color:green;" >at least 2 characters please.</small>
                </p>
      </div>
    </div>
      <input type="hidden" name="owner_register_token" id="owner_register_token" value="<?php echo Token::generate( "OWNER_REGISTER_FORM" ); ?>" > <!-- CSFR defense -->
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
	<br/>

        <label class="checkbox">
                <input type="checkbox" ng-model="newUser.terms" value="1" required>
                I accept 4baboom.com's <a target="_blank" href="/home/terms">Terms of Service</a>.
        </label>
	<br/>

        <button ng-disabled="owner_register_form.$invalid" type="submit" class="btn btn-primary">Register</button>
        <span id="registration_message"></span>
      </div>
    </div>
  </form>
</font>
</div>

<!-- Owner Login or Register End -->
 <?php include ( '../common/owner_trailer' ); ?>
 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular.min.js"></script>

</body>
</html>
