

function inbound_getChild(id)
{
var i;
if(id=='AgentsCategory1') i = 1;
if(id=='AgentsCategory2') i = 2;
if(id=='AgentsCategory3') i = 3;
if(id=='AgentsCategory4') i = 4;
if(id=='AgentsCategory5') i = 5;
var labeld = i;
for(; i<5; i++)
{
	try{
		document.getElementById("AgentsCategory"+(i+1)).value;					
		document.getElementById("cat"+i).innerHTML = '';
		}
		catch(err)
		{}
}

	var parent= document.getElementById("AgentsCategory1").value;
	var label = 1;

	if(parent==''){ return;}
	
	try{
		
		 parent = document.getElementById("AgentsCategory2").value;
		 label =2;
		 
		 
		 parent = document.getElementById("AgentsCategory3").value;
		 label =3;
		
		
		 parent = document.getElementById("AgentsCategory4").value;
		 label =4;
		 
		 
		 parent = document.getElementById("AgentsCategory5").value;
		 label =5;	
		 
		 }
	catch(err)
	{
		
	}
	
	$.post("/dialdesk/Agents/getChild",
        {
          Parent: parent,
          Label: label
        },
        function(data,status){
            
            
            
            //alert("Data: " + data + "\nStatus: " + status);
			if(data!='')
			{
				//var table = document.getElementById("cat1");
				//var row   = table.insertRow(label);
				//var cell1 = row.insertCell(0);
				//var cell2 = row.insertCell(1);
                                if((label+1)==2)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Sub Scenarios"+(label)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat1").innerHTML = column;
                                }
                                if((label+1)==3)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Sub Scenarios"+(label)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat2").innerHTML = column;
                                }
                                if((label+1)==4)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Sub Scenarios"+(label)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat3").innerHTML = column;
                                }
                                if((label+1)==5)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Sub Scenarios"+(label)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat4").innerHTML = column;
                                }
                                //cell1.innerHTML = "Category"+(label+1);
				//cell2.innerHTML = data;
                                
			}
                        
        });
	
}


function outbound_getChild(id)
{
var i;
if(id=='OutboundCategory1') i = 1;
if(id=='OutboundCategory2') i = 2;
if(id=='OutboundCategory3') i = 3;
if(id=='OutboundCategory4') i = 4;
if(id=='OutboundCategory5') i = 5;
var labeld = i;
for(; i<5; i++)
{
	try{
		document.getElementById("OutboundCategory"+(i+1)).value;					
		//document.getElementById("cat1").deleteRow(labeld);
                document.getElementById("cat"+i).innerHTML = '';
		}
		catch(err)
		{}
}

	var parent= document.getElementById("OutboundCategory1").value;
	var label = 1;

	if(parent==''){ return;}
	
	try{
		
		 parent = document.getElementById("OutboundCategory2").value;
		 label =2;
		 
		 
		 parent = document.getElementById("OutboundCategory3").value;
		 label =3;
		
		
		 parent = document.getElementById("OutboundCategory4").value;
		 label =4;
		 
		 
		 parent = document.getElementById("OutboundCategory5").value;
		 label =5;	
		 
		 }
	catch(err)
	{
		
	}
	
	$.post("/agent/OutboundCategories/getChild",
        {
          Parent: parent,
          Label: label
        },
        function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
			if(data!='')
			{
                            /*
				var table = document.getElementById("cat1");
				var row   = table.insertRow(label);
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				cell1.innerHTML = "Category"+(label+1);
				cell2.innerHTML = data;
                                */
                               
                                    if((label+1)==2)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Category"+(label+1)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat1").innerHTML = column;
                                }
                                if((label+1)==3)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Category"+(label+1)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat2").innerHTML = column;
                                }
                                if((label+1)==4)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Category"+(label+1)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat3").innerHTML = column;
                                }
                                if((label+1)==5)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Category"+(label+1)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat4").innerHTML = column;
                                }
                               
			}			
        });
	
}


function checkCharacter(e,t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
                return false;
                }
                 return true;
               
            }
            catch (err) {
                alert(err.Description);
            }
   }
