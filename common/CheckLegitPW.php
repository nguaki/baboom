<?php
function iCheckLegitPassword( $Subject )
{
	if( preg_match( LEGIT_PASSWORD_PATTERN, $Subject ) )
	{
		$match = true;
	}
	else
	{
		$match = false;
	}
	return $match;
}
?>
