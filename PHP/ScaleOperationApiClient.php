<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class ScaleOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function Scale($inputId, $axis, $scaleToMm, $callbackUrl = null)
		{
			$requestData = array(
				'inputId' => $inputId, 
				'axis' => $axis,
				'scaleToSizeMm' => $scaleToMm,
				'callbackUrl'=>$callbackUrl);

			return $this->PostOperation("/web-api/operation/scale", $requestData);
		}

		public function GetScaleOperationResult($operationId)
		{
			$result = $this->GetResult("/web-api/operation/".$operationId."/scale/result");
			return $result->resultId;
		}
	}
}
?>
