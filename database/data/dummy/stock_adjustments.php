<?php

return [
    [
        'item_type' => 'bahan_baku',
        'item_id' => 1, // Susu Murni
        'stok_sistem' => 510,
        'stok_fisik' => 500,
        'selisih' => -10,
        'alasan' => 'Penyusutan alami penguapan saat penyimpanan',
        'tanggal' => '2026-05-21',
        'created_by' => 2,
    ],
    [
        'item_type' => 'produk',
        'item_id' => 1, // Strawberry
        'stok_sistem' => 148,
        'stok_fisik' => 150,
        'selisih' => 2,
        'alasan' => 'Kelebihan input saat perhitungan stok fisik bulanan',
        'tanggal' => '2026-05-22',
        'created_by' => 2,
    ]
];
