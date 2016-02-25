<?php
namespace wFirma\Entity;

class Invoice implements \wFirma\IEntity
{   
    /**
     * Tworzy XML'a dla żądania utworzenia nowej faktury.
     * @params $options - tablica asocjacyjna z danymi do faktury. Wymagane klucze:
     *      - contractorData - tablica asocjacyjna z danymi nabywcy. Wymagane 
     *                      klucze to: 'name', 'street', 'zip', 'city'.
     *      - paymentmethod - metoda płatności. Dostępne wartości: 'cash', 
     *                      'transfer', 'compensation', 'cod', 'payment_card'.
     *      - priceType - rodzaj płatności. Dostępne wartości to 'netto' i 'brutto'.
     *      - disposaldate - data sprzedaży w formacie RRRR-MM-DD.
     *      - paymentdate - termin płatności w formacie RRRR-MM-DD.
     *      - products - tablica asocjacyjna ze sprzedanymi produktami: Wymagane
     *                      klucze to: 'name', 'unit', 'count', 'price', 'vat'.
     */
    public static function add(array $options)
    {
        $xml =  
            '<?xml version="1.0" encoding="UTF-8"?>
            <api>
                <invoices>
                    <invoice>
                        <contractor>
                            <name>' . $options['contractorData']['name'] . '</name>
                            <street>' . $options['contractorData']['street'] . '</street>
                            <zip>' . $options['contractorData']['zip'] . '</zip>
                            <city>' . $options['contractorData']['city'] . '</city>
                        </contractor>

                        <paymentmethod>' . $options['paymentmethod'] . '</paymentmethod>
                        <date>' . $options['disposaldate'] . '</date>
                        <disposaldate>' . $options['disposaldate'] . '</disposaldate>
                        <paymentdate>' . $options['paymentdate'] . '</paymentdate>
                        <price_type>' . $options['priceType'] . '</price_type>

                        <invoicecontents>';
        
            foreach ($options['products'] as $product) {
                $xml .= self::_getXmlOfInvoicecontent($product);
            }
        
        $xml .= '       </invoicecontents>

                    </invoice>
                </invoices>
            </api>';
        
        return $xml;
    }
    
    private static function _getXmlOfInvoicecontent(array $productData)
    {
        return 
            '<invoicecontent>
                <name>' . $productData['name'] . '</name>
                <unit>' . $productData['unit'] . '</unit>
                <count>' . $productData['count'] . '</count>
                <price>' . $productData['price'] . '</price>
                <vat>' . $productData['vat'] . '</vat>
                <description>' . (isset($productData['description']) ? $productData['description'] : '') . '</description>
            </invoicecontent>';
    }

    public static function delete(array $options)
    {   
    }

    public static function edit(array $options)
    {
    }

    public static function find(array $options)
    {
    }

    public static function get(array $options)
    {
    }

}
