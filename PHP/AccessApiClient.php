<?php
namespace MaterialiseCloud
{
    use MaterialiseCloud\ApiHttpRequest;
    
	class AccessApiClient
	{
		private $_host;

		public function __construct($host)
		{
			$this->_host = $host;
		}

		public function GetAuthToken($clientId, $secret, $userEmail, $userPassword)
		{
			$request = new ApiHttpRequest("https://".$this->_host."/token");

			$encodedSecret = base64_encode($clientId.":".$secret);
			$request->AddHeader("Authorization", "Basic ".$encodedSecret);
            
			$data = $this->CreateAuthTokenBody($userEmail, $userPassword);
            $response = $request->Post($data, "application/x-www-form-urlencoded");
            
            return $this->ParseResponse($response);
		}

		public function GetAuthTokenByRefreshToken($clientId, $secret, $refreshToken)
		{
			$request = new ApiHttpRequest("https://".$this->_host."/token");

			$encodedSecret = base64_encode($clientId.":".$secret);
			$request->AddHeader("Authorization", "Basic ".$encodedSecret);
			$request->AddHeader("Content-Type", "application/x-www-form-urlencoded");

			$data = $this->CreateAuthTokenBodyByRefreshToken($refreshToken);

			$response = $request->Post($data,"application/x-www-form-urlencoded");
			return $this->ParseResponse($response);
		}

		private function CreateAuthTokenBody($userEmail, $userPassword)
		{
			return "grant_type=password&username=".urlencode($userEmail)."&password=".urlencode($userPassword);
		}

		private function CreateAuthTokenBodyByRefreshToken($refreshToken)	
		{
			return "grant_type=refresh_token&refresh_token=".urlencode($refreshToken);
		}
        
        private function ParseResponse($responseJson)
        {
            $jsonObj = json_decode(utf8_encode($responseJson));
            $result = new AuthTokenResponse();
            $result->AccessToken = $jsonObj->{"access_token"};
            $result->AccessTokenExpiresInSec = $jsonObj->{"expires_in"};
            
            $result->RefreshToken = $jsonObj->{"refresh_token"};
            $result->RefreshTokenIssued = strtotime($jsonObj->{".issued"});
            $result->RefreshTokenExpires = strtotime($jsonObj->{".expires"});
            
            return $result;
        }
	}
    
    class AuthTokenResponse
    {
        public $AccessToken;
        public $AccessTokenExpiresInSec;
        public $RefreshToken;
        public $RefreshTokenIssued;
        public $RefreshTokenExpires;
    }
}
?>