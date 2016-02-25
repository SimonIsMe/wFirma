<?php
namespace wFirma;

use wFirma\AbstractConnector;
use wFirma\Entity\Invoice;

class Api
{
    /**
     * @var AbstractConnector
     */
    private $_connector;
    
    public function __construct(AbstractConnector $connector)
    {
        $this->_connector = $connector;
    }
    
    public function addInvoice(array $options)
    {
        return $this->request('/invoices/add', Invoice::add($options));
    }
    
    public function request($action, $xml)
    {
        return $this->_connector->request($action, $xml);
    }
}