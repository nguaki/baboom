<?php
/******************************************************************/
/* FILE: ConnectDB.php                                            */
/*                                                                */
/*                                                                */
/* DESCRIPTION: Connects to DB.                                   */
/*                                                                */
/*                                                                */
/*                                                                */
/*                                                                */
/*                                                                */
/*                                                                */
/* WHO      WHEN          WHAT                                    */
/*------    -------       -------------------------------------   */
/*J         01-20-15      Moved to Linux                          */
/*                        Uses persistent DB connections.         */
/*                                                                */
/*                                                                */
/******************************************************************/

function vConnectDB( $sDBName )
{
	global $mysqli;
	
	##$mysqli = new mysqli( "localhost", "root", "John0316", "test" );
	$mysqli = new mysqli( "p:localhost", "root", "bb4587", "baboom" );  // p: stands for persistent DB connection.

	// When there is too many connections,  this can lower the		
	// overhead.  However,  extra server has to be increased right away
	// because it can create DB connection error or DB table lock. (12-26-14)

	######
	# 12-12-14  Gets the error number of the connection.
	######	
	#if (mysqli_connect_errno())
	if ( $mysqli->connect_errno )
	{
		printf( "Connect failed: %s\n", $mysqli->connect_errno );
		exit();
	}
	
	#######
	# 12-12-14 Now use a database.  If the DB doesn't exists, exit out
	#          of the program.
	#######
	$bResult = $mysqli->select_db( $sDBName );
	if( $bResult == FALSE )
	{
		printf( "$sDBName database doesn't exists. \n" );
		exit();
	}
}
?>
