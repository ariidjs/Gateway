<?php

return [
    'product'   =>  [
        'base_uri'  =>  env('PRODUCT_SERVICE_BASE_URL'),
        'secret'  =>  env('PRODUCT_SERVICE_SECRET'),
    ],
    'store'   =>  [
        'base_uri' =>  env('STORE_SERVICE_BASE_URL'),
        'secret'   =>  env('STORE_SERVICE_SECRET'),
    ],
    'detailTransaction'   =>  [
        'base_uri' =>  env('DETAIL_TRANSACTION_SERVICE_BASE_URL'),
        'secret'   =>  env('DETAIL_TRANSACTION_SERVICE_SECRET'),
    ],
];
