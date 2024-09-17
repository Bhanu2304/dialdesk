function ajax(url,uqry,div)
{ 
	var xmlhttp = false;
	
	if (window.XMLHttpRequest)
	{
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
        xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{     

	       	document.getElementById(div).innerHTML = xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("POST",url,true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(uqry);
}

function reallocatedata(val)
{
	var clus =  document.getElementById('sel_clus').value;
	var url  = 'ajax.php';
	var uqry = 'mode=RESHOWDATA&fos='+val+'&clus='+clus;
	var div  = 'redatadiv';
       ajax(url,uqry,div);
}

function reallocatefos(fos,data_id,oldfos)
{ 
	var url  = 'ajax.php';
	var uqry2 = 'mode=READDFOS&fos='+fos+'&data_id='+data_id+'&oldfos='+oldfos;
	var div  = 'fosdiv';
       ajax(url,uqry2,div);
}

function audit_data(val)
{
	var url  = 'ajax.php';
	var uqry = 'mode=AUDITDATA&fos='+val;
	var div  = 'auditdiv';
       ajax(url,uqry,div);
}
function count_data(val)
{
       var stats = document.getElementById('sel_status').value;
	if(stats=='')
	{ stats='';} else { stats=stats }
	var url  = 'ajax.php';
	var uqry4 = 'mode=CNTDATA&fos_id='+val+'&stats='+stats;
	var div  = 'countdiv';
       ajax(url,uqry4,div);
}

function imp_fos(val)
{ 
       var stats = document.getElementById('sel_status').value;
	var fos = document.getElementById('sel_lead').value;
	var url  = 'ajax.php';
	var uqry3 = 'mode=SHOWDATAFOS&fos_id='+fos+'&stats='+stats+'&impdate='+val;
	var div  = 'datafosdiv';
	ajax(url,uqry3,div);
}
function batch_fos(val)
{ 
       var stats = document.getElementById('sel_status').value;
	var fos = document.getElementById('sel_lead').value;
	var impdate = document.getElementById('impdate').value;
	var url  = 'ajax.php';
	var uqry3 = 'mode=SHOWDATAFOS&fos_id='+fos+'&stats='+stats+'&impdate='+impdate+'&batchfos='+val;
	var div  = 'datafosdiv';
	ajax(url,uqry3,div);
}

function imp_count(val)
{
       var stats = document.getElementById('sel_status').value;
	var fos = document.getElementById('sel_lead').value;
	var url  = 'ajax.php';
	var uqry4 = 'mode=CNTDATA&fos_id='+fos+'&stats='+stats+'&impdate='+val;
	var div  = 'countdiv';
       ajax(url,uqry4,div);
}
function batch_count(val)
{
       var stats = document.getElementById('sel_status').value;
	var fos = document.getElementById('sel_lead').value;
	var impdate = document.getElementById('impdate').value;
	var url  = 'ajax.php';
	var uqry4 = 'mode=CNTDATA&fos_id='+fos+'&stats='+stats+'&impdate='+impdate+'&batchfos='+val;
	var div  = 'countdiv';
       ajax(url,uqry4,div);
}

function showFos(val)
{	var url  = 'ajax.php';
	var uqry = 'mode=SHOWFos&clus='+val;
	var div  = 'newshow';
       ajax(url,uqry,div);
}

function shownewFos(val)
{
	var fosid=document.getElementById("hidden_fos").value;
	var url  = 'ajax.php';
	var uqry = 'mode=SHOWNEWFOS&clus='+val+'&key='+fosid;
	var div  = 'shownewfos';
	ajax(url,uqry,div);
}

function showFosMulti(val)
{ 
    if(val==1)
	{
	var val = document.getElementById('casetype').value;
	}
	var val1 = document.getElementById('sel_clus').value;
	var url  = 'ajax.php';
	var uqry = 'mode=MULTIFOS&clus='+val1+'&casetype='+val;
	var div  = 'multishow';
    ajax(url,uqry,div);
}
function showCaseType(val)
{ 
	var url  = 'ajax.php';
	var uqry = 'mode=SHOWCASE&clus='+val;
	var div  = 'showcase';
       ajax(url,uqry,div);
}

function SELFOSVIEW(val)
{	
    var visi = document.getElementById('currentstatus').value;
    var url  = 'ajax.php';
    var uqry = 'mode=SELFOS1&clus='+val+'&status='+visi;
    var div  = 'fosshow1';
    ajax(url,uqry,div);
}

function FosSelect(val)
{	
  //  alert(val);
     if(val==1)
	{
	var val = document.getElementById('Cluster').value;
	}
       var url  = 'ajax.php';
	var uqry = 'mode=SELFOS&clus='+val;
	var div  = 'fosshow';
       ajax(url,uqry,div);
}

function REFosSelect(val)
{	
   if(val==1)
	{
	var val = document.getElementById('sel_clus').value;
	}
	var url  = 'ajax.php';
	var uqry = 'mode=RESELFOS&clus='+val;
	var div  = 'refosshow';
    ajax(url,uqry,div);
}

function ShowCluster(val)
{	
    var fdate = document.getElementById('start_date').value;
	var tdate = document.getElementById('end_date').value;
    var url  = 'ajax.php';
	var uqry = 'mode=SHOWCLU&fdate='+fdate+'&tdate='+tdate+'&datetype='+val;
	var div  = 'showclu';
    ajax(url,uqry,div);
}

function ShowFOSW(val)
{	
    var url  = 'ajax.php';
    var uqry = 'mode=SHOWFOSW&clus='+val;
    var div  = 'showfosw';
    ajax(url,uqry,div);
}

function AuditFOS(val)
{
    var url  = 'ajax.php';
    var uqry = 'mode=ATFOS&clus='+val;
    var div  = 'auditfos';
    ajax(url,uqry,div);
}

function init_func()
{
	var val=document.getElementById("cluster").value;
	shownewFos(val);
}

function bodyready()
{
	if(document.readyState!=undefined)
	{
		if (document.readyState !== 'complete') { return; }
		clearTimeout(counter_timer);
		init_func();
	}
	else
	{
		clearTimeout(counter_timer);
		document.onload = init_func();
	}
}

counter_timer = setInterval(bodyready,1000);

function func_fos(val)
{ 
 	var url  = 'ajax.php';
	var uqry = 'mode=FNFS&clus='+val;
	var div  = 'showfs';
       ajax(url,uqry,div);
}
function checkuser(val)
{
 	var url  = 'ajax.php';
	var uqry = 'mode=CHKUSR&fos='+val;
	var div  = 'checkfos';
       ajax(url,uqry,div);
}
function auditfos(val)
{	
      if(val==1)
	{
	var val = document.getElementById('Cluster').value;
	}
       var url  = 'ajax.php';
	var uqry = 'mode=ADTFOS&clus='+val;
	var div  = 'fosaudit';
       ajax(url,uqry,div);
}

function show_hide(obId)
{
	var imgId="#img"+obId;
	var tblId="#tbl"+obId;
	
	if($(tblId).css('display')=="none")
	{
		$(imgId).attr('src','img/minus_icon.gif');
		$(tblId).show();		
	}
	else
	{
		$(imgId).attr('src','img/plus_icon.png');		
		$(tblId).hide();		
	}
}
function show_hide_detail(obId)
{
	var imgId="#img"+obId;
	var tblId="#tbl"+obId;
	
	if($(tblId).css('display')=="none")
	{
		$(imgId).attr('src','img/minus_icon.gif');
		$(tblId).show();		
	}
	else
	{
		$(imgId).attr('src','img/plus_icon.png');		
		$(tblId).hide();		
	}
}



function auditcalling(val,masid)
{	
    var cboxes = document.getElementsByName('checkfield[]');
    var len = cboxes.length;
	var arr = [];
    for (var i=0; i<len; i++) 
	{ 
	   if(cboxes[i].checked==true)
	   {
	   arr.push(cboxes[i].value);
       }
	}
    if(arr=='') { arr=val; } else { arr=arr; }
    var url  = 'ajax-audit.php';
    var uqry = 'data_id='+val+'&masid='+masid+'&chkdataid='+arr;
    //alert(uqry);
    var div  = 'auditdiv';
    ajax(url,uqry,div);
}

