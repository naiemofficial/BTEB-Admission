<?php
session_start();
include('assets/api/educationboard.php');
include('assets/api/addressbook.php');

function httpPost($url, $data){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}


// Institute information form mySql database (For now here it's added as static)
$cmySql = array(
	133160 => array(
		'eiin' => '133160',
		'name' => 'Cumilla Polytechnic Institute',
		'shift' => array(
			'0' => null,
			'1' => array(
				666 => array(
					'base' => 'computer',
					'name' => 'Computer Technology',
					'student' => 120
				),
				665 => array(
					'base' => 'civil',
					'name' => 'Civil Technology',
					'student' => 120
				),
				667 => array(
					'base' => 'electrical',
					'name' => 'Electrical Technology',
					'student' => 120
				),
				668 => array(
					'base' => 'electronics',
					'name' => 'Electronics Technology',
					'student' => 120
				),
				670 => array(
					'base' => 'mechanical',
					'name' => 'Mechanical Technology',
					'student' => 120
				),
				671 => array(
					'base' => 'power',
					'name' => 'Power Technology',
					'student' => 120
				)
			),
			'2' => array(
				666 => array(
					'base' => 'computer',
					'name' => 'Computer Technology',
					'student' => 120
				),
				665 => array(
					'base' => 'civil',
					'name' => 'Civil Technology',
					'student' => 120
				),
				667 => array(
					'base' => 'electrical',
					'name' => 'Electrical Technology',
					'student' => 120
				),
				668 => array(
					'base' => 'electronics',
					'name' => 'Electronics Technology',
					'student' => 120
				),
				670 => array(
					'base' => 'mechanical',
					'name' => 'Mechanical Technology',
					'student' => 120
				),
				671 => array(
					'base' => 'power',
					'name' => 'Power Technology',
					'student' => 120
				)
			)
		)			
	)
);

$return = new stdClass();
// get ajax requested data
if ( isset($_POST['step']) == 'first' ) {
	// this is first step data

	$board = isset($_POST['board']) ? $_POST['board'] : '' ; // get selected board field info
	$year = isset($_POST['year']) ? $_POST['year'] : '' ; // get selected year field info
	$roll = isset($_POST['roll']) ? $_POST['roll'] : '' ; // get roll field info
	$reg = isset($_POST['reg']) ? $_POST['reg'] : '' ; // get reg field info

	$data = array(
		"fetch"=>"result",
		'exam' => 'ssc', // default set exam as ssc
		'board' => $board,
		'year' => $year,
		'roll' => $roll,
		'reg' => $reg
	);


	$url = "https://project.naiem.info/bteb/result.php";
	$student_info = json_decode(httpPost($url, $data), true);
	// $student_info = acd_student_info($data); // Pass ajax data to a function to get student information
	

	$return = $student_info;
	$student_info = (array)$student_info;
	if ( !array_key_exists('error', $student_info) ) {
		// there are no error so we store student data as PHP session. We will use this data in 3rd step
		$std_gpa = doubleval($student_info['gpa']);
		
		if( $std_gpa > doubleval(3.49) ){
			
			$std_subj = $student_info['subject'];
			
			// $std_subj[2]['grade'] = 'F';
			// $std_subj[11]['grade'] = 'F';
			for( $s=0; $s < count($std_subj); $s++ ){
				if( intval($std_subj[$s]['code']) == 109 || intval($std_subj[$s]['code']) == 126 ){
					$xgr = $std_subj[$s]['grade'];
					switch ($xgr) {
						case 'A+':
							$xgrx = 
							$xgrx[] = true;
							break;
							case 'A':
								$xgrx[] = true;
								break;
								case 'A-':
									$xgrx[] = true;
									break;
						case 'B':
							$xgrx[] = true;
							break;
							default:
							$xgrx[] = false;
							break;
					}
				}
			}
			/*$search = '109'; // 126
			$key = (string) array_search($search, array_column($std_subj, 'code'));
			$grade = ($key >= "0" && isset($std_subj[(int)$key]['grade'])) ? $std_subj[$key]['grade'] : '' ;
			
			var_dump($grade);*/

			if( in_array(true, $xgrx) ){
				$_SESSION['student_info'] = $student_info; // Store student info as session..

				$subject = $_SESSION['student_info']['subject'];
				for( $s=1; count($subject)>=$s; $s++, $px++ ){
					$indx = ($s)-1;
					$px = 0;
					$sub_code = intval($subject[$indx]['code']);
					if( $sub_code == 109 || $sub_code == 126 ){
						$_SESSION['student_info']['subject'][$indx] = $subject[$indx];
					}else{
						$_SESSION['student_info']['subject'][$indx] = null;
					}
				}
				$_SESSION['student_info']['subject'] = array_values(array_filter($_SESSION['student_info']['subject']));
				$_SESSION['student_info']['user_id'] = mt_rand();
			}else{
				$return = '';
				$return->error[] = '(General or Higher) Mathmatics GPA must be at least 3.00';
			}
		}else{
			$return = '';
			$return->error[] = 'Applicant GPA must be at least 3.50';
		}
	}
	echo json_encode($return); // Pass php data for ajax response
}elseif( isset($_POST['payment']) == 'ok' ) {
	
	//  Payment Methods
	$payment_methods = array(
		'bkash'	=> array(
			'min_amount' => 150
		), 
		'dbbl'	=> array(
			'min_amount' => 150
		), 
		'nogod'	=> array(
			'min_amount' => 150
		), 
		'tcash'	=> array(
			'min_amount' => 150
		), 
		'surecash'	=> array(
			'min_amount' => 150
		)
	);

	
	if( !isset($_POST['shift_cat']) || $_POST['shift_cat'] == 'undefined' || $_POST['shift_cat'] == '' || $_POST['shift_cat'] == null ){
		$return->error[] = 'Select shift that you want apply';
	
	}else{
		$shift_cat = isset($_POST['shift_cat']) ? $_POST['shift_cat'] : '' ;
		if( $shift_cat == 'a' || $shift_cat == 'b' || $shift_cat == 'c' ){
			if( !isset($_POST['pay_method']) || $_POST['pay_method'] == 'undefined' || $_POST['pay_method'] == '' || $_POST['pay_method'] == null ) {
				$return->error[] = 'Please select payment method.';
			}else{
				
				$pay_method = isset($_POST['pay_method']) ? $_POST['pay_method'] : '' ;
				if( array_key_exists($pay_method, $payment_methods) ){
				// Passed Paymnet method
					$passed_SMA = 'amount_error';

					// Payment amount validation
					if( !isset($_POST['amount']) || $_POST['amount'] == 'undefined' || $_POST['amount'] == '' || $_POST['amount'] == null ){
						$return->error[] = 'Please enter amount that you paid.';
					}else{
						$entered_amount = isset($_POST['amount']) ? intval($_POST['amount']) : '';
						if( is_int($entered_amount) ){
							if( $entered_amount<intval($payment_methods[$pay_method]['min_amount']) ){
								$return->error[] = 'Amount can\'t be less than '.$payment_methods[$pay_method]['min_amount'];
							}else{
								$passed_SMA = 1; // SMA = Shift, Method, Amount
							}
						}else{
							$return->error[] = 'Payment amount must be number';
						}
					}

					// Transaction ID
					if( !isset($_POST['trnx']) || $_POST['trnx'] == 'undefined' || $_POST['trnx'] == '' || $_POST['trnx'] == null ){
						$return->error[] = 'You must have to enter transaction number (TRX ID)';
					}else{
						$entered_trx = isset($_POST['trnx']) ? $_POST['trnx'] : '';
						if( $passed_SMA == 'amount_error'  ){
							// $return->error[] = 'Please enter amount that you paid.';
							// This error alreay added in payment_amount validation condition
						}elseif( $passed_SMA == 1 ){						
							if( preg_match('/^[A-Za-z0-9]+$/', $entered_trx) ){								
								$return = array(
									'success'	=> 'SUCCESS',
									'payment'	=> array(
										'shift_cat' => $shift_cat,
										'method' 	=> $pay_method,
										'amount' 	=> $entered_amount,
										'trx' 		=> $entered_amount
									),
									'student_info'	=> isset($_SESSION['student_info'])?$_SESSION['student_info']:'ok'
								);
								$_SESSION['payment'] = $return['payment'];
								
							}else{
								$return->error[] = 'Transaction ID can\'t contain special charecter without number and letter';
							}
						}else{
							$return->error[] = 'There was an error please try again later';
						}
					}
				}else{
					$return->error[] = 'Sorry! you can\'t pay with selected payment method. Please try with another method.';
				}
			}
		}else{
			$return->error[] = 'There was an error. Please refresh the page and try again';
		}		
	}
	echo json_encode($return);
}elseif( isset($_POST['upload_ap_img']) ) {
    if( count($_FILES) == 0 && !isset($return->refresh) ){
		$return->error[] = 'Please select applicant picture';
	}elseif ( isset($_FILES['applicant_img']) ) {

		// Get $_SESSION data for image name
		$user_id = isset($_SESSION['student_info']['user_id'])?$_SESSION['student_info']['user_id']:' ';
		$std_roll = isset($_SESSION['student_info']['roll'])?$_SESSION['student_info']['roll']:' ';
		$std_reg = isset($_SESSION['student_info']['reg'])?$_SESSION['student_info']['reg']:' ';

		// Image identity configure
		$ap_img = array(
			'dir'	=> 'uploads/'.date('Y').'/',
			'name'	=> basename(isset($_FILES['applicant_img']['name']) ? $user_id.'-'.$std_roll.'-'.$std_reg : ' '),
			'format'	=> strtolower(pathinfo($_FILES['applicant_img']['name'], PATHINFO_EXTENSION))
		);
		$target_file = $ap_img['dir'].''.$ap_img['name'].'.'.$ap_img['format'];

		// Check if image file is a actual image or fake image
	    $check = getimagesize($_FILES["applicant_img"]["tmp_name"]);
	    
		
	    if($check == false) {
			$return->error[] = 'Selected file is not an image.';
	    } else {
			// Check file size
			if ($_FILES["applicant_img"]["size"] > 500000 ) {
				$return->error[] = 'Sorry, your image is too large.';
			}else{
				// Allow certain file formats
				if($ap_img['format'] != "jpg" && $ap_img['format'] != "jpeg" ) {
					$return->error[] = 'Sorry, only JPG, JPEG files are allowed.';
				}elseif( !isset($return->error) ){
					if (move_uploaded_file($_FILES['applicant_img']['tmp_name' ], $target_file) ) {
						$return->success[] = 'Image Successfully Uploaded';
				        $_SESSION['student_info']['applicant_image'] = $target_file;
				        $return->image = $target_file;
				    } else {
						$return->error[] = 'Sorry, there was an error uploading your file.';
				    }
				}else{
			        $return->error[] = 'Image Not Uploaded. There was an error';
			    }
			}
	    }
		
	}elseif( !isset($return->refresh) && !isset($return->error) ){
		$return->refresh[] = 'There was an error. Please refresh the page and try again. Image';
	}
	echo json_encode($return);
}elseif( isset($_POST['next']) == 'third' ) {

	// Gender Selection - Condition pririty (1)
    if( $_POST['gender'] == 'undefined' ){
    	// Error
        $return->error[] = 'Please select gender';      
    }else{
    	if( $_POST['gender'] == 'male' || $_POST['gender'] == 'female' || $_POST['gender'] == 'other' ){

	        $genderval = isset($_POST['gender']) ? $_POST['gender'] : '';

	    	$std_info = $_SESSION['student_info'];
	    	$std_info['gender'] = $genderval;
	    	$_SESSION['student_info'] = $std_info;

	        // $return->student_info[] = $std_info;
        }else{
        	$return->refresh[] = 'There was an error. Please refresh the page and try again. Gender';
        }
    }


    // Applicant picture upload - - Condition pririty (2)
    if( count($_FILES) == 0 && !isset($return->refresh) ){
		$return->error[] = 'Please select applicant picture';
	}elseif ( isset($_FILES['applicant_img']) && !isset($return->refresh) ) {
        $return->success[] = 'Image Successfully Uploaded';
		// When image successfully upload. A session will be send for institute page
		$cur_shift_cat = isset($_SESSION['payment']['shift_cat'])?$_SESSION['payment']['shift_cat']:' ';
		$return->payment = array(
			'shift_cat' => $cur_shift_cat
		);
	}elseif( !isset($return->refresh) && !isset($return->error) ){
    	$return->refresh[] = 'There was an error. Please refresh the page and try again. Image';
	}

	// JSON Output
    echo json_encode($return);
}elseif( isset($_POST['add_choice']) == 'ok'){
 
	// $_POST Data assign into variable
	$institute = isset($_POST['institute']) ? $_POST['institute'] : '';
	$shift = isset($_POST['shift']) ? $_POST['shift'] : '';
	$technology = isset($_POST['technology']) ? $_POST['technology'] : '';
	$session_shift_cat = (isset( $_SESSION['payment']['shift_cat'])? $_SESSION['payment']['shift_cat']:'');

	// $_POST condition checking
	if( isset($_POST['institute'] ) ){
		if( $institute == 'undefined' || $institute == '' || $institute == null  ){
			$return->error[] = 'Select your desired institute';
		}elseif( array_key_exists($institute, $cmySql) ){ 	// used $cmySql - for database connection
			
				if( !isset($_POST['shift']) || $shift == 'undefined' || $shift == '' || $shift == null ){
					$return->error[] = 'Select shift';
				}elseif( array_key_exists($shift, $cmySql[$institute]['shift']) ){ 	// used $cmySql - for database connection
					switch ($session_shift_cat) {
						case 'a':
						case 'A':
							$session_shift = '1';
							break;
						case 'b':
						case 'B':
							$session_shift = '2';
							break;
						case 'c':
						case 'C':
							$session_shift = 'true';
							break;
						default:
							$session_shift = '0';
							break;
					}
					if( (($shift == '1' || $shift == '2') && $session_shift == 'true') || $shift == $session_shift ){
						if( isset($_POST['technology']) ){
							if( $technology == 'undefined' || $technology == '' || $technology == null ){
								$return->error[] = 'Select technology';
							}elseif( array_key_exists($technology, $cmySql[$institute]['shift'][$shift]) ){ 	// used $cmySql - for database connection
								if(isset($_POST['duplicate'])){
									$return->error[] = 'Duplicate Found';
								}else{
									// Passed All Errors
									$return->success[] = "SUCCESS";

									// Collect data to insert to choice list
									$return->choice_data = array(
										'eiin' => $cmySql[$institute]['eiin'],
										'name' => $cmySql[$institute]['name'],
										'trade' => $technology,
										'department' => $cmySql[$institute]['shift'][$shift][$technology]['name'],
										'shift' => $shift
									);
								}
							}else{
								$return->error[] = 'Selected technology not found';
							}
						}
					}else{
						$return->error[] = 'Sorry! You can\'t apply '.ordinal(intval($shift)).' shift';
					}
				}else{
					$return->error[] = 'Selected shift not found';
				}
		}else{
			$return->error[] = 'Selected institute EIIN doesn\'t found on database';
		}
	}
	echo json_encode($return);
}elseif( isset($_POST['choice_list'])){
	$choice_list = isset($_POST['choice_list'])?$_POST['choice_list']:'';
	$choice_data = isset($_POST['choice_list_data'])?json_decode(json_encode($_POST['choice_list_data'], true)):'';

	$cur_shift_cat = isset($_POST['cur_shift_cat'])?$_POST['cur_shift_cat']:'';
	$session_shift_cat = (isset( $_SESSION['payment']['shift_cat'])? $_SESSION['payment']['shift_cat']:'');
	$errorPassed = false;
	switch ($session_shift_cat) {
		case 'a':
		case 'A':
			$session_shift = '1';
			break;
		case 'b':
		case 'B':
			$session_shift = '2';
			break;
		case 'c':
		case 'C':
			$session_shift = 'true';
			break;
		default:
			$session_shift = '0';
			break;
	}


	if( $_POST['choice_list'] == 'confirm' ){
		$choice_list = isset($_POST['choice_list'])?$_POST['choice_list']:'';
		if( $choice_data == null || $choice_data == 'undefined' ||  $choice_data == '' ){
			$return->error[] = 'There was an critical error. Please refresh your browser and try again';
		}else{
			if( $cur_shift_cat == $session_shift ){
				// Find the object which is matching current shift
				if( $session_shift == 1 || $session_shift == 2 || $session_shift == 'true' ){
					if( array_key_exists(1, $choice_data) == null ){
						$return->error[] = 'No values found on '.ordinal(1).' shift';
					}elseif( array_key_exists(2, $choice_data) == null ){
						$return->error[] = 'No values found on '.ordinal(2).' shift';
					}else{
						$shift_count = count($choice_data);
						for( $sh=0; $shift_count>$sh; $sh++ ){
							if( $choice_data[$sh] != 'false' && $choice_data[$sh] != 'true' ){ 
							/*$choice_data contains all shifts data, it's used instead of shift data
							in this condition those strings 'true' for <thead> available 'false' for detect single shift*/
								$ins_cnt = count($choice_data[$sh]);
								if( $ins_cnt >= 5 && $ins_cnt <= 10 ){								
									for( $ins=0; $ins_cnt > $ins; $ins++ ){

										$cd_sl		= isset($choice_data[$sh][$ins]->sl)?$choice_data[$sh][$ins]->sl:'';
										$cd_eiin	= isset($choice_data[$sh][$ins]->eiin)?intval($choice_data[$sh][$ins]->eiin):'';
										$cd_trade	= isset($choice_data[$sh][$ins]->eiin)?intval($choice_data[$sh][$ins]->trade):'';
										$cd_shift	= isset($choice_data[$sh][$ins]->eiin)?intval($choice_data[$sh][$ins]->shift):'';

										if( array_key_exists($cd_eiin, $cmySql) ){
											if( (( $cd_shift == 1 || intval($cd_shift) == 2 ) && $session_shift == 'true') || intval($cd_shift) == $session_shift ){
												if( array_key_exists($cd_shift, $cmySql[$cd_eiin]['shift']) ){ 	
												// used $cmySql - for database connection
													if( array_key_exists(intval($cd_trade), $cmySql[$cd_eiin]['shift'][$cd_shift]) ){ 	
													// used $cmySql - for database connection
														
														// Applicant Choice List
														// $cd_session[$cd_shift][0] = null;
														$cd_session[$cd_shift][] = array(
															'sl' => $choice_data[$sh][$ins]->sl,
															'eiin' => $choice_data[$sh][$ins]->eiin,
															'trade' => $choice_data[$sh][$ins]->trade,
															'shift' => intval($choice_data[$sh][$ins]->shift),
															'institute' => $cmySql[$cd_eiin]['name'],
															'technology' => $cmySql[$cd_eiin]['shift'][$cd_shift][$cd_trade]['name']
														);
														$errorPassed = true;
													}else{
														$return->error[] = 'The Trade-'.$cd_trade.' from EIIN-'.$cd_eiin.' in '.ordinal($cd_shift).' shift doesn\'t exist on database';
													}
												}else{
													$return->error[] = 'Selected '.ordinal($cd_shift).' shift of EIIN-'.$cd_eiin.' doesn\'t exist';
												}
											}else{
												$return->error[] = 'You can\'t apply '.ordinal($cd_shift).' shift of EIIN-'.$cd_eiin.', Trade-'.$cd_trade;
											}
										}else{
											$return->error[] = 'The EIIN-<b>'.$cd_eiin.'</b> doesn\'t exist in database';
										}
									}
								}else{
									$return->error[] = 'You have select at least 5 and maximum 10 choice in '.ordinal($sh).' shift';
								}
							}
						}
						if( $errorPassed && empty($return->error) ){
							$return->success[] = 'SUCCESS';
							$_SESSION['choice_data'] = $cd_session;
						}
					}
				}
			}else{
				$return->error[] = 'There was a problem. Data mismatched.';
			}
		}
	}else{
		$return->error[] = 'There was an error. Please refresh this page.';
	}
	// var_dump($_POST['choice_list']);
	echo json_encode($return);
}elseif( isset($_POST['upload_quota_img']) ){
	
	$q_files_count = count($_FILES);

    if( $q_files_count == 0 ){
		$return->error[] = 'Please upload quota verification paper image / print copy';
	}elseif( isset($_POST['limit'])  ) {
		$return->error[] = 'Only one file will be accept for each selected quota';
	}elseif( isset($_FILES) && $q_files_count > 0 ) {

		for( $i=1; $q_files_count>=$i; $i++ ){
			$indx = $i-1;

			// Get $_SESSION data for image name
			$user_id = isset($_SESSION['student_info']['user_id'])?$_SESSION['student_info']['user_id']:'';
			$std_roll = isset($_SESSION['student_info']['roll'])?$_SESSION['student_info']['roll']:' ';
			$std_reg = isset($_SESSION['student_info']['reg'])?$_SESSION['student_info']['reg']:' ';

			// Image identity configure
			$qta_img = array(
				'dir'	=> 'uploads/temp/',
				'name'	=> basename(isset($_FILES[$indx]['name']) ? $user_id.'-'.$std_roll.'-'.$std_reg.'-'.time() : ' '),
				'format'	=> strtolower(pathinfo($_FILES[$indx]['name'], PATHINFO_EXTENSION))
			);


			$target_file = $qta_img['dir'].''.$qta_img['name'].'.'.$qta_img['format'];
			// Check if image file is a actual image or fake image
		    $check = getimagesize($_FILES[$indx]["tmp_name"]);

		    if($check == false) {
		    	$return->error[0] = 'One of your selected file is not an image.';
		    } else {
		        // Check file size
				if ($_FILES[$indx]["size"] > 500000 ) {
				    $return->error[0] = 'Sorry, one of your selected image is too large.';
				}else{
					// Allow certain file formats
					if($qta_img['format'] != "jpg" && $qta_img['format'] != "jpeg" ) {
					    $return->error[0] = 'Sorry, only JPG, JPEG files are allowed.';
					}elseif( !isset($return->error) ){
						if (move_uploaded_file($_FILES[$indx]['tmp_name' ], $target_file) ) {
					        $return->success[] = 'Image Successfully Uploaded';
					        $return->data[$indx] = array(
					        	'name'	=> isset($_FILES[$indx]['name'])?$_FILES[$indx]['name']:0,
					        	'size'	=> isset($_FILES[$indx]['size'])?$_FILES[$indx]['size']:0,
					        	'src'	=> $target_file
					        );
					    } else {
					        $return->error[0] = 'Sorry, there was an error uploading your file.';
					    }
					}else{
				        $return->error[0] = 'Image Not Uploaded. There was an error';
				    }
				}
		    }
		}
		
	}elseif( !isset($return->refresh) && !isset($return->error) ){
    	$return->refresh[] = 'There was an error. Please refresh the page and try again. Image';
	}
	echo json_encode($return);
}elseif( isset($_POST['delete_qtav']) ){

	$qtav_path = isset($_POST['qtav_path'])?$_POST['qtav_path']:0;

	if( file_exists($qtav_path) ){
		unlink($qtav_path);
		$return->success['delete'] = true; 
	}else{
		$return->error[] = 'The delete requested file doesn\'t exist on server';
	}
	echo json_encode($return);
}elseif( isset($_POST['register']) ){
	if( isset($_POST['minimum_files']) ){
		$return->error[] = 'You must upload one file for every selected quota';
	}else{

		$quotaOk=false; $addressOk=false; $mobileOk=false; $emailOk=false; $passOk=false;

		if( isset($_POST['quota_data']) ){
			$quota_data = isset($_POST['quota_data'])?(array)json_decode($_POST['quota_data']):'';
			$quota_data['selected'] = isset($quota_data['selected'])?((array)$quota_data['selected']):'';
			$quota_data['files'] = isset($quota_data['files'])?((array)$quota_data['files']):'';
			if( count($quota_data['selected']) == count($quota_data['files']) ){
				$quotaOk=true;
			}
		}
		

		
		// Address Information
		if( isset($_POST['address']) ){
			$address = isset($_POST['address'])?(array)json_decode($_POST['address']):'';
			if( in_array('undefined', $address) ){
				$return->error[] = 'Please select and fill all required fields';
			}else{
				if( array_key_exists($address['division'], $bd_addressbook) ){
					if( array_key_exists($address['district'], $bd_addressbook[$address['division']]) ){
						if( in_array($address['upzilla'], $bd_addressbook[$address['division']][$address['district']]) ){
							if( strlen($address['pc']) != 4 ){
								$return->error[] = 'Postal code must be 4 digits';
							}else{
								// Passed address and put into $_SESSION
								$addressOk=true;
							}
						}else{
							$return->error[] = 'Selected upzilla not doesn\'t exist on database';
						}
					}else{
						$return->error[] = 'Selected district not doesn\'t exist on database';
					}					
				}else{
					$return->error[] = 'Selected division not doesn\'t exist on database';
				}
			}
		}else{
			$return->error[0] = 'There was an error in additional information';
		}

		// Security Information
		if( isset($_POST['security']) ){
			$security = isset($_POST['security'])?(array)json_decode($_POST['security']):'';

			// Mobile
			if( $security['mobile'] == 'undefined' ){
				$return->error[] = 'Please enter applicant mobile number';
			}else{
				if( strlen(strval(intval($security['mobile']))) == 10 && strlen($security['mobile']) == 11 ){
					if( substr($security['mobile'], 0, 2) == 01 ){
						$mobileOk = true;
					}else{
						$return->error[] = 'Invalid mobile number';
					}
				}else{
					$return->error[] = 'Mobile number must be 11 digits';
				}
			}

			// E-mail
			if( $security['email'] == 'undefined' ){
				$return->error[] = 'Please enter applicant e-mail';
			}else{
				if( !filter_var($security['email'], FILTER_VALIDATE_EMAIL) ){
					$return->error[] = 'Invalid e-mail format';
				}else{
					$emailOk = true;
				}
			}

			// Password
			if( $security['pass'] == 'undefined' ){
				$return->error[] = 'Please enter password';
			}else{
				if( $security['confirm_pass'] == 'undefined' ){
					$return->error[] = 'Please enter confirm password';
				}else{
					if( $security['pass'] != $security['confirm_pass'] ){
						$return->error[] = 'Confirm password didn\'t matched';
					}else{
						if( strlen($security['confirm_pass']) < 8 ){
							$return->error[] = 'Password must be at least 8 charecter long';
						}else{
							$passOk = true;
						}
					}
				}
			}
		}else{
			$return->error[0] = 'There was an error in additional information';
		}

		// Information validate
		if( $addressOk && $mobileOk && $emailOk && $passOk ){
			if( $quotaOk ){
				$_SESSION['quota'] = $quota_data;
			}else{
				$_SESSION['quota'] = null;
			}
			$_SESSION['address'] = $address;
			$_SESSION['security'] = $security;
			$return->success[] = 'SUCCESS';
			$return->data = isset($_SESSION)?$_SESSION:'';

			

		}
	}
	echo json_encode($return);
}else {
	session_destroy();
}

























// SMS Information Start
/*$sms = array(
	'receiver'	=> 16222,
	'id'		=> 'BTAD',
	'board'		=> 'COM',
	'roll'		=> 171296,
	'pass_yr'	=> 2017,
	'reg'		=> 1411533691,
	'shift'		=> 'C',

	'amount'	=> 300,
	'trx'		=> 'ASDFGLKJH'
);*/
// SMS Information End
/*if( $_POST['payment_method'] != 'undefined' && $_POST['payment_trx'] != 'undefined' ){
	// Ajax Requested Data
	$pay_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
	$pay_trx = isset($_POST['payment_trx']) ? $_POST['payment_trx'] : ''; 

	// Add to Ajax Response
	$student_info['payment'] = $payment;
}*/



function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}




?>