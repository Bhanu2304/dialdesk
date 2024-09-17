$(document).ready(function(){
$('#IvrWaitForMsg').on('change',function(){
		value = $("#IvrWaitForMsg").val();
		if(value == 'Yes')
			{	
				$('#table tr:eq('+(0)+')').after('<tr><td>Welcome Msg</td><td><input type="text" value="Welcome" name="data[Ivr][msg] required"><td></td></tr>');
			}
			else
			{
				$("#table").find("tr:eq("+1+")").remove();
			}
	})});