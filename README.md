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
	var filter = 'all'; // remove or comment out if you want to limit users to see only their own greivances
	var renderTable = function(data) {
		jQuery('#grievanceTable').dataTable( {
			data: data,
			columns: [
			// configure columns here
				{ title: "Name", data: "EmployeeName" },
				{ title: "Employee #", data: "Employee" },
				{ title: "Domicile", data: "Domicile" },
				{ title: "Grievance Rep", data: "GrievanceRep" },
				{ title: "Date Filed", data: "DateFiled" },
				{ title: "Status", data: "Status" },
				{ title: "Occurence Date", data: "DateofOccurrence" },
				{ title: "Dispute Type", data: "TypeofDispute" },
				{ title: "Summary", data: "Summary" }
			]
		});
		jQuery('#grievanceContent').html('');
	};
</script>
<script type="text/javascript" src="/wp-content/plugins/grievance/media/js/load.js"></script>
```
