<?php include( 'page_header.php' );?>

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
<!-         01-22-15      Made a link to login/register client.   ->
<!-         01-22-15      Made a link to login/register owner.    ->
<!-         06-11-15      Modified some callback names.           ->
<!-                                                               ->
<!-                                                               ->
<!-                                                               ->
<!----------------------------------------------------------------->

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
      <a class="navbar-brand" href="#">4BABOOM.COM</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-4baboom-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="/index_4baboom.php">Home <span class="sr-only">(current)</span></a></li>
        <li><a href="/about.php">About Us</a></li>
        <li><a href="#">Blog</a></li>
        <li id="member-dropdown" class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Member <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li id="member-login"><a href="/member/member_login_register.php">Register or Login</a></li>
            <li id="member-profile"><a href="#">Update Profile</a></li>
            <li class="divider"></li>
            <li><a href="/member/member_search_front_end.php">Facility Search</a></li>
            <li class="divider"></li>
            <li  id="member-logout"><a href="/member/member_logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li id="owner-dropdown" class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Facility Owner <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li id="owner-login"><a href="/owner/owner_login_register.php">Register or Login</a></li>
            <li id="owner-search"><a href="/owner/owner_search_front_end.php">Search & Claim Existing Facilities</a></li>
            <li id="owner-add"><a href="/owner/owner_add_full_facility_front_end.php">Add New Facilities</a></li>
            <li id="owner-update"><a href="/owner/owner_list_facilities_owned_for_update.php">Update/Manage Your Facilities</a></li>
            <li class="divider"></li>
            <li id="owner-profile"><a href="#">Update Your Owner/Administrator Profile</a></li>
            <li class="divider"></li>
            <li  id="owner-logout"><a href="/owner/owner_logout.php">Logout</a></li>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
