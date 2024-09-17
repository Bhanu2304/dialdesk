$(document).ready(function()
{$("#ObImportsCampaignName").on('change',function(){
	 a = $("#ObImportsCampaignName").val();
	
	document.getElementById("nn").innerHTML = '<a href="download?id='+a+'">Download</a>';
	document.getElementById("note").innerHTML = '<br/>Note: <span  style="color:#000099">After download format plz convert in csv to upload Campaign data.</span><br/><br/>';	
	})});