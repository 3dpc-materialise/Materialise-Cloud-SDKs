<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class AnalyzeOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function Analyze($inputId, $callbackUrl = null)
		{
			$requestData = array(
				'inputId' => $inputId, 
				'callbackUrl'=>$callbackUrl);

			return $this->PostOperation("/web-api/operation/analyze", $requestData);
		}

		public function GetAnalyzeOperationResult($operationId)
		{
			$response = $this->GetResult("/web-api/operation/".$operationId."/analyze/result");

			$result = new AnalyzeOperationResult();
			$result->Vertices = $response->vertices;
			$result->Triangles = $response->triangles;
			$result->Contours = $response->contours;
			$result->Shells = $response->shells;
			$result->VolumeMm3 = $response->volumeMm3;
			$result->SurfaceAreaMm2 = $response->surfaceAreaMm2;
			$result->DimensionXMm = $response->dimensionXMm;
			$result->DimensionYMm = $response->dimensionYMm;
			$result->DimensionZMm = $response->dimensionZMm;
			$result->BadEdges = $response->badEdges;
			$result->BadContours = $response->badContours;

			$result->SolidityParams->InvertedNormals=$response->solidityParams->invertedNormals;
			$result->SolidityParams->BadEdges=$response->solidityParams->badEdges;
			$result->SolidityParams->BadContours=$response->solidityParams->badContours;
			$result->SolidityParams->NearBadEdges=$response->solidityParams->nearBadEdges;
			$result->SolidityParams->PlanarHoles=$response->solidityParams->planarHoles;

			$result->QualityParams->NoiseShells=$response->qualityParams->noiseShells;
			$result->QualityParams->OverlappingTriangles=$response->qualityParams->overlappingTriangles;
			$result->QualityParams->IntersectingTriangles=$response->qualityParams->intersectingTriangles;
			$result->QualityParams->IsFacetingScoreOk=$response->qualityParams->isFacetingScoreOk;

			return $result;
		}
	}


	class AnalyzeOperationResult
	{
		public function __construct()
		{
			$this->SolidityParams = new SolidityParamsData();
			$this->QualityParams = new QualityParamsData();
		}


		public $Vertices;
		public $Triangles;
		public $Contours;
		public $Shells;
		public $VolumeMm3;
		public $SurfaceAreaMm2;
		public $DimensionXMm;
		public $DimensionYMm;
		public $DimensionZMm;
		public $BadEdges;
		public $BadContours;

		public $SolidityParams;
		public $QualityParams;
	}

	class SolidityParamsData
	{
		public $InvertedNormals;
		public $BadEdges;
		public $BadContours;
		public $NearBadEdges;
		public $PlanarHoles;
	}
	
	class QualityParamsData
	{
		public $NoiseShells;
		public $OverlappingTriangles;
		public $IntersectingTriangles;
		public $IsFacetingScoreOk;
	}
}


?>
