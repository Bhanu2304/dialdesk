function training_file_validate()
{
var inp = document.getElementById('TrainingMasterFiles');
var size; var ext;
for (var i = 0; i < inp.files.length; ++i) 
{
  var name = inp.files.item(i).name;
  size += inp.files.item(i).size;
  if(i>9)
  {
	 alert("Please Do Not Select More Than 10 Files");  
  	return false;
  }

 ext = name.substring(name.lastIndexOf('.') + 1);

  if(ext=='docx' ||
     ext=='docm' ||
     ext=='dotx' ||
     ext=='dotm' ||
     ext=='xlsx' ||
     ext=='xlsm' ||
     ext=='xltx' ||
     ext=='xltm' ||
     ext=='xlsb' ||
     ext=='xlam' ||
     ext=='pptx' ||
     ext=='pptm' ||
     ext=='ppt' ||
     ext=='ppsx' ||
     ext=='ppsm' ||
     ext=='potx' ||
     ext=='potm' ||
     ext=='ppam' ||
     ext=='sldx' ||
     ext=='sldm' ||
     ext=='gif' ||
     ext=='jpg' ||
     ext=='jpeg' ||
     ext=='png' ||
     ext=='swf' ||
     ext=='psd' ||
     ext=='bmp' 

	){}
	else{
		alert("Please Upload PPT,MS-Excel,MS-Word,Images Only");
		return false;
	  }
}

 size = size/1024;
 size = Math.ceil(size/1024);
if(size > 500)
{
alert("Do not Upload file size greater than 500 MB");
return false;
}
return true;
}