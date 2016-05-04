<?php

namespace MaterialiseCloud;

require_once("ApiHttpRequest.php");
require_once("ApiException.php");
require_once("AccessApiClient.php");
require_once("OperationFileApi.php");
require_once("OperationApiClient.php");
require_once("RepairOperationApiClient.php");
require_once("TokenProviderInterface.php");
require_once("TokenProvider.php");


$apiId = ""; //your api id
$apiSecret = ""; //your api secret
$userEmail = ""; //your user email
$userPassword = ""; //your user password
$host = "api.cloud.materialise.com"; // for production environment 

$tokenProvider = new TokenProvider(new AccessApiClient($host), $apiId, $apiSecret, $userEmail, $userPassword);


$sourceFilePath = "_porsche.stl";
$destinationFilePath = "_porsche_repaired.stl";

$filesClient = new OperationFileApi($host, $tokenProvider);
$fileId = $filesClient->UploadFile($sourceFilePath);


$repairer = new RepairOperationApiClient($host, $tokenProvider);
$operationId = $repairer->Repair($fileId);
$repairer->WaitForOperationToFinish($operationId);
$repairedFileId = $repairer->GetRepairOperationResult($operationId);

$filesClient->DownloadFile($repairedFileId,$destinationFilePath);

?>