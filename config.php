<?php

class Config 
{
    //  ścieżka do pliku logowania
    const LOG_PATH_FILE = "/log/log.txt";
    
    //  wyświetlanie błędów
    const ERROR_REPORTING_LEVEL = -1;
    const DISPLAY_ERRORS = TRUE;
    
    //  dane do wFirma
    const WFIRMA_USER = '***';
    const WFIRMA_PASSWORD = '***';
    
    //  metoda płatności
    const PAYMENT_METHOD = 'cash';
    
    //  Termin płatności - termin (wyrażony w dniach od daty zakupu) w jakim klienci mogą opłacić dokument
    const PAYMENT_DEADLINE = 7;
}