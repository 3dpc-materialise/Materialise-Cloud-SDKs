<?php 
namespace MaterialiseCloud
{
	use MaterialiseCloud\OperationApiClient;
	class GenerateThumbnailOperationApiClient extends OperationApiClient
	{
		public function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			parent::__construct($host, $accessTokenProvider);
		}

		public function GenerateThumbnail($inputId, $widthPx, $heightPx, $cameraAngleX, $cameraAngleY, $cameraAngleZ, $callbackUrl = null)
		{
			$requestData = array(
				'inputId' => $inputId, 
				'width' => $widthPx,
				'height' => $heightPx,
				'cameraAngleX'=> $cameraAngleX,
				'cameraAngleY'=> $cameraAngleY,
				'cameraAngleZ'=> $cameraAngleZ,
				'callbackUrl'=> $callbackUrl);

			return $this->PostOperation("/web-api/operation/thumbnail", $requestData);
		}

		public function GetGenerateThumbnailOperationResult($operationId)
		{
			$result = $this->GetResult("/web-api/operation/".$operationId."/thumbnail/result");
			return $result->fileId;
		}
	}
}
?>
