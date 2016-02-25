<?php
namespace wFirma;

use wFirma\Connector\AuthException;
use wFirma\Connector\ConnectorExcepton;

abstract class AbstractConnector
{
    const API_URL = 'https://api2.wfirma.pl/';
    
    //  możlwe kody błędów
    const ERROR_AUTH = 'AUTH';
    const ERROR_AUTH_5_MINUTES = 'AUTH FAILED LIMIT WAIT 5 MINUTES';
    const INPUT_ERROR = 'INPUT ERROR';
    const ACTION_NOT_FOUND = 'ACTION NOT FOUND';
    const NOT_FOUND = 'NOT FOUND';
    const FATAL = 'FATAL';
    const ERROR = 'ERROR';
    const OUT_OF_SERVICE = 'OUT OF SERVICE';
    const DENIED_SCOPE_REQUESTED = 'DENIED SCOPE REQUESTED';
    
    abstract public function connect($oauthToken, $oathTokenSecret);
    abstract public function request($action, $xml);
    
    /**
     * Metoda wysyła próbne żądanie, aby stwierdzić, czy połączenie zostało 
     * nawiązane lub czy dane dostępowe sa poprawne.
     */
    protected  function _validateConnection()
    {
        $result = $this->request('/users/get', '');
        $authErrors = array(self::ERROR_AUTH, self::ERROR_AUTH_5_MINUTES);
        if (in_array($result['status']['code'], $authErrors)) {
            throw new AuthException('Błąd autoryzacji. Upewnij się, że podane dane dostępowe są poprawne.');
        }
    }
    
    protected function _validateStatus($resultJson)
    {
        if (null == $resultJson) {
            throw new ConnectorExcepton('API nie zwróciło odpowiedzi.');
        }
        
        switch($resultJson['status']['code']) {
            case self::ACTION_NOT_FOUND:
                throw new ConnectorExcepton('Wywoływana akcja nie istnieje.');
                break;
            case self::NOT_FOUND:
                throw new ConnectorExcepton('Podany obiekt nie istnieje.');
                break;
            case self::ERROR:
                throw new ConnectorExcepton('Podczas próby dodania lub modyfikacji obiektu wystąpiły błędy walidacji.');
                break;
            case self::FATAL:
                throw new ConnectorExcepton('Wewnętrzny błąd API.');
                break;
            case self::INPUT_ERROR:
                throw new ConnectorExcepton('Podane dane wejściowe są niepoprawne.');
                break;
            case self::OUT_OF_SERVICE:
                throw new ConnectorExcepton('Serwis API tymczasowo wyłączony. Proszę spróbować później.');
                break;
            case self::DENIED_SCOPE_REQUESTED:
                throw new ConnectorExcepton('Próba wywołania zakresu do którego nie ma się dostępu.');
                break;
            case self::ERROR_AUTH:
            case self::ERROR_AUTH_5_MINUTES:
                throw new AuthException('Błąd autoryzacji. Upewnij się, że podane dane dostępowe są poprawne.');
                break;
        }
    }
}