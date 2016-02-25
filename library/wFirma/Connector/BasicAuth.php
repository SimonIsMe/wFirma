<?php
namespace wFirma\Connector;

use \wFirma\AbstractConnector;

class BasicAuth extends AbstractConnector
{
    private $_user;
    private $_password;
    
    function connect($user, $password)
    {
        $this->_user = $user;
        $this->_password = $password;
        
        $this->_validateConnection();
    }
    
    public function request($action, $xml)
    {
        $curl = $this->_getCurl($action, $xml);
        $resultJson = json_decode(curl_exec($curl), true);
        curl_close($curl);
        $this->_validateStatus($resultJson);
        return $resultJson;
    }

    private function _getCurl($action, $xml)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERPWD, $this->_user . ':' . $this->_password);
        curl_setopt($curl, CURLOPT_URL, AbstractConnector::API_URL . $action . '?outputFormat=json');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
        return $curl;
    }
}
