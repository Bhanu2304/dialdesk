<?php
if(isset($editform))
{
	foreach($editform as $post):
        $id = $post['Ivr']['id'];
        $first_name = $post['Ivr']['Msg'];
        $parent_id = $post['Ivr']['parent_id'];
        $hide = $post['Ivr']['hide'];
        $checked = $hide == 1 ? "checked" : "";
        $optionType = $post['Ivr']['OptionType'];
endforeach;
	

	    echo <<<EOL
    <form class="edit_data" method="post" action="">
        <img class="close" src="/images/close.png" />
        <input type="text" class="first_name" name="first_name" value="{$first_name}" placeholder="Option">
        <input type="checkbox" style="display:None" {$checked} value="{$hide}" name="showhideval" id="hide" />
        <select name="optionType" required="">
            <option value="{$optionType}" >{$optionType}</option>
            <option value="Order Info">Order Info</option>
            <option value="Product Type">Product Category</option>
            <option value="Product">Product</option>
            <option value="Add To Cart">Add To Cart</option>
            <option value="Purchase">Purchase</option>
            <option value="Talk To Agent">Talk To Agent</option>
            <option value="Quest">Questionnaire</option>
            <option value="BackParentMenu">Back To Parent Menu</option>
            <option value="BackMainMenu">Back To Main Menu</option>
            <option value="Close">Bot Close</option>
        </select>
        <input type="submit" class="edit" name="submit" value="submit">    
    </form>
EOL;
}
if(isset($addform))
{
echo <<<EOL
    <form class="add_data" method="post" action="">
        <img class="close" src="/images/close.png" />
        <input type="text" class="first_name" name="first_name" placeholder="Add Field">
        <input style="display:None" type="checkbox" name="showhideval" id="hide" />
        <select name="optionType" required="">
            <option value="">Select</option>
            <option value="Order Info">Order Info</option>
            <option value="Product Type">Product Category</option>
            <option value="Product">Product</option>
            <option value="Add To Cart">Add To Cart</option>
            <option value="Purchase">Purchase</option>
            <option value="Talk To Agent">Talk To Agent</option>
            <option value="Quest">Questionnaire</option>
            <option value="BackParentMenu">Back To Parent Menu</option>
            <option value="BackMainMenu">Back To Main Menu</option>
            <option value="Close">Bot Close</option>
        </select>
        <input type="submit" class="submit" name="submit" value="Submit">
    </form>
EOL;
	
}

if(isset($add))
{
	echo $add;
}
?>