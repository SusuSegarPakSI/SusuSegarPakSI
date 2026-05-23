<?php

return [
    [
        'nomor_transaksi' => 'TRX-20260520-0001',
        'tanggal' => '2026-05-20',
        'customer_id' => 1, // Retno
        'subtotal' => 300000.00,
        'diskon_nominal' => 15000.00,
        'grand_total' => 285000.00,
        'metode_bayar' => 'Transfer',
        'jumlah_bayar' => 285000.00,
        'kembalian' => 0.00,
        'status' => 'Lunas',
        'catatan' => 'Pengiriman via GoSend',
        'created_by' => 2, // Admin Default
        'details' => [
            [
                'produk_id' => 1, // Strawberry
                'harga_satuan' => 6000.00,
                'hpp_satuan' => 3500.00,
                'kuantitas' => 30,
                'subtotal' => 180000.00,
            ],
            [
                'produk_id' => 2, // Cokelat
                'harga_satuan' => 6000.00,
                'hpp_satuan' => 3600.00,
                'kuantitas' => 20,
                'subtotal' => 120000.00,
            ]
        ]
    ],
    [
        'nomor_transaksi' => 'TRX-20260521-0002',
        'tanggal' => '2026-05-21',
        'customer_id' => 3, // Aditya
        'subtotal' => 57500.00,
        'diskon_nominal' => 0.00,
        'grand_total' => 57500.00,
        'metode_bayar' => 'Tunai',
        'jumlah_bayar' => 100000.00,
        'kembalian' => 42500.00,
        'status' => 'Lunas',
        'catatan' => 'Beli langsung di outlet',
        'created_by' => 2, // Admin Default
        'details' => [
            [
                'produk_id' => 1, // Strawberry
                'harga_satuan' => 6000.00,
                'hpp_satuan' => 3500.00,
                'kuantitas' => 5,
                'subtotal' => 30000.00,
            ],
            [
                'produk_id' => 3, // Plain
                'harga_satuan' => 5500.00,
                'hpp_satuan' => 3000.00,
                'kuantitas' => 5,
                'subtotal' => 27500.00,
            ]
        ]
    ]
];
