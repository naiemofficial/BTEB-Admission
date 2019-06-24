<?php if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
    // this part is show wintout ajax request
    include('header.php');
    include('aside.php');
} 
?>

<section id="second">
	<div class="form-group">
		<label for="shift">Select shift that you want apply</label>
		<select name="shift" id="shift" class="form-control">
			<option value="" selected>Choose shift &mdash; &mdash;</option>
			<option value="a">A - 1st / Morning Shift</option>
			<option value="b">B - 2nd / Day Shift</option>
			<option value="c">C - Both / 1st & 2nd Shift</option>
		</select>
	</div>

	<div class="form-group">
		<label for="payment_method">Payment Method</label>
		<select name="payment_method" id="payment_method" class="form-control">
			<option value="" selected>Select a payment method &mdash; &mdash;</option>
			<option value="bkash">BKash</option>
			<option value="dbbl">DBBL (Rocket)</option>
			<option value="nogod">Nogod</option>
			<option value="tcash">tCash</option>
			<option value="surecash">Surecash</option>
		</select>
	</div>

	<div class="form-row">
		<div class="col">
			<label for="amount">Pay amount</label>
	        <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter amount" value=""/>
		</div>
		<div class="col">
			<label for="payment_trx">Enter payment transaction id</label>
	        <input type="text" class="form-control" name="payment_trx" id="payment_trx" placeholder="Enter Transaction (TRX) ID" value=""/>
		</div>
	</div>
</section>

<?php if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
    // this part is show wintout ajax request
    include('footer.php');
} ?>












