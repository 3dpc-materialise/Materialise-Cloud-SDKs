<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class ReduceTrianglesOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function ReduceTriangles($inputId, $accuracyMm, $maxAngle, $numberOfIterations, $callbackUrl = null)
		{
			$requestData = array(
				'inputId' => $inputId, 
				'accuracyMm' => $accuracyMm,
				'maxAngle' => $maxAngle,
				'numberOfIterations' => $numberOfIterations,
				'callbackUrl'=>$callbackUrl);

			return $this->PostOperation("/web-api/operation/reduce-triangles", $requestData);
		}

		public function GetReduceTrianglesOperationResult($operationId)
		{
			$result = $this->GetResult("/web-api/operation/".$operationId."/reduce-triangles/result");
			return $result->resultId;
		}
	}
}
?>
