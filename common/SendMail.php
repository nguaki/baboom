<?php

function SendActivateEmailNotice( $SafeEmail, $email_code )
{

	$msg = " Hello, \n\r\n\r " . 
			
			"\n\r\n\r\n\r
			Please activate your account by clicking below or copy
			and paste the URL to the browser.  This will complete 
			your registration \n\r\n\r

			http://54.67.1.138/owner/owner_activate_email.php?email=" . $SafeEmail . "&email_code="  .  $email_code .	
			"\n\r\n\r  
	
			Thank you, 
			
			\n\r\n\r,
			
			Baboom";

	$recipient = $SafeEmail;
	$subject = "email activation";
	$mailheaders = "From: BABOOM";


	mail( $SafeEmail, $subject, $msg, $mailheaders );


}

function SendActivateEmailNoticeForMember( $SafeEmail, $email_code )
{

	$msg = " Hello, \n\r\n\r " . 
			
			"\n\r\n\r\n\r
			Please activate your account by clicking below or copy
			and paste the URL to the browser.  This will complete 
			your registration \n\r\n\r

			http://54.67.1.138/member/member_activate_email.php?email=" . $SafeEmail . "&email_code="  .  $email_code .	
			"\n\r\n\r  
	
			Thank you, 
			
			\n\r\n\r,
			
			Baboom";

	$recipient = $SafeEmail;
	$subject = "email activation";
	$mailheaders = "From: BABOOM";


	mail( $SafeEmail, $subject, $msg, $mailheaders );


}

function SendTemporaryPWNotice( $SafeEmail, $Temp_PW )
{
	$msg = " Hello, \n\r\n\r " . 
			
			"\n\r\n\r\n\r
			Your temporary password is : "  .  $Temp_PW .
			
			"\n\r\n\r" .

			"Please sign in using this password and you will be prompted to change the password.  
			
			\n\r\n\r  
			
			Thank you, 
		
			\n\r\n\r,
			
			Baboom"
		;

	// COmposes subject, header and recipient.
	$recipient = $SafeEmail;
	$subject = "Password Recovery";
	$mailheaders = "From: BABOOM";


	mail( $SafeEmail, $subject, $msg, $mailheaders );

}


?>
