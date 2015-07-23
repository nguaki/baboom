<?php

#########################################################
# FILE: token.php
#
# DESCRIPTION : The main piece to defend against Cross Site
#               Forgery Request.
#
# WHO      WHEN     WHAT
#--------------------------------
#Irvine    03-15-15 created.
#          06-17-15 Created form identifications.     
#              
#              
#              
#########################################################
class Token
{
	// Generates a token for a hidden key html tag of a form.
	// Saves the key into a session for a synchronization check.
	public static function generate( $FORM_NAME )
	{
		$Token = base64_encode(openssl_random_pseudo_bytes(32));

		if( $FORM_NAME == "OWNER_LOGIN_FORM" )
		{
			$_SESSION['owner_login_token'] = $Token;
		}
		else if( $FORM_NAME == "OWNER_REGISTER_FORM" )
		{
			$_SESSION['owner_register_token'] = $Token;
		}
		else if( $FORM_NAME == "OWNER_RESET_PW_FORM" )
		{
			$_SESSION['owner_reset_pw_token'] = $Token;
		}
		else if( $FORM_NAME == "OWNER_RECOVER_PW_FORM" )
		{
			$_SESSION['owner_recover_pw_token'] = $Token;
		}
		else if( $FORM_NAME == "MEMBER_LOGIN_FORM" )
		{
			$_SESSION['member_login_token'] = $Token;
		}
		else if( $FORM_NAME == "MEMBER_REGISTER_FORM" )
		{
			$_SESSION['member_register_token'] = $Token;
		}
		else if( $FORM_NAME == "MEMBER_RESET_PW_FORM" )
		{
			$_SESSION['member_reset_pw_token'] = $Token;
		}
		else if( $FORM_NAME == "MEMBER_RECOVER_PW_FORM" )
		{
			$_SESSION['member_recover_pw_token'] = $Token;
		}
		return $Token;
//if( DEBUG )
//if( DEBUG )
//{
//	echo "<br>" . "FROM token.php  _SESSION['token'] = " . $_SESSION['token'] . "<br>";
//}
	}
	
	// Checks if the token matches.  This is the crux of CSFR prevention.
	public static function check( $FORM_NAME, $xtoken)
	{		
		$TokenMatch = false;

		if( $FORM_NAME == "OWNER_LOGIN_FORM" )
		{
			if( isset($_SESSION['owner_login_token']) && $xtoken === $_SESSION['owner_login_token'] ) //Check the type also,not just the value.
			{
				unset( $_SESSION['owner_login_token']); //Once it is used, delete the token. Token gets generated whenever there is a refresh.
				$TokenMatch = true;
			}
		}
		else if( $FORM_NAME == "OWNER_REGISTER_FORM" )
		{
			if( isset($_SESSION['owner_register_token']) && $xtoken === $_SESSION['owner_register_token'] ) //Check the type also,not just the value.
			{
				unset( $_SESSION['owner_register_token']); //Once it is used, delete the token. Token gets generated whenever there is a refresh.
				$TokenMatch = true;
			}
		}
		else if( $FORM_NAME == "OWNER_RESET_PW_FORM" )
		{
			if( isset($_SESSION['owner_reset_pw_token']) && $xtoken === $_SESSION['owner_reset_pw_token'] ) //Check the type also,not just the value.
			{
				unset( $_SESSION['owner_reset_pw_token']); //Once it is used, delete the token. Token gets generated whenever there is a refresh.
				$TokenMatch = true;
			}
		}
		else if( $FORM_NAME == "OWNER_RECOVER_PW_FORM" )
		{
			if( isset($_SESSION['owner_recover_pw_token']) && $xtoken === $_SESSION['owner_recover_pw_token'] ) //Check the type also,not just the value.
			{
				unset( $_SESSION['owner_recover_pw_token']); //Once it is used, delete the token. Token gets generated whenever there is a refresh.
				$TokenMatch = true;
			}
		}
		else if( $FORM_NAME == "MEMBER_LOGIN_FORM" )
		{
			if( isset($_SESSION['member_login_token']) && $xtoken === $_SESSION['member_login_token'] ) //Check the type also,not just the value.
			{
				unset( $_SESSION['member_login_token']); //Once it is used, delete the token. Token gets generated whenever there is a refresh.
				$TokenMatch = true;
			}
		}
		else if( $FORM_NAME == "MEMBER_REGISTER_FORM" )
		{
			if( isset($_SESSION['member_register_token']) && $xtoken === $_SESSION['member_register_token'] ) //Check the type also,not just the value.
			{
				unset( $_SESSION['member_register_token']); //Once it is used, delete the token. Token gets generated whenever there is a refresh.
				$TokenMatch = true;
			}
		}
		else if( $FORM_NAME == "MEMBER_RESET_PW_FORM" )
		{
			if( isset($_SESSION['member_reset_pw_token']) && $xtoken === $_SESSION['member_reset_pw_token'] ) //Check the type also,not just the value.
			{
				unset( $_SESSION['member_reset_pw_token']); //Once it is used, delete the token. Token gets generated whenever there is a refresh.
				$TokenMatch = true;
			}
		}
		else if( $FORM_NAME == "MEMBER_RECOVER_PW_FORM" )
		{
			if( isset($_SESSION['member_recover_pw_token']) && $xtoken === $_SESSION['member_recover_pw_token'] ) //Check the type also,not just the value.
			{
				unset( $_SESSION['member_recover_pw_token']); //Once it is used, delete the token. Token gets generated whenever there is a refresh.
				$TokenMatch = true;
			}
		}

		return $TokenMatch;

	}
}
