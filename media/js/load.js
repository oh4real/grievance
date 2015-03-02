jQuery(document).ready(function($){
	var build_grievance_form = function() {
		includeContent(
			"<div id='grievance_form'>" +
			"<div><label for='grievance_user_id'>Grievance Login</label><input id='grievance_user_id' /></div>" +
			"<div><label for='grievance_password'>Grievance Password</label><input type='password' id='grievance_password' /></div>" +
			"<button id='grievance_form_submit'>Submit</button>" +
			"<button id='grievance_cancel_form'>Cancel</button>" +
			"</div>"
			);
		$('#updateSettings').toggle();
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
					includeContent("<img src='/wp-content/plugins/grievance/media/images/ajax-loader.gif' class='grievance-loader'>");
				},
				success:function(data) {
					// This outputs the result of the ajax request
					var dataObj = $.parseJSON(data);
					if (dataObj.status === 400) {
						$("#grievance_form").addClass('error');
					} else {
						includeContent('<h2>Settings Updated</h2>Refresh page to see your grievances.');
						$('#updateSettings').toggle();
					}
				},
				error: function(errorThrown){
					includeContent("There was a problem. Please check with website administrator.");
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
				includeContent("<img src='/wp-content/plugins/grievance/media/images/ajax-loader.gif' class='grievance-loader'>");
			},
			success:function(data) {
				// This outputs the result of the ajax request
				var dataObj = JSON.parse(data);
				if (dataObj.status === 401) {
					build_grievance_form();
				} else if (dataObj.status === 404){
					includeContent("No Grievances found for you at grievancego.com.");
				} else if (dataObj.data !== null) {
					renderResults(dataObj.data);
				} else if (dataObj.html !== null) {
					includeContent(dataObj.html);
				} else {
					includeContent("There was a problem. Please check with website administrator.");
				}
			},
			error: function(errorThrown){
				includeContent("There was a problem. Please check with website administrator.");
				console.log(errorThrown);
			}
		});
	};
	$('#updateSettings').click(function(){
		build_grievance_form();
	});
	get_grievance_table();
});