<?php if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
    // this part is show wintout ajax request
    include('header.php');
    include('aside.php');
} 
	include('assets/api/addressbook.php'); 
?>

<div class="additional">
	<div class="row">
		<div class="col-md-3">
			<div class="quota block_check">
				<div class="check-nr">
					<input type="checkbox" name="freedomfighter" id="freedomfighter" value="freedomfighter">
					<label for="freedomfighter">
						<small></small>
						<span>Freedom Fighter</span>
                	</label>
				</div>
				<div class="check-nr">
					<input type="checkbox" name="disability" id="disability" value="disability">
					<label for="disability">
						<small></small>
						<span>Disable</span>
                	</label>
				</div>
				<div class="check-nr">
					<input type="checkbox" name="ethnic" id="ethnic" value="ethnic">
					<label for="ethnic">
						<small></small>
						<span>Ethnic</span>
                	</label>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="q_placeholder">
				<div class="quota_uploads">
					<div class="q_not_selected">
						<p> <strong>No Quota Selected</strong> <br/> If you're eligible for any quota. You can select that from the left. <br> [Note: you must have to provide authentic information. We will verify it. If we found something went wrong there. It can affect on your application]</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">

		<div class="col-md-6">
			<div class="additional_info address">			
				<div class="info_h">
					Address
				</div>
				<div class="row">
					<div class="col-12">
						<label for="division">Division :</label> 
						<select name="division" id="division" class="form-control">
							<option value="" disabled selected>Select division &mdash; &mdash;</option>
							<?php
								$division  = array_keys($bd_addressbook);
								$division_cnt  = count($bd_addressbook);
								for( $i=1; $division_cnt>=$i; $i++ ){
									echo '<option value="'.$division[$i-1].'">'.$division[$i-1].'</option>';
								}
							?>
						</select>
					</div>
					<div class="col-12">
						<label for="district">District : <span class="address district"><i class="fas fa-circle-notch fa-spin"></i></span></label> 
						<select class="form-control" id="district" disabled="true">
							<option value="" disabled selected>Select district &mdash; &mdash;</option>
						</select>
					</div>
					<div class="col-12">
						<label for="upzilla">Upzilla : <span class="address upzilla"><i class="fas fa-circle-notch fa-spin"></i></label> 
						<select class="form-control" id="upzilla"  disabled="true">
							<option value="" disabled selected>Select upzilla &mdash; &mdash;</option>
						</select>
					</div>
					<div class="col-7">
						<label for="po">Post Office :</label> 
						<input type="text" class="form-control" id="po" value="" readonly>
					</div>
					<div class="col-5">
						<label for="pc">Postal Code :</label> 
						<input type="text" class="form-control" id="pc" placeholder="0000" value="" readonly>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="additional_info security">
				<div class="info_h">
					Contact and Security Information
				</div>
				<div class="row">
					<div class="col-12">
						<label for="mobile">Mobile Number :</label> 
						<input type="number" class="form-control" id="mobile" value="">
					</div>
					<div class="col-12">
						<label for="upzilla">E-mail :</label> 
						<input type="email" class="form-control" id="email" value="">
					</div>
					<div class="col-12">
						<label for="pass">Password :</label> 
						<input type="password" class="form-control" id="pass" value="">
					</div>
					<div class="col-12">
						<label for="confirm_pass">Confirm Password :</label> 
						<input type="password" class="form-control" id="confirm_pass" value="">
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<?php include('button_next.php'); ?> 
		</div>
	</div>
</div>


<?php if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
    // this part is show wintout ajax request
    include('footer.php');
}?>

<script>
/*function insHtml(data){
	var insHtml  = 	'<div class="col-6">';
		insHtml +=		'<div class="info_h preview">';
		insHtml +=		'Choice List';
		insHtml +=	'</div>';
		insHtml +=	'<table class="table table-bordered">';
		insHtml +=		'<thead>';
		insHtml +=			'<th>SL</th>';
		insHtml +=			'<th>Institute Name</th>';
		insHtml +=			'<th>Department</th>';
		insHtml +=		'</thead>';
		insHtml +=		'<tbody>';
		insHtml +=			'<tr>';
		insHtml +=				'<td>1</td>';
		insHtml +=				'<td>Cumila Polytechnic Institute</td>';
		insHtml +=				'<td>Computer Technology</td>';
		insHtml +=			'</tr>';
		insHtml +=		'</tbody>';
		insHtml +=	'</table>';
		insHtml +=	'</div>';

	$('.content-wrapper').prepend(insHtml)
}
insHtml();*/
</script>