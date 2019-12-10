<?php

/*
|-------------------------------------------
| API Version
|-------------------------------------------
|
| This value is the version of your api.
| It's used when there's no specified
| version on the routes, so it will take this
| as the default, or current.
 */

return [

    'name' => 'Payment',

    /*
    |--------------------------------------------------------------------------
    | Sagepay Configuration
    |--------------------------------------------------------------------------
    | Vendor Name: The vendor name for the account
    | Integration Key: The value for the Integration key (username)
    | Integration Password: The value for the Integration password
    |
    */
    'vendor_name'    => env('SAGEPAY_VENDOR', 'vendorname'),

    /*
    |--------------------------------------------------------------------------
    | Mode
    |--------------------------------------------------------------------------
    | Environment: This specifies the environment for which the credentials apply (test or live)
    |
    */
    'mode'          => (strtoupper(env('APP_ENV')) !== 'PRODUCTION') ? env('SAGEPAY_MODE', 'test') : 'live',
    'base_url'      => env('SAGEPAY_URL', 'https://pi-test.sagepay.com/api/v1/'),
    'currency'      => env('SAGEPAY_CURRENCY', 'GBP'),
    'country'      => env('SAGEPAY_COUNTRY', 'GB'),
    'call'          => env('SAGEPAY_CALL', true),
    'secure3Durl'   => env('SAGEPAY_SUCCESS', 'http://sagepay.local/pay'),

    /*
    |--------------------------------------------------------------------------
    | Authentication Configuration
    |--------------------------------------------------------------------------
    | In order to access our protected resources you must authenticate with our API by providing us with your:
    |
    | Integration Key: The value for the Integration key (username)
    | Integration Password: The value for the Integration password
    |
    */
    'username'      => env('SAGEPAY_USERNAME', 'gandalf'),
    'password'      => env('SAGEPAY_PASSWORD', 'MyPreciousX350!@^!'),
    'key'           => env('SAGEPAY_KEY', 'dzlSN2ZSOWYxenhnYXNTNWVjNDZia05vaTFsekFDNGlrV1pxa2gxZnFFa1Z6RkxsS0M6enVBRnVpckM1UEc0bEoyMlQzcmxCdDRXY1NmcTRpOWdyblJOcWpHWktYVGhDOFMwVmkwakt3V21tMHc2RGhZd2Q='),

];
