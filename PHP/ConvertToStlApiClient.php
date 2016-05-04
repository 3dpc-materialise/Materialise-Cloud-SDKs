<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class ConvertToStlApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function ConvertToStl($fileId, $measurementUnits, $callbackUrl = null)
		{
			$requestData = array(
				'fileId' => $fileId, 
				'measurementUnits' => $measurementUnits,
				'callbackUrl'=>$callbackUrl);

			return $this->PostOperation("/web-api/operation/stl-conversion", $requestData);
		}

		public function GetConvertToStlOperationResult($operationId)
		{
			$result = $this->GetResult("/web-api/operation/".$operationId."/stl-conversion/result");
			return $result->fileId;
		}
	}
}
?>
