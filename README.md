# grievance-go

# Install Notes
1. Upload the zippded plugin.
2. Activate the plugin for the website you want (or you can try Network Activate, but that's not tested).
3. Go to Dashboard : Settings : Grievance and add a valid username, password and IGS# (log in to grievancego.com and ID is in top right corner).
4. On the page you want this rendered, add id of `includedContent` like so:
```<div id="includedContent"></div>```
5. Add this inline JS to run on the page:
```javascript
	jQuery(document).ready(function($){
		$.ajax({
		        url: ajaxurl,
		        data: {
		            'action':'grievance_ajax_request'
		        }, 
		        beforeSend:function() {
		            $("#includedContent").html("<img src='/wp-content/plugins/grievance/images/ajax-loader.gif' class='grievance-loader'>");
		        },
		        success:function(data) {
		            // This outputs the result of the ajax request
		            $("#includedContent").html(data);
		        },
		        error: function(errorThrown){
		            $("#includedContent").html("There was a problem. Please check with website administrator.");
		            console.log(errorThrown);
		        }
		    });
	 });
```
