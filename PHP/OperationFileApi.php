<?php
namespace MaterialiseCloud
{
    use MaterialiseCloud\ApiHttpRequest;
    use MaterialiseCloud\ApiException;
    
	class OperationFileApi
    {
        private $_host;
        private $_tokenProvider;
        
        public function __construct($host, TokenProviderInterface $accessTokenProvider)
        {
            $this->_host = $host;
            $this->_tokenProvider = $accessTokenProvider;
        }
        
        public function UploadFile($filePath)
        {
            $url = "https://".$this->_host."/web-api/operation/file";
            $request = new ApiHttpRequest($url);
            $request->AddFilePart($filePath);
            $result = $request->PostAsMultipart($this->_tokenProvider->GetAccessToken());
            
            $jsonObj = json_decode(utf8_encode($result));
            if($request->IsResponseStatusOk())
            {
            	return $jsonObj->fileId;
            }
            $this->ThrowException($jsonObj);
        }

        public function DownloadFile($fileId, $filePath)
        {
        	$url = "https://".$this->_host."/web-api/operation/file/".$fileId;
            $request = new ApiHttpRequest($url);
            $result = $request->Get($this->_tokenProvider->GetAccessToken());

            if($request->IsResponseStatusOk())
            {
            	$this->WriteToFile($filePath, $result);
            	return;
            }
            
            $jsonObj = json_decode(utf8_encode($result));
            $this->ThrowException($jsonObj);
            
        }

        private function WriteToFile($filePath, $content)
        {
        	$dirPath = dirname($filePath);
        	if(!file_exists($dirPath))
        	{
        		mkdir($dirPath);
        	}
        	$file = fopen($filePath, 'w');
        	fwrite($file, $content);
        	fclose($file);
    	}

        private function ThrowException($errorObj)
        {
            if(isset($errorObj->errors))
            {
                throw new ApiException($errorObj->errors[0]->code, $errorObj->errors[0]->message);
            }
            else
            {
                throw new ApiException(-1, "Unknown error.");
            }
        }
    }
}

?>