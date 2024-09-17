<?php
if(isset($editform))
{
	foreach($editform as $post):
        $id = $post['Ivr']['id'];
        $first_name = $post['Ivr']['Msg'];
        $parent_id = $post['Ivr']['parent_id'];
        $hide = $post['Ivr']['hide'];
        $checked = $hide == 1 ? "checked" : ""; 		
endforeach;
	

	    echo <<<EOL
    <form class="edit_data" method="post" action="">
        <img class="close" src="images/close.png" />
        <input type="text" class="first_name" name="first_name" value="{$first_name}" placeholder="first name">
        <input type="checkbox" {$checked} value="{$hide}" name="showhideval" id="hide" />
        <label for="hide">Hide Child Nodes</label>
        <input type="submit" class="edit" name="submit" value="submit">    
    </form>
EOL;
}
if(isset($addform))
{
echo <<<EOL
    <form class="add_data" method="post" action="">
        <img class="close" src="images/close.png" />
        <input type="text" class="first_name" name="first_name" placeholder="Add Field">
        <input type="checkbox" name="showhideval" id="hide" />
        <label for="hide">Hide Child Nodes</label>
        <input type="submit" class="submit" name="submit" value="Submit">
    </form>
EOL;
	
}

if(isset($add))
{
	echo $add;
}
?>