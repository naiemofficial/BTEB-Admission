(function($){
	$(document).ready(function(){

		function processOk(target){
			var html = $('<div class="process progress"><div class="bar"></div></div>').insertAfter(target);
			var seconds = 0;
			var interval = setInterval(function(){
				seconds++;
				if(seconds == 3){
					clearInterval(interval);
					location.reload(); //Refresh page
				}
				$('.process.progress .bar').css('width', (100)+'%');
			}, 1000);

			return html;
		}

		var adm_sgnup = $(document).find('#admin_signup');
		if( adm_sgnup.length != 0 ){
			$('.alert.message').addClass('alert-primary');
			$('.alert.message').slideDown('fast');
			$('#alert-msg').text('Database already configured. Now sign up for admisnistration.');
		}

		$(document).on('click', 'button#edt_srvr', function(){
			$('input#hostname').removeAttr('readonly');
		});

		$(document).on('click', 'button#create_config', function(){
			var sendval = 'action=create_config';
			$.ajax({
				type: 'POST',
				url: '../inc/load/db-process.php',
				data: sendval,
				context: this,
				beforeSend: function(){
					$(this).prepend('<i class="fas fa-cog fa-spin"></i>');
					$('.alert.db_conf').slideUp('fast');
				},
				success: function(response){
					var object = JSON.parse(response);
					$('.alert.db_conf').slideDown('fast');
					if(object.hasOwnProperty('error')){
						$('.alert.db_conf').removeClass('alert-success');
						$('.alert.db_conf').addClass('alert-danger');
						$('#dbcon-msg').text(object.error);
					}else{
						$('.alert.db_conf').removeClass('alert-danger');
						$('.alert.db_conf').addClass('alert-success');
						$('#dbcon-msg').text(object.success);
						processOk('.alert.db_conf');
					}
				},
				complete: function(){
					$(this).find('i').remove();
				}
			});
		});

		$(document).on('click', 'button#db_config', function(){
			var hostname = $('input#hostname').val();
			var db_name = $('input#db_name').val();
			var db_action = $('input#db_action:checked').val();
			var db_user = $('input#db_user').val();
			var db_pass = $('input#db_pass').val();

			var sendData = new FormData();
			if( hostname == undefined || hostname == ''){
				sendData.append('hostname', 'undefined');
			}else{
				sendData.append('hostname', hostname);
			}
			if( db_name == undefined || db_name == ''){
				sendData.append('db_name', 'undefined');
			}else{
				sendData.append('db_name', db_name);
			}
			if( db_action == undefined || db_action == ''){
				sendData.append('db_action', 'connect');
			}else{
				sendData.append('db_action', db_action);
			}
			if( db_user == undefined || db_user == ''){
				sendData.append('db_user', 'undefined');
			}else{
				sendData.append('db_user', db_user);
			}
			if( db_pass == undefined || db_pass == ''){
				sendData.append('db_pass', '');
			}else{
				sendData.append('db_pass', db_pass);
			}
			sendData.append('db_config', 'OK');

			$.ajax({
				type: 'POST',
				url: '../inc/load/db-process.php',
				data: sendData,
				processData: false,
				contentType: false,
				beforeSend: function(){
					$('#loading').prepend($('<img>',{id:'load',src:'../assets/images/loading.gif'}));
	            	$('.alert.message').slideUp('fast');
				},
				success: function(response){
					var object = JSON.parse(response);
					$('.alert.message').slideDown('fast');
					if(object.hasOwnProperty('error')){
						$('.alert.message').removeClass('alert-success');
						$('.alert.message').addClass('alert-danger');
						$('#alert-msg').text(object.error);
					}else{
						$('.alert.message').removeClass('alert-danger');
						$('.alert.message').addClass('alert-success');
						$('#alert-msg').text(object.success);

						$('#db_config .card-body').fadeOut('slow', function(){
							$.get("admin-sign-up.php", function(data) {
								$('#db_config .card-body').replaceWith(data);
								$('#db_config .card-body').hide();
								$('#db_config .card-body').show("slide", { direction: "right" }, 600);
							});
						});
					}					
				},
				complete: function(){
					$('#loading #load').remove();
				}
			});
		});

		$(document).on('change', 'input#db_pass, input#db_user', function(){
			var db_user = $('input#db_user').val();
			var db_pass = $('input#db_pass').val();
			if( db_user == 'root' && (db_pass == 'undefined' || db_pass == '') ){
				$('<div class="blank_db_pass"><p>[NOTE]: Password can be use as blank when DB User is "root"</p></div>').insertAfter('input#db_pass');
				$('div.blank_db_pass').slideDown(500);
				$('div.blank_db_pass p').show("slide", { direction: "right" }, 600);
			}else{
				$('div.blank_db_pass p').hide("slide", { direction: "right" }, 500);
				$('div.blank_db_pass').slideUp(800);
			}
		});



		$(document).on('click', 'button#admin_sign_up', function(){
			var username = $('input#username').val();
			var name = $('input#name').val();
			var email = $('input#email').val();
			var pass = $('input#pass').val();
			var confirm_pass = $('input#confirm_pass').val();

			var sendData = new FormData();
			sendData.append('username', 'admin');
			sendData.append('role', 'dbadministrator');
			sendData.append('name', 'Database Administrator');
			sendData.append('submit_action', 'dbadmin');
			if( email == undefined || email == ''){
				sendData.append('email', 'undefined');
			}else{
				sendData.append('email', email);
			}
			if( pass == undefined || pass == ''){
				sendData.append('pass', 'undefined');
			}else{
				sendData.append('pass', pass);
			}
			if( confirm_pass == undefined || confirm_pass == ''){
				sendData.append('confirm_pass', 'undefined');
			}else{
				sendData.append('confirm_pass', confirm_pass);
			}
			sendData.append('admin_sign_up', 'OK');

			$.ajax({
				type: 'POST',
				url: '../inc/load/db-process.php',
				data: sendData,
				processData: false,
				contentType: false,
				beforeSend: function(){
					$('#loading').prepend($('<img>',{id:'load',src:'../assets/images/loading.gif'}));
	            	$('.alert.message').slideUp('fast');
				},
				success: function(response){
					var object = JSON.parse(response);
					$('.alert.message').slideDown('fast');
					if(object.hasOwnProperty('error')){
						$('.alert.message').removeClass('alert-success');
						$('.alert.message').addClass('alert-danger');
						$('#alert-msg').text(object.error);
					}else{
						$('.alert.message').removeClass('alert-danger');
						$('.alert.message').addClass('alert-success');
						$('#alert-msg').text(object.success);

						var admin_setup_complete =  '' +
							'<div class="card-body c-c scs_brdr">' +
							'	<div class="admin_setup">' +
							'		<i class="fas fa-user"></i>' +
							'		<p>Administration setup has been completed</p>' +
							'		<button type="submit" id="admin_login_btn">Click here to Login</button>' +
							'	</div>' +
							'</div>' 
						$('#db_config .card-body').fadeOut('slow', function(){
							$('#db_config.bg-white').css('padding-bottom', '70px');

							$('#db_config .card-body').replaceWith(admin_setup_complete);
							$('#db_config .card-body').hide();
							$('#db_config .card-body').show("slide", { direction: "right" }, 600);
						});

					}					
				},
				complete: function(){
					$('#db_config.bg-white').css('padding-bottom', '40px');
					$('#loading #load').remove();
				}
			});
		});

		$(document).on('click', 'button#admin_login_btn', function(){
			$('#db_config .card-body').fadeOut('slow', function(){
				$.get("../administrator/maintenance.php", function(data) {
					$('#db_config .card-body').replaceWith(data);
					$('#db_config .card-body').hide();
					$('#db_config .card-body').show("slide", { direction: "right" }, 500);
				});
			});
		});

		$(document).on('click', 'button#maintenance_login', function(){
			var username = $('#maintenance_login input#username').val();
			var password = $('#maintenance_login input#password').val();

			var sendData = new FormData();
			sendData.append('SESSID', getCookie('PHPSESSID'));
			sendData.append('maintenance_login', 'submit');

			if( username == undefined || username == ''){
				sendData.append('username', 'undefined');
			}else{
				sendData.append('username', username);
			}
			if( password == undefined || password == ''){
				sendData.append('password', 'undefined');
			}else{
				sendData.append('password', password);
			}

			$.ajax({
				type: 'POST',
				url: '../inc/load/db-process.php',
				data: sendData,
				processData: false,
				contentType: false,
				beforeSend: function(){
					$('#loading #load').remove();
					$('#loading').prepend($('<img>',{id:'load',src:'../assets/images/loading.gif'}));
	            	$('.alert.message').slideUp('fast');
				},
				success: function(response){
					var object = JSON.parse(response);
					$('.alert.message').slideDown('fast');
					if( object.hasOwnProperty('error') ){
						$('.alert.message').removeClass('alert-success');
						$('.alert.message').addClass('alert-danger');
						$('#alert-msg').text(object.error);
					}else{
						$('.alert.message').removeClass('alert-danger');
						$('.alert.message').addClass('alert-success');
						$('#alert-msg').text(object.success);
						processOk('.alert.message');
					}
				},
				complete: function(){
					$('#loading #load').remove();
				}
			});
		});

		$(document).on('click', 'button#add_admin_user', function(){
			var username = $('input#username').val();
			var role	= $("#role option:selected").val();
			var name	= $('input#name').val();
			var mobile	= $('input#mobile').val();
			var email	= $('input#email').val();
			var pass	= $('input#pass').val();
			var confirm_pass = $('input#confirm_pass').val();

			var sendData = new FormData();
			sendData.append('submit_action', 'add_admin_user');

			if( username == undefined || username == ''){
				sendData.append('username', 'undefined');
			}else{
				sendData.append('username', username);
			}
			if( role == undefined || role == ''){
				sendData.append('role', 'undefined');
			}else{
				sendData.append('role', role);
			}
			if( name == undefined || name == ''){
				sendData.append('name', 'undefined');
			}else{
				sendData.append('name', name);
			}
			if( mobile == undefined || mobile == ''){
				sendData.append('mobile', 'undefined');
			}else{
				sendData.append('mobile', mobile);
			}
			if( email == undefined || email == ''){
				sendData.append('email', 'undefined');
			}else{
				sendData.append('email', email);
			}
			if( pass == undefined || pass == ''){
				sendData.append('pass', 'undefined');
			}else{
				sendData.append('pass', pass);
			}
			if( confirm_pass == undefined || confirm_pass == ''){
				sendData.append('confirm_pass', 'undefined');
			}else{
				sendData.append('confirm_pass', confirm_pass);
			}
			sendData.append('admin_sign_up', 'OK');

			$.ajax({
				type: 'POST',
				url: '../inc/load/db-process.php',
				data: sendData,
				processData: false,
				contentType: false,
				beforeSend: function(){
					$('#loading #load').remove();
					$('#loading').prepend($('<img>',{id:'load',src:'../assets/images/loading.gif'}));
	            	$('.alert.message').slideUp('fast');
				},
				success: function(response){
					var object = JSON.parse(response);
					$('.alert.message').slideDown('fast');
					if(object.hasOwnProperty('error')){
						$('.alert.message').removeClass('alert-success');
						$('.alert.message').addClass('alert-danger');
						$('#alert-msg').text(object.error);
					}else{
						$('.alert.message').removeClass('alert-danger');
						$('.alert.message').addClass('alert-success');
						$('#alert-msg').text(object.success);

						var admin_setup_complete =  '' +
							'<div class="card-body c-c scs_brdr">' +
							'	<div class="admin_setup">' +
							'		<i class="fas fa-user"></i>' +
							'		<p>Administration setup has been completed</p>' +
							'		<button type="submit" id="admin_login_btn">Click here to Login</button>' +
							'	</div>' +
							'</div>' 
						$('#db_config .card-body').fadeOut('slow', function(){
							$('#db_config.bg-white').css('padding-bottom', '70px');

							$('#db_config .card-body').replaceWith(admin_setup_complete);
							$('#db_config .card-body').hide();
							$('#db_config .card-body').show("slide", { direction: "right" }, 600);
						});

					}					
				},
				complete: function(){
					$('#db_config.bg-white').css('padding-bottom', '40px');
					$('#loading #load').remove();
				}
			});
		});




		$(document).on('change', '#import_institute input[type=file]', function(e){
			if (window.File && window.FileList && window.FileReader) {
				$(document).find('.get_sheet_columns').slideUp('fast').promise().done(function(){
					$(document).find('.get_sheet_columns').remove();
				});
				$(document).find('#import_institute .import_result').slideUp('slow').promise().done(function(){
					$(document).find('#import_institute .import_result').remove();
				});

				var filename = ' ';
				if( e.target.files.hasOwnProperty(0) ){
					$(this).prev().addClass('active');
					filename = e.target.files[0].name;
					$(this).prev().find('.impt_file_name').remove();
					$(this).prev().append('<span class="impt_file_name"></span>');
					$(this).prev().find('.impt_file_name').html(filename);
					$('#upload_inst').hide();
					$('#upload_inst').slideDown();

					var	formData = new FormData();
					formData.append('import_inst_file', $('#import_institute input[type=file]')[0].files[0]);
					formData.append('load_data', 'OK');
					$.ajax({
						type: 'POST',
						url: '../inc/load/file-process.php',
						data: formData,
						processData: false,
						contentType: false,
						context: this,
						beforeSend: function(){
							$('.alert.message').slideUp('fast');
							$(this).prev().find('span').prepend('<i class="fas fa-cog fa-spin"></i>');
						},
						success: function(response){
							var object = JSON.parse(response);
							if(object.hasOwnProperty('error')){
								$('.alert.message').removeClass('alert-success');
								$('.alert.message').addClass('alert-danger');
								$('#alert-msg').text(object.error);
								$('.alert.message').slideDown('fast');
							}else{

								console.log(object);

								$('.alert.message').removeClass('alert-danger');
								$('#alert-msg').text('');
								var colsH = '<div class="get_sheet_columns"><div class="sheet_content_h"><h3>Column / Fileds </h3><button class="inst_import" type="submit"><i class="fa fa-cloud-upload"></i>Import</button></div></div>';
								$('#import_institute fieldset').append(colsH);
								var labels = ['eiin', 'name', 'type', 'mobile', 'email'];
								for( var i=0; i<labels.length; i++ ){
									$('#import_institute .get_sheet_columns').append('<div class="fieldSET"><label>'+labels[i]+'</label><div class="placeholder '+labels[i]+'"></div></div>');
									$('.placeholder.'+labels[i]).append('<select id="'+labels[i]+'"><option value="">Choose column</option></select>');
									
									for( var x=0; x<object.success['columns'].length; x++ ){
										$('select#'+labels[i]).append('<option value="'+x+'">'+object.success['columns'][x]+'</option>');
									}
								}
								$('.get_sheet_columns').slideDown('slow');
								console.log(object.success['columns']);
							}
						},
						complete: function(){
							$(this).prev().find('span i').remove();
						}
					});
				}else{
					$(this).prev().find('span').remove();
					$(this).prev().removeClass('active');
					$(document).find('.get_sheet_columns').slideUp('fast').promise().done(function(){
						$(document).find('.get_sheet_columns').remove();
					});
				}
			}else{
				alert("Sorry, | Your browser doesn't support to File API")
			}
		});


		function fieldSET(data){
			var val1 = $('.fieldSET select#eiin').val();
			var val2 = $('.fieldSET select#name').val();
			var val3 = $('.fieldSET select#type').val();
			var val4 = $('.fieldSET select#mobile').val();
			var val5 = $('.fieldSET select#email').val();

			if( (val1 == null || val1 == '' || val1 == 'undefined') ||
				(val2 == null || val2 == '' || val2 == 'undefined') ||
				(val3 == null || val3 == '' || val3 == 'undefined') ||
				(val4 == null || val4 == '' || val4 == 'undefined') ||
				(val5 == null || val5 == '' || val5 == 'undefined')
			){
				return false; 
			}else{
				if( data == 'bool' ){
					return true;
				}else if( data == 'data' ){
					var val = {};
						val['eiin']	= val1;
						val['name'] = val2;
						val['type'] = val3;
						val['mobile'] = val4;
						val['email']  = val5
					return val;
				}
			}
		}

		

		$(document).on('change', '.fieldSET select', function() {
			$('.fieldSET select').find('option').prop('disabled', false);
			$('.fieldSET select').each(function() {
			$('.fieldSET select').not(this).find('option[value="' + this.value + '"]').prop('disabled', true); 
			});

			if( fieldSET('bool') ){
				$('.sheet_content_h button').attr('id', 'inst_import');
				$('.sheet_content_h button').removeClass('inst_import');
			}
		});


		$(document).on('click', '#inst_import', function() {
			var	formData = new FormData();
			if( fieldSET('data') ){
				formData.append('inputs', JSON.stringify(fieldSET('data')));
			}else{
				formData.append('error', 'OK');
			}

			formData.append('import_data', 'OK');
			formData.append('import_inst_file', $('#import_institute input[type=file]')[0].files[0]);
			$.ajax({
				type: 'POST',
				url: '../inc/load/file-process.php',
				data: formData,
				processData: false,
				contentType: false,
				context: this,
				beforeSend: function(){
					$('.alert.message').slideUp('fast');
					$('.sheet_content_h .load').remove();
					$('.sheet_content_h').append('<i class="fas fa-cog fa-spin load"></i>');
				},
				success: function(response){
					var object = JSON.parse(response);
					$('.alert.message').slideDown('fast');
					if(object.hasOwnProperty('error')){
						$('.alert.message').removeClass('alert-success');
						$('.alert.message').addClass('alert-danger');
						$('#alert-msg').text(object.error);
					}else{
						/*if( object.success.hasOwnProperty('alert') ){
							$('.alert.message').removeClass('alert-danger');
							$('.alert.message').addClass('alert-success');
							$('#alert-msg').text(object.success['alert']);
						}*/
						if( object.hasOwnProperty('result') ){
							$(document).find('#import_institute .get_sheet_columns').slideUp('fast').promise().done(function(){
								$(document).find('#import_institute .get_sheet_columns').remove();
							});
							$('#import_institute fieldset').append('<div class="import_result"></div>');
							$('#import_institute .import_result').append(object.result);
							$('#import_institute .import_result').slideDown('slow');
						}
					}
				},
				complete: function(){
					$('.sheet_content_h .load').remove();
				}
			});
		});


		$(document).on('click', 'fieldset #add_institute', function() {
			var institute_eiin 	= $('input#institute_eiin').val();
			var institute_name 	= $('input#institute_name').val();
			var institute_type 	= $("#institute_type option:selected").val();
			var institute_mobile = $('input#institute_mobile').val();
			var institute_email 	= $('input#institute_email').val();

			var sendData = new FormData();

			if( institute_eiin == undefined || institute_eiin == ''){
				sendData.append('institute_eiin', 'undefined');
			}else{
				sendData.append('institute_eiin', institute_eiin);
			}
			if( institute_name == undefined || institute_name == ''){
				sendData.append('institute_name', 'undefined');
			}else{
				sendData.append('institute_name', institute_name);
			}
			if( institute_type == undefined || institute_type == ''){
				sendData.append('institute_type', 'undefined');
			}else{
				sendData.append('institute_type', institute_type);
			}
			if( institute_mobile == undefined || institute_mobile == ''){
				sendData.append('institute_mobile', 'undefined');
			}else{
				sendData.append('institute_mobile', institute_mobile);
			}
			if( institute_email == undefined || institute_email == ''){
				sendData.append('institute_email', 'undefined');
			}else{
				sendData.append('institute_email', institute_email);
			}
			sendData.append('add_institute_manually', 'OK');

			$.ajax({
				type: 'POST',
				url: '../inc/load/db-process.php',
				data: sendData,
				processData: false,
				contentType: false,
				beforeSend: function(){
					$('#loading #load').remove();
					$('#loading').prepend($('<img>',{id:'load',src:'../assets/images/loading.gif'}));
	            	$('.alert.message').slideUp('fast');
				},
				success: function(response){
					var object = JSON.parse(response);
					$('.alert.message').slideDown('fast');
					if(object.hasOwnProperty('error')){
						$('.alert.message').removeClass('alert-success');
						$('.alert.message').addClass('alert-danger');
						$('#alert-msg').text(object.error);
					}else{
						$('.alert.message').removeClass('alert-danger');
						$('.alert.message').addClass('alert-success');
						$('#alert-msg').text(object.success);
						$('#add_institute input, #add_institute select').reset();
					}					
				},
				complete: function(){
					$('#db_config.bg-white').css('padding-bottom', '40px');
					$('#loading #load').remove();
				}
			});
		});


		function getCookie(cname) {
			var name = cname + "=";
			var decodedCookie = decodeURIComponent(document.cookie);
			var ca = decodedCookie.split(';');
			for(var i = 0; i <ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
				}
			}
			return "";
		}

		


	});
})(jQuery);



