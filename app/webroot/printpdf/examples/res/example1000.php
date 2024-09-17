<html>
<head>
<style>
    .main{
        border: 1px solid black;
        font-family: Arial,Calibri,Candara,Segoe,Segoe UI,Optima,sans-serif;
    }
    h4{
        color: #000000;
        text-decoration: underline;
        text-align: center;
    }
    .box-top-text{
        left: 45px;
        position: relative;
        font-size: 11px;
        
    }
    .box1{
        border: 2px dashed black;
        width:650px;
        left: 45px;
        position: relative;
        height:400px;
        margin-bottom: 50px;
    }
    .box2{
        border: 1px dotted black;
        border-radius: 5px;
        width:325px;
        left: 30px;
        position: relative;
        height:310px;
        margin-bottom: 25px;
        margin-top:15px;
        z-index: 999999;
    }
    
    .right{
        float:right;
        font-size: 14px;
        font-weight: bold;
        margin-left: 150px;
    }

    .left{
        float:left;
        font-size: 12px;
        font-weight: bold;
    }
    
    .box{
        border: 1px solid black;
        width:15px;
        height:15px;
        margin: 5px;
        margin-top:-15px;
    }
    
   .line { 
       border-bottom: 1px solid #000; 
       width: 270px;
       border-style: dotted;
   }
   
   .fline { 
       border-bottom: 1px solid #000; 
       width: 200px;
   }
   
   .pro-text{
        margin-left: 400px;
        font-size: 10px;
        margin-top:-15px;
   }
   .to{
       margin-left:50px;
       margin-top:20px;
   }
   .from{
       margin-left:30px;
       margin-bottom: 15px;
   }
   .product{
       margin-left:420px;
       text-decoration: underline;
       font-weight: bold;
   }
   .pincode{
       margin-left: 120px;
   }
   .contatc{
       margin-left: 87px;
   }
   .signature{
       margin-left:300px;
       text-decoration: underline;
       font-weight: bold;
   }
   
   .word-space{
       word-spacing: 30px;
   }
   
   
   #customers {
    /*font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;*/
    border-collapse: collapse;
    width: 100%;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}

.bx1{
    width:650px;
    position: relative;
    left: 30px;
    top:10px;
    height:310px;
    margin-bottom: 25px;
    margin-top:20px;
}

.bx2{
    width:325px;
    position: relative;
    left: 30px;
    top:10px;
    height:310px;
    margin-bottom: 25px;
    margin-top:20px;
}
.bx3{
    width:325px;
    height:310px;
    position:relative;
    left:400px;
    top:-508px;
}
/*
.bx3{ 
    width:325px;
    left: 30px;
    position: relative;
    height:310px;
    margin-bottom: 25px;
    margin-top:28px;
}

.bx4{  
    width:325px;
    height:310px;
    position:relative;
    left:400px;
    top:-520px;
}
*/
</style>

</head>
<body>

<?php

$start_date=$_GET['start_date'];
$end_date=$_GET['end_date'];
$DataId=$_GET['DataId'];


ini_set("display_errors", 1);
$con = mysql_connect("localhost","root","dial2123","false",128);
$db  = mysql_select_db("db_paypik",$con);

$sel = "SELECT * FROM tbl_qr_master WHERE id in($DataId)";
$rsc = mysql_query($sel);
$cnt = mysql_num_rows($rsc); 

include "qrlib.php";   

$i=1;
while ($dataFetch = mysql_fetch_assoc($rsc)){        
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
     
    //html PNG location prefix
   $PNG_WEB_DIR = 'temp/';

    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    $filename = $PNG_TEMP_DIR.'paypikm'.$dataFetch['Id'].'.png'; 
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 4;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

    //echo "sadfasdfds"; exit;
    if(!empty($dataFetch))
    { 
        // user data
       $filename = $PNG_TEMP_DIR.'test'.md5($dataFetch['Url'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png'; 
       QRcode::png($dataFetch['Url'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    }
    
    /*
    if($i==2){
        $box="bx2";  
    }
    else if($i==3){
        $box="bx3";
        $br="<div style='margin-top:50px;' >&nbsp;</div>";
    }*/
?>
    
    <?php //if($i ==1){ ?>
    
    <div class="bx1" style="margin-left:35px;" >
        <div style="margin-left:200px;"><img src="http://paypik.in/app/webroot/paypikm/html2pdf/examples/res/logopunchline.png" style="width:160px;" ></div>
        <div style="margin-top:30px;" >
            <div style="font-size: 13px;font-weight:bold;margin-left:75px;" >SCAN & PAY</div>
            <div style="margin-left:50px;" ><img src="http://paypik.in/app/webroot/paypikm/html2pdf/examples/res/<?php echo $PNG_WEB_DIR.basename($filename);?>" style="width:130px;" ></div>
            <div  style="margin-top:30px;">
                <div style="font-size:13px;"><strong>Merchant Name:</strong> <?php echo $dataFetch['MerName'];?></div>
                <div style="font-size:13px;"><strong>Mobile No:</strong> <?php echo $dataFetch['MobileNo'];?></div>
                <div style="font-size:13px;"><strong>QR Code ID:</strong> <?php echo $dataFetch['QrId'];?></div>
                <img  src="http://paypik.in/app/webroot/paypikm/html2pdf/examples/res/landscape.png" style="width:150px;position: relative;left:-10px;top:20px;">
            </div> 
        </div>  
        <div style="position:relative;left:35%;top:-58%" >
            <img  src="http://paypik.in/app/webroot/paypikm/html2pdf/examples/res/PaypikLandscape.png" style="width:295px;">
        </div>
    </div>
    
    <?php //}else{?>
    <div class="bx2" style="margin-left:35px;" >
        <div class="from1"> 
            <div  style="margin-left:65px;" ><img src="http://paypik.in/app/webroot/paypikm/html2pdf/examples/res/logopunchline.png" style="width:130px;" ></div>
                
            <div style="margin-top:10px;">
                <div style="font-size:10px;"><strong>Merchant Name:</strong> <?php echo $dataFetch['MerName'];?></div>
                <div style="font-size:10px;"><strong>Mobile No:</strong> <?php echo $dataFetch['MobileNo'];?></div>
                <div style="font-size:10px;"><strong>QR Code ID:</strong> <?php echo $dataFetch['QrId'];?></div>
            </div>
            
            <div style="margin-top:10px;">
                <div style="font-size: 13px;font-weight:bold;margin-left:95px;" >SCAN & PAY</div>
                <div style="margin-left:70px;" ><img style="" src="http://paypik.in/app/webroot/paypikm/html2pdf/examples/res/<?php echo $PNG_WEB_DIR.basename($filename);?>" style="width:130px;" ></div><br/>
                <div style="margin-left:-20px;" ><img style="" src="http://paypik.in/app/webroot/paypikm/html2pdf/examples/res/PaypikPortrait.png" style="width:300px;" ></div><br/>
            </div>
        </div>
    </div>
    
    <div class="bx3" style="margin-left:35px;" >
        <div class="from1"> 
            <div  style="margin-left:65px;" ><img src="http://paypik.in/app/webroot/paypikm/html2pdf/examples/res/logopunchline.png" style="width:130px;" ></div>
                
            <div style="margin-top:10px;">
                <div style="font-size:10px;"><strong>Merchant Name:</strong> <?php echo $dataFetch['MerName'];?></div>
                <div style="font-size:10px;"><strong>Mobile No:</strong> <?php echo $dataFetch['MobileNo'];?></div>
                <div style="font-size:10px;"><strong>QR Code ID:</strong> <?php echo $dataFetch['QrId'];?></div>
            </div>
            
            <div style="margin-top:10px;">
                <div style="font-size: 13px;font-weight:bold;margin-left:95px;" >SCAN & PAY</div>
                <div style="margin-left:70px;" ><img style="" src="http://paypik.in/app/webroot/paypikm/html2pdf/examples/res/<?php echo $PNG_WEB_DIR.basename($filename);?>" style="width:130px;" ></div><br/>
                <div style="margin-left:-20px;" ><img style="" src="http://paypik.in/app/webroot/paypikm/html2pdf/examples/res/PaypikPortrait.png" style="width:300px;" ></div><br/>
            </div>
        </div>
    </div>
    <?php //}?>
    
    <div style='margin-top:50px;' >&nbsp;</div>
    <?php  //if($i==1){echo $br;} ?>

<?php if($i==1){$i=0;} $i++;  }?>

 
</body>
</html>