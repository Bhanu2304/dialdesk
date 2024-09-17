<?php
class findChild
{
        			function in_parent($in_parent, $store_all_id) {
            if (in_array($in_parent, $store_all_id)) {
                
				$result = $this->Ivr->find('all',array('conditions'=>array('parent_id'=>$in_parent)));
                $html .= $in_parent == 0 ? "<ul class='tree'>" : "<ul>";
                foreach($result as $post):
                    $html .= "<li";
                    if (0)
                        $html.= " class='thide'";
                    $html .= "><div id=" . $result['Ivr']['id'] . "><span class='first_name'>" . $result['Ivr']['msg'] . "</span></div>";
                    in_parent($result['Ivr']['id'], $store_all_id);
                    $html.= "</li>";
                endforeach;
                $html.= "</ul>";								
            }			
        }

}

?>