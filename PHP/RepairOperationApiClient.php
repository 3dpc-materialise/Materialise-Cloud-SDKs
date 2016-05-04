<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;

	class RepairOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function Repair($fileId, $callbackUrl = null)
		{
			$parameters = array(
				"fileId"=>$fileId, 
				"callbackUrl"=>$callbackUrl);
			
			$operationId = $this->PostOperation("/web-api/operation/repair", $parameters);
			return $operationId;

		}

		public function GetRepairOperationResult($operationId)
		{
			$result = $this->GetResult("/web-api/operation/".$operationId."/repair/result");
			return $result->fileId;
		}
	}
}
?>