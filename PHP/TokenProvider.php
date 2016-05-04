<?php
namespace MaterialiseCloud 
{
	class TokenProvider implements TokenProviderInterface
	{
		private $_accessApiClient;
		private $_username;
		private $_userPassword;
		private $_apiClientId;
		private $_apiClientSecret;

		private $_accessToken;
		private $_refreshToken;
		private $_accesTokenExpirationTimestamp;

		public function __construct($accessApiClient, $apiClientId, $apiClientSecret, $username, $userPassword)
		{
			$this->_accessApiClient = $accessApiClient;
			
			$this->_username = $username;
			$this->_userPassword = $userPassword;
			$this->_apiClientId = $apiClientId;
			$this->_apiClientSecret = $apiClientSecret;
		}

		public function GetAccessToken()
		{
			if($this->_accessToken == NULL)
			{
				$this->RequestAndAssignTokens();
			}

			if($this->IsAccessTokenExpired())
			{
				$this->RefreshAccessToken();
			}

			return $this->_accessToken;
		}

		private function IsAccessTokenExpired()
		{
			$timestamp = time();
			$deltaInSeconds = 20;

			return $timestamp >= $this->_accesTokenExpirationTimestamp - $deltaInSeconds;
		}

		private function RefreshAccessToken()
		{
			$tokenResponse = $this->_accessApiClient->GetAuthTokenByRefreshToken($this->_apiClientId, $this->_apiClientSecret, $this->_refreshToken);

			$this->SetTokenData($tokenResponse);
		}

		private function RequestAndAssignTokens()
		{
			$tokenResponse = $this->_accessApiClient->GetAuthToken($this->_apiClientId, $this->_apiClientSecret, $this->_username, $this->_userPassword);

		    $this->SetTokenData($tokenResponse);
		}

		private function SetTokenData($tokenResponse)
		{
			$this->_accessToken = $tokenResponse->AccessToken;
			$this->_accesTokenExpirationTimestamp = $tokenResponse->RefreshTokenIssued + $tokenResponse->AccessTokenExpiresInSec;
			$this->_refreshToken = $tokenResponse->RefreshToken;
		}
	}	
}


?>