<?php
/*
This example shows basic upload-import-analyze-repair-export-download flow.

If you need other operations just uncomment them and take a look on documentation 
for the format of returning result
*/


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
require_once("WallThicknessAnalysisOperationApiClient.php");

require_once("RepairOperationApiClient.php");
require_once("GeometricalRepairOperationApiClient.php");
require_once("ShrinkwrapRepairAccuracies.php");
require_once("ShrinkwrapRepairOperationApiClient.php");

require_once("ScaleOperationApiClient.php");
require_once("GenerateThumbnailOperationApiClient.php");
require_once("ReduceTrianglesOperationApiClient.php");
require_once("HollowingOperationApiClient.php");

require_once("ImportOperationApiClient.php");
require_once("ExportOperationApiClient.php");
require_once("TokenProviderInterface.php");
require_once("TokenProvider.php");


$apiId = ""; //your api id
$apiSecret = ""; //your api secret
$userEmail = ""; //your user email
$userPassword = ""; //your user password
$host = "api.cloud.materialise.com"; // for production environment 


$tokenProvider = new TokenProvider(new AccessApiClient($host), $apiId, $apiSecret, $userEmail, $userPassword);

echo "uploading\n";
$sourceFilePath = "meerkat_bad.3DS";
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

	/*
	echo "running shrink wrap repairing\n";
	$repairer = new ShrinkwrapRepairOperationApiClient($host, $tokenProvider);
	$operationId = $repairer->Repair($resultId, ShrinkwrapRepairAccuracies::Rough);
	$repairer->WaitForOperationToFinish($operationId);
	$resultId = $repairer->GetShrinkwrapRepairOperationResult($operationId);
	echo "repair done\n";
	*/	

	/*
	echo "running geometrical repair\n";
	$repairer = new GeometricalRepairOperationApiClient($host, $tokenProvider);
	$operationId = $repairer->Repair($resultId);
	$repairer->WaitForOperationToFinish($operationId);
	$resultId = $repairer->GetGeometricalRepairOperationResult($operationId);
	echo "repair done\n";
	*/
}

/*
echo "scaling\n";
$scaler = new ScaleOperationApiClient($host, $tokenProvider);
$operationId = $scaler->Scale($resultId, Axes::Y, 10);
$scaler->WaitForOperationToFinish($operationId);
$resultId = $scaler->GetScaleOperationResult($operationId);
echo "scale done\n";
*/

/*echo "preview generation\n";
$previewer = new GenerateThumbnailOperationApiClient($host, $tokenProvider);
$operationId = $previewer->GenerateThumbnail($resultId, 300, 300, 145, 145, 145);
$previewer->WaitForOperationToFinish($operationId);
$fileId = $previewer->GetGenerateThumbnailOperationResult($operationId);
echo "generation done\n";

echo "download preview\n";
$filesClient->DownloadFile($fileId,"model_preview.jpg");
echo "download preview done\n";
*/

/*
echo "reducing triangles\n";
$reducer = new ReduceTrianglesOperationApiClient($host, $tokenProvider);
$operationId = $reducer->ReduceTriangles($resultId, 2, 15, 1);
$reducer->WaitForOperationToFinish($operationId);
$resultId = $reducer->GetReduceTrianglesOperationResult($operationId);
echo "reducing done\n";
*/

/*
echo "running hollowing\n";
$hollower = new HollowingOperationApiClient($host, $tokenProvider);
$operationId = $hollower->Hollow($resultId, 1);
$hollower->WaitForOperationToFinish($operationId);
$resultId = $hollower->GetHollowingOperationResult($operationId);
echo "hollowing done\n";
*/

/*
echo "running wallthickness analysis\n";
$analyzer = new WallThicknessAnalysisOperationApiClient($host, $tokenProvider);
$operationId = $analyzer->Analyze($resultId, 1, 2);
$analyzer->WaitForOperationToFinish($operationId);
$analysisResults = $analyzer->GetWallThicknessAnalysisOperationResult($operationId);
echo "analysis done\n";

echo "downloading analysis model\n";
$filesClient->DownloadFile($analysisResults->FileId, "analysis_model.stl");
echo "analysis model download done\n";
*/

echo "exporting\n";
$exporter = new ExportOperationApiClient($host, $tokenProvider);
$operationId = $exporter->Export($resultId, ExportFormats::Stl);
$exporter->WaitForOperationToFinish($operationId);
$exportedFileId = $exporter->GetExportOperationResult($operationId);
echo "export done\n";

echo "download\n";
$destinationFilePath = "meerkat_modified.stl";
$filesClient->DownloadFile($exportedFileId, $destinationFilePath);
echo "download done\n";

?>