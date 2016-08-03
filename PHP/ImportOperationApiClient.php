<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class ImportOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function Import($fileId, $measurementUnits, $callbackUrl = null)
		{
			$requestData = array(
				'fileId' => $fileId, 
				'measurementUnits' => $measurementUnits,
				'callbackUrl'=>$callbackUrl);

			return $this->PostOperation("/web-api/operation/import", $requestData);
		}

		public function GetImportOperationResult($operationId)
		{
			$result = $this->GetResult("/web-api/operation/".$operationId."/import/result");
			return $result->resultId;
		}
	}
}
?>
