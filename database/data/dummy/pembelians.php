<?php

return [
    [
        'nomor_faktur' => 'INV-BB-20260515-001',
        'nomor_po' => 'PO-20260510-001',
        'supplier_id' => 1, // Segar Jaya
        'tanggal_faktur' => '2026-05-15',
        'tanggal_jatuh_tempo' => '2026-06-15',
        'subtotal' => 4000000.00,
        'total' => 4000000.00,
        'status_bayar' => 'Lunas',
        'catatan' => 'Bahan Baku Susu murni 500 liter',
        'created_by' => 2,
        'details' => [
            [
                'bahan_baku_id' => 1, // Susu Murni
                'kuantitas' => 500,
                'harga_satuan' => 8000.00,
                'subtotal' => 4000000.00,
            ]
        ],
        'pembayaran' => [
            [
                'tanggal_bayar' => '2026-05-15',
                'jumlah_bayar' => 4000000.00,
                'metode' => 'Transfer Bank Mandiri',
                'catatan' => 'Lunas langsung saat barang datang',
            ]
        ]
    ],
    [
        'nomor_faktur' => 'INV-PK-20260516-002',
        'nomor_po' => 'PO-20260512-002',
        'supplier_id' => 2, // Packindo
        'tanggal_faktur' => '2026-05-16',
        'tanggal_jatuh_tempo' => '2026-06-16',
        'subtotal' => 1600000.00,
        'total' => 1600000.00,
        'status_bayar' => 'Belum Lunas',
        'catatan' => 'Botol kemasan 2000 pcs',
        'created_by' => 2,
        'details' => [
            [
                'bahan_baku_id' => 5, // Botol Plastik
                'kuantitas' => 2000,
                'harga_satuan' => 800.00,
                'subtotal' => 1600000.00,
            ]
        ],
        'pembayaran' => []
    ]
];
