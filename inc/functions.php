<?php
	function mobile_validation( $data ){
		if( $data == 'undefined' ){
			$return->error[] = 'Please enter applicant mobile number';
		}else{
			if( strlen(strval(intval($data))) == 10 && strlen($data) == 11 ){
				if( substr($data, 0, 2) == 01 ){
					$return->success[] = true;
					$return->success['data'] = $data;
				}else{
					$return->error[] = 'Invalid mobile number';
				}
			}else{
				$return->error[] = 'Mobile number must be 11 digits';
			}
		}

		return $return;
	}
?>