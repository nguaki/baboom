<?php
/******************************************************************/
/*                                                                */
/* DESCRIPTION: Called from member_facility_search.php and        */
/*              owner_facility_search.php.                       */
/*              Constructs correct SQL statement and returns      */
/*              the SQL statement.                                */
/*                                                                */
/*                                                                */
/*                                                                */
/* WHO      WHEN          WHAT                                    */
/*------    -------       -------------------------------------   */
/*          06-20-15      Commented.                              */
/*          06-27-15      May want to use prepare statement.      */
/*                        Make sure there are right indexes.      */
/*                        See php.net/manual/en/pdo.prepared-statements.php.  */
/*                                                                */
/*                                                                */
/******************************************************************/

function sFindSearchCriteria1( $SafeCity, $SafeZipCode, &$Case )
{
		##########
		# 12-12-14
		# This is the case where County has not selected but city name was entered.
		##########
		if(  ( strlen($SafeCity) > 0 ) && ( strlen($SafeZipCode) == 0 ) )
		{
			$Case = 2;
			
			$iNumberOfOcc = substr_count( $SafeCity, "," );

			if( $iNumberOfOcc == 0 )
			{
				//$Get_Detailed_info = "SELECT * from rcfe where city = '".$SafeCity."'";
				$Get_Detailed_info = "SELECT * from rcfe where city = '$SafeCity'";
			}
			else
			{	
				$Cities = explode( "," , $SafeCity );

				$sql_string = "select * from rcfe where city = '";

				$i = 1;
				foreach( $Cities as $City )
				{
					$City = ltrim( $City );
					$City = rtrim( $City );
					
					if( $i <= $iNumberOfOcc )
					{
						$sql_string .=$City."' or city ='";
					}
					else
					{
						$sql_string .=$City."' order by city";
					}
					$i++;
				}
				$Get_Detailed_info = $sql_string;
				//echo $Get_Detailed_info;
				
			}
		}
		# This is the case where City has not selected but zipcode was entered.
		else if( ( strlen($SafeCity) == 0 ) && ( strlen($SafeZipCode) > 0 ) )
		{
			$Case = 3;
			//$Get_Detailed_info = "SELECT * from rcfe where zipcode = '".$SafeZipCode."'";
			$Get_Detailed_info = "SELECT * from rcfe where zipcode = '$SafeZipCode'";
		}
		#This is  where city AND zipcode search take place. 
		else if( ( strlen($SafeCity) > 0 ) && ( strlen($SafeZipCode) > 0 ) )
		{
			$Case = 4;
			//$Get_Detailed_info = "SELECT * from rcfe where zipcode = '".$SafeZipCode."' and city = '".$SafeCity."'";
			$Get_Detailed_info = "SELECT * from rcfe where zipcode = '$SafeZipCode' and city = '$SafeCity'";
		}
		
		return $Get_Detailed_info;
}
?>
