jQuery(document).ready(function($){
	$('head').append($('<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.css">'));
	$('head').append($('<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.js"></script>'));
	var grievance_content = function(html) {
		$('#grievanceContent').html(html);
	};
	var build_grievance_form = function() {
		grievance_content(
			"<div id='grievance_form'>" +
			"<div><label for='grievance_user_id'>Grievance Login</label><input id='grievance_user_id' /></div>" +
			"<div><label for='grievance_password'>Grievance Password</label><input type='password' id='grievance_password' /></div>" +
			"<button id='grievance_form_submit'>Submit</button>" +
			"<button id='grievance_cancel_form'>Cancel</button>" +
			"</div>"
			);
		$('#grievance_cancel_form').click(function() {
			$('#grievance_form').remove();
			get_grievance_data();
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
					grievance_content("<img src='/wp-content/plugins/grievance/media/images/ajax-loader.gif' class='grievance-loader'>");
				},
				success:function(data) {
					// This outputs the result of the ajax request
					var dataObj = $.parseJSON(data);
					if (dataObj.status === 400) {
						$("#grievance_form").addClass('error');
					} else {
						grievance_content('<h2>Settings Updated</h2>Refresh page to see your grievances.');
					}
				},
				error: function(errorThrown){
					grievance_content("There was a problem. Please check with website administrator.");
					console.log(errorThrown);
				}
			});
		});
	};
	var get_grievance_data = function() {
		$.ajax({
			url: ajaxurl,
			data: {
				'action':'grievance_ajax_request',
				'filter': typeof(filter) !== 'undefined' ? filter : ''
			},
			beforeSend:function() {
				grievance_content("<img src='/wp-content/plugins/grievance/media/images/ajax-loader.gif' class='grievance-loader'>");
			},
			success:function(data) {
				// This outputs the result of the ajax request
				var dataObj = JSON.parse(data);
				if (dataObj.status === 401) {
					build_grievance_form();
				} else if (dataObj.status === 404){
					grievance_content("No Grievances found for you at grievancego.com.");
				} else if (dataObj.data !== null) {
					renderTable(dataObj.data);
				} else if (dataObj.html !== null) {
					grievance_content(dataObj.html);
				} else {
					grievance_content("There was a problem. Please check with website administrator.");
				}
			},
			error: function(errorThrown){
				grievance_content("There was a problem. Please check with website administrator.");
				console.log(errorThrown);
			}
		});
	};
	get_grievance_data();
});