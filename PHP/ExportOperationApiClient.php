<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class ExportOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function Export($inputId, $exportToFormat, $callbackUrl = null)
		{
			$requestData = array(
				'inputId' => $inputId, 
				'exportToFormat'=> $exportToFormat,
				'callbackUrl'=>$callbackUrl);

			return $this->PostOperation("/web-api/operation/export", $requestData);
		}

		public function GetExportOperationResult($operationId)
		{
			$result = $this->GetResult("/web-api/operation/".$operationId."/export/result");
			return $result->fileId;
		}
	}
}
?>
