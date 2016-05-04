<?php 

namespace MaterialiseCloud
{
	use MaterialiseCloud\ApiHttpRequest;

	abstract class OperationApiClient 
	{
		protected $_host;
		protected $_accessTokenProvider;

		protected function __construct($host, TokenProviderInterface $accessTokenProvider)
		{
			$this->_host = $host;
			$this->_accessTokenProvider = $accessTokenProvider;
		}

		protected function PostOperation($url, $parameters)
		{
			$request = new ApiHttpRequest($this->_host.$url);

			$result = $request->PostAsJson($parameters, $this->_accessTokenProvider->GetAccessToken());

			$this->CheckResponseIsOk($request, $result);
			$jsonObj = json_decode(utf8_encode($result));
			
			return $jsonObj->operationId;
		}

		protected function GetResult($url)
		{
			$request = new ApiHttpRequest($this->_host.$url);
			$result = $request->Get($this->_accessTokenProvider->GetAccessToken());

			$this->CheckResponseIsOk($request, $result);
			
			return json_decode(utf8_encode($result));	
		}

		public function GetOperationStatus($operationId)
		{
			$request = new ApiHttpRequest($this->_host."/web-api/operation/".$operationId."/status");

			$result = $request->Get($this->_accessTokenProvider->GetAccessToken());

			$this->CheckResponseIsOk($request, $result);

			return json_decode(utf8_encode($result));
		}

		public function WaitForOperationToFinish($operationId, $pollingIntervalMilliseconds = 3000)
		{
			$isCompleted = false;
			$result = NULL;

			while(!$isCompleted)
			{
				usleep($pollingIntervalMilliseconds * 1000);

				$result = $this->GetOperationStatus($operationId);

				$isCompleted = $result->isCompleted;
			}

			return $result->isSuccessful;
		}

		private function CheckResponseIsOk($request, $response)
		{
			if(!$request->IsResponseStatusOk())
			{
				$this->ThrowApiException($response);
			}
		}

		private function ThrowApiException($response)
		{
			$result = json_decode(utf8_encode($response));			

			if(isset($result->errors))
			{
				$error = $result->errors[0];

				throw new ApiException($error->code, $error->message);
			}
			else 
			{
				throw new ApiException(-1, "Unknown error");
			}
		}
	}
}
?>