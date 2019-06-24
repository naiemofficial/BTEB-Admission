<?php 
session_start();
include('../header.php');
$db_conf = '../inc/config.php';
if( file_exists($db_conf) ){
	include($db_conf);
}
if( file_exists('../inc/functions.php') ){
	include('../inc/functions.php');
}

$login_conn = false;

if( isset($_COOKIE['user_login']) ){
	$cookie_login = unserialize($_COOKIE['user_login']);
	
	$user_status	 	= isset($cookie_login['status'])?$cookie_login['status']:false;
	$user_auth	 		= isset($cookie_login['auth'])?$cookie_login['auth']:false;
	$user_username	 	= isset($cookie_login['username'])?$cookie_login['username']:'';
	$user_password	 	= isset($cookie_login['password'])?$cookie_login['password']:'';
	// Login Check
	try {
		if( function_exists('database') ){
			$database = database();
			$query = $database->prepare("
		    	SELECT	*
		    	FROM	user_maintenance
		    	WHERE	username='$user_username' AND password='$user_password'
		    ");
		    $query->execute();
		    $login_conn = $query->setFetchMode(PDO::FETCH_ASSOC);
		    $login_conn = $query->fetchAll();
		    $current_user = $login_conn[0];

		    $current_user['role'] = unserialize($current_user['role']);
	    }
	}
	catch(PDOException $event){
		$login_conn = false;
	}
}
if( $login_conn && $user_status == 'LOGGED' && $user_auth ){ 
	if( in_array('dbadministrator', $current_user['role']) ){ ?>	
	<div class="row">
		<div class="col-4">
			<div class="bg-white">
				<div class="profile_sidebar">
					<picture>
						<span><i class="fas fa-user"></i></span>
					</picture>
					<name>
						<span><?php echo $current_user['name']; ?></span>
					</name>
				</div>
				<div class="act_tabs">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<!-- <li class="nav-item">
							<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
						</li> -->
						<li class="nav-item">
							<a class="nav-link" id="add-user-tab" data-toggle="tab" href="#add-user" role="tab" aria-controls="add-user" aria-selected="false"> <i class="fa fa-user-plus"></i> Add Admin User</a>
						</li>
						<li class="nav-item position-relative">
							<a class="nav-link" id="import_institute-tab" data-toggle="tab" href="#import_institute" role="tab" aria-controls="import_institute" aria-selected="false"> <i class="fa fa-cloud-upload"></i> Import Institute </a>
							<a class="nav-link active import_add" id="add_institute-tab" data-toggle="tab" href="#add_institute" role="tab" aria-controls="import_institute" aria-selected="false"> ADD MANUALLY </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="contact-tab" href="#logout"> <i class="fa fa-sign-out-alt"></i> Logout</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-8">
			<div class="bg-white">
				<div class="alert message alert-dismissible fade show" role="alert">
					<ul id="alert-msg"></ul>
				</div>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade">
						<div class="c-c h-100">
							<div class="admin_setup">
								<i class="fas fa-database"></i>
								<p>Welcome to database administrator profile</p>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="add-user" role="tabpanel" aria-labelledby="add-user-tab">
						<fieldset>
							<legend>Add Admin User</legend>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="username">Username</label>
										<input type="text" class="form-control" id="username" value="">
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="role">Role</label>
										<select id="role" class="form-control">
											<option value="administrator">Administrator</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" id="name" value="">
							</div>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="mobile">Mobile</label>
										<input type="text" class="form-control" id="mobile" value="">
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="email">E-mail</label>
										<input type="email" class="form-control" id="email" value="">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="pass">Password</label>
										<input type="password" class="form-control" id="pass" value="">
										</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="confirm_pass">Password</label>
										<input type="password" class="form-control" id="confirm_pass" value="">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div id="loading" style="display: inline-block;"></div>
								<button type="submit" id="add_admin_user" class="btn btn-primary btn-sm float-right">Add user</button>
							</div>
						</fieldset>
					</div>
					<div class="tab-pane fade" id="import_institute" role="tabpanel" aria-labelledby="import_institute-tab">
						<fieldset>
							<legend>Import Institute</legend>
							<div class="select_doc">
								<label for="import_inst">
									<i class="fas fa-file"></i>
									<strong>Select File (XLS, XLXS, CSV, TSV)</strong>
								</label>
								<input type="file" id="import_inst" content-type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" accept=".xls, .xlsx, .csv">
							</div>
						</fieldset>
					</div>
					<div class="tab-pane fade show active" id="add_institute" role="tabpanel" aria-labelledby="add_institute-tab">
						<fieldset>
							<legend>Add Institute Manually</legend>
							<div class="row">
								<div class="col-3">
									<div class="form-group">
										<label for="institute_eiin">EIIN</label>
										<input type="number" class="form-control" id="institute_eiin" value="133160">
									</div>
								</div>
								<div class="col-9">
									<div class="form-group">
										<label for="institute_name">Institute Name</label>
										<input type="text" class="form-control" id="institute_name" value="Cumilla Polytechnic Institute">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="institute_role">Role</label>
										<input type="text" class="form-control" id="institute_role" value="INSTITUTE" readonly>
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="institute_type">Institute Type</label>
										<select id="institute_type" class="form-control">
											<option value="">Choose institute type &mdash; &mdash;</option>
											<option value="government" selected>Government</option>
											<option value="private">Private</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="instiute_mobile">Mobile</label>
										<input type="text" class="form-control" id="institute_mobile" value="">
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="instiute_email">E-mail</label>
										<input type="email" class="form-control" id="institute_email" value="">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div id="loading" style="display: inline-block;"></div>
								<button type="submit" id="add_institute" class="btn btn-primary btn-sm float-right">Add Institute</button>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</div>
		
<?php } }else{ ?>
	<div class="bg-white h-auto pd-40" id="db_config">
		<div class="row">
			<div class="col-4">
				<div class="flex-center sdb-ic">
					<i class="fas fa-database"></i>
				</div>
				<div class="db_help">
					<button class="btn" type="button" data-toggle="modal" data-target="#db-help">Help</button>
				</div>
			</div>

			<div class="col-8">
				<div class="alert message alert-dismissible fade show" role="alert">
					<ul id="alert-msg"></ul>
				</div>
				<div class="card db_config">
					<?php
					if( function_exists('database') ){
						try {
							$database = database();
							$db_conn = true;
						}
						catch(PDOException $event){
							$db_conn = false;
						}
						
						try {
							$database = database();
							$query = $database->prepare("
						    	SELECT	username
						    	FROM	user_maintenance
						    	WHERE	username='admin'
						    ");
						    $query->execute();
						    $admin_setup = $query->fetchAll();
						}
						catch(PDOException $event){
							$admin_setup = false;
						}

					if( $db_conn && !$admin_setup){
						include('admin-sign-up.php');
					}elseif( $db_conn && $admin_setup ){ ?>
						<div class="card-body c-c scs_brdr">
							<div class="admin_setup">
								<i class="fas fa-user"></i>
								<p>Administration setup has been completed</p>
								<button type="submit" id="admin_login_btn">Click here to Login</button>
							</div>
						</div>
					<?php }else{ ?>
						<div class="card-body">
							<div class="form-group position-relative">
								<label for="hostname">Hostname</label>
								<input type="text" class="form-control" id="hostname" value="localhost" readonly>
								<button class="abs-t-r" id="edt_srvr"><i class="fa fa-pen-square"></i></button>
							</div>
							<div class="form-group position-relative">
								<label for="db_name">Database Name</label>
								<input type="text" class="form-control" id="db_name" value="admission">
								<span class="create-db-btn">
									<input type="checkbox" name="db_action" id="db_action" value="create">
									<label for="db_action"><i class="fas fa-plus-circle"></i> Create New</label>
								</span>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-6">
										<label for="db_user">Database user</label>
										<input type="text" class="form-control" id="db_user" value="root">
									</div>
									<div class="col-6">
										<label for="db_pass">Database password</label>
										<input type="password" class="form-control" id="db_pass">
									</div>	
								</div>
							</div>
							<div class="form-group">
								<div id="loading" style="display: inline-block;"></div>
								<button type="submit" id="db_config" class="btn btn-primary btn-sm float-right">Configure</button>
							</div>
						</div>
					<?php } }else{ ?>
						<div class="card-body c-c fld_brdr">
							<div class="db_conf_nready">
								<i class="far fa-cogs"></i>
								<p>Database configuration file isn't ready yet</p>
								<p class="normal-text">Click on Help</p>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>


			<!-- DB Help -->
			<div class="modal fade" id="db-help" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<button type="button" class="btn cancel" data-dismiss="modal"><i class="fa fa-times-octagon"></i></button>
						<div class="modal-body">
							<div class="alert db_conf alert-dismissible">
								<ul id="dbcon-msg"></ul>
							</div>
							<p>If you haven't created config.php file or deleted the config.php. You can simple make ready the config.php file by clicking below button</p>
							<div class="db_help">
								<button type="submit" id="create_config" class="btn btn-primary btn-sm">Create DB config</button>
							</div>
							<p>If is there anything else to create config.php file by clicking above button. You can put that codes manually from below</p>
						</div>
					</div>
				</div>
			</div>
	  


		</div>
	</div>
<?php } include('../footer.php'); ?>