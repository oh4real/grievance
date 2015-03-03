# grievance-go

# Install Notes
1. Upload the zippded plugin.
2. Activate the plugin for the website you want (or you can try Network Activate, but that's not tested).
3. Go to Dashboard : Settings : Grievance and add a valid IGS# (log in directly to grievancego.com and ID is in top right corner).
4. On the page you want this rendered, add minimum required content:
```<div id="includedContent"></div>
	<button id="updateSettings" value="Update" class="grievanceUpdateButton" >Update Settings</button>
	<script type="text/javascript">
		var filter = 'all'; // remove for user-limited records
		// these functions are referenced by load.js so you can develop layouts
		var renderResults = function(data) {
			// build templates and pass them thru
			includeContent(JSON.stringify(data));
		};
		var includeContent = function(html) {
			jQuery('#includedContent').html(html);
		}
	</script>
	<script type="text/javascript" src="/wp-content/plugins/grievance/media/js/load.js" />
```