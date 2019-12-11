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

?>
<html>
 <head>
 <Title>Azure Submission 2</Title>
 <style type="text/css">
 	body { background-color: #fff; border-top: solid 10px #000;
 	    color: #333; font-size: .85em; margin: 20; padding: 20;
 	    font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
 	}
 	h1, h2, h3,{ color: #000; margin-bottom: 0; padding-bottom: 0; }
 	h1 { font-size: 2em; }
 	h2 { font-size: 1.75em; }
 	h3 { font-size: 1.2em; }
 	table { margin-top: 0.75em; }
 	th { font-size: 1.2em; text-align: left; border: none; padding-left: 0; }
 	td { padding: 0.25em 2em 0.25em 0em; border: 0 none; }
 </style>
 </head>
 <body>
 <h1>Upload foto disini!</h1>

 <form action="uploadFoto.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="berkas" id="berkas">
    <input type="submit" value="Upload Image" name="submit_foto">
</form>
 </body>
 </html>