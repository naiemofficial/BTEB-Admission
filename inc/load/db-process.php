<?php
session_start();
$conf_file = '../config.php';
if( file_exists($conf_file) ){
	include($conf_file);
}
if( file_exists('../functions.php') ){
	include('../functions.php');
}
$dbn_con = 'Sorry! Couldn\'t connect with database. Maybe database didn\'t confgured yet. ';



$return = new stdClass();
if( isset($_POST['action']) ){
	$db_action = isset($_POST['action'])?$_POST['action']:'';
	if( $db_action == 'create_config' ){
		if( !file_exists($conf_file) ){
			fopen($conf_file, "wb") or die("Unable to open file!");
			$return->success[] = 'New "config.php" file created ';
		}
		
		$file_data = implode(preg_grep("/(?=.*define).*/",file($conf_file), PREG_GREP_INVERT ));
		$content = "<?php\n\tdefine('SERVER', 'localhost');\n\tdefine('DB_NAME', 'database_name');\n\tdefine('DB_USER', 'database_user');\n\tdefine('DB_PASS', 'database_password');\n\n\tif( defined('SERVER') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS') ){\n\t\tfunction database(){"."\n\t\t\t".'$server = SERVER;'."\n\t\t\t".'$name = DB_NAME;'."\n\t\t\t".'$user = DB_USER;'."\n\t\t\t".'$pass = DB_PASS;'."\n\t\t\t".'$database = new PDO("mysql:host=$server;dbname=$name", $user, $pass);'."\n\t\t\t".'$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);'."\n\t\t\t".'return $database;'."\n\t\t".'}'."\n\t}\n?>";
		file_put_contents($conf_file, $content);
		$return->success[] = 'Succesfully writes configuration codes in '.$conf_file.'. Datebase file is ready to configure';
		
	}else{
		$return->error[] = 'There was an error. Please refresh the page.';
	}
	echo json_encode($return);
}elseif( isset($_POST['db_config']) ){
	$server	= isset($_POST['hostname'])?$_POST['hostname']:'localhost';
	$db_name	= isset($_POST['db_name'])?$_POST['db_name']:'';
	$db_action	= isset($_POST['db_action'])?$_POST['db_action']:'';
	$db_user	= isset($_POST['db_user'])?$_POST['db_user']:'root';
	$db_pass	= isset($_POST['db_pass'])?$_POST['db_pass']:'';

	$db = array(
		'server' => $server,
		'name' => $db_name,
		'user' => $db_user,
		'pass' => $db_pass,
	);
	

	if( $server == 'undefined'  ){
		$return->error[] = 'Enter server hostname';
	}else{
		if( $db_name == 'undefined' ){
			$return->error[] = 'Enter database name';
		}else{
			if( $db_user == 'undefined'  ){
				$return->error[] = 'Enter database user';
			}else{
				if( $db_user != 'root' && $db_pass == 'undefined' ){
					$return->error[] = 'Password can\'t be blank without "root user"';
				}elseif( $db_user == 'root' && $db_pass == 'undefined' ){
					$db_pass = '';
				}
				if( !property_exists($return, 'error') ){
					function database_configure($db){
						$conf_file = '../config.php';
						if( file_exists($conf_file) ){
							if( !SERVER ){
								$return->error[] = 'constant("SERVER") defined blank/null. Before cofigure DB make sure this line is exist in config.php file -> define(\'SERVER\', \'localhost\');';
							} else{
								if( !DB_NAME ){
									$return->error[] = 'constant("DB_NAME") defined blank/null. Before cofigure DB make sure this line is exist in config.php file -> define(\'DB_NAME\', \'database_name\');';
								}elseif( DB_NAME != 'database_name' ){
									$return->error[] = 'Databese already configured with "'.DB_NAME.'" ';
								}else{
									if( DB_USER != 'database_user' ){
										$return->error[] = 'constant("DB_USER") doesn\'t defined value "database_user" in config.php file. To configure click on help';
									}else{
										if( DB_PASS != 'database_password' ){
											$return->error[] = 'constant("DB_PASS") doesn\'t defined value "database_password" in config.php file';
										}else{	
											$db_file_contents = file_get_contents($conf_file); 
											$content = str_replace('localhost', $db['server'], $db_file_contents);
											file_put_contents($conf_file, $content);

											$db_file_contents = file_get_contents($conf_file); 
											$content = str_replace('database_name', $db['name'], $db_file_contents);
											file_put_contents($conf_file, $content);

											$db_file_contents = file_get_contents($conf_file); 
											$content = str_replace('database_user', $db['user'], $db_file_contents);
											file_put_contents($conf_file, $content);

											$db_file_contents = file_get_contents($conf_file); 
											$content = str_replace('database_password', $db['pass'], $db_file_contents);
											file_put_contents($conf_file, $content);

											$return->success[] = 'Database successfully configured';
										}
									}
								}
							}
						}else{
							$return->error[] = 'Database configuration file (config.php) doesn\'t exist';
						}
						return $return;
					}

					
					if( $db_action == 'connect' ){
						try {
							$db_connection = new PDO("mysql:host=$server;dbname=$db_name", $db_user, $db_pass);
							$db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$return->success[] = database_configure($db);
						}
						catch(PDOException $event){
							$return->error[] = 'Database connection faild. '.$event->getMessage();
						}
						$db_connection = null;
					}elseif( $db_action == 'create' ){
						try {
							$db_connection = new PDO("mysql:host=$server", $db_user, $db_pass);
							$db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

							$sql = "CREATE DATABASE $db_name";
							$db_connection->exec($sql);
							$return->success[] = database_configure($db);
							$return->success[] = 'New database successfully created ';
						}
						catch(PDOException $event){
							$return->error[] = 'Database configuration faild. '.$event->getMessage();
						}
						$db_connection = null;
					}
					
				}else{
					$return->error[] = 'There was an critical error. Please contact with developer to resolve it.';
				}
			}
		}
	}
	echo json_encode($return);
}elseif( isset($_POST['admin_sign_up']) ){
	$submit_action	= isset($_POST['submit_action'])?$_POST['submit_action']:'';
	$username	= isset($_POST['username'])?$_POST['username']:'';
	$role 		= isset($_POST['role'])?serialize([$_POST['role']]):'';
	$name 		= isset($_POST['name'])?$_POST['name']:'';
	$mobile 	= isset($_POST['mobile'])?$_POST['mobile']:'';
	$email 		= isset($_POST['email'])?$_POST['email']:'';
	$pass 		= isset($_POST['pass'])?$_POST['pass']:'';
	$confirm_pass = isset($_POST['confirm_pass'])?$_POST['confirm_pass']:'';


	if( $username == 'undefined' ){
		$return->error[] = 'Please enter username ';
	}else{
		if( $name == 'undefined' ){
			$return->error[] = 'Please enter '.$username.' full name ';
		}else{
			$mobile_validation = new stdClass();
			if( $submit_action == 'dbadmin' ){
				$mobile_validation->success[] = true;
			}elseif( $submit_action == 'add_admin_user' ){
				$mobile_validation = mobile_validation($mobile);
			}
			if( property_exists($mobile_validation, 'error') ){
				$return->error[] = $mobile_validation['error'];
			}else{
				if( $email == 'undefined' ){
					$return->error[] = 'Please enter an e-mail';
				}else{
					if( !filter_var($email, FILTER_VALIDATE_EMAIL) ){
						$return->error[] = 'Invalid e-mail format';
					}else{
						// Password
						if( $pass == 'undefined' ){
							$return->error[] = 'Please enter password';
						}else{
							if( $confirm_pass == 'undefined' ){
								$return->error[] = 'Please enter confirm password';
							}else{
								if( $pass != $confirm_pass ){
									$return->error[] = 'Confirm password didn\'t matched';
								}else{
									if( strlen($confirm_pass) < 8 ){
										$return->error[] = 'Password must be at least 8 charecter long';
									}else{
										$confirm_pass = $confirm_pass; // Encode Password
										if( function_exists('database') ){

											if( $submit_action == 'dbadmin' ){
												$table_name = 'user_maintenance';
												try {
													$database = database();
													$database->beginTransaction();
													$sql = "CREATE TABLE $table_name (
														id			INT(6)			UNSIGNED AUTO_INCREMENT,
														role		VARCHAR(500) 	NOT NULL,
														username	VARCHAR(30) 	NOT NULL,
														mobile		VARCHAR(50) 		NULL,
														name		VARCHAR(50) 	NOT NULL,
														email		VARCHAR(50),
														password	VARCHAR(50),
														entry_time	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
														PRIMARY KEY(id, username)
													)";
													$database->exec($sql);

													$sql = "CREATE TABLE institute_user (
														id			INT(6)			UNSIGNED AUTO_INCREMENT,
														eiin		VARCHAR(8) 		NOT NULL,
														name		VARCHAR(200) 	NOT NULL,
														type		VARCHAR(200) 	NOT NULL,
														slogan		VARCHAR(200) 		NULL,
														estd		VARCHAR(5) 			NULL,
														logo		VARCHAR(500) 		NULL,
														address		VARCHAR(1000) 		NULL,
														mobile		VARCHAR(11) 	NOT NULL,
														email		VARCHAR(80) 	NOT NULL,
														role		VARCHAR(20) 	NOT NULL,
														password	VARCHAR(50) 	NOT NULL,
														entry_time	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
														PRIMARY KEY(id, eiin)
													)";
													$database->exec($sql);

													$sql = "
														CREATE TABLE applicants (
															id				INT(8)	UNSIGNED AUTO_INCREMENT,
															user_id			VARCHAR(16)		,
															reg				VARCHAR(10)		,
															name			VARCHAR(60)		,
															gender			VARCHAR(10)		,
															date_of_birth	VARCHAR(60)		,
															father			VARCHAR(60)		,
															mother			VARCHAR(60)		,
															address			VARCHAR(250)	,
															mobile			VARCHAR(15)		,
															email			VARCHAR(60)		,
															password		VARCHAR(48)		,
															applicant_image	VARCHAR(300)	,
															entry_time	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
															PRIMARY KEY (id, user_id, reg)
														)
													";
													$database->exec($sql);

													$sql = "
														CREATE TABLE applicants_exam_data (
															id				INT(8)	UNSIGNED AUTO_INCREMENT,
															user_id			VARCHAR(16)		,
															exam			VARCHAR(5)		,
															board			VARCHAR(60)		,
															year			VARCHAR(60)		,
															roll			VARCHAR(10)		,
															reg				VARCHAR(10)		,
															gpa				VARCHAR(5)		,
															institute		VARCHAR(100)	,
															subjects		VARCHAR(200)	,
															entry_time	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
															PRIMARY KEY (id, user_id, reg)
														)
													";
													$database->exec($sql);


													$sql = "
														CREATE TABLE applications_data (
															id				INT(8)	UNSIGNED AUTO_INCREMENT,
															user_id			VARCHAR(16)		,
															reg				VARCHAR(10)		,
															pay_cat			VARCHAR(2)		,
															pay_method		VARCHAR(10)		,
															pay_amount		VARCHAR(6)		,
															pay_trx			VARCHAR(15)		,
															quotas			VARCHAR(1000)	,
															choice_first_shift VARCHAR(900)	,
															choice_second_shift VARCHAR(900) ,
															entry_time	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
															PRIMARY KEY (id, user_id, reg)
														)
													";
													$database->exec($sql);

													$return->success[] = 'Database is ready to use';
													$database->commit();											
												} catch(PDOException $event){								
													// $return->error[] = 'Error: '.$event->getMessage();
												}

												if( property_exists($return, 'success') || isset($return->success)){
													$sql = "
													INSERT INTO $table_name (role,		username,		name,		email,		password) 
													VALUES 					('$role',	'$username',	'$name',	'$email',	'$confirm_pass')
													";
													try {
														$database = database();
														$database->exec($sql);
														$return->success[] = 'Administration data has been successfully recoreded';
													}
													catch(PDOException $event){
														$return->error[] = 'Couldn\'t entry administration data'.' '.$event->getMessage();
													}
												}
											}elseif( $submit_action = 'add_admin_user' ){
												if($username=='admin'){
													$return->error[] = 'The username '.$username.' isn\'t allowed.';
												}else{
													$cookie = isset($_COOKIE['user_login'])?unserialize($_COOKIE['user_login']):'';
													if( isset($cookie['auth']) == TRUE && isset($cookie['status']) == 'LOGGED' ){

														try {
															$database = database();
															$database->beginTransaction();
																$query = $database->prepare("
															    	SELECT	username
															    	FROM	user_maintenance
															    	WHERE	username='$username'
															    ");
															    $query->execute();
															    $result = $query->fetchAll();
															    if( $result ){
															    	$return->error[] = 'Username already exist';
															    	$usernameOK = false;
															    }else{
															    	$usernameOK = true;
															    }
																$query = $database->prepare("
															    	SELECT	email
															    	FROM	user_maintenance
															    	WHERE	email='$email'
															    ");
															    $query->execute();
															    $result = $query->fetchAll();
															    if( $result ){
															    	$return->error[] = 'Email already exist';
															    	$emailOk = false;
															    }else{
															    	$emailOk = true;
															    }
															    
															    if( $usernameOK && $emailOk ){
																	$sql = "
																		INSERT INTO user_maintenance (role,		username,		name,		mobile,		email,		password) 
																		VALUES 						('$role',	'$username',	'$name',	'$mobile',	'$email',	'$confirm_pass')
																	";
																	$database->exec($sql);
																	$return->success[] = 'Succesfully added admin';
															    }

														    $database->commit();
														 }
														catch(PDOException $event){
															$database->rollback();
															$return->error[] = 'Error: '.$event->getMessage();
														}
													}else{
														$return->error[] = 'You must login first. Please refresh the page.';
													}
												}
											}
										}else{
											$return->error[] = $dbn_con;
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	echo json_encode($return);
}elseif( isset($_POST['confirm_application']) ){
	if( isset($_POST['agree_false']) ){
		$return->error['agree'] = 'You must be agree with rules';
	}else{

			$m_dir = $_SERVER['DOCUMENT_ROOT'].'/bteb/';
			$applicant_image_loc = $m_dir.$_SESSION['student_info']['applicant_image'];

		
			// 'dir'	=> 'uploads/'.date('Y').'/',
			$ap_dir		= $m_dir.'userfiles/applicant_images/';
			$ap_name	= basename($applicant_image_loc);

			$target_file = $ap_dir.$ap_name;
		
			$_SESSION['student_info']['applicant_image'] = $ap_name;

		if (rename($applicant_image_loc, $target_file) ) {

			if( isset($_SESSION['choice_data'], $_SESSION['choice_data'][1]) || isset($_SESSION['choice_data'], $_SESSION['choice_data'][2]) ){
				$cd_datax = isset($_SESSION['choice_data'])?$_SESSION['choice_data']:'';
				if( count($cd_datax)>=1 ){
					for( $sh_i=1; 2>=$sh_i; $sh_i++ ){
						if( array_key_exists($sh_i, $cd_datax) ){
							for( $sl_i =1; count($cd_datax[$sh_i])>=$sl_i; $sl_i++  ){
								unset($cd_datax[$sh_i][$sl_i-1]['institute']);
								unset($cd_datax[$sh_i][$sl_i-1]['technology']);
							}
						}
					}
				}
				$_SESSION['choice_data']= $cd_datax;
			}
			// Table Applicants
				$user_id	= isset($_SESSION['student_info']['user_id'])?$_SESSION['student_info']['user_id']:null;
				$reg		= isset($_SESSION['student_info']['reg'])?$_SESSION['student_info']['reg']:null;
				$name 		= isset($_SESSION['student_info']['name'])?$_SESSION['student_info']['name']:null;
				$gender 	= isset($_SESSION['student_info']['gender'])?$_SESSION['student_info']['gender']:null;
				$dob 		= isset($_SESSION['student_info']['dob'])?$_SESSION['student_info']['dob']:null;
				$father 	= isset($_SESSION['student_info']['father'])?$_SESSION['student_info']['father']:null;
				$mother 	= isset($_SESSION['student_info']['mother'])?$_SESSION['student_info']['mother']:null;
				$address 	= isset($_SESSION['address'])?serialize($_SESSION['address']):null;
				$mobile 	= isset($_SESSION['security']['mobile'])?$_SESSION['security']['mobile']:null;
				$email 		= isset($_SESSION['security']['email'])?$_SESSION['security']['email']:null;
				$password 	= isset($_SESSION['security']['confirm_pass'])?sha1($_SESSION['security']['confirm_pass']):null;
				$image 		= isset($_SESSION['student_info']['applicant_image'])?$_SESSION['student_info']['applicant_image']:null;

			// Table applicants_exam_data
				$exam		= isset($_SESSION['student_info']['exam'])?$_SESSION['student_info']['exam']:null;
				$board		= isset($_SESSION['student_info']['board'])?$_SESSION['student_info']['board']:null;
				$year		= isset($_SESSION['student_info']['year'])?$_SESSION['student_info']['year']:null;
				$roll		= isset($_SESSION['student_info']['roll'])?$_SESSION['student_info']['roll']:null;
				$reg		= isset($_SESSION['student_info']['reg'])?$_SESSION['student_info']['reg']:null;
				$gpa		= isset($_SESSION['student_info']['gpa'])?$_SESSION['student_info']['gpa']:null;
				$institute	= isset($_SESSION['student_info']['institute'])?$_SESSION['student_info']['institute']:null;
				$subjects	= isset($_SESSION['student_info']['subject'])?serialize($_SESSION['student_info']['subject']):null;

			// Applications Data 
				$pay_cat 		= isset($_SESSION['payment']['shift_cat'])?$_SESSION['payment']['shift_cat']:null;
				$pay_method		= isset($_SESSION['payment']['method'])?$_SESSION['payment']['method']:null;
				$pay_amount		= isset($_SESSION['payment']['amount'])?$_SESSION['payment']['amount']:null;
				$pay_trx 		= isset($_SESSION['payment']['trx'])?$_SESSION['payment']['trx']:null;
				$quotas			= isset($_SESSION['quota'])?$_SESSION['quota']:null;
				$choice_first_shift		= null;
				$choice_second_shift	= null;
			
			if( isset($_SESSION['choice_data'], $_SESSION['choice_data'][1]) ){
				$choice_first_shift 	= serialize($_SESSION['choice_data'][1]);
			}
			if( isset($_SESSION['choice_data'], $_SESSION['choice_data'][2]) ){
				$choice_second_shift	= serialize($_SESSION['choice_data'][2]);
			}
			try{
				$database = database();
				$database->beginTransaction();


					$sql = $database->prepare("
						INSERT INTO applicants	(user_id, 	reg, 	name,	gender, date_of_birth, father, mother, address, mobile, email, password, applicant_image) 
						VALUES (:user_id, :reg, :name, :gender, :date_of_birth, :father, :mother, :address, :mobile, :email, :password, :applicant_image)");
					$sql->bindParam(':user_id', $user_id);
					$sql->bindParam(':reg', $reg);
					$sql->bindParam(':name', $name);
					$sql->bindParam(':gender', $gender);
					$sql->bindParam(':date_of_birth', $dob);
					$sql->bindParam(':father', $father);
					$sql->bindParam(':mother', $mother);
					$sql->bindParam(':address', $address);
					$sql->bindParam(':mobile', $mobile);
					$sql->bindParam(':email', $email);
					$sql->bindParam(':password', $password);
					$sql->bindParam(':applicant_image', $image);
					$sql->execute();



					$sql = $database->prepare("
						INSERT INTO applicants_exam_data (user_id, 	exam, 	board,	year, roll, reg, gpa, institute, subjects) 
						VALUES (:user_id, :exam, :board, :year, :roll, :reg, :gpa, :institute, :subjects)");
					$sql->bindParam(':user_id', $user_id);
					$sql->bindParam(':exam', $exam);
					$sql->bindParam(':board', $board);
					$sql->bindParam(':year', $year);
					$sql->bindParam(':roll', $roll);
					$sql->bindParam(':reg', $reg);
					$sql->bindParam(':gpa', $gpa);
					$sql->bindParam(':institute', $institute);
					$sql->bindParam(':subjects', $subjects);
					$sql->execute();



					$sql = $database->prepare("
						INSERT INTO applications_data (user_id, 	reg, 	pay_cat,	pay_method, pay_amount, pay_trx, quotas, choice_first_shift, choice_second_shift) 
						VALUES (:user_id, :reg, :pay_cat, :pay_method, :pay_amount, :pay_trx, :quotas, :choice_first_shift, :choice_second_shift)");
					$sql->bindParam(':user_id', $user_id);
					$sql->bindParam(':reg', $reg);
					$sql->bindParam(':pay_cat', $pay_cat);
					$sql->bindParam(':pay_method', $pay_method);
					$sql->bindParam(':pay_amount', $pay_amount);
					$sql->bindParam(':pay_trx', $pay_trx);
					$sql->bindParam(':quotas', $quotas);
					$sql->bindParam(':choice_first_shift', $choice_first_shift);
					$sql->bindParam(':choice_second_shift', $choice_second_shift);
					$sql->execute();


				$database->commit();
				$return->succes[] = 'Data Entry Succesfully';
			}
			catch( PDOException $event ){
				// $database->rollback();
				$return->error[] = 'Error: '.$event->getMessage();
			}
			$database = null;
		}else{
			$return->error[] = 'There was a problem';
		}
	}
	echo json_encode($return);
}elseif( isset($_POST['maintenance_login']) ){
	$ssid = isset($_POST['SESSID'])?$_POST['SESSID']:'';
	$phpssid = isset($_COOKIE['PHPSESSID'])?$_COOKIE['PHPSESSID']:'';

	if( $ssid == $phpssid ){
		$username = isset($_POST['username'])?$_POST['username']:'';
		$password = isset($_POST['password'])?$_POST['password']:'';
		if( $username == 'undefined' ){
			$return->error[] = 'Please enter username';
		}
		if( $password == 'undefined' ){
			$return->error[] = 'Please enter password';
		}
		if( $username != 'undefined' && $password != 'undefined' ){
			// Login Check
			try {
				$database = database();
				$query = $database->prepare("
			    	SELECT	*
			    	FROM	user_maintenance
			    	WHERE	username='$username' AND password='$password'
			    ");
			    $query->execute();
			    $result = $query->fetchAll();
			}
			catch(PDOException $event){
				// Requested -> Error
			}

			if($result){
				// setcookie('user', serialize($_COOKIE['user']), time()+3600, '/');
				// unset($_COOKIE['user']);
				
				$user_cookie = array(
					'status' => 'LOGGED',
					'auth'	 => TRUE,
					'username'	 => $username,
					'password'	 => $password,
				);
				$user_cookie = serialize($user_cookie);
				setcookie('user_login', $user_cookie, time()+3600, '/');
				$return->success[] = 'Successfully logged in';
			}else{
				$return->error[] = 'Incorrect information provided';
			}
		}
	}
	echo json_encode($return);
}elseif( isset($_POST['add_institute_manually']) ){

	$inst_eiin	 = isset($_POST['institute_eiin'])?$_POST['institute_eiin']:'';
	$inst_name	 = isset($_POST['institute_name'])?$_POST['institute_name']:'';
	$inst_type	 = isset($_POST['institute_type'])?$_POST['institute_type']:'';
	$inst_mobile = isset($_POST['institute_mobile'])?$_POST['institute_mobile']:'';
	$inst_email	 = isset($_POST['institute_email'])?$_POST['institute_email']:'';
	$inst_role 	 = 'institute';
	$inst_pass 	 = rand(100000, 999999);


	if( $inst_eiin == 'undefined' ){
		$return->error[] = 'Please enter EIIN ';
	}else{
		if( strlen($inst_eiin) < 6 ){
			$return->error[] = 'EIIN should be at least 6 digits ';
		}else{
			if( $inst_name == 'undefined' ){
				$return->error[] = 'Please enter institute name ';
			}else{
				if( $inst_type == 'undefined' ){
					$return->error[] = 'Please select institute type ';
				}else{
					if( $inst_type == 'government' || $inst_type == 'private' ){
						$mobile_validation = mobile_validation($inst_mobile);
						if( property_exists($mobile_validation, 'error') ){
							$return->error[] = $mobile_validation;
						}else{
							if( $inst_email == 'undefined' ){
								$return->error[] = 'Please enter an e-mail';
							}else{
								if( !filter_var($inst_email, FILTER_VALIDATE_EMAIL) ){
									$return->error[] = 'Invalid e-mail format';
								}else{
									if( function_exists('database') ){
										
										$cookie = isset($_COOKIE['user_login'])?unserialize($_COOKIE['user_login']):'';
										if( isset($cookie['auth']) == TRUE && isset($cookie['status']) == 'LOGGED' ){
											try {
												$database = database();
													$query = $database->prepare("
												    	SELECT	eiin
												    	FROM	institute_user
												    	WHERE	eiin='$inst_eiin'
												    ");
												    $query->execute();
												    $result = $query->fetchAll();
												    if( $result ){
												    	$return->error[] = 'EIIN-'.$inst_eiin.' already exist';
												    }else{
														$sql = "
															INSERT INTO institute_user (eiin,		name,		type,		mobile,		email, role,		password) 
															VALUES ('$inst_eiin',	'$inst_name',	'$inst_type',	'$inst_mobile',	'$inst_email',	'$inst_role', '$inst_pass')
														";
														$database->exec($sql);
														$return->success[] = 'Succesfully added EIIN-'.$inst_eiin;
												    }
											 }
											catch(PDOException $event){
												$return->error[] = 'Error: '.$event->getMessage();
											}
										}else{
											$return->error[] = 'You must login first. Please refresh the page.';
										}
									}else{
										$return->error[] = $dbn_con;
									}



								}
							}
						}
					}
					else{
						$return->error[] = 'Institute type should be (governement, or private)';
					}
				}
			}
		}
	}

	echo json_encode($return);
}else{
	session_destroy();
}


?>
