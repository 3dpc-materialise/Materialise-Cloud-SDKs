<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class GeometricalRepairOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function Repair($inputId, $callbackUrl = null)
		{
			$requestData = array(
				'inputId' => $inputId, 
				'callbackUrl'=>$callbackUrl);

			return $this->PostOperation("/web-api/operation/geometrical-repair", $requestData);
		}

		public function GetGeometricalRepairOperationResult($operationId)
		{
			$result = $this->GetResult("/web-api/operation/".$operationId."/geometrical-repair/result");
			return $result->resultId;
		}
	}
}
?>
