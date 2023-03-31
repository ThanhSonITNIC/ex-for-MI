<?php

return [
    'auth' => [
        'host' => env('TOMONI_AUTH_HOST', 'http://auth.tomoniglobal.test'),
    ],

    'accounting' => [
        'host' => env('TOMONI_ACCOUNTING_HOST', 'http://accounting.tomoniglobal.test'),
    ],

    'product' => [
        'host' => env('TOMONI_PRODUCT_HOST', 'http://product.tomoniglobal.test'),
    ],

    'warehouse' => [
        'host' => env('TOMONI_WAREHOUSE_HOST', 'http://warehouse.tomoniglobal.test'),
    ],

    'order' => [
        'host' => env('TOMONI_ORDER_HOST', 'http://order.tomoniglobal.test'),
    ],

    'helper' => [
        'host' => env('TOMONI_HELPER_HOST', 'http://helper.tomoniglobal.test'),
    ],
];