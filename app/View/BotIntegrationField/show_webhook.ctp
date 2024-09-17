<?php $auth_token = base64_encode($client_id);

    $request_headers = array(
        'Content-Type' => 'application/json',
        'Auth-Token' => ''
    );

    if (empty($request_data)) {
        
        $response_data = array(
            'status' => 'error',
            'message' => 'Request data is missing or empty.'
        );
    } else {
        
        $response_data = array(
            'status' => 'success',
            'message' => 'Data processed successfully.'
        );
    }


?>
<style>
   .webhook-panel {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin: 20px;
        padding: 20px;
    }

    /* Header styling */
    .webhook-header {
        background-color: #f8f9fa;
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        border-radius: 8px 8px 0 0;
        text-align: center;
    }

    .webhook-title {
        font-size: 24px;
        color: #333;
        font-weight: bold;
    }

    /* Section Styling */
    .section {
        margin: 20px 0;
    }

    /* Endpoint URL */
    .endpoint-section h5, .headers-section h5, .request-data-section h5, .response-data-section h5 {
        font-size: 18px;
        color: #007bff;
        font-weight: bold;
        border-bottom: 2px solid #007bff;
        padding-bottom: 5px;
    }

    .url {
        font-size: 16px;
        color: #343a40;
        background: #e9ecef;
        padding: 10px;
        border-radius: 5px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Code blocks */
    .code-block {
        background-color: #f8f9fa;
        padding: 15px;
        font-size: 14px;
        border-radius: 6px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        color: #333;
        font-family: 'Courier New', monospace;
    }

    /* Success and error colors */
    .response-data-section .code-block {
        font-weight: bold;
    }


    #copy{
        font-size: 18px;
        color: #007bff;
        font-weight: bold;
        border-bottom: 2px solid #007bff;
        padding-bottom: 5px;
    }

    


</style>
<script>
    function copyToken() 
    {
        var tokenText = document.getElementById("token-text").textContent.trim();
        
        var tempInput = document.createElement("input");
        tempInput.value = tokenText;
        document.body.appendChild(tempInput);
        
        tempInput.select();
        document.execCommand("copy");
        
        document.body.removeChild(tempInput);
        
        alert("Token copied to clipboard: " + tokenText);
    }
</script>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Bot Integration Field Mapping</a></li>
    <li class="active"><a href="#">Webhook Details</a></li>
</ol>

<?php if(isset($client_id) && !empty($client_id)){ ?>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default webhook-panel">
        <div class="panel-heading webhook-header">
            <h4 class="webhook-title">Webhook Details for Client: <strong><?php echo $master_client['RegistrationMaster']['company_name']; ?></strong></h4>
            <h4 class="">Auth-Token: 
                <strong id="token-text"><?php echo $token_arr['BotIntegrationToken']['token']; ?><strong>
            </h4>
            <button onclick="copyToken()"  style="background: none; border: none; cursor: pointer;" title="Copy">
                <span id="copy"><i class="fa fa-copy"></i> copy token</span>
            </button>
            

        

        </div>
        <div class="panel-body webhook-body">
            <!-- Endpoint URL Section -->
            <div class="section endpoint-section">
                <h5>Endpoint URL:</h5>
                <p class="url"><strong>https://dialdesk.co.in/dialdesk/app/webroot/bot_webhook_file/index.php</strong></p>
            </div>

            <!-- Request Headers Section -->
            <div class="section headers-section">
                <h5>Request Headers:</h5>
                <pre class="code-block"><code><?php echo json_encode($request_headers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?></code></pre>
            </div>

            <!-- Request Data Section -->
            <div class="section request-data-section">
                <h5>Request Data:</h5>
                <pre class="code-block"><code><?php echo json_encode($request_data, JSON_PRETTY_PRINT); ?></code></pre>
            </div>

            <!-- Response Data Section -->
            <div class='section response-data-section'>
                <h5>Response Data:</h5>
                <pre class="code-block" style="color: <?php echo ($response_data['status'] === 'success' ? 'green' : 'red'); ?>;"><code><?php echo htmlspecialchars(json_encode($response_data, JSON_PRETTY_PRINT)); ?></code></pre>
            </div>
        </div>
    </div>


        
    </div>
</div>
<?php } ?>




