<?php 
$db_conf = '../inc/config.php';
if( file_exists($db_conf) ){
	include($db_conf);
}
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

	if( $db_conn && $admin_setup ){ 
?>
	<div class="card-body" id="maintenance_login">
		<h4 class="text-center">Login</h4>
		<hr>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" id="username">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" class="form-control" id="password">
		</div>
		<div class="form-group">
			<div id="loading" style="display: inline-block;"></div>
			<button type="submit" id="maintenance_login" class="btn btn-primary btn-sm float-right">Login</button>
		</div>
	</div>
<?php } } ?>

