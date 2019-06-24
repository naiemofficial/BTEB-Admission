	
(function($) {
    $(document).ready(function(){ 


	// First step click event

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
	                    	$('#load').remove();
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
										$('#first').replaceWith(data);
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
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 


	// Second step click event
		        
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
	                    	$('#load').remove();
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
										
										for( var i=0, row=0; Object.keys(subject).length>i; i++, row){
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

	// Upload Applicant Image

				$(document).on('change', '#applicant_picture', function(e){
					if (window.File && window.FileList && window.FileReader) {
							var formdata = new FormData();
							formdata.append('applicant_img', $('#third input[type=file]')[0].files[0]);
							formdata.append('upload_ap_img', true);
							$.ajax({
				                type: "POST", 
				                url: "checkform.php",
				                data: formdata, 
				                processData: false,
				                contentType: false,
				                beforeSend: function(){
				                	/*$('#loading').prepend($('<img>',{id:'load',src:'assets/images/loading.gif'}));
				                	$("#form-message").css("display", "none");
				                	$('.progress-bar').width('50%');*/
				                	$('.upload_progress').css("display", "flex");
				                },
								xhr: function() {
									var xhr = new window.XMLHttpRequest();
									xhr.upload.addEventListener("progress", function(evt) {
										if (evt.lengthComputable) {
											var percentComplete = ((evt.loaded / evt.total) * 100);
											$(".progress-bar").width(percentComplete + '%');
											$(".progress-bar").html(percentComplete+'%');
										}
									}, false);
									return xhr;
								},
				                success: function (response) {
				                	var object = JSON.parse(response);
				                	if( object.hasOwnProperty('error') || object.hasOwnProperty('refresh') ) {
				                		$('#form-message').text(object.error);
				                		$("#form-message").css("display", "block");
				                		$('.upload_progress').hide();
				                	} else {
				                		$("#form-message").css("display", "none");
				                		$('.sidebar ul li:nth-child(3)').removeClass('active');
				                	}
				                },
				                complete: function(){
				                	// $('#load').remove();
				                }
							});
							e.preventDefault(); // prevent php click event
						// $(".avartar-picker input[type=file]").on("change", function(get) {});
					} else {
						alert("|Sorry, | Your browser doesn't support to File API")
					}
				});

										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 

	// Third step click event	
		        $(document).on("click", "#third + .process #next", function(e) {

		        	// For getting input[type=file] here used an object FormData()
					var formdata = new FormData();

					// Getting input values and append/add to object with file input
					formdata.append('applicant_img', $('#third input[type=file]')[0].files[0]);
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
	                    	$('#load').remove();
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

	// Institute add Fourth step click event
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
					handle: '.institute_list .table tbody tr:hover::after',
					cancel: '',
					update: function(){
						var rows = 0, the_row='';
							rows = $(".institute_list .table tbody tr").length;
							the_row = '.institute_list .table tbody tr:nth-child';

						for( var i=1; rows>=i; + i++ ){
							$(the_row+'('+i+') td:first-child').html(i);
						}
					}
				});

	// Delete institute from choice list
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
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 
										// 

	// Fourth step complete - click event
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

				   // Looping start for every tbody insde table
				    var target_table = $('.institute_list > table');
				    var xtd_obj = {};
				    var valid_req = true;
				    if( target_table.length != 0 ){
				    	target_table.find('thead').each(function(thead_key){
				    		if( thead_key == true ){
				    			xtd_obj[0] = 'true';
				    		}
				    	});
				        target_table.find('tbody').each(function(tbody_key, tbody_val){
				        	// Set object key as cur_shift_cat
				        	xtd_obj[0] = true;
				        	if( cur_shift_cat == 1 ){
				        		tbody_key = 1;
				        		xtd_obj[2] =false;
				        	}else if( cur_shift_cat == 2 ){
				        		tbody_key = 2;
				        		xtd_obj[1] =false;
				        	}else if( cur_shift_cat == 'true' ){
				        		tbody_key = (tbody_key+1);
				        	}
				            xtd_obj[tbody_key] = {};
				            if( tbody_key < 1 ){
				            	// If there's no tbody - null
				                xtd_obj = null;
				            }else{
				                $(tbody_val).find('tr').each(function(tr_key, tr_val){
				                	xtr_key = xtd_obj[tbody_key][tr_key] = {};
			                        $(tr_val).find('td').each(function(td_key, td_val){
			                        	if( td_key == 0 ){
			                        		xtd_obj[tbody_key][tr_key]['sl'] = $.trim($(td_val).text());
			                        	}else if( td_key == 1 ){
			                        		xtd_obj[tbody_key][tr_key]['eiin'] = $.trim($(td_val).text());
			                        	}else if( td_key == 4 ){
			                        		xtd_obj[tbody_key][tr_key]['trade'] = $.trim($(td_val).text());
			                        	}else if( td_key == 5 ){
			                        		xtd_obj[tbody_key][tr_key]['shift'] = $.trim($(td_val).text());
			                        	}
			                        });				                    
				                });
				            }
				        }); 
				    }else{
				    	xtd_obj = null;
				    }


					var forward_choiced_data = {};
					// Append data to forward
					forward_choiced_data['choice_list_data']	= xtd_obj;
					forward_choiced_data['cur_shift_cat'] 		= cur_shift_cat;
					forward_choiced_data['choice_list'] 		= 'confirm';

					$.ajax({
						type: 'POST',
						url: 'checkform.php',
						data: forward_choiced_data,
						beforeSend: function(){
							$('#load').remove();
	                    	$('#loading').prepend($('<img>',{id:'load',src:'assets/images/loading.gif'}));
	                    	$("#form-message").css("display", "none");
						},
						success: function(response){
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

	                    		// Success step
	                    		$('.sidebar ul li:nth-child(4)').removeClass('active');
	                    		$('.sidebar ul li:nth-child(4)').addClass('success'); 

	                    		// Active step
	                    		$('.sidebar ul li:nth-child(5)').addClass('active');

	                    		// Next Step
	                    		$('.institute_list + div button#next').fadeOut('slow', function() {
									$.get("additional.php", function(data) {
										$('.choice_selection').replaceWith($(data));
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

	// Select Quota Verification
				$(document).ready(function(){
					$(document).on('change', '.quota > .check-nr > input[type="checkbox"]', function(event){
						var totalchecked  = $(this).closest(".quota").find("input[type='checkbox']:checked").length;
						
						var uploadQuota  = '<div class="q_upload" style="display: none">'
									 	+ '		<input type="file" id="q_verfication" name="files[]" multiple/>'
										+ '		<label for="q_verfication">'
										+ '			<i class="far fa-cloud-upload"></i>'
										+ '			<span>Choose Picture</span>'
										+ '		</label>'
										+ '</div>';

						if ( totalchecked >= 1 ) {
							$(this).closest(".row").find(".q_not_selected").hide();
							if ( $(this).closest(".row").find(".quota_uploads > .q_upload").length ) {
								// check if .q_upload div exist in .quota_uploads div . If exist just visible it. Else append & visible it
								$(this).closest(".row").find(".quota_uploads > .q_upload").show();
							} else {
								$(this).closest(".row").find(".quota_uploads").append(uploadQuota).closest(".quota_uploads").find(".q_upload").fadeIn("slow");
							}
						} else {
							$(this).closest(".row").find(".quota_uploads > .q_upload").remove();
							$(this).closest(".row").find(".q_not_selected").fadeIn("slow");
							// var qta_insrtd = $('.quota_uploads .uploaded_file').length;

							$('.quota_uploads').find('.uploaded_file').each(function(indx, insrtd){
								$(insrtd).find('.del_q').each(function(indx, insrtd){
									var data_path = $(insrtd).data('path'); 
									var deletequotav = 'qtav_path='+data_path+'&delete_qtav=true';

									$.ajax({
								    	type: 'POST',
								    	url: 'checkform.php',
								    	data: deletequotav,
								    	context: this,
								    	success: function(response){
								    		var object = response;
								    		if( object.hasOwnProperty('error') ) {
								    			$('#form-message').text(object.error);
						                		$("#form-message").css("display", "block");
						                	} else {
						                			
						                		$(this.closest('.q_single_file span')).css('background', '#fd000030');

					                			var target_box = $(this.closest('.q_single_file')).parent();
												$(target_box).fadeOut( 'slow').promise().done(function () {
													$(target_box).remove();
												});
						                		
						                		$("#form-message").css("display", "none");
								    		}
								    	}
								    });

								});
							});
						}
						event.preventDefault();
					});
				});

	// Upload Quota Documents
				$(document).on('change', '#q_verfication', function(e){
					if (window.File && window.FileList && window.FileReader) {

						var	formdata = new FormData();
						var q_files = []
						jQuery.each(jQuery('.q_upload input[type=file]')[0].files, function(i, file) {
							q_files[i] = file;
							formdata.append([i], file);
						});

						formdata.append('upload_quota_img', true);
						var prevf = $('.quota_uploads .uploaded_file').length;
						var qta_ckd = $('.quota input[type=checkbox]:checked').length;

						q_files = (q_files.length)+prevf;

						if( q_files != qta_ckd && q_files > qta_ckd){
							formdata.append('limit', true);
						}
							$.ajax({
				                type: "POST", 
				                url: "checkform.php",
				                data: formdata, 
				                processData: false,
				                contentType: false,
				                beforeSend: function(){
				                	$('.upload_progress').show();
				                },
								xhr: function() {
									var xhr = new window.XMLHttpRequest();
									xhr.upload.addEventListener("progress", function(evt) {
										if (evt.lengthComputable) {
											var percentComplete = ((evt.loaded / evt.total) * 100);
											$(".progress-bar").width(percentComplete + '%');
										}
									}, false);
									return xhr;
								},
				                success: function (response) {
				                	var object = JSON.parse(response);

				                	if( object.hasOwnProperty('error') ) {
			                    		// Error Codes here
			                    		$('#form-message').text(object.error);
			                    		// Show warning when get error 
			                    		$("#form-message").css("display", "block");
			                    	} else {
			                    		// Hide warning when success
					                	for(var i=1; object.data.length>=i; i++ ){
					                		var indx = (i)-1;
						                	if( object.hasOwnProperty('error') ) {
						                		$('#form-message').text(object.error);
						                		$("#form-message").css("display", "block");
						                	} else {
						                		$("#form-message").css("display", "none");
						                		var qApndHTML  = '<div class="uploaded_file">';
														qApndHTML += '	<div class="q_single_file">';
														qApndHTML += '		<img data-path="'+object.data[indx]['src']+'" src="'+object.data[indx]['src']+'"/>';
														qApndHTML += '		<span>';
														qApndHTML += '			<strong title="'+object.data[indx]['name']+'">'+object.data[indx]['name']+'</strong>';
														qApndHTML += '			<p>'+fileSize(parseFloat(object.data[indx]['size']))+'</p>';
														qApndHTML += '			<button class="del_q" data-path="'+object.data[indx]['src']+'"><i class="fa fa-trash"></i></button>';
														qApndHTML += '			<button class="move_q"><i class="fa fa-arrows"></i></button>';
														qApndHTML += '		</span>';
														qApndHTML += '	</div>';
														qApndHTML += '</div>';
												
												$('.quota_uploads').append(qApndHTML).hide().fadeIn(1000);
						                	}
					                	}
					                }
				                },
				                complete: function(){
				                	// $('#load').remove();
				                }
							});
						e.preventDefault(); // prevent php click event
					} else {
						alert("|Sorry, | Your browser doesn't support to File API")
					}
				});

	// Filesize identifier
			  	function fileSize(bytes,decimalPoint) {
				   if(bytes == 0) return '0 Bytes';
				   var k = 1000,
				       dm = decimalPoint || 2,
				       sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
				       i = Math.floor(Math.log(bytes) / Math.log(k));
				   return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
				}

	// Delete quota verfication uploads
				$(document).on("click", ".q_single_file span button.del_q", function(e){
					var qtav_path = this.dataset.path;
				    var deletequotav = 'qtav_path='+qtav_path+'&delete_qtav=true';

				    $.ajax({
				    	type: 'POST',
				    	url: 'checkform.php',
				    	data: deletequotav,
				    	context: this,
				    	success: function(response){
				    		var object = response;
				    		if( object.hasOwnProperty('error') ) {
				    			$('#form-message').text(object.error);
		                		$("#form-message").css("display", "block");
		                	} else {
		                			
		                		$(this.closest('.q_single_file span')).css('background', '#fd000030');

	                			var target_box = $(this.closest('.q_single_file')).parent();
								$(target_box).fadeOut( 'slow').promise().done(function () {
									$(target_box).remove();
								});
		                		
		                		$("#form-message").css("display", "none");
				    		}
				    	}
				    });
				    e.preventDefault;
				});

	// Drag and Drop quota verfication uploads
				$( ".q_placeholder .quota_uploads" ).sortable( {
					placeholder : "q-soratble-highlight",
					handle: '.q_single_file span .move_q',
					cancel: '',
					over: function(event, ui) {
		                var cl = ui.item.attr('class');
		                $('.q-soratble-highlight').addClass(cl);
		            },
					start: function(e, ui) {
		                ui.placeholder.height(ui.helper.outerHeight()).html('<div class="placeholder_border"></div>');
		            },
		            stop: function( event, ui ) {
		            	$(ui.item).addClass("selected").siblings().removeClass('selected');
		            }
				});

	// Address -----------
					function address_query(data){
						$.ajax({
							type: 'POST',
							url: 'assets/api/addressbook.php',
							data: data,
							beforeSend: function(){
								if( data.match(/division/gi) && data.match(/district/gi) == null && data.match(/upzilla/gi) == null ){
									$('label span.address.district').fadeIn();
		                    		$('select#district').children('option:not(:first-child)').remove();
		                    		$('select#upzilla').children('option:not(:first-child)').remove();
		                    		$('select#upzilla').attr('disabled', true);
		                    		$('input#po').attr('readonly', true);
		                    		$('input#pc').attr('readonly', true);
								}else if( data.match(/division/gi) && data.match(/district/gi) && data.match(/upzilla/gi) == null ){
		                    		$('select#upzilla').children('option:not(:first-child)').remove();
		                    		$('select#upzilla').children('option:not(:first-child)').remove();
			                    	$('label span.address.upzilla').fadeIn();
		                    		$('input#po').attr('readonly', true);
		                    		$('input#pc').attr('readonly', true);
			                    }
							},
							success: function(response){
		                    	var object = JSON.parse(response);
		                    	if( object.hasOwnProperty('error') ) {
		                    		$('#form-message').text(object.error);
		                    		$("#form-message").css("display", "block");
		                    	} else {
		                    		if(object.address.hasOwnProperty('district')){
										for( var i=1; object.address['district'].length>=i; i++ ){
											var optHtml = '<option value="'+object.address['district'][(i)-1]+'">'+object.address['district'][(i)-1]+'</option>';
											$('select#district').append(optHtml);
										}
									}
		                    		if(object.address.hasOwnProperty('upzilla')){
										for( var i=1; object.address['upzilla'].length>=i; i++ ){
											var optHtml = '<option value="'+object.address['upzilla'][(i)-1]+'">'+object.address['upzilla'][(i)-1]+'</option>';
											$('select#upzilla').append(optHtml);
										}
									}
									if(object.address.hasOwnProperty('po')){
										// 
									}
								}
							},
							complete: function(){
								if( data.match(/division/gi) && data.match(/district/gi) == null && data.match(/upzilla/gi) == null ){
									$('label span.address.district').fadeOut();
									$('label + select#district').removeAttr('disabled');
								}else if( data.match(/division/gi) && data.match(/district/gi) && data.match(/upzilla/gi) == null ){
									$('label span.address.upzilla').fadeOut();
									$('label + select#upzilla').removeAttr('disabled');
								}else if( data.match(/division/gi) && data.match(/district/gi) && data.match(/upzilla/gi) ){
									$('label + input#po').removeAttr('readonly');
									$('label + input#pc').removeAttr('readonly');
								}
							}
						});
					}
					$(document).on('change', '.address #division', function(event){
						var addressval = $('.address #division').val();
						var querydata = 'division='+addressval;
						address_query(querydata);
					});
					$(document).on('change', '.address #district', function(event){
						var divisionval = $('.address #division').val();
						var districtval = $('.address #district').val();
						var querydata = 'division='+divisionval+'&district='+districtval;
						address_query(querydata);
					});
					$(document).on('change', '.address #upzilla', function(event){
						var divisionval = $('.address #division').val();
						var districtval = $('.address #district').val();
						var upzillaval = $('.address #upzilla').val();
						var querydata = 'division='+divisionval+'&district='+districtval+'&upzilla='+upzillaval;
						address_query(querydata);
					});


	// // Additional Information --- Register click event
				$(document).on('click', '.additional .process button#next', function(e){
					var formData = new FormData();

					// Quota Data
						var qta_ckd = $('.quota input[type=checkbox]:checked').length;
						var qta_files = $('.quota_uploads .uploaded_file').length;
						if( qta_ckd >= 1 ){

							var quota_data = {};
								quota_data['selected'] = {};
								quota_data['files'] = {};

							if( qta_files < 1 || qta_ckd != qta_files ){
								formData.append('minimum_files', 'no');
							}else if( qta_ckd > 3 || qta_files > 3 ){
								formData.append('critical', 'problem');
							}else{
								for( var i=1; 3>= i; i++ ){
									quota_data['selected'][(i)-1] = $('.quota .check-nr:nth-child('+i+') input[type=checkbox]:checked').val();
								}
								$('.quota_uploads').find('.uploaded_file').each(function(indx, vals){
									quota_data['files'][indx] = jQuery(this).find('img').data('path');
								});
								formData.append('quota_data', JSON.stringify(quota_data));
							}
						}
					// Address
						var address = {};
							
							address['division'] = $(".address select#division option:selected").val();
							if( address['division'] == null || address['division'] == 'undefined' || address['division'] == '' ){
								address['division'] = 'undefined';
							}
							
							address['district'] = $(".address select#district option:selected").val();
							if( address['district'] == null || address['district'] == undefined || address['district'] == '' ){
								address['district'] = 'undefined'
							}

							address['upzilla'] = $(".address select#upzilla option:selected").val();
							if( address['upzilla'] == null || address['upzilla'] == undefined || address['upzilla'] == '' ){
								address['upzilla'] = 'undefined'
							}

							address['po'] = $(".address input#po").val();
							if( address['po'] == null || address['po'] == undefined || address['po'] == '' ){
								address['po'] = 'undefined'
							}

							address['pc'] = $(".address input#pc").val();
							if( address['pc'] == null || address['pc'] == undefined || address['pc'] == '' ){
								address['pc'] = 'undefined'
							}
						formData.append('address', JSON.stringify(address));
					// Security Information
						var security = {};
							security['mobile'] = $(".security input#mobile").val();
							if( security['mobile'] == null || security['mobile'] == undefined || security['mobile'] == '' ){
								security['mobile'] = 'undefined'
							}

							security['email'] = $(".security input#email").val();
							if( security['email'] == null || security['email'] == undefined || security['email'] == '' ){
								security['email'] = 'undefined'
							}

							security['pass'] = $(".security input#pass").val();
							if( security['pass'] == null || security['pass'] == undefined || security['pass'] == '' ){
								security['pass'] = 'undefined'
							}

							security['confirm_pass'] = $(".security input#confirm_pass").val();
							if( security['confirm_pass'] == null || security['confirm_pass'] == undefined || security['confirm_pass'] == '' ){
								security['confirm_pass'] = 'undefined'
							}


					formData.append('security', JSON.stringify(security));
					formData.append('register', 'submit');
					$.ajax({
	                    type: "POST", 
	                    url: "checkform.php",
	                    data: formData, 
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
	                    		// Next Step
	                    		// $('body').css('overflow-y', 'hidden');
	                    		// $('body').css('height', '100%');

								
								
								$.get("confirm.php", function(data) {

									$('body .container-fluid.preview').remove();
									$('body').css('overflow', 'hidden');
									$('body').prepend($(data));

									$('body .container-fluid.preview').fadeIn(500);
									$('body .container-fluid.preview .container ').show("slide", { direction: "up" }, 800);

									// Basic Info
									$('ul#basic li:nth-child(1)').append(object.data.student_info['name']);
									$('ul#basic li:nth-child(2)').append(object.data.student_info['father']);
									$('ul#basic li:nth-child(3)').append(object.data.student_info['mother']);
									$('ul#basic li:nth-child(4)').append(object.data.student_info['gender']);
									$('ul#basic li:nth-child(5)').append(object.data.student_info['dob']);

									// SSC Info
									$('ul#ssc li:nth-child(1)').append(object.data.student_info['board']);
									$('ul#ssc li:nth-child(2)').append(object.data.student_info['year']);
									$('ul#ssc li:nth-child(3)').append(object.data.student_info['roll']);
									$('ul#ssc li:nth-child(4)').append(object.data.student_info['reg']);
									$('ul#ssc li:nth-child(5)').append(object.data.student_info['gpa']);

									// Payment
									switch(object.data.payment['shift_cat']){
										case 'a':
											$('ul#payment li:nth-child(1)').append('1st shift');
										break;
										case 'b':
											$('ul#payment li:nth-child(1)').append('2nd shift');
										break;
										case 'c':
											$('ul#payment li:nth-child(1)').append('1st & 2nd both shift');
										break;

									}
									$('ul#payment li:nth-child(2)').append(object.data.payment['method']);
									$('ul#payment li:nth-child(3)').append(object.data.payment['amount']+' &#2547;');
									$('ul#payment li:nth-child(4)').append(object.data.payment['trx']);

									// Subject
									for( var i=1; object.data.student_info.subject.length>=i; i++ ){
										var sbj_c = parseInt(object.data.student_info.subject[(i)-1]['code']);
										var sbj_n = object.data.student_info.subject[(i)-1]['name'];
										var sbj_g = object.data.student_info.subject[(i)-1]['grade'];
										$('ul#subject').append('<li id="'+sbj_c+'"><strong></strong></li>');
										$('ul#subject li#'+sbj_c+' strong').append(sbj_n);
										$('ul#subject li#'+sbj_c).append(sbj_g);
									}


									// Choice List
									function insHtml(indx, data){
										var insHtml  = 	'<div class="col-6" id="'+indx+'">';
											insHtml +=		'<div class="info_h preview">';
											insHtml +=			'Choice List - '+ordinal(indx)+' shift';
											insHtml +=		'</div>';
											insHtml +=		'<table class="table table-bordered">';
											insHtml +=			'<thead>';
											insHtml +=				'<th>SL</th>';
											insHtml +=				'<th>EIIN</th>';
											insHtml +=				'<th>Trade</th>';
											insHtml +=			'</thead>';
											insHtml +=			'<tbody>';
											
											insHtml +=			'</tbody>';
											insHtml +=		'</table>';
											insHtml +=	'</div>';
										$('.row#institute').append(insHtml);

										for( var s=1; choice_data[indx].length>=s; s++ ){
											var sl = choice_data[indx][(s)-1]['sl'];
											var institute = choice_data[indx][(s)-1]['institute'];
											var technology = choice_data[indx][(s)-1]['technology'];
											instrHtml  =	'<tr>';
											instrHtml +=		'<td>'+sl+'</td>';
											instrHtml +=		'<td>'+institute+'</td>';
											instrHtml +=		'<td>'+technology+'</td>';
											instrHtml +=	'</tr>';
											$('.row#institute #'+indx+' table tbody').append(instrHtml);
										}
									}

									var choice_data = object.data.choice_data;
									for( var x=1; 2>=x; x++ ){
										if( choice_data.hasOwnProperty(x) ){
											insHtml(x, choice_data);
										}
									}

									// Quota
									if( object.data.quota == null ){
										$('ul#quota').append('<li style="color: #d2d2d2;"><center>You don\'t have any selected quota</center></li>');
									}else{
										var ap_qta = object.data.quota.selected;
										for( var q=0; ap_qta.length>=q; q++ ){
											if( q == 0 ){
												// 
											}else{
												$('ul#quota').append('<li>'+ap_qta[(q)-1]+'</li>');
											}
										}
									}

									// Contact
									$('ul#contact li:nth-child(1)').append(object.data.security['mobile']);
									$('ul#contact li:nth-child(2)').append(object.data.security['email']);

									// Address
									$('ul#address li:nth-child(1)').append(object.data.address['division']);
									$('ul#address li:nth-child(2)').append(object.data.address['district']);
									$('ul#address li:nth-child(3)').append(object.data.address['upzilla']);
									$('ul#address li:nth-child(4)').append(object.data.address['po']);
									$('ul#address li:nth-child(5)').append(object.data.address['pc']);

								})

								// console.log(object.data.student_info['name']);
								// Append data to confirm page
	                    	}
	                    },
	                    complete: function(){
	                    	// Hide loading icon
	                    	$('#load').remove();
	                    }

					});
		            e.preventDefault(); // prevent php click event
				});

				// Close confirm
				$(document).on('click', '.preview button#close_confirm', function(e){
					$('body .container-fluid.preview .container ').hide("slide", { direction: "up" }, 500).promise().done(function(){
						$('body').removeAttr('style');
						$('body .container-fluid.preview').fadeOut(1500);
						$('body .container-fluid.preview').remove();
					});
				});

				$(document).on('click', '#confirm button#confirm_application', function(e){
					var agree = ($('#applicant_agree').is(':checked'));
					var confirm_data = 'confirm_application=submit';
					if( !agree ){
						confirm_data += '&agree_false';
					}
					$.ajax({
						type: 'POST',
						url: 'inc/load/db-process.php',
						data: confirm_data,
						beforeSend: function(){
							$('#load').remove();
							$('.conf_error').remove();
							$('.agree_error').remove();
							$('#loading').prepend($('<img>',{id:'load',src:'assets/images/loading.gif'}));
	                    	$("#form-message").css("display", "none");
						},
						success: function(response){
							var object = JSON.parse(response);
							$('#load').remove();
	                    	if( object.hasOwnProperty('error') ) {
	                    		if( object.error.hasOwnProperty('agree') ){
	                    			$('.alert.preview').append('<p class="agree_error">'+object.error.agree+'</p>');
	                    		}else{
	                    			$('<div class="alert alert-danger conf_error">'+object.error+'</div>').insertAfter('.alert,preview');
	                    			$('.conf_error').slideDown('fast');
	                    		}
	                    	} else {
	                    		$("#form-message").css("display", "none");

	                    		// Success step
	                    		$('.sidebar ul li:nth-child(5)').removeClass('active');
	                    		$('.sidebar ul li:nth-child(5)').addClass('success');

	                    		console.log('SUCCESS');
	                    		// $('#first').replaceWith();
	                    		var successHTML;
	                    		successHTML =   '<div class="card-body success-final">'+
	                    						'<i class="fa fa-check-circle"></i>' +
	                    						'<h2>Application successfully submitted</h2>' +
	                    						'</div>';

	                    		$('.preview_application .row').fadeOut('slow', function() {
									$('.preview_application .row').replaceWith(successHTML);
								});
								$('#form.form').fadeOut('slow', function() {
									$('#form.form.card-body').replaceWith(successHTML);
								});
	                    	}
						},
						confirm: function(){
							$('#load').remove();
						}
					});
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







// '<span class="img-delete"><i class="fa fa-times"></i></span>'
            
	