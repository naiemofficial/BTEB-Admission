<?php include('header.php'); ?>
<?php include('aside.php'); ?>
	<div class="row">
		<div class="col-4">
			<picture class="selection h-100">
				<img class="rounded" src="assets/images/applicant_applying.png" alt="">
			</picture>
		</div>
		<div class="col-8">
			<section id="first">
		    	<div class="form-group">
		    		<label for="year">SSC Examination Year</label>
		    		<select name="year" id="year" class="form-control custom-select">
                    	<option value="">Select Year &mdash; &mdash;</option>
                    	<?php
                        	for($i=1; $i<=10; $i++){
                        		$elg_yr = (date('Y')+1) - $i; // date('Y') - $i
							    echo '<option value="'.$elg_yr.'">'. $elg_yr .'</option>';
							}
                    	?>
                    </select>
				</div>
				<div class="form-group">
					<label for="board">SSC Board</label>
                    <select name="board" id="board" class="form-control">
                     	<option value="" selected>Select Board &mdash; &mdash;</option>
						<option value="barisal">Barisal</option>
						<option value="chittagong">Chittagong</option>
						<option value="comilla">Comilla</option>
                        <option value="dhaka">Dhaka</option>
						<option value="dinajpur">Dinajpur</option>
						<option value="jessore">Jessore</option>
						<option value="mymensingh">Mymensingh</option>
                        <option value="rajshahi">Rajshahi</option>
                        <option value="sylhet">Sylhet</option>
                        <option value="madrasah">Madrasah</option>
						<option value="tec">Technical</option>
						<option value="dibs">DIBS(Dhaka)</option>
                    </select>
				</div>
		    	<div class="form-group">
		    		<label for="roll">SSC Roll</label>
					<input type="number" class="form-control" id="roll" placeholder="Enter SSC roll" value="">
				</div>
				<div class="form-group">
					<label for="reg">SSC Reg</label>
                    <input type="number" class="form-control" name="reg" id="reg" placeholder="Enter SSC reg" value=""/>
				</div>
			</section>
			<?php include('button_next.php'); ?> 
        </div>
	</div>
<?php include('footer.php'); ?>     









































