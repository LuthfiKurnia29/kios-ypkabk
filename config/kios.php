<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rekening Bank Statis Kios Mandiri YPKABK
    |--------------------------------------------------------------------------
    |
    | Daftar rekening bank resmi Yayasan Peduli Kasih ABK untuk menerima
    | pembayaran pemesanan barang. Mudah diubah dan diperluas di sini.
    |
    */
    'bank_accounts' => [
        'BCA' => [
            'code' => 'BCA',
            'name' => 'Bank Central Asia (BCA)',
            'short_name' => 'BCA',
            'account_number' => '018-384-9900',
            'account_number_raw' => '0183849900',
            'account_holder' => 'Yayasan Peduli Kasih ABK',
            'color' => 'bg-blue-50 text-blue-800 border-blue-200',
        ],
        'BRI' => [
            'code' => 'BRI',
            'name' => 'Bank Rakyat Indonesia (BRI)',
            'short_name' => 'BRI',
            'account_number' => '009-601-002-345-502',
            'account_number_raw' => '009601002345502',
            'account_holder' => 'Yayasan Peduli Kasih ABK',
            'color' => 'bg-sky-50 text-sky-800 border-sky-200',
        ],
        'Mandiri' => [
            'code' => 'Mandiri',
            'name' => 'Bank Mandiri',
            'short_name' => 'Mandiri',
            'account_number' => '142-00-1988234-1',
            'account_number_raw' => '1420019882341',
            'account_holder' => 'Yayasan Peduli Kasih ABK',
            'color' => 'bg-amber-50 text-amber-800 border-amber-200',
        ],
        'BNI' => [
            'code' => 'BNI',
            'name' => 'Bank Negara Indonesia (BNI)',
            'short_name' => 'BNI',
            'account_number' => '083-912-4567',
            'account_number_raw' => '0839124567',
            'account_holder' => 'Yayasan Peduli Kasih ABK',
            'color' => 'bg-orange-50 text-orange-800 border-orange-200',
        ],
    ],
];
