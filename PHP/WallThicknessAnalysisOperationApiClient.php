<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class WallThicknessAnalysisOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function Analyze($inputId, $minimalWallThicknessMm, $accuracyWallThickness, $callbackUrl = null)
		{
			$requestData = array(
				'inputId' => $inputId, 
				'minimalWallThicknessMm' => $minimalWallThicknessMm,
				'accuracyWallThickness' => $accuracyWallThickness,
				'callbackUrl'=>$callbackUrl);

			return $this->PostOperation("/web-api/operation/wall-thickness-analysis", $requestData);
		}

		public function GetWallThicknessAnalysisOperationResult($operationId)
		{
			$response = $this->GetResult("/web-api/operation/".$operationId."/wall-thickness-analysis/result");

			$result = new WallThicknessAnalysisOperationResult();
			$result->FileId = $response->fileId;
			$result->HasThinWalls = $response->hasThinWalls;
			$result->HasPossibleThinWalls = $response->hasPossibleThinWalls;

			return $result;
		}
	}

	class WallThicknessAnalysisOperationResult
	{
		public $FileId;
		public $HasThinWalls;
		public $HasPossibleThinWalls;
	}
}


?>
