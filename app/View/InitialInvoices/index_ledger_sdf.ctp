<?php //print_r($branch_master); ?>
<?php $this->Form->create('Add',array('controller'=>'AddInvParticular','action'=>'view')); ?>
<?php foreach($branch_master as $post) :
	$data[$post['Addbranch']['branch_name']]=$post['Addbranch']['branch_name'];
	endforeach;
?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >Invoice</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Billing">View Exposure</a></li>
</ol>
<div class="page-heading">            
    <h1>View Exposure</h1>
</div>
<style>
    .message{color: green;}
    .left_bord 
    {
        border-right: thin solid;
        border-color: black;
    }

        .page-header 
        {
            margin: 0;
            font-weight: 800;
        }

        /*
        modal
        -----
        */

        .modalBox {

            position: fixed;
            z-index: 99;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background: rgba(0, 0, 0, 0.6);
        }
            .modalContent {
                background: white;
                padding: 40px 40px 20px;
                width: 30%;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                animation-name: modalZoom;
                animation-duration: 0.3s;

            }

            .close {
                color: black;
                position: absolute;
                top: 2px;
                right: 10px;
                font-size: 24px;
                cursor: pointer;
                transition: color 0.3s linear;

            }

            
        @-webkit-keyframes modalZoom {
            from {
                transform: translate(-50%, -50%) scale(0);
            }

            to {
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes modalZoom {
            from {
                transform: translate(-50%, -50%) scale(0);
            }

            to {
                transform: translate(-50%, -50%) scale(1);
            }
        }



</style>
<script>
    function onMonth()
    {
        $('#form_month').submit();
    }

    function checkNumber(val,evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        
        if (charCode> 31 && (charCode < 48 || charCode > 57) )
            {            
                return false;
            }
            if(val.length>10)
            {
                return false;
            }
        return true;
    }

    function modalsms(id)
    {

        $(".containe"+id).show();

    $(".close"+id).click(function(){
        $(".containe"+id).hide();
    });

    }
</script>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
            
            
            <div class="panel-body" style="margin-top:-10px;">
            <?php 
            date_default_timezone_set('Asia/Kolkata');

                    if (date('m') <= 3) {
                        $financial_year = (date('Y')-1) . '-' . date('Y');
                    } else {
                        $financial_year = date('Y') . '-' . (date('Y') + 1);
                    }
            ?>
            <h4 class="page-header text-center"><span> <?= date('F');?> </span> ( <span> <?= $financial_year;?></span> ) 
            <br><?php echo $this->Session->flash(); ?></h4>
<!--                <form id="form_month" method="post" action="index_ledger">
                <select name="month" id="month" onchange="onMonth()">
                    <option value="">Select</option>
                    <option value="Jan" <?php if($month=='Jan'){echo 'selected';} ?>>Jan</option>
                    <option value="Feb" <?php if($month=='Feb'){echo 'selected';} ?>>Feb</option>
                    <option value="Mar" <?php if($month=='Mar'){echo 'selected';} ?>>Mar</option>
                </select> 
                </form>-->
              <div>
        <p style="font-size: 20px;">Sorry for the inconvenience. We&rsquo;re performing some maintenance at the moment. If you need to you can always follow us on for updates, otherwise we&rsquo;ll be back up shortly!</p>
        <p>&mdash; IT Team</p>
    </div>
            </div>
	</div>
    </div>
</div>



<?php $this->Form->end();?>

