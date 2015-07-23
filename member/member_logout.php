<?php
session_start();
/******************************************************************/
/* FILE:                                                          */
/*                                                                */
/*                                                                */
/* DESCRIPTION: Logout member.                                    */
/*                                                                */
/*                                                                */
/*                                                                */
/*                                                                */
/*                                                                */
/*                                                                */
/* WHO      WHEN          WHAT                                    */
/*------    -------       -------------------------------------   */
/*          02-04-15      Created.                                */
/*                                                                */
/******************************************************************/

if( DEBUG )
{
	var_dump( $_SESSION );
}

session_destroy();

if( DEBUG )
{
	var_dump( $_SESSION );
}

header( "LOCATION: ../index_4baboom.php" );
?>
