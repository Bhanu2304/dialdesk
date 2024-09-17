
<select  name="SheetName" class="form-control"  required="" onchange="this.form.submit();">
    <option value="">Select Sheet Name</option>
    <?php foreach($SheetName as $key=>$value){?>
    <option <?php if($SelectSheetName =="Category$value##$key"){echo "selected='selected'";} ?> value="Category<?php echo $value;?>##<?php echo $key;?>"><?php echo $key;?></option>
    <?php }?>
</select>
                   