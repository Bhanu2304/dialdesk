<div class="modal-body">
    <div class="panel-body detail">
        <div class="tab-content">
            <div class="tab-pane active" id="horizontal-form">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables "> 
                    <tr>
                        <td>MSISDN</td>
                        <td><?php echo $history['ObcallMasterout']['MSISDN']; ?></td>
                    </tr>
                    <?php 
                        $keys = array_keys($ecr);
                        foreach($keys as $k){ ?>
                        <tr>
                            <td>Category <?php echo $k;?></td>
                            <td><?php echo $history['ObcallMasterout']["Category".$k]?></td>
                        </tr>
                    <?php }
                     $j=1;
                    foreach($fieldName as $post){ 
                        echo "<tr>";
                         echo "<td>".$post['ObField']['FieldName']."</td>";
                        echo "<td>".$history['ObcallMasterout']['Field'.$j]."</td>";
                        echo "</tr>";
                         $j++;
                    }
                    ?>
                    <tr>
                        <td>Call Date</td>
                        <td><?php echo $history['ObcallMasterout']["CallDate"]?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>   
</div>
<div class="modal-footer">
    <button type="button" id="close-sr-popup" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

     



			





