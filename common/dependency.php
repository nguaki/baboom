<?php
/***************************************************************************/
/* FILE: dependency.php                                                    */
/*                                                                         */
/* DESCRIPTION: This  file will be used to simplify include files( depen-  */
/*              injection).  With this file, every php files in the pro-   */
/*              ject need to include just this file once.                  */
/*                                                                         */
/* WHO       WHEN        WHAT                                              */
/* James     01-15-15    Moved from windows.                               */
/*                       Added error related lines.  These lines need to   */
/*                       turned off when it goes to the public.            */
/*                                                                         */
/*           03-15-15    Added salt.php                                    */
/*           03-20-15    Added token.php                                   */
/*           06-11-15    Modified some function names.                     */
/*                                                                         */
/*                                                                         */
/***************************************************************************/

define( 'IP_ADDRESS', "54.67.1.138" );
//$LEGIT_PASSWORD_PATTERN = '/^[A-Za-z0-9]+$/';
define( 'LEGIT_PASSWORD_PATTERN', '/^[A-Za-z0-9]+$/' );

ini_set(`display_errors`, 'off');
error_reporting(E_ALL | E_STRICT);

$ROOT = $_SERVER['DOCUMENT_ROOT']; # Apache root directory is at /xampp/htdocs. 
define('DEBUG', true);

#define( 'PROJECT_DIRECTORY', '/xampp/my_exercises/senior_site_project');
define( 'PROJECT_DIRECTORY', '/var/www/html');

define( 'APACHE_IMAGES_DIRECTORY', 'img/' );  //This is default image directory.
											   //HTML cannot sync with the directory path of C drive so
											   //it is better to follow the framework of the APACH framework.

#define( 'ABSOLUTE_IMAGES_PATH', "C:\\xampp\\htdocs\\img\\" );  //Ths is the absolute path where the images reside.
define( 'ABSOLUTE_IMAGES_PATH', "/var/www/html/img/" );  //Ths is the absolute path where the images reside.
															   //This is where APACHE wants the images to be stored.
define( 'MAX_FAC_ALLOWED', 5);
define( 'SUCCESS', 1);
define( 'FAILURE', 0);


require_once 'ConnectDB.php';
require_once 'Find_Search_criteria1.php';  

require_once 'JS_popup.php';
require_once 'salt.php';
require_once 'token.php';
require_once 'CheckLegitPW.php';
require_once 'SendMail.php';

#define ('HOME_PAGE', 'http://localhost:8080/xampp/my_exercises/senior_site_project/facility_search_main/index-4baboom.php' );
define ('HOME_PAGE', 'http://54.67.1.138/index_4baboom.php' );

define ('OWNER_LOGIN_PAGE', 'http://54.67.1.138/owner/owner_login_register.php' );
define ('OWNER_DISPLAY_PAGE', 'http://54.67.1.138/owner/DisplayDetail_for_Owner.php' );
define ('MEMBER_LOGIN_PAGE', 'http://54.67.1.138/member/member_login_register.php' );
define ('FACILITY_CLAIM_PAGE', 'http://54.67.1.138/owner/owner_claim_facility_front_end.php' );
define ('MEMBER_SEARCH_PAGE', 'http://54.67.1.138/member/member_search.php' );
define ('OWNER_SEARCH_PAGE', 'http://54.67.1.138/owner/owner_search_front_end.php' );
define ('OWNER_PASSWORD_RECOVER_PAGE', 'http://54.67.1.138/owner/owner_recover_password_front_end.php' );

define ('TEST', 1 );
define ('NUMBER_OF_FACILITIES_PER_PAGE_SEARCH', 5 );
//session_start();
?>
