<?php

namespace MaterialiseCloud;

require_once("ExportFormats.php");
require_once("MeasurementUnits.php");
require_once("Axes.php");
require_once("ApiHttpRequest.php");
require_once("ApiException.php");
require_once("AccessApiClient.php");
require_once("OperationFileApi.php");
require_once("OperationApiClient.php");

require_once("AnalyzeOperationApiClient.php");
require_once("RepairOperationApiClient.php");
require_once("ScaleOperationApiClient.php");
require_once("ImportOperationApiClient.php");
require_once("ExportOperationApiClient.php");
require_once("TokenProviderInterface.php");
require_once("TokenProvider.php");


$apiId = ""; //your api id
$apiSecret = ""; //your api secret
$userEmail = ""; //your user email
$userPassword = ""; //your user password
$host = ""; // for production environment 


$tokenProvider = new TokenProvider(new AccessApiClient($host), $apiId, $apiSecret, $userEmail, $userPassword);

$sourceFilePath = "meerkat_bad.3DS";
$destinationFilePath = "meerkat_modyfied.stl";

echo "uploading\n";
$filesClient = new OperationFileApi($host, $tokenProvider);
$fileId = $filesClient->UploadFile($sourceFilePath);
echo "upload done\n";

echo "importing\n";
$importer = new ImportOperationApiClient($host, $tokenProvider);
$operationId = $importer->Import($fileId, MeasurementUnits::Mm);
$importer->WaitForOperationToFinish($operationId);
$resultId = $importer->GetImportOperationResult($operationId);
echo "import done\n";


echo "analyzing\n";
$analyzer = new AnalyzeOperationApiClient($host, $tokenProvider);
$operationId = $analyzer->Analyze($resultId);
$analyzer->WaitForOperationToFinish($operationId);
$analysisResults = $analyzer->GetAnalyzeOperationResult($operationId);
echo "analysis done\n";

if($analysisResults->BadEdges > 0 
	|| $analysisResults->BadContours> 0
	|| $analysisResults->VolumeMm3<=0)
{
	echo "repairing\n";
	$repairer = new RepairOperationApiClient($host, $tokenProvider);
	$operationId = $repairer->Repair($resultId);
	$repairer->WaitForOperationToFinish($operationId);
	$resultId = $repairer->GetRepairOperationResult($operationId);
	echo "repair done\n";	
}

echo "scaling\n";
$scaler = new ScaleOperationApiClient($host, $tokenProvider);
$operationId = $scaler->Scale($resultId, Axes::Y, 10);
$scaler->WaitForOperationToFinish($operationId);
$resultId = $scaler->GetScaleOperationResult($operationId);
echo "scale done\n";

echo "exporting\n";
$exporter = new ExportOperationApiClient($host, $tokenProvider);
$operationId = $exporter->Export($resultId, ExportFormats::Stl);
$exporter->WaitForOperationToFinish($operationId);
$exportedFileId = $exporter->GetExportOperationResult($operationId);
echo "export done\n";

echo "download\n";
$filesClient->DownloadFile($exportedFileId,$destinationFilePath);
echo "download done\n";

?>