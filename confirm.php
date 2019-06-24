<?php if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
    // this part is show wintout ajax request
    include('header.php');
} 
include('assets/api/addressbook.php');
session_start();
?>
	<div class="container-fluid preview">
		<div class="container">
			<div class="text-right">
				<button id="close_confirm"><i class="fa fa-times-octagon"></i></button>
			</div>
			<div id="confirm" class="preview_application">
				<div class="row">
					<div class="col-2">
						<img src="<?php echo $_SESSION['student_info']['applicant_image'] ?>" alt="">
					</div>

					<div class="col-10">
						<div class="prview_appl basic">
							<div class="row">
								<div class="col-6">
									<div class="info_h preview">
										Basic Informations
									</div>
									<ul id="basic">
										<li><strong>Name </strong></li>
										<li><strong>Father's Name </strong></li>
										<li><strong>Mother's Name </strong></li>
										<li><strong>Gender </strong></li>
										<li><strong>Date of Birth </strong></li>
									</ul>
								</div>
								<div class="col-6">
									<div class="info_h preview">
										SSC Informations
									</div>
									<ul id="ssc">
										<li><strong>Board </strong></li>
										<li><strong>Year </strong></li>
										<li><strong>Roll </strong></li>
										<li><strong>Reg </strong></li>
										<li><strong>GPA </strong></li>
									</ul>
								</div>
								<div class="col-5">
									<div class="info_h preview">
										Payment
									</div>
									<ul id="payment">
										<li><strong>Paid for </strong> </li>
										<li><strong>Method </strong> </li>
										<li><strong>Amount </strong> </li>
										<li><strong>TRX ID </strong> </li>
									</ul>
								</div>
								<div class="col-7">
									<div class="info_h preview">
										Subject Eligibility
									</div>
									<ul id="subject">
									</ul>
								</div>

								<div class="col-12">
									<div class="row" id="institute">
									</div>
								</div>

								<div class="col-6">
									<div class="info_h preview">
										Quota
									</div>
									<ul id="quota">
									</ul>
									<div class="info_h preview">
										Contact
									</div>
									<ul id="contact">
										<li><strong>Mobile </strong> </li>
										<li><strong>E-mail </strong> </li>
									</ul>
								</div>
								<div class="col-6">
									<div class="info_h preview">
										Address
									</div>
									<ul id="address">
										<li><strong>Division </strong> </li>
										<li><strong>District </strong> </li>
										<li><strong>Upzilla </strong> </li>
										<li><strong>Post Office </strong> </li>
										<li><strong>Postal Code </strong> </li>
									</ul>
								</div>
							</div>
							<div class="alert alert-warning preview" role="alert">
								<div class="checkbox agree">
									<input type="checkbox" id="applicant_agree">
									<label for="applicant_agree"></label>
								</div>
								<p>Be sure you're providing valid information. Wrong information can affect on your application. All rights resereved by Bangladesh Technical Education Board (BTEB). If you agree click on the checkbox and confirm your submission.</p>
							</div>
							<div class="form-group process" align="right">
								<div id="loading" style="display: inline-block;"></div>
								<button type="submit" id="confirm_application" class="btn btn-primary">Confirm and Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
    // this part is show wintout ajax request
    include('footer.php');
}?>