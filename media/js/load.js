jQuery(document).ready(function($){
	var build_grievance_form = function() {
		$("#includedContent").html(
			"<div id='grievance_form'><div><label for='grievance_user_id'>Grievance Login</label><input id='grievance_user_id' /></div><div><label for='grievance_password'>Grievance Password</label><input type='password' id='grievance_password' /></div><button id='grievance_form_submit'>Submit</button><button id='grievance_cancel_form'>Cancel</button></div>"
			);
		$('#grievance_cancel_form').click(function() {
			$('#grievance_form').remove();
			$('#updateSettings').toggle();
			get_grievance_table();
		});
		$('#grievance_form_submit').click(function(){
			$.ajax({
				url: ajaxurl,
				method: 'post',
				data: {
					'action':'grievance_ajax_form_request',
					'formData': {
						'grievance_user_id' : $('#grievance_user_id').val(),
						'grievance_password' : $('#grievance_password').val()
					}
				},
				beforeSend:function() {
					$("#includedContent").html("<img src='/wp-content/plugins/grievance/media/images/ajax-loader.gif' class='grievance-loader'>");
				},
				success:function(data) {
					// This outputs the result of the ajax request
					var dataObj = $.parseJSON(data);
					if (dataObj.status === '404') {
						$("#grievance_form").addClass('error');
					} else {
						$("#includedContent").html('<h2>Settings Updated</h2>Refresh page to see your grievances.');
						$('#updateSettings').toggle();
					}
				},
				error: function(errorThrown){
					$("#includedContent").html("There was a problem. Please check with website administrator.");
					console.log(errorThrown);
				}
			});
		});
	};
	var get_grievance_table = function() {
		$.ajax({
			url: ajaxurl,
			data: {
				'action':'grievance_ajax_request'
			},
			beforeSend:function() {
				$("#includedContent").html("<img src='/wp-content/plugins/grievance/media/images/ajax-loader.gif' class='grievance-loader'>");
			},
			success:function(data) {
				// This outputs the result of the ajax request
				var dataObj = $.parseJSON(data);
				if (dataObj.status === '404') {
					$('#updateSettings').toggle();
					build_grievance_form();
				} else if (dataObj.data !== null) {
					$("#includedContent").html(dataObj.data);
				} else {
					$("#includedContent").html("No Grievances found for you at grievancego.com.");
				}
			},
			error: function(errorThrown){
				$("#includedContent").html("There was a problem. Please check with website administrator.");
				console.log(errorThrown);
			}
		});
	};
	$('#updateSettings').click(function(){
		$('#updateSettings').toggle();
		build_grievance_form();
	});
	get_grievance_table();
});