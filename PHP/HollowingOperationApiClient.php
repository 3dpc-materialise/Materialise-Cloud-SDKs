<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class HollowingOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function Hollow($inputId, $wallThicknessMm, $callbackUrl = null)
		{
			$requestData = array(
				'inputId' => $inputId, 
				'wallThicknessMm' => $wallThicknessMm,
				'callbackUrl'=>$callbackUrl);

			return $this->PostOperation("/web-api/operation/hollowing", $requestData);
		}

		public function GetHollowingOperationResult($operationId)
		{
			$result = $this->GetResult("/web-api/operation/".$operationId."/hollowing/result");
			return $result->resultId;
		}
	}
}
?>
