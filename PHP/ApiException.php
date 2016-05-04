<?php
namespace MaterialiseCloud
{
    use Exception;

    class ApiException extends Exception
    {
        public function __construct($errorCode, $errorMessage, Exception $innerException = null)
        {
            parent::__construct($errorMessage." Error Code: ".$errorCode , $errorCode, $innerException);
        }
    }
}
?>