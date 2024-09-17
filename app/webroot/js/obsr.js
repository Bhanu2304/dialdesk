$(document).ready(function(){
    $("#ObSrCreationsCampaignName").on('change',function(){
	var a = ("#ObSrCreationsCampaignName").val();
	document.getElementById("#nn").innerHTML = '<a href="ObSrCreation/download?id='+a+'">Download</a>';	
    });
});