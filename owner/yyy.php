<?php
#########################################################
# FILE: reset_owner_password.php
#
# DESCRIPTION : This is the front end code for login/register owner.
#
# WHO      WHEN     WHAT
#--------------------------------
#Irvine    03-13-15 Implemented CSFR prevention.
#
#########################################################
session_start();
require_once 'C:\\xampp\\htdocs\\xampp\\my_exercises\\senior_site_project\\common\\dependency.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>4BABOOM.COM - Owner Login or Register</title>
  <meta charset="utf-8>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

  <style>
    body {
      color: #0000FF;
      background-image: url("sample3.gif");
    }
  </style>

</head>

<body>

<!-- Page Header -->
<div class="row">
  <div class="col-sm-12" style="background-color:#0000FF">
    <h4><img src="file:///c:\BTT\4baboom\4baboom-logo.jpg" alt="4BABOOM LOGO" class="img-rounded" style="width:50px;height:50px"><small><span class="glyphicon glyphicon-home"></span><a href="file:///c:\BTT\4baboom\index-4baboom.html"> Senior for Senior Online Community</a></small></h4>

  </div>
</div>

<!-- Header - Navigation Bar -->
<nav class="navbar navbar-inverse" style="background-color:#191970">
  <div class="container-fluid">
    <!-- Logo and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-4baboom-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">4BABOOM.COM - OWNER</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-4baboom-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="file:///c:\BTT\4baboom\index-4baboom.html">Home <span class="sr-only">(current)</span></a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Blog</a></li>
        <li class="dropdown disabled">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Member <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Register or Login</a></li>
            <li><a href="#">Update Profile</a></li>
            <li class="divider"></li>
            <li><a href="#">Facility Search</a></li>
          </ul>
        </li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown active">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Facility Owner <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="file:///c:\BTT\4baboom\facility-owner\owner-login-register.html">Register or Login</a></li>
            <li><a href="#">Search & Claim Existing Facilities</a></li>
            <li><a href="#">Add New Facility</a></li>
            <li><a href="#">Update Facility Vacancy & Other Info</a></li>
            <li class="divider"></li>
            <li><a href="#">Update Owner Profile</a></li>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
  
<!-- Owner Login or Register Start -->

<!-- Login Form -->

<div class="container">
  <h3><span class="glyphicon glyphicon-heart"></span>Lets recover your password.-</h3>
  <br>
  <br>

<!--<h4><span class="glyphicon glyphicon-log-in"></span><b> </b>Please fill in the email...<h4> -->
<form method="post" class="form-horizontal" action="reset_owner_password.php">
  <div class="form-group">
    <h4><label for="old_password" class="col-sm-2 control-label">old password:</label><h4>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="old_password" name="old_password" placeholder="password">
    </div>
    <br>
	<br>

	<h4><label for="new_password1" class="col-sm-2 control-label">new password:</label><h4>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="new_password1" name="new_password1" placeholder="password">
    </div>
    <br>
	<br>
    
	<h4><label for="new_password2" class="col-sm-2 control-label">confirm password:</label><h4>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="new_password2" name="new_password2" placeholder="password">
    </div>
   </div>
  <div class="form-group">
    
  <div class="col-sm-offset-2 col-sm-10">
    <button type="submit" class="btn btn-primary">Login</button>
  </div>
</form>
<br>
<br>


<!-- Owner Login or Register End -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</body>
</html>
