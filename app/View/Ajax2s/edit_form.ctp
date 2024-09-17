<?php

foreach($data as $post):
        $id = $data['Ivr']['id'];
        $first_name = $data['Ivr']['Msg'];
        $parent_id = $data['Ivr']['parent_id'];
        $hide = $data['Ivr']['hide'];
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

?>