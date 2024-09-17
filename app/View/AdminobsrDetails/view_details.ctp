<?php 
$NewField = explode(',',$Data[0][0]['CatField']);
$ArrCnt   = count($NewField);
?>
<div class="srpopup">
    <div class="cancle" onclick="closePopup()">&times;</div> 
    <div class="divborder">
        <table>
            <tr>
                <td>MSISDN</td>
                <td><div style="margin-left:100px;">-----</div></td>
                <td><div style="margin-left:100px;"></div></td>
                <td><?php echo $data[1];?></td>
            </tr>
            <?php for($i=0;$i<$ArrCnt;$i++){?>
            <tr>
                <td><?php echo is_numeric($NewField[$i])?'Category'.$NewField[$i]:$NewField[$i]; ?></td>
                <td><div style="margin-left:100px;">-----</div></td>
                <td><div style="margin-left:100px;"></div></td>
                <td><?php echo $data[2+$i];?></td>
            </tr>
            <?php }?>
        </table>
    </div>
    <div class="divborder">
        <h4>Close Loop Category</h4>
        <table>
            <tr>
                <td>Close Loop Cat1</td>
              	<td><div style="margin-left:100px;">-----</div></td>
              	<td><div style="margin-left:100px;"></div></td>
               	<td><?php if(isset($close_category[0]['close_cat1'])){echo $close_category[0]['close_cat1'];}?></td>
            </tr>
          
            <tr>
                <td>Close Loop Cat2</td>
                <td><div style="margin-left:100px;">-----</div></td>
                <td><div style="margin-left:100px;"></div></td>
                <td><?php if(isset($close_category[0]['close_cat2'])){echo $close_category[0]['close_cat2'];}?></td>
            </tr>
        </table>
        <?php 
        $url= $this->webroot.'SrDetails/update_closeloop';
        $cat1=$close_category[0]['close_cat1'];
        $cat2=$close_category[0]['close_cat2'];
        $srno=$close_category[0]['srno'];
        ?>
        <p class="signin button"> 
            <input type="button" class="btn btn-raised btn-default btn-primary" value="Update" onclick="updateCloseLoop('<?php echo $url;?>','<?php echo $cat1;?>','<?php echo $cat2;?>','<?php echo $srno;?>');"  /> 
        </p>
    </div>	
</div>


			





