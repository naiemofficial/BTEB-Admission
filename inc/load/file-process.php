<?php

$conf_file = '../config.php';
if( file_exists($conf_file) ){
	include($conf_file);
}
if( file_exists('../functions.php') ){
	include('../functions.php');
}
$docReader = false;
$PHPExcel = '../../inc/PHPExcel.php';
if( file_exists($PHPExcel) ){
	require_once $PHPExcel; 
	$docReader = true;


	function docReader($path){
		$document = PHPExcel_IOFactory::load($path);
		$activeSheetData = $document->getActiveSheet()->toArray(); //toArray(null, true, true, true);
		foreach($activeSheetData as $vals){
			$rows[] = array_filter($vals);
		}
		$rows = array_filter($rows);
		return $rows;
	}
}


	if( isset($_POST['load_data']) || isset($_POST['import_data']) ){
		if( isset($_POST['error']) ){
			$return->error[] = 'There was an critical error';
		}else{
			$return = '';
		    if( count($_FILES) == 0 && !isset($return->refresh) ){
				$return->error[] = 'Please select file';
			}else{
				$format = strtolower(pathinfo($_FILES['import_inst_file']['name'], PATHINFO_EXTENSION));
		        // Check file size
				if ($_FILES["import_inst_file"]["size"] > 1000000 ) {
				    $return->error[] = 'Sorry, your file is too large.';
				}else{
					// Allow certain file formats
					if( $format != "xls" && $format != "xlsx" ) {
					    $return->error[] = 'Sorry, only xls and xlsx files are allowed.';
					}elseif( !isset($return->error) ){
						if( $docReader && function_exists('docReader') ){
							if( isset($_POST['load_data']) ){

								$path = isset($_FILES['import_inst_file']['tmp_name'])?$_FILES['import_inst_file']['tmp_name']:'';
								$columns = isset(docReader($path)[0])?docReader($path)[0]:array();
								if( count($columns) > 0 ){
									$return->success['alert'] 	= 'SUCCESS';
									$return->success['columns']	= isset($columns)?$columns:'';
								}else{
									$return->error[] = 'No Data/Cell founds in first row';
								}
							}elseif( isset($_POST['import_data']) ){
								$inputs = isset($_POST['inputs'])?(array)json_decode($_POST['inputs']):'';
								$docarray =  docReader($_FILES['import_inst_file']['tmp_name']);
								try {
									$database = database();
									foreach ($docarray as $key => $value) {
										if( $key > 0 && function_exists('database') ){

											$r_err = '';
											// EIIN
											if( isset($value[$inputs['eiin']]) ){
												if( strlen(strval($value[$inputs['eiin']])) < 6 ){
													$res_err->$key[] = 'EIIN should be at least 6 digit';
													$r_err = $key;
												}else{
													$eiin = $value[$inputs['eiin']];
												}
											}else{
												$res_err->$key[] = 'EIIN not found';
												$r_err = $key;
											}

											// Name
											if( isset($value[$inputs['name']]) ){
												$name = $value[$inputs['name']];
											}else{
												$res_err->$key[] = 'Institute name not found';
												$r_err = $key;
											}



											// Type
											if( isset($value[$inputs['type']]) ){
												switch ($value[$inputs['type']]) {
													case 'GOVERNMENT':
													case 'Government':
													case 'government':
													case 'GOVT.':
													case 'Govt.':
													case 'govt.':
													case 'GOV':
													case 'Gov':
													case 'gov':
														$type = 'government';
														break;
													case 'PRIVATE':
													case 'Private':
													case 'private':
													case 'PVT.':
													case 'Pvt.':
													case 'pvt.':
													case 'PVT':
													case 'Pvt':
													case 'pvt':
														$type = 'private';
														break;
													default:
														$res_err->$key[] = 'Institute type should be (governement, or private)';
														$r_err = $key;
													break;
												}
											}else{
												$res_err->$key[] = 'Institute type not found';
												$r_err = $key;
											}

											// Mobile
											if( isset($value[$inputs['mobile']]) ){
												$mobile_validation = mobile_validation(strval($value[$inputs['mobile']]));
												if( property_exists($mobile_validation, 'error') ){
													$res_err->$key[] = $mobile_validation->error[0];
													$r_err = $key;
												}else{
													$mobile = $mobile_validation->success['data'];
												}
											}else{
												$res_err->$key[] = 'Mobile number shouldn\'t be empty';
												$r_err = $key;
											}


											// E-mail
											if( isset($value[$inputs['email']]) ){
												if( !filter_var($value[$inputs['email']], FILTER_VALIDATE_EMAIL) ){
													$res_err->$key[] = 'Invalid e-mail format';
													$r_err = $key;
												}else{
													$email = $value[$inputs['email']];
												}
											}else{
												$res_err->$key[] = 'E-mail not found';
												$r_err = $key;
											}


											$eiin 	= isset($eiin)?$eiin:'';
											$name 	= isset($name)?$name:'';
											$type 	= isset($type)?$type:'';
											$mobile = isset($mobile)?$mobile:'';
											$email 	= isset($email)?$email:'';
											$role	= 'institute';
											$pass	= rand(100000, 999999);


											if( isset($r_err) && $r_err == $key ){
												$r0 = isset($res_err->$key[0])?$res_err->$key[0]:'';
												$r1 = isset($res_err->$key[1])?', '.$res_err->$key[1]:'';
												$r2 = isset($res_err->$key[2])?', '.$res_err->$key[2]:'';
												$r3 = isset($res_err->$key[3])?', '.$res_err->$key[3]:'';
												$r4 = isset($res_err->$key[4])?', '.$res_err->$key[4]:'';
												$r5 = isset($res_err->$key[5])?', '.$res_err->$key[5]:'';
												$r6 = isset($res_err->$key[6])?', '.$res_err->$key[6]:'';
												$r7 = isset($res_err->$key[7])?', '.$res_err->$key[7]:'';
												$r8 = isset($res_err->$key[8])?', '.$res_err->$key[8]:'';
												$r9 = isset($res_err->$key[9])?', '.$res_err->$key[9]:'';

												$append_res = $r0.$r1.$r2.$r3.$r4.$r5.$r6.$r7.$r8.$r9;

												$return->result[] = '<div class="impt error"> <span>ROW: '.$key.'</span> '.$append_res.' </div>';
												
											}else{
												// Check primary keys exist or not
												$get_query = $database->prepare("
											    	SELECT	eiin
											    	FROM	institute_user
											    	WHERE	eiin='$eiin'
											    ");
											    $get_query->execute();
											    $result = $get_query->fetchAll();
											    if( $result ){
											    	$return->result[] = '<div class="impt error"> <span>ROW: '.$key.'</span> EIIN-'.$eiin.' already exist</div>';
											    }else{
											    	$sql = "
														INSERT INTO institute_user (eiin,	name,	  type,		mobile,		email,		role, 	password) 
														VALUES 						('$eiin', '$name', '$type', '$mobile',	'$email',	'$role', '$pass')
													";
													$database->exec($sql);
													$return->result[] = '<div class="impt success"> <span>ROW: '.$key.'</span> EIIN-'.$eiin.' data imported</div>';
											    }
											    $return->success['alert'] = 'Data imported';
											}
										}
									}
								}
								catch(PDOException $event){
									$return->error[] = 'Couldn\'t entry administration data'.' '.$event->getMessage();
								}
							}
						}
					}
				}
			}
		}
		echo json_encode($return);

		
	}



?>
