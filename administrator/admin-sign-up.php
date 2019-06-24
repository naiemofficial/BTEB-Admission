<div class="card-body" id="admin_signup">
	<h4 class="text-center">Sign up for administration</h4>
	<hr>
	<div class="form-group">
		<div class="row">
			<div class="col-6">
				<label for="username">Username</label>
				<input type="text" class="form-control" id="username" value="admin" readonly>
			</div>
			<div class="col-6">
				<label for="name">Name</label>
				<input type="text" class="form-control" id="name" value="Database Administrator" readonly>
			</div>	
		</div>
	</div>
	<div class="form-group">
		<label for="email">E-mail</label>
		<input type="email" class="form-control" id="email" value="">
	</div>
	<div class="form-group">
		<div class="row">
			<div class="col-6">
				<label for="pass">Password</label>
				<input type="password" class="form-control" id="pass" value="">
			</div>
			<div class="col-6">
				<label for="name">Confirm Password</label>
				<input type="password" class="form-control" id="confirm_pass" value="">
			</div>	
		</div>
	</div>
	<div class="form-group">
		<div id="loading" style="display: inline-block;"></div>
		<button type="submit" id="admin_sign_up" class="btn btn-primary btn-sm float-right">Submit</button>
	</div>
</div>