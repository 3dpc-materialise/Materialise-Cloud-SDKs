<?php
namespace MaterialiseCloud
{
	class ApiHttpRequest
	{
        private $_responseHttpStatusCode;
        
		private $_apiUrl;
		private $_headers;
		private $_bodyParts;

		public function __construct($url)
		{
			$this->_apiUrl = $url;
			$this->_headers = array();
			$this->_bodyParts = array();
		}

		public function AddHeader($name, $value)
		{
			array_push($this->_headers, $name.": ".$value);
		}

		public function AddFilePart($filePath)
		{
			$fileData = file_get_contents($filePath);
			
			$fileInfo = pathinfo($filePath);
			
			$fileName = $fileInfo["basename"];
			
			$data= "Content-Disposition: form-data; name=\"file\"; filename=\"$fileName\"\r\n";
			$data.= "Content-Type: application/octet-stream\r\n\r\n";
			$data.= $fileData;

			array_push($this->_bodyParts, $data);
		}

		public function PostAsJson($data, $accessToken)
		{
			$this->AddAccessTokenHeader($accessToken);
			$this->AddHeader("Accept", "application/json");
			$this->AddHeader("Content-Type", "application/json");

			$body = json_encode($data);

			return $this->DoPost($this->_apiUrl, $this->_headers, $body);
		}

		public function PostAsMultipart($accessToken)
		{
			$this->AddAccessTokenHeader($accessToken);
			$this->AddHeader("Accept", "application/json");

			$delimiter = "----------------".uniqid();
			$this->AddHeader("Content-Type", "multipart/form-data; boundary=".$delimiter);
			

			$body = "--$delimiter\r\n".implode("\r\n--$delimiter\r\n", $this->_bodyParts)."\r\n--$delimiter--\r\n";
			$this->AddHeader("Content-Length", strlen($body));

			return $this->DoPost($this->_apiUrl, $this->_headers, $body);
		}
		
		public function PostAsFormData($dictionary, $accessToken=null)
		{
			if(isset($accessToken))
			{
				$this->AddAccessTokenHeader($accessToken);	
			}
			
			$this->AddHeader("Accept", "application/json");

            
			return $this->DoPost($this->_apiUrl, $this->_headers, $dictionary);
		}

        public function Post($data, $contentType)
        {
            $this->AddHeader("Accept", "application/json");
            $this->AddHeader("Content-Type", $contentType);
            
            return $this->DoPost($this->_apiUrl, $this->_headers, $data);
        }

		public function Get($accessToken)
		{
			$this->AddAccessTokenHeader($accessToken);
			$this->AddHeader("Accept", "application/json");

			return $this->DoGet($this->_apiUrl, $this->_headers);
		}

        public function IsResponseStatusOk()
        {
            return $this->_responseHttpStatusCode === 200;
        }


		private function AddAccessTokenHeader($accessToken)
		{
			$this->AddHeader("Authorization", "Bearer ".$accessToken);
		}

		private function DoPost($apiUrl, $headers, $body)
		{
			$curl = curl_init($apiUrl);
			
			curl_setopt($curl,CURLOPT_POSTFIELDS , $body);
			curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl,CURLOPT_POST, true);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER , true);
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($curl);
				
            $this->_responseHttpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
             
			curl_close($curl);
			
			return $result;
		}

		private function DoGet($apiUrl, $headers)
		{
			$curl = curl_init($apiUrl);
			
			curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl,CURLOPT_HTTPGET, true);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER , true);
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($curl);
				
			$this->_responseHttpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
			curl_close($curl);
			
			return $result;
		}
	}
}
?>