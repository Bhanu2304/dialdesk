function fosValidateForm()
    { //alert('sdfs');
		var str=true;
		document.getElementById("msg1").innerHTML="";
		document.getElementById("msg2").innerHTML="";
		document.getElementById("msg3").innerHTML="";
		document.getElementById("msg4").innerHTML="";

        
        if(document.frmfos.frmname.value=='')
        {
            document.getElementById("msg1").innerHTML="Plz Enter name!!";
            str=false;
        }
        if(document.frmfos.frmuser.value=='')
        {
            document.getElementById("msg2").innerHTML="Plz Enter UserID!!";
            str=false;
        }
        if(document.frmfos.frmPassword.value=='')
        {
            document.getElementById("msg3").innerHTML="Plz Enter Password!!";
            str=false;
        }          
       if(document.frmfos.frmclus.value=='')
        {          
            document.getElementById("msg4").innerHTML="Plz Select Cluster!!";
            str=false;
        }           

        return str;
    }    

function manualValidateForm()
    {
        var str=true;
        //document.getElementById("status").innerHTML="";
        
        if(document.frmmanual.status.value==0)
        {
           document.frmmanual.status.focus();
		   document.getElementById("msg1").innerHTML="Plz Enter status!!";
            str=false;
        }
        
	return str;
    }    

function auditValidateForm()
    {
        var str=true;        
        if(document.frmfos.status.value==0)
        {
           document.frmfos.status.focus();
           document.getElementById("msg1").innerHTML="Plz Enter status!!";
           str=false;
        }
        if(document.frmfos.status.value=="Positive")
        {
           var i   = 1;
           var id  = "choice1";
           var obj = document.getElementById(id);
           
	    while(obj!=null)
           {
		var chkObj=document.getElementById(id);
		if(chkObj.value=="")
		{
			chkObj.focus();
              	document.getElementById("alt"+i).innerHTML="Plz Enter status!!";
			return false;
		}
		else
		{
			document.getElementById("alt"+i).innerHTML="";
		}
		i++; 
	       id  = "choice"+i;
           	obj = document.getElementById(id);
           }
	if(document.frmfos.otherselect.value==0)
        {
           document.frmfos.otherselect.focus();
           document.getElementById("msg2").innerHTML="Plz Select!!";
           str=false;
        }            
        if(document.frmfos.landmark.value=="")
        {
           document.frmfos.landmark.focus();
           document.getElementById("msg3").innerHTML="Plz Enter Landmark!!";  
           str=false;
        } 
        if(document.frmfos.risk.value=="")
        {
           document.frmfos.risk.focus();
           document.getElementById("msg4").innerHTML="Plz Select Risk!!";
           str=false;
        } 

    }
   else
    {
	if(document.frmfos.nreason.value==0)
        {
           document.frmfos.nreason.focus();
           document.getElementById("msg5").innerHTML="Plz Select!!";
           str=false;
        }      
    }
        return str;
 }    


function chgstatus(val)
    {
	  if(val=='Positive')
	   {
		 document.getElementById('showtab1').style.display='block';
		 document.getElementById('showtab2').style.display='none';  
	   }
	   else if(val=='Hold')
	   {
		 document.getElementById('showtab2').style.display='block';
		 document.getElementById('negativeReason').style.display='none';
		 document.getElementById('negativeSelect').style.display='none';
		 document.getElementById('showtab1').style.display='none';  
	   }
	   else
	   {
		  document.getElementById('showtab2').style.display='block';
		   document.getElementById('negativeReason').style.display='block';
		   document.getElementById('negativeSelect').style.display='inline';
		   document.getElementById('showtab1').style.display='none';
		}
	}
	
function chgreport(val)
    { 
	  if(val=='StandardExport')
	   {
		 document.getElementById('stndreport').style.display='block';
		 document.getElementById('finalstat').style.display='none';
	   }

	  else if(val=='FinalStatusExport')
	   {
		 document.getElementById('finalstat').style.display='block';
		 document.getElementById('stndreport').style.display='none';
	   }

      else
	   {
		 document.getElementById('stndreport').style.display='none';
	     document.getElementById('finalstat').style.display='none';
	   }
	}
function citywise(val)
    { 
	  if(val=='CityWise')
	   {
		 document.getElementById('cityw').style.display='block'; 
		 document.getElementById('fosw').style.display='none';
	   }

	  else if(val=='FOSWise')
	   {
		 document.getElementById('cityw').style.display='none';
		 document.getElementById('fosw').style.display='block';
	   }

     else
	   {
		 document.getElementById('cityw').style.display='none';
	   }
	}
	


function copytext()
{ 
   var DetailName = document.getElementById("DetailName").value; 
   var cpvstat = document.getElementById("status").value; 
   var date=	new Date();
   
     if(cpvstat=='Positive')
      { 
		 if(DetailName=='COCP') 
		 { //alert('sdf sd');
        document.getElementById("Officeremarks").value=  (date.getDate()) + "-" + (date.getMonth()+1) + "-" + (date.getFullYear())+ " On Visit Met with applicant "+document.getElementById("cntrelation").value+" "+document.getElementById("cntperson").value+" CONFIRM ALL DETAIL AND C.P. CONFIRMED THAT FIRM IS RUNNING  "+document.getElementById("firmname").value+" "+document.getElementById("Organisation").value+" FROM "+get_choice('Years of Business')+" ON "+get_choice('Office Ownership')+" AND ACCORDING TO C.P. "+get_choice('Number of Employees')+" Employees ARE WORKING IN THIS FIRM USER OFFICAL USE SIM CARD RECEIVED AND CONFIRMED ID CHK "+document.getElementById("ProofCheck").value+" "+document.getElementById("idproofno").value+" Alt no. "+document.getElementById("altno").value+" "+document.getElementById("landmark").value+" "+document.getElementById("frmother").value+" AD "+document.getElementById("NearestADLocation").value+" "+document.getElementById("emailid").value+""; 
		 }
		 else if(get_choice('Occupation')=='Farmer ')
		 { //alert(DetailName);
        document.getElementById("remarks").value=  (date.getDate()) + "-" + (date.getMonth()+1) + "-" + (date.getFullYear())+ " On Visit Met with applicant Authorized Person "+document.getElementById("cntrelation").value+" "+document.getElementById("cntperson").value+". Applicant is (a/doing/running)  "+get_choice('Occupation')+" "+get_choice('Acres of land')+" IN "+document.getElementById("Organisation").value+" From "+get_choice('Yr of Service / Business')+" income "+get_choice('Salary')+" Living in family  "+ get_choice('Ownership of House')+" house from last "+get_choice('Year Of Stay')+" Sim card received  ID Proof check "+document.getElementById("ProofCheck").value+" "+document.getElementById("idproofno").value+" Distance "+document.getElementById("Distance").value+" "+document.getElementById("altno").value+" "+document.getElementById("landmark").value+" AD "+document.getElementById("NearestADLocation").value+" "+document.getElementById("emailid").value+" "+document.getElementById("BillOnMail").value+" "+document.getElementById("VodafoneAppEducated").value+""; 
		 }
		 else{
			 //alert(DetailName);
        document.getElementById("remarks").value=  (date.getDate()) + "-" + (date.getMonth()+1) + "-" + (date.getFullYear())+ " On Visit Met with applicant Authorized Person "+document.getElementById("cntrelation").value+" "+document.getElementById("cntperson").value+". Applicant is (a/doing/running)  "+get_choice('Occupation')+" IN "+document.getElementById("Organisation").value+" From "+get_choice('Yr of Service / Business')+" income "+get_choice('Salary')+" Living in family  "+ get_choice('Ownership of House')+" house from last "+get_choice('Year Of Stay')+" Sim card received  ID Proof check "+document.getElementById("ProofCheck").value+" "+document.getElementById("idproofno").value+" Distance "+document.getElementById("Distance").value+" "+document.getElementById("altno").value+" "+document.getElementById("landmark").value+" AD "+document.getElementById("NearestADLocation").value+" "+document.getElementById("emailid").value+" "+document.getElementById("BillOnMail").value+" "+document.getElementById("VodafoneAppEducated").value+""; 
			 }
	  }
     else
      {
        document.getElementById("remarks").value=  document.getElementById("negativeremarks").value; 

      }   
 }

function auditcopytext()
{ 
   var DetailName = document.getElementById("DetailName").value;
   var cpvstat = document.getElementById("status").value; 

    var date=	new Date();
   if(document.getElementById("chkremarks").checked==true)
   {  
       if(cpvstat=='Positive')
      { //alert('ddd');
		 if(DetailName=='COCP') 
		 {
		  document.getElementById("Officeremarks").value=  (date.getDate()) + "-" + (date.getMonth()+1) + "-" + (date.getFullYear())+ " On Visit Met with applicant "+document.getElementById("cntrelation").value+" "+document.getElementById("cntperson").value+" CONFIRM ALL DETAIL AND C.P. CONFIRMED THAT FIRM IS RUNNING  "+document.getElementById("firmname").value+" "+document.getElementById("Organisation").value+" FROM "+get_choice('Years of Business')+" ON "+get_choice('Office Ownership')+" AND ACCORDING TO C.P. "+get_choice('Number of Employees')+" Employees ARE WORKING IN THIS FIRM USER OFFICAL USE SIM CARD RECEIVED AND CONFIRMED ID CHK "+document.getElementById("ProofCheck").value+" "+document.getElementById("idproofno").value+" Alt no. "+document.getElementById("altno").value+" "+document.getElementById("landmark").value+" "+document.getElementById("frmother").value+" AD "+document.getElementById("NearestADLocation").value+" "+document.getElementById("emailid").value+""; 
		 }
		 else if(get_choice('Occupation')=='Farmer ')
		 { //alert(DetailName);
        document.getElementById("remarks").value=  (date.getDate()) + "-" + (date.getMonth()+1) + "-" + (date.getFullYear())+ " On Visit Met with applicant Authorized Person "+document.getElementById("cntrelation").value+" "+document.getElementById("cntperson").value+". Applicant is (a/doing/running)  "+get_choice('Occupation')+" "+get_choice('Acres of land')+" IN "+document.getElementById("Organisation").value+" From "+get_choice('Yr of Service / Business')+" income "+get_choice('Salary')+" Living in family  "+ get_choice('Ownership of House')+" house from last "+get_choice('Year Of Stay')+" Sim card received  ID Proof check "+document.getElementById("ProofCheck").value+" "+document.getElementById("idproofno").value+" Distance "+document.getElementById("Distance").value+" "+document.getElementById("altno").value+" "+document.getElementById("landmark").value+" AD "+document.getElementById("NearestADLocation").value+" "+document.getElementById("emailid").value+" "+document.getElementById("BillOnMail").value+" "+document.getElementById("VodafoneAppEducated").value+""; 
		 }
		 else
		 { //alert(DetailName);
        document.getElementById("remarks").value=  (date.getDate()) + "-" + (date.getMonth()+1) + "-" + (date.getFullYear())+ " On Visit Met with applicant Authorized Person "+document.getElementById("cntrelation").value+" "+document.getElementById("cntperson").value+". Applicant is (a/doing/running)  "+get_choice('Occupation')+" IN "+document.getElementById("Organisation").value+" From "+get_choice('Yr of Service / Business')+" income "+get_choice('Salary')+" Living in family  "+ get_choice('Ownership of House')+" house from last "+get_choice('Year Of Stay')+" Sim card received  ID Proof check "+document.getElementById("ProofCheck").value+" "+document.getElementById("idproofno").value+" Distance "+document.getElementById("Distance").value+"  "+document.getElementById("altno").value+" "+document.getElementById("landmark").value+" AD "+document.getElementById("NearestADLocation").value+" "+document.getElementById("emailid").value+" "+document.getElementById("BillOnMail").value+" "+document.getElementById("VodafoneAppEducated").value+""; 
			 }
	   }
     else
      {
        document.getElementById("remarks").value=  document.getElementById("negativeremarks").value; 
      }   
    } 
   else
    {
         document.getElementById("remarks").value=  document.getElementById("negativeremarks").value; 
    }
}


function get_choice(valObj)
{
	var id;
	var val;
	var arrObj=new Array();

	for(j=0;j<=50;j++)
	{
		var tmpid=j+1;
		var tmpid="questions"+tmpid;	
		if(document.getElementById(tmpid)!=null)
		{ 
			arrObj[j]=document.getElementById(tmpid).value;
		}
		else { break; }
	}
	for(i=0;i<arrObj.length;i++) { if(arrObj[i]==valObj) { id=i+1; id="choice"+id; break; } }
	
	if(document.getElementById(id)!=undefined) { val=document.getElementById(id).value; }
	
	return val;
}


function auditCalling()
    {
		var str=true;
		document.getElementById("msg1").innerHTML="";
		document.getElementById("msg2").innerHTML="";

        if(document.frmentry.CallingStatus.value=='')
        {
            document.getElementById("msg1").innerHTML="Plz Select Status!!";
            str=false;
        }
        if(document.frmentry.Remakrs.value=='')
        {
            document.getElementById("msg2").innerHTML="Plz Enter Remark!!";
            str=false;
        }
        return str;
    }    
function DisbaleNTP(val)
{ 
	if(val!='Self')
	{
	  document.getElementById("NTPStatus").disabled = true;	
	  document.getElementById("DataPack").disabled = true;
	  document.getElementById("date").disabled = false;	
	}
	else
	{
	  document.getElementById("NTPStatus").disabled = false;	
	  document.getElementById("DataPack").disabled = false;
	  document.getElementById("date").disabled = true;	
	}
}
function DisbaleMetTime(val)
{ 
	if(val!='Self')
	{
	  document.getElementById("date").disabled = false;	
	}
	else
	{
	  document.getElementById("date").disabled = true;	
	}
}