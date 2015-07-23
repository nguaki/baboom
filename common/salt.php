<?php
////////////////////////////////////////////////////////////////////////////////////////////
//  DESCRIPTION:  These 2 functions play critical roles on keeping passwords in the DB.
//                Further studies should be done to pick out the best cryption methods.
//
//  WHO       WHEN      WHAT
//            06-11-15  mcrypt_create_iv() stored in binary so it is very hard to debug.
//
////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////
//
//  DESCRIPTION : Random generation.  This will be saved in the database.
//                Not sure if this is good idea.  Need more research.
//
//  WHO        WHEN      WHAT
//             06-11-15  Removed mcrypt_create_iv() since it generated binary number.
//                       Had a serious problem of not able to login the usrs that
//                       are legitimate registered users.
////////////////////////////////////////////////////////////////////////////////////////////

function salt()
{
	//return mcrypt_create_iv( 32 );

	$string = md5(uniqid(rand(), true));
	return substr( $string, 0, 3 );
}

////////////////////////////////////////////////////////////////////////////////////////////
//
//  DESCRIPTION : Generates a long string based on password and randomly generated 'salt'.
//
//  WHO        WHEN     WHAT
//             06-11-15 Fixed the 2nd parameter to concatenation.
//
////////////////////////////////////////////////////////////////////////////////////////////
function getHash( $password, $salt = '' )
{
	return hash( 'sha256', $password . $salt );
}

/*
$salt = salt();
$hash = getHash( 'John0316', $salt );
$DBPassword = $hash;
echo $salt . '<br>';
echo $hash;


$new_hash = getHash( 'John0316', $salt );

if( $new_hash == $DBPassword )
{
	echo "MATCH";
}
else
{
	echo "No Match";
}
*/
?>
