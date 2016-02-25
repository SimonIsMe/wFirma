<?php
require '../../autoloader.php';

//  W produkcyjnym kodzie odebranie danych z zapytania powinno wyglądać tak:
//  $order = $_POST;

//  ...ale zamiast tego mamy sztuczne dane:
$order = array(
    'order' => array(
        'id' => '2',
        'created_at' => '2011-12-19 21:42:37',
        'delivery_name' => 'Odbiór osobisty',
        'delivery_price' => '850',
        'discount_code' => '',
        'discount_on_value' => '',
        'discount_type' => '',
        'discount_value' => '0',
        'landing_site' => '',
        'order_date' => '2011-12-19 21:42:37',
        'order_number' => '1007',
        'order_status' => '3',
        'payment' => '2',
        'payment_name' => 'Odbiór osobisty',
        'payment_status' => '',
        'price' => '49800',
        'referring_site' => '',
        'require_shipping' => '1',
        'updated_at' => '2011-12-19 21:42:37',
        'weight' => '600',
        'shipping_address' => array
            (
            'name' => 'Jan Kowalski',
            'phone' => '599 155 053',
            'address1' => 'Biskupa 97',
            'zip_code' => '25-122',
            'city' => 'Kielce',
            'country' => 'Poland',
            'country_code' => 'pl',
            'latitude' => '0.000000000000000',
            'longitude' => '0.000000000000000',
        ),
        'fulfillments' => array
            (
            'id' => '434234',
            'order_id' => '1001',
            'status' => 'done',
            'tracking_company' => 'inpost',
            'inpost_machine' => 'KIE100',
            'created_at' => '2013-03-12 12:43:29',
            'updated_at' => '2013-03-12 12:43:29',
        ),
        'order_items' => array
            (
            0 => array
                (
                'product_id' => '10',
                'variant_id' => '22',
                'sku' => 'ADD13',
                'name' => 'Adidas SAMOA SHOES Blue - 34',
                'title' => 'Adidas SAMOA SHOES Blue',
                'weight' => '300',
                'quantity' => '2',
                'tax' => '23.00',
                'price' => '24900',
                'require_shipping' => 1,
            )
        ),
        'customer' => array
            (
            'id' => '4',
            'name' => 'Jan',
            'last_name' => 'Kowalski',
            'full_name' => 'Jan Kowalski',
            'email' => 'jan.kowalski@somemail.com',
            'orders_count' => '44',
            'orders_price_sum' => '378707',
            'accept_newsletter' => '0',
            'created_at' => '2011-12-19 21:42:37',
            'updated_at' => '2012-05-21 10:35:59'
        )
    )
);

//  Logujemy odebrane dane
\SS\Log::log(print_r($order, true));

//  Tworzymy tablicę parametrów, które zostaną przesłane do wFirma.pl
$paymentdate = date('Y-m-d', strtotime('+' . Config::PAYMENT_DEADLINE . ' days', strtotime($order['order']['order_date'])));
$options = array(
    'contractorData' => array(
        'name' => $order['order']['shipping_address']['name'],
        'street' => $order['order']['shipping_address']['address1'],
        'zip' => $order['order']['shipping_address']['zip_code'],
        'city' => $order['order']['shipping_address']['city'],
    ),
    'paymentmethod' => Config::PAYMENT_METHOD,
    'disposaldate' => substr($order['order']['order_date'], 0, 10),
    'paymentdate' => $paymentdate,
    'priceType' => 'brutto',
    'products' => array ()
);
foreach ($order['order']['order_items'] as $orderItem) {
    $options['products'][] = array(
        'name' => $orderItem['name'],
        'unit' => 'szt.',   //  TODO: to pole jest jest wymagane, ale nie ma go w API Shoplo
        'count' => $orderItem['quantity'],
        'price' => $orderItem['price'],
        'vat' => $orderItem['tax']
    );
}

//  Łączę się z wFirma.pl i wysłam żądanie
try {
    $connector = new \wFirma\Connector\BasicAuth();
    $connector->connect(\Config::WFIRMA_USER, \Config::WFIRMA_PASSWORD);
    $api = new \wFirma\Api($connector);
    $api->addInvoice($options);
} catch (\wFirma\Connector\AuthException $exception) {
    echo $exception->getMessage();
} catch (\wFirma\Connector\ConnectorExcepton $exception) {
    echo $exception->getMessage();
}