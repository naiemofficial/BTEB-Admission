	
		(function($) {
		    $(document).ready(function(){ 

		    	/*
		    	* First step click event
		    	*/


		    	/*$(document).on("change", "select#board", function(e) {
		    		var option = $(this).val();
		    		var board = 'assets/images/board/'+option+'_board.png'; 
		    		$("picture.selection img").attr("src", board);
		    		$("picture.selection img").addClass('board');
		    	});*/
		    	
		    	/*$('#board').on('select', '#board', function(e){
		    		var getvalue = $("#board option:selected").val();
		    		$('picture img').attr("src", 'source_link');
		    		console.log(getvalue);
		    	});*/


		    	$(document).on("click", "#first + .process #next", function(e) {


		    		// Input values
		    		var boardval = $("#board option:selected").val();
		    		var yearval = $("#year option:selected").val(); 
		    		var rollval = $("#roll").val(); 
		    		var regval = $("#reg").val();

		    		// Send values
		    		var datatopass = 'board=' + boardval + '&year=' + yearval + '&roll=' + rollval + '&reg=' + regval + '&step=first'; /// step:first used for php $_POST request validation
					
					// Ajax Start
		    		$.ajax({
	                    type: "POST", 
	                    url: "checkform.php", 
	                    data: datatopass, 
	                    beforeSend: function(){
	                    	// Before / while send request the loading icon will be enable
	                    	$('#loading').prepend($('<img>',{id:'load',src:'assets/images/loading.gif'}));
	                    	$("#form-message").css("display", "none");
	                    },
	                    success: function (response) {
	                    	// Get AJAX Response
	                    	var object = JSON.parse(response);

	                    	// If there's available any error
	                    	if( object.hasOwnProperty('error') ) {
	                    		// Error Codes here
	                    		$('#form-message').text(object.error);

	                    		// Show warning when get error
	                    		$("#form-message").css("display", "block");
	                    	} else {
	                    	// If there's no error - then >>>

	                    		// Hide warning when success
	                    		$("#form-message").css("display", "none");

	                    		// Success step
	                    		$('.sidebar ul li:first-child').removeClass('active');
	                    		$('.sidebar ul li:first-child').addClass('success');

	                    		// Active step
	                    		$('.sidebar ul li:nth-child(2)').addClass('active');

	                    		// Visible Next step (2)
								$('#first').fadeOut('slow', function() {
									$.get("payment.php", function(data) {
										// Getting next step page by 'data' variable
										// Replace data of #first id
										$('#first').replaceWith($(data));
									});
								});
	                    	}
	                    },
	                    complete: function(){
	                    	// Hide loading icon
	                    	$('#load').remove();
	                    }
	                });
		            e.preventDefault(); // prevent php click event
		        });






		        /*
		        * Second step click event
		        */
		        $(document).on("click", "#second + .process #next", function(e) {

		    		// Input values
		    		var shift_cat = $("#shift option:selected").val(); // Get Shift Payment method value
		    		var payment_method = $("#payment_method option:selected").val(); // Get selected Payment method value
		    		var amount = $("#amount").val(); // Get Amount of payment
		    		var tranx_num = $("#payment_trx").val(); // Get Transaction number value

		    		// Semd Values
		    		var datapasstwo = 'shift_cat='+shift_cat+'&pay_method=' + payment_method + '&amount='+amount+'&trnx=' + tranx_num + '&payment=ok'; // part:second used for php $_POST request validation
					
					// AJAX Start
					$.ajax({
	                    type: "POST", 
	                    url: "checkform.php", 
	                    data: datapasstwo,
	                    beforeSend: function(){
	                    	// Before / while send request the loading icon will be enable
	                    	$('#loading').prepend($('<img>',{id:'load',src:'assets/images/loading.gif'}));
	                    	$("#form-message").css("display", "none");
	                    },
	                    success: function (response) {
	                    	// Get AJAX Response
	                    	var object = JSON.parse(response);
	                    	
	                    	// If there's available any error
	                    	if( object.hasOwnProperty('error') ) {
	                    		// Error Codes here
	                    		$('#form-message').text(object['error']);

	                    		// Show warning when get error 
	                    		$("#form-message").css("display", "block");
	                    	} else {
	                    	// If there's no error - then >>>
	                    		// Hide warning when success
	                    		$("#form-message").css("display", "none");

	                    		// Success step
	                    		$('.sidebar ul li:nth-child(2)').removeClass('active');
	                    		$('.sidebar ul li:nth-child(2)').addClass('success');

	                    		// Active step
	                    		$('.sidebar ul li:nth-child(3)').addClass('active');


	                    		// Visible Next step (2)
								$('#form div.row').fadeOut('slow', function() {
									// Getting next step page by 'data' variable
									$.get("profile.php", function(profile) {
										
										// Replace data of #second id
										$('#form div.row').replaceWith($(profile));

										// Append and visible requested student_info data on the #third step (profile overview)
										$('.profile_overview #name').val($('#name').val() + object.student_info.name);
										$('.profile_overview #father').val($('#father').val() + object.student_info.father);
										$('.profile_overview #mother').val($('#mother').val() + object.student_info.mother);
										$('.profile_overview #date-of-birth').val($('#date-of-birth').val() + object.student_info.dob);
										$('.profile_overview #board').val($('#board').val() + object.student_info.board);
										$('.profile_overview #year').val($('#year').val() + object.student_info.year);
										$('.profile_overview #roll').val($('#roll').val() + object.student_info.roll);
										$('.profile_overview #reg').val($('#reg').val() + object.student_info.reg);

										// Eligibility by subject
										var subject = object.student_info.subject;

										// var tableHtml for appending table row while get eligible subject
										var tableHtml 	=	'<tr>'		;
											tableHtml 	+=	'	<td>'	;
											tableHtml 	+=	'	</td>'	;
											tableHtml 	+=	'	<td>'	;
											tableHtml 	+=	'	</td>'	;
											tableHtml 	+=	'	<td>'	;
											tableHtml 	+=	'	</td>'	;
											tableHtml 	+=	'	<td>'	;
											tableHtml 	+=	'	</td>'	;
											tableHtml 	+=	'</tr>'		;

										var sub_elg = {};
											sub_elg['ok'] = '<i class="fa fa-check-circle success"></i>';
											sub_elg['!ok'] = '<i class="fa fa-times-circle not_success"></i>';
										
										for( var i=0, row=0; subject.length>i; i++, row){
											if( subject[i]['code'] == 109 || subject[i]['code'] == 126  ){
												row = 1+row;

												// Subject eligibility status
												var status;
												switch (subject[i]['grade']) {
													case 'A+':
													case 'A':
													case 'A-':
													case 'B':
														status = sub_elg['ok'];
														break;
													default:
														status = sub_elg['!ok'];
												}
												
												// Append row if found applicant related subject
												$('.subject_eligibility table tbody').append(tableHtml);

												// Subject visible data
												$('.subject_eligibility table tbody tr:nth-child('+row+') td:first-child').text( subject[i]['code'] );
												$('.subject_eligibility table tbody tr:nth-child('+row+') td:nth-child(2)').text(subject[i]['name']);
												$('.subject_eligibility table tbody tr:nth-child('+row+') td:nth-child(3)').text(subject[i]['grade']);
												$('.subject_eligibility table tbody tr:nth-child('+row+') td:nth-child(4)').append(status);
											}
										};
									});
								});
	                    	}
	                    },
	                    complete: function(){
	                    	// Hide loading icon
	                    	$('#load').remove();
	                    }
	                });
		            e.preventDefault(); // prevent php click event
		        });




		        /*
		        * Third step click event
		        */
		        $(document).on("click", "#third + .process #next", function(e) {

		        	// For getting input[type=file] here used an object FormData()
					var formdata = new FormData();

					// Getting input values and append/add to object with file input
					formdata.append('filename', $('#third input[type=file]')[0].files[0]);
					formdata.append('gender', $("input[name='gender']:checked").val());

					// next:third used for php $_POST request validation
					formdata.append('next', 'third');

					$.ajax({
	                    type: "POST", 
	                    url: "checkform.php",
	                    data: formdata, 
	                    processData: false,
	                    contentType: false,
	                    beforeSend: function(){
	                    	// Before / while send request the loading icon will be enable
	                    	$('#loading').prepend($('<img>',{id:'load',src:'assets/images/loading.gif'}));
	                    	$("#form-message").css("display", "none");
	                    },
	                    success: function (response) {
	                    	// Get AJAX Response
	                    	var object = JSON.parse(response);

	                    	// If there's available any error || or need to refresh the current page
	                    	if( object.hasOwnProperty('error') || object.hasOwnProperty('refresh') ) {
	                    		// Error Codes here
	                    		$('#form-message').text(object.error);

	                    		// Show warning when get error 
	                    		$("#form-message").css("display", "block");
	                    		
	                    	} else {
	                    		// Hide warning when success
	                    		$("#form-message").css("display", "none");

	                    		// Success step
	                    		$('.sidebar ul li:nth-child(3)').removeClass('active');
	                    		$('.sidebar ul li:nth-child(3)').addClass('success');

	                    		// Active step
	                    		$('.sidebar ul li:nth-child(4)').addClass('active');

	                    		// Get shift category
	                    		var shift_cat = object.payment['shift_cat'];
								$('#form ul + .row').fadeOut('slow', function() {
									$.get("institute.php", function(data) {
										$('#form> ul + .row').replaceWith($(data));



										// Append tbody into institute_list table
										var tbodyHtml 	=	'<tbody>'	;
											tbodyHtml 	+=	'</tbody>'	;

										
										switch (shift_cat) {
											case 'a':
												shc_i = 'only 1st';
												shift_cat_option = $('<option></option>').attr('value','1').text('Morning / 1st Shift');
												tBody = tbodyHtml;
												break;
											case 'b':
												shc_i = 'only 2nd';
												shift_cat_option = $('<option></option>').attr('value','2').text('Day / 2nd Shift');
												tBody = tbodyHtml;
												break;
											case 'c':
												shc_i = '1st & 2nd : both';
												shift_cat_option =  '<option value="1">Morning / 1st Shift</option>';
												shift_cat_option += '<option value="2">Day / 2nd Shift</option>';
												tBody = tbodyHtml+' '+tbodyHtml;
												break;
											default:
												shc_i = '0';
												shift_cat_option = '';
												break;
										}
										// Append select shift options by checking shift_cat
										$('#placeholder select#shift').append(shift_cat_option);

										// shift_cat ID
										$('#placeholder .alert #shift_cat').text(shift_cat);

										// shift_cat a & b will get 'basic' class name
										if( shift_cat == 'a' || shift_cat == 'b' ){
											$('#placeholder .alert #shift_cat').addClass('basic');
										}

										// shift_cat info
										$('#placeholder .alert span:last-child').text(shc_i);

										// Append tbody inside table
										$('.institute_list table').append(tBody);

										// UI-Shortable 
										$('.institute_list table tbody').sortable({
											placeholder : "ui-state-highlight",
											update: function(){
												var rows = 0, the_row='';
													rows = $(".institute_list .table tbody tr").length;
													the_row = '.institute_list .table tbody tr:nth-child';

												for( var i=1; rows>=i; + i++ ){
													$(the_row+'('+i+') td:first-child').html(i);
												}
											}
										});

									});
								});

								
								
	                    	}

	                    },
	                    complete: function(){
	                    	// Hide loading icon
	                    	$('#load').remove();
	                    }
	                });
		            e.preventDefault(); // prevent php click event
		        });	
		    // 





		    // 
		        /*
				* Institute add Fourth step click event
		        */   
		        $(document).on("click", "button#add", function(e){

		        	// Create an array to add input data 
		        	var choice_data = {};

		        	// Getting input values and append to variable which is defined as 'choice_data' vairable
		        	choice_data['institute']	= $('#placeholder select#institute option:selected').val();
		        	choice_data['technology']	= $('#placeholder select#technology option:selected').val();
		        	choice_data['shift']		= $('#placeholder select#shift option:selected').val();
		        	choice_data['add_choice'] = 'ok';

		        	// Get current shift_cat
					var cur_shift_cat = $("#placeholder #shift_cat").text();
		        	var tbody = 2; 
					if( choice_data['shift'] == '2' && (cur_shift_cat == 'c' || cur_shift_cat == 'C') ){
						tbody = parseInt(choice_data['shift'])+1;
					}	        	

		        	// Duplicate choice validation
		        	var rows = 0, the_row='';
						rows = $('.institute_list .table tbody:nth-child('+tbody+') tr').length;
						the_row = '.institute_list .table tbody:nth-child('+tbody+') tr:nth-child';

					var fwd_id = $('#placeholder select#institute option:selected').val()+'-';
						fwd_id += $('#placeholder select#technology option:selected').val()+'-';
						fwd_id += ordinal($('#placeholder select#shift option:selected').val());
		        	
		        	// Send Data
        			choice_data = 'institute='+choice_data['institute']+'&technology='+choice_data['technology']+'&shift='+choice_data['shift']+'&add_choice='+choice_data['add_choice'];
		        	
		        	// Check every row for duplicate validation
		        	for( var i=1; rows>=i; i++ ){
		        		var cus_id = $(the_row+'('+i+') td:nth-child(2)').text()+'-';
		        			cus_id += $(the_row+'('+i+') td:nth-child(5)').text()+'-';
		        			cus_id += $(the_row+'('+i+') td:nth-child(6)').text();

		        		if( cus_id.replace(/\s/g, '') == fwd_id.replace(/\s/g, '') ){
		        			choice_data += '&duplicate=true';
		        		}
		        	}
					




		        	$.ajax({
		        		type: 'POST',
		        		url: 'checkform.php',
		        		data: choice_data,
		        		beforeSend: function(){
		        			$("#form-message").css("display", "none");
		        			// Before / while send request the loading icon will be enable
	                    	$("button#add i").replaceWith('</i><i class="fas fa-cog fa-spin"></i>');
		        		},
		        		success: function(response) {
	                    	// Get AJAX Response
	                    	var object = JSON.parse(response);

	                    	// If there's available any error |
	                    	if( object.hasOwnProperty('error') ) {
	                    		// Error Codes here
	                    		$('#form-message').text(object.error);
	                    		// Show warning when get error 
	                    		$("#form-message").css("display", "block");
	                    	} else {
	                    		// Hide warning when success
	                    		$("#form-message").css("display", "none");
	                    		

	                    		// var tableHtml for appending table row while get new choice will be add
								var tableHtml 	=	'<tr>'		;
									tableHtml 	+=	'	<td>'	;
									tableHtml 	+=	'	</td>'	;
									tableHtml 	+=	'	<td>'	;
									tableHtml 	+=	'	</td>'	;
									tableHtml 	+=	'	<td>'	;
									tableHtml 	+=	'	</td>'	;
									tableHtml 	+=	'	<td>'	;
									tableHtml 	+=	'	</td>'	;
									tableHtml 	+=	'	<td>'	;
									tableHtml 	+=	'	</td>'	;
									tableHtml 	+=	'	<td>'	;
									tableHtml 	+=	'	</td>'	;
									tableHtml 	+=	'	<td>'	;
									tableHtml 	+=	'	</td>'	;
									tableHtml 	+=	'</tr>'		;


								
								// Getting choice_data from object
								var choice = object['choice_data'];
								if( choice['shift'] == '2' && (cur_shift_cat == 'c' || cur_shift_cat == 'C') ){
									tbody = parseInt(choice['shift'])+1;
								}



								// Append row if request to add new item in choice list
								$('.institute_list .table tbody:nth-child('+tbody+')').append(tableHtml);


								var row_count = 0;
									row_count = $('.institute_list .table tbody:nth-child('+tbody+') tr').length;

								// Make table row with auto counter as short selector
								var row = '.institute_list .table tbody:nth-child('+tbody+') tr:nth-child('+row_count+') ';
								


								// Delete choice Button
								var delete_choice = '<button id="delete-choice"><i class="fa fa-trash-alt x"></i></button>';


								// Append choiced list data into table data (td) cell of appended row 
								$(row+'td:nth-child(1)').append(row_count);
								$(row+'td:nth-child(2)').append(choice['eiin']);
								$(row+'td:nth-child(3)').append(choice['name']);
								$(row+'td:nth-child(4)').append(choice['department']);
								$(row+'td:nth-child(5)').append(choice['trade']);
								$(row+'td:nth-child(6)').append(ordinal(choice['shift']));
								$(row+'td:nth-child(7)').append(delete_choice);


								// Hide bottom alert {}
								$('.institute_list .alert-info.choicedata').fadeOut('slow');
	                    	}
		        		},
	                    complete: function(){
	                    	$("button#add i").replaceWith('<i class="fa fa-plus-circle">');
	                    }
		        	});
		        	e.preventDefault(); // prevent php click event
		        });


				// Drag and Drop Institute List
				$( ".institute_list table tbody" ).sortable( {
					placeholder : "ui-state-highlight",
					update: function(){

						var rows = 0, the_row='';
							rows = $(".institute_list .table tbody tr").length;
							the_row = '.institute_list .table tbody tr:nth-child';

						for( var i=1; rows>=i; + i++ ){
							$(the_row+'('+i+') td:first-child').html(i);
						}
						console.log(i);
					}
				});

				// Delete institute from choice list'
				$(document).on("click", ".institute_list table tr button#delete-choice", function(e){

						var target_row = $(this.closest('.institute_list table tr td')).parent();

					    $(target_row).fadeOut( 'slow').promise().done(function () {
					    	$(target_row).remove();
							var rows = 0, the_row='';
								rows = $(".institute_list .table tbody tr").length;
								the_row = '.institute_list .table tbody tr:nth-child';

							// Re-assign SL count when delete complete
							for( var i=0; rows>=i; + i++ ){
								$(the_row+'('+i+') td:first-child').html(i);
							}

							// Show alert when all choiced data deleted
							if( rows == 0 ){
								$('.institute_list .alert-info.choicedata').fadeIn('slow');
							}
					    });
				});



				/* Fourth step complete - click event */ 
				$(document).on('click', '.institute_list + .process #next', function(e){

					// current shift_cat
					var cur_shift_cat = $("#placeholder #shift_cat").text();
					switch (cur_shift_cat) {
						case 'a':
						case 'A':
							cur_shift_cat = 1;
							break;
						case 'b':
						case 'B':
							cur_shift_cat = 2;
							break;
						case 'c':
						case 'C':
							cur_shift_cat = 'true';
							break;
						default:
							cur_shift_cat = 0;
							break;
					}	

					// Variable declaration
					var tbodys=0,	rows=0,		the_row='',		the_xrow={},	xtd_obj={};
					// Variable assign
					tbodys = $('.institute_list .table tbody').length+1;

				   // Looping start for every tbody insde table
					/*if( tbodys >= 2){
						for( var x=2; tbodys>=x; x++ ){
							rows = $('.institute_list .table tbody:nth-child('+x+') tr').length;
							the_row = '.institute_list .table tbody:nth-child('+x+') tr:nth-child(';

							console.log(x);
							// Row looping
							for( var i=1; rows >= i; i++ ){
						        var get_row_values = {
						            'sl_priority'   : $(the_row+i+') td:nth-child(1)').text(),
						            'eiin'          : $(the_row+i+') td:nth-child(2)').text(),
						            'trade'         : $(the_row+i+') td:nth-child(5)').text(),
						            'shift'         : parseInt($(the_row+i+') td:nth-child(6)').text())
						        };
						        the_xrow[i] = get_row_values;
						    }
							
							if( cur_shift_cat == 0 ){
								// Critical error
							}else if( tbodys == 2 && ( cur_shift_cat == 1 || cur_shift_cat == 2 ) ){
						    	xtd_obj[cur_shift_cat] = the_xrow;
						    }else if( (tbodys >= 2 && cur_shift_cat == 'true') && (cur_shift_cat != 1 && cur_shift_cat != 2 || cur_shift_cat != 0) ){
						    	xtd_obj[x-1] = the_xrow;
						    }else{
						    	// Critical error
						    }
					    }
					}else{
						xtd_obj[0] = null;
						xtd_obj[1] = null;
						xtd_obj[2] = null;
					}*/







					    var target_table = $('.institute_list > table');
					    var xtd_obj = [];

					    if( target_table.length != 0 ){
					        target_table.find('tbody').each(function(tbody_key, tbody_val){
					            xtd_obj[tbody_key] = [];
				                $(tbody_val).find('tr').each(function(tr_key, tr_val){
			                        xtd_obj[tbody_key][tr_key] = [];
			                        $(tr_val).find('td').each(function(td_key, td_val){
			                        	xtd_obj[cur_shift_cat][(tr_key+1)][td_key] = $.trim($(td_val).text());
									});
								});
					        }); 
					    }

						console.log(xtd_obj);


/*if( tr_key < 5 ){
    // limit
}else{
	// continue
}
if( cur_shift_cat == 0 ){
	// critical error
}else if( cur_shift_cat == 1 || cur_shift_cat == 2 || cur_shift_cat == 'true' ){
	continue
}
if( tbody_key < 0 ){
	// Minimum maximum               
}else{

}
*/

















				   

					var forward_choiced_data = {};

					// Append data to forward
					forward_choiced_data['choice_list_data']	= JSON.stringify(xtd_obj);
					forward_choiced_data['choice_list'] 		= 'confirm';

					console.log(forward_choiced_data['choice_list_data']);


					$.ajax({
						type: 'POST',
						url: 'checkform.php',
						data: forward_choiced_data,
						beforSend: function(){
							// Before / while send request the loading icon will be enable
	                    	$('#loading').prepend($('<img>',{id:'load',src:'assets/images/loading.gif'}));

	                    	// When loading hide form mesage
	                    	$("#form-message").css("display", "none");
						},
						success: function(response){
							// Get AJAX Response
	                    	var object = JSON.parse(response);

	                    	console.log(object);
							/*
	                    	// If there's available any error |
	                    	if( object.hasOwnProperty('error') ) {
	                    		// Error Codes here
	                    		$('#form-message').text(object.error);
	                    		// Show warning when get error 
	                    		$("#form-message").css("display", "block");
	                    	} else {
	                    		// Hide warning when success
	                    		$("#form-message").css("display", "none");
	                    	}*/
						},
						complete: function(){
							// Hide loading icon
	                    	$('#load').remove();
						}
					});
					e.preventDefault(); // prevent php click event
				});








		    });
		})(jQuery);
	





// URL Reader for image
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#applicant_image')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}


// Ordinal 1st, 2nd, 3rd, 4th, 5th
function ordinal(number) {
    var ends = ['th','st','nd','rd','th','th','th','th','th','th'];
    if (((number % 100) >= 11) && ((number%100) <= 13))
        return number+ 'th';
    else
        return number+ ends[number % 10];
}







