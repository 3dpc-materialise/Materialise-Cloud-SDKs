<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class ShrinkwrapRepairOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function Repair($inputId, $accuracy, $callbackUrl = null)
		{
			$requestData = array(
				'inputId' => $inputId, 
				'accuracy' => $accuracy,
				'callbackUrl'=>$callbackUrl);

			return $this->PostOperation("/web-api/operation/shrinkwrap-repair", $requestData);
		}

		public function GetShrinkwrapRepairOperationResult($operationId)
		{
			$result = $this->GetResult("/web-api/operation/".$operationId."/shrinkwrap-repair/result");
			return $result->resultId;
		}
	}
}
?>
