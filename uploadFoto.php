<?php 
require_once 'vendor/autoload.php';
require_once "./random_string.php";
$ini = @parse_ini_file('env/.env', true);

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$accName = $ini['ACCOUNT_NAME'];
$accKey = $ini['ACCOUNT_KEY'];
$connectionString = "DefaultEndpointsProtocol=https;AccountName=".$accName.";AccountKey=".$accKey;

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

$namaFile = $_FILES['berkas']['name'];
$namaSementara = $_FILES['berkas']['tmp_name'];

$dirUpload = "upload/";

$fileToUpload = "upload/$namaFile";

$terupload = move_uploaded_file($namaSementara, $dirUpload.$namaFile);

if ($terupload) {
    $createContainerOptions = new CreateContainerOptions();
    $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
    // Menetapkan metadata dari container.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");
 
    $containerName = "blockblobs".generateRandomString();
 
    
        // Membuat container.
        $blobClient->createContainer($containerName, $createContainerOptions);
        
        # Mengunggah file sebagai block blob
       echo "Here your file :)";
         
        $content = fopen($fileToUpload, "r");
         
        //Mengunggah blob
        $blobClient->createBlockBlob($containerName, $fileToUpload, $content);

         // List blobs.
         $listBlobsOptions = new ListBlobsOptions();
         $listBlobsOptions->setPrefix("upload");
 
      //  echo "These are the blobs present in the container: ";
 
         do{
             $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
             foreach ($result->getBlobs() as $blob)
             {
               //  echo $blob->getName().": ".$blob->getUrl()."<br />";
             }
         
             $listBlobsOptions->setContinuationToken($result->getContinuationToken());
         } while($result->getContinuationToken());
         echo "<br />";

         echo '<img src="'.$blob->getUrl().'">';        

       
} else {
    echo "Upload Gagal!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Azure Computer Vision</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>
 
<script type="text/javascript">
    function processImage() {
        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************
 
        // Replace <Subscription Key> with your valid subscription key.
        var subscriptionKey = "bc806d0dfd1244ecbc28dd3eabde16b2";
 
        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
 
        // Request parameters.
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };
 
        // Display the image.
        var sourceImageUrl = document.getElementById("inputImage").value;
        document.querySelector("#sourceImage").src = sourceImageUrl;
 
        // Make the REST API call.
        $.ajax({
            url: uriBase + "?" + $.param(params),
 
            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader(
                    "Ocp-Apim-Subscription-Key", subscriptionKey);
            },
 
            type: "POST",
 
            // Request body.
            data: '{"url": ' + '"' + sourceImageUrl + '"}',
        })
 
        .done(function(data) {
            // Show formatted JSON on webpage.
            $("#responseTextArea").val(JSON.stringify(data, null, 2));
        })
 
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
            errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
        });
    };
</script>
<br>
<br>
<br>
<input type="text" name="inputImage" id="inputImage"
    value="<?php echo $blob->getUrl() ?>" />
<button onclick="processImage()">Click to Analyze image</button>
<br><br>
<div id="wrapper" style="width:1020px; display:table;">
    <div id="jsonOutput" style="width:600px; display:table-cell;">
        Response:
        <br><br>
        <textarea id="responseTextArea" class="UIInput"
                  style="width:580px; height:400px;"></textarea>
    </div>
    <div id="imageDiv" style="width:420px; display:table-cell;">
        Source image:
        <br><br>
        <img id="sourceImage" width="400" />
    </div>
</div>
</body>
</html>