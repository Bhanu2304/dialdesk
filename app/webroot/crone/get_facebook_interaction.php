<?php
//$db1=mysql_connect('192.168.137.230','root','vicidialnow');
$db1=mysql_connect('localhost','root','dial@mas123');
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

include('php-sdk/src/facebook.php');

$qry = mysql_query("SELECT * FROM fb_master ",$db1);
while($data = mysql_fetch_assoc($qry)){
    
    $facebook = new Facebook(array(
        'appId' => $data['fb_app_id'],
        'secret' => $data['fb_app_secret'],
        'default_graph_version' =>'v2.8'
    ));
      
    $access_token = $facebook->getAccessToken();

    for($i=1;$i<=5;$i++){
        
    $data['fb_page_id']=$data['fb_page_id'.$i];
    $data['fb_page_name']=$data['fb_page_name'.$i];
    $data['fb_page_token']=$data['fb_page_token'.$i];
    
    $fbpost = $facebook->api('/'.$data['fb_page_id'].'/feed?fields=from,message,full_picture,created_time');
    
    echo "<pre>";
    print_r($fbpost["data"]);
    echo "</pre>";
    
   
    if (isset($fbpost["data"]) && !empty($fbpost["data"])) {
        foreach($fbpost["data"] as $post){
            
            $date_source = strtotime($post['created_time']);
            $post['created_time'] = date('Y-m-d H:i:s', $date_source);
            
            $postExist = mysql_query("SELECT id FROM social_media_feedback WHERE message_id='{$post['id']}' and client_id='{$data['client_id']}' and social_media_type='facebook' and sender_type='Wall Post'",$db1);
            $numrow=mysql_num_rows($postExist);
            if($numrow == 0){
                mysql_query("INSERT INTO social_media_feedback(client_id,social_media_type,page_id,page_name,access_token,message,message_id,picture,sender_name,sender_id,sender_type,sender_time)VALUES('{$data['client_id']}','{$data['social_media_type']}','{$data['fb_page_id']}','{$data['fb_page_name']}','{$data['fb_page_token']}','{$post['message']}','{$post['id']}','{$post['full_picture']}','{$post['from']['name']}','{$post['from']['id']}','Wall Post','{$post['created_time']}')",$db1); 
            }        
        }   
    }
    
    //comment sourse code
    
    $fb_comments = $facebook->api('/'.$data['fb_page_id'].'/feed?fields=comments');

   
    echo "<pre>";
    print_r($fb_comments["data"]);
    echo "</pre>";
   
    
    if (isset($fb_comments["data"]) && !empty($fb_comments["data"])) {
        foreach($fb_comments["data"] as $row){
            //echo $row['id'];
            foreach($row['comments']['data'] as $val){
                
                $date_source = strtotime($val['created_time']);
                $val['created_time'] = date('Y-m-d H:i:s', $date_source);
                  
                // start get comment reply
                $fb_comments1 = $facebook->api('/'.$val['id'].'?fields=comments');
                foreach($fb_comments1['comments']['data'] as $rowval){
                    $date_source1 = strtotime($rowval['created_time']);
                    $rowval['created_time'] = date('Y-m-d H:i:s', $date_source1);
                    
                   // echo "<pre>";
                   // print_r($rowval);
                    //echo "</pre>";
                    
                    $commentExist1 = mysql_query("SELECT id FROM social_media_feedback WHERE message_id='{$rowval['id']}' and client_id='{$data['client_id']}' and social_media_type='facebook' and sender_type='Comment'",$db1);
                    $numrow1=mysql_num_rows($commentExist1);
                    if($numrow1 == 0){
                       mysql_query("INSERT INTO social_media_feedback(client_id,social_media_type,page_id,page_name,access_token,message,message_id,reply_id,sender_name,sender_id,sender_type,sender_time)VALUES('{$data['client_id']}','{$data['social_media_type']}','{$data['fb_page_id']}','{$data['fb_page_name']}','{$data['fb_page_token']}','{$rowval['message']}','{$rowval['id']}','{$row['id']}','{$rowval['from']['name']}','{$rowval['from']['id']}','Comment','{$rowval['created_time']}')",$db1); 
                    }   
                }
                // end get comment reply

                $commentExist = mysql_query("SELECT id FROM social_media_feedback WHERE message_id='{$val['id']}' and client_id='{$data['client_id']}' and social_media_type='facebook' and sender_type='Comment'",$db1);
                $numrow=mysql_num_rows($commentExist);
                if($numrow == 0){
                    mysql_query("INSERT INTO social_media_feedback(client_id,social_media_type,page_id,page_name,access_token,message,message_id,reply_id,sender_name,sender_id,sender_type,sender_time)VALUES('{$data['client_id']}','{$data['social_media_type']}','{$data['fb_page_id']}','{$data['fb_page_name']}','{$data['fb_page_token']}','{$val['message']}','{$val['id']}','{$row['id']}','{$val['from']['name']}','{$val['from']['id']}','Comment','{$val['created_time']}')",$db1); 
                }
            } 
        }   
    }
    
    }
              
}

