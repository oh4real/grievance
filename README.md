# grievance

# Install Notes
1. Upload the zipped plugin. If pulled from github, remove "-master" from zip name.
2. Activate the plugin for the website you want (or you can try Network Activate, but that's not tested).
3. Go to Dashboard : Settings : Grievance and add a valid IGS# (log in directly to grievancego.com and ID is in top right corner).
4. On the page you want this rendered, add minimum required content:
```
<div id="grievanceContent"></div>
<table id="grievanceTable"></table>
<script type="text/javascript">
	// define filter with value 'all' to see all records, otherwise default of user only.
	var filter = 'all'; 
	// adjust columns as needed per https://datatables.net/reference/option/#Columns
	var dataColumns = [
		{ title: "Grievance Number", data: "GrievNbr"},
		{ title: "Name", data: "EmployeeName" },
		{ title: "Employee #", data: "Employee" },
		{ title: "Domicile", data: "Domicile" },
		{ title: "Grievance Rep", data: "GrievanceRep" },
		{ title: "Date Filed", data: "DateFiled" },
		{ title: "Status", data: "Status" },
		{ title: "Occurence Date", data: "DateofOccurrence" },
		{ title: "Dispute Type", data: "TypeofDispute" },
		{ title: "Summary", data: "Summary" }
	];
</script>
<script type="text/javascript" src="/wp-content/plugins/grievance/media/js/load.js"></script>
```

# Testing
1. Execute the test.php from command line with 3 arguments: username password ID
```
php test.php UserName PassWord IGS####
```
2. The test.php will write the JSON output to /tmp/response.json
3. If the script is short and no output, then there might be an error, but Grievance_Client::plugin_ajax_request() blocks errors (too verbose from HTML parsing). Comment out the line:
```
error_reporting(E_PARSE);
```
