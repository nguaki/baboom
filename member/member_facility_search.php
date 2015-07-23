<?php
//header('Cache-Control: no cache');     // Cannot store $SESSION
//session_cache_limiter('private_no_expire');
//session_cache_limiter('public');
session_start();

############################################
#
#  FILE NAME:client_facility_search.php
#
#  DESCRIPTION: This is the main piece of the website.
#               This is where everything begins.
#  
# WHO      WHEN      WHAT
# ----     --------  -----------
# James    12-31-14  Inserted ConnectDB.php into dependency.php
#          01-21-15  Moved to linux from windows env.  Modified require_once directory path.
#          01-24-15  Resolved re-submission problem.
#                    Resolved if there is no inputs.
#          01-31-15  Inserted license_status.
#                    Worked on pagination.
#          06-26-15  Modified script name.
#
############################################
//header('Cache-Control: no cache');     // Cannot store $SESSION
//session_cache_limiter('private_no_expire');
//session_cache_limiter('public');

require_once '../common/dependency.php';
vConnectDB( "baboom" );

#If there is just one input then proceed with the query.
#If all the inputs are empty then exit.
if(DEBUG)
{
	var_dump( $_GET );
	if( isset( $_GET['cities'], $_GET['zipcode'] ) )
	{
		echo $_GET['cities'];
		echo $_GET['zipcode'];
	}
}

if( !isset($_SESSION['cities'] ) && !isset( $_SESSION['zipcode'] ) )
{
	if( ( $_GET['cities'] == "" ) && ( $_GET['zipcode'] == "" ))
	{
		popup( "Please fill out the inputs.  Thanks!", MEMBER_SEARCH_PAGE );
	}
}

################
#prevents SQL injection
#Insert a backslash or an escape character in front of characters that can
#pose SQL injection.  A character like '.
################
if( isset( $_GET['cities'] ) || isset( $_GET['zipcode'] ) )
{
        $SafeCity = mysqli_real_escape_string( $mysqli, $_GET['cities']);
        $SafeZipCode = mysqli_real_escape_string( $mysqli, $_GET['zipcode']);
        $_SESSION['cities'] = $SafeCity;
        $_SESSION['zipcode'] = $SafeZipCode;
if(DEBUG)
{
	var_dump( $_SESSION );
}
	//exit;
}
else
{
        if( isset($_SESSION['cities'] ) )
                $SafeCity = $_SESSION['cities'];

        if( isset( $_SESSION['zipcode'] ) )
                $SafeZipCode = $_SESSION['zipcode'];
if(DEBUG)
{
	echo $SafeCity;
	var_dump( $_SESSION );
}
	//exit;
}

//$SafeCity = mysqli_real_escape_string( $mysqli, $_GET['cities']);
//$SafeZipCode = mysqli_real_escape_string( $mysqli, $_GET['zipcode']);

$Case = 0;
$Get_Detailed_info = sFindSearchCriteria1( $SafeCity, $SafeZipCode, $Case );
		
#echo "Running this query <br>";
#cho "$Get_Detailed_info <br>";

if( $res = $mysqli->query( $Get_Detailed_info ) )
{
	if( $Case == 1 )
	{
		$display_block = "<h1>Search Result for " .$SafeCounty. "</h1>";
	}
	else if( $Case == 2 )
	{
		$display_block = "<h1>Search Result for " .$SafeCity. "</h1>";
	}
	else if( $Case == 3 )
	{
		$display_block = "<h1>Search Result for " .$SafeZipCode. "</h1>";
	}
	else if( $Case == 4 )
	{
		$display_block = "<h1>Search Result for " .$SafeZipCode. " and " .$SafeCity. "</h1>";
	}
	        $rows = mysqli_num_rows($res);

if(DEBUG)
{
        echo $rows;
}

        $page_rows = NUMBER_OF_FACILITIES_PER_PAGE_SEARCH;
        // This tells us the page number of our last page
        $last = ceil($rows/$page_rows);
        // This makes sure $last cannot be less than 1
        if($last < 1){
                $last = 1;
        }
        // Establish the $pagenum variable
        $pagenum = 1;
        // Get pagenum from URL vars if it is present, else it is = 1
        if(isset($_GET['pn'])){
                $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
        }
        // This makes sure the page number isn't below 1, or more than our $last page
        if ($pagenum < 1) {
                $pagenum = 1;
        } else if ($pagenum > $last) {
                $pagenum = $last;
        }
        // This sets the range of rows to query for the chosen $pagenum
        $limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;

        // This is your query again, it is for grabbing just one page worth of rows by applying $limit

        $New_Query = $Get_Detailed_info . " " . $limit;

if(DEBUG)
{
        echo $New_Query;
}

        if( $res1 = $mysqli->query( $New_Query ) )
        {
                // This shows the user what page they are on, and the total number of pages
                //$textline1 = "Testimonials (<b>$rows</b>)";
                $textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
                // Establish the $paginationCtrls variable
                $paginationCtrls = '';
                // If there is more than 1 page worth of results
                if($last != 1)
                {
                        /* First we check if we are on page one. If we are then we don't need a link to
                           the previous page or the first page so we do nothing. If we aren't then we
                           generate links to the first page, and to the previous page. */
                        if ($pagenum > 1) {
                                $previous = $pagenum - 1;
                                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
                                // Render clickable number links that should appear on the left of the target page number
                                for($i = $pagenum-4; $i < $pagenum; $i++){
                                        if($i > 0){
                                                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
                                        }
                                }
                        }
                        // Render the target page number, but without it being a link
                        $paginationCtrls .= ''.$pagenum.' &nbsp; ';
                        // Render clickable number links that should appear on the right of the target page number
                        for($i = $pagenum+1; $i <= $last; $i++){
                                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
                                if($i >= $pagenum+4){
                                        break;
                                }
                        }
  
                        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                        if ($pagenum != $last) {
                                $next = $pagenum + 1;
                                $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'">Next</a> ';
                        }
                }

                $Display_block = '';

		
		while( $newArray= $res1->fetch_array( MYSQLI_ASSOC ) )
		{
			$display_block .= "<p><strong>Address:</strong><br/><ul>";
			
			$facility_type = stripslashes($newArray['facility_type']);
			$facility_number = stripslashes($newArray['facility_number']);
			$facility_name = stripslashes($newArray['facility_name']);
			$licensee = stripslashes($newArray['licensee']);
			$license_status = stripslashes($newArray['license_status']);
			$administrator = stripslashes($newArray['administrator']);
			$telephone = stripslashes($newArray['telephone']);
			$address = stripslashes($newArray['address']);
			$city = stripslashes($newArray['city']);
			$state = stripslashes($newArray['state']);
			$zipcode = stripslashes($newArray['zipcode']);
			$county = stripslashes($newArray['county']);
			$facility_cap = stripslashes($newArray['facility_cap']);
			$shared_available = stripslashes($newArray['shared_available']);
			$semi_private_available = stripslashes($newArray['semi_private_available']);
			$private_available = stripslashes($newArray['private_available']);
				
				
			$display_block .= "<li>$facility_name</li>";
			$display_block .= "<li>$facility_type</li>";
			// $display_block .= "<li>$address $city</li>";
			$display_block .= "<li>$city</li>";
                        $display_block .= "<li>$state $zipcode</li>";

			
			$urlcode_facility = urlencode($facility_number);
			$urlcode_state = urlencode($state);
if(DEBUG)
{				
		echo $urlcode_facility;
		echo $urlcode_state;
}		
			$urlcode_facility_name = urlencode($facility_name);
			$urlcode_address = urlencode($address);
			$urlcode_city = urlencode($city);
			$urlcode_zipcode = urlencode($zipcode);
			$urlcode_telephone = urlencode($telephone);
			$urlcode_licensee = urlencode($licensee);
			$urlcode_facility_type = urlencode($facility_type);
			$urlcode_facility_cap = urlencode($facility_cap);
			$urlcode_license_status = urlencode($license_status);
			$urlcode_shared_available = urlencode($shared_available);
			$urlcode_semi_private_available = urlencode($semi_private_available);
			$urlcode_private_available = urlencode($private_available);

			$display_block .= "<a href=../search/DisplayDetail_for_Client.php?facility_name=$urlcode_facility_name&address=$urlcode_address&facility_num=$urlcode_facility&city=$urlcode_city&state=$urlcode_state&zipcode=$urlcode_zipcode&telephone=$urlcode_telephone&licensee=$urlcode_licensee&facility_type=$urlcode_facility_type&facility_cap=$urlcode_facility_cap&status=$urlcode_license_status&shared_avail=$urlcode_shared_available&semi_private_avail=$urlcode_semi_private_available&private_avail=$urlcode_private_available>More Info</a></ul>";
		}
		$res1->free_result();
	}
	$res->free_result();
	// $mysqli->close();     //This is persistent DB pool.  DB management will be automatically taken care.
                                // Close() function will make performance to go down.
}	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>4BABOOM.COM - Client Facility Search</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/client.css">
	<?php include( '../common/header.php' );?> <!--worked -->
        <style type="text/css">
                body{ font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;}
                div#pagination_controls{font-size:21px;}
                div#pagination_controls > a{ color:#06F; }
                div#pagination_controls > a:visited{ color:#06F; }
        </style>
</head>
<body>
	<div>
		<?php include( '../common/member_navigation.php' );?> 
	</div>
	<!-- need it for first lines that gets cut off by the navigation bar. -->
	<p><?php echo $textline2; ?></p>
	<?php echo $display_block; ?>
        <div id="pagination_controls"><?php echo $paginationCtrls; ?></div>
	<?php include ( '../common/member_trailer' ); ?>
</body>
</html>
