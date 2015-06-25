<?php
ini_set("soap.wsdl_cache_enabled", "0"); // отключаем кеширование WSDL-файла для тестирования

// HTTP
define('HTTP_LTK', 'http://demo119.grissoft.com.ua/ltkapi/');
define('HTTP_SERVER', 'http://demo119.grissoft.com.ua/admin/');
define('HTTP_CATALOG', 'http://demo119.grissoft.com.ua/');

// HTTPS
define('HTTPS_SERVER', 'http://demo119.grissoft.com.ua/admin/');
define('HTTPS_CATALOG', 'http://demo119.grissoft.com.ua/');

// DIR
define('DIR_APPLICATION', '/var/www/demo119.grissoft.com.ua/admin/');
define('DIR_SYSTEM', '/var/www/demo119.grissoft.com.ua/system/');
define('DIR_LANGUAGE', '/var/www/demo119.grissoft.com.ua/admin/language/');
define('DIR_TEMPLATE', '/var/www/demo119.grissoft.com.ua/admin/view/template/');
define('DIR_CONFIG', '/var/www/demo119.grissoft.com.ua/system/config/');
define('DIR_IMAGE', '/var/www/demo119.grissoft.com.ua/image/');
define('DIR_CACHE', '/var/www/demo119.grissoft.com.ua/system/cache/');
define('DIR_DOWNLOAD', '/var/www/demo119.grissoft.com.ua/system/download/');
define('DIR_UPLOAD', '/var/www/demo119.grissoft.com.ua/system/upload/');
define('DIR_LOGS', '/var/www/demo119.grissoft.com.ua/system/logs/');
define('DIR_MODIFICATION', '/var/www/demo119.grissoft.com.ua/system/modification/');
define('DIR_CATALOG', '/var/www/demo119.grissoft.com.ua/catalog/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'demo119');
define('DB_PASSWORD', '258789orion');
define('DB_DATABASE', 'demo119');
define('DB_PREFIX', '');
