<?php
/**
 * test.php
 */
ini_set("soap.wsdl_cache_enabled", "0"); // отключаем кеширование WSDL-файла для тестирования
header("Content-Type: text/html; charset=utf-8");
header('Cache-Control: no-store, no-cache');
header('Expires: '.date('r'));

define("SERVICE_URL", "http://demo119.grissoft.com.ua/ltkapi/index.php");
//define("SERVICE_URL", "http://otapi.net/OtapiWebService2.asmx?WSDL");

$req = array(
    'customer_id' => 7
);
$client = new SoapClient(SERVICE_URL);

try {
//    print_r($client->__getTypes ());exit;
//    print_r($client->__getFunctions ());exit;
//print_r($client->getCustomer($req));
print_r($client->getCustomers($req));
//var_dump($client->getCustomer());
//var_dump($client);
//var_dump($_SERVER);

} catch (Exception $e) {
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
}