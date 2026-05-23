<?php

return [
    [
        'nomor_produksi' => 'PROD-20260518-001',
        'tanggal_mulai' => '2026-05-18',
        'tanggal_selesai' => '2026-05-18',
        'nama_batch' => 'Batch Strawberry Pagi',
        'produk_output_id' => 1, // Strawberry
        'jumlah_batch' => 2,
        'target_output' => 100,
        'actual_output' => 100,
        'status' => 'Selesai',
        'hpp_per_unit' => 3500.00,
        'catatan' => 'Susu strawberi 250ml kualitas premium',
        'created_by' => 2,
        'biaya' => [
            [
                'jenis_biaya' => 'bahan_baku',
                'keterangan' => 'Susu Murni Segar',
                'nominal' => 200000.00, // 25 liter * 8000 = 200000
            ],
            [
                'jenis_biaya' => 'bahan_penolong',
                'keterangan' => 'Gula dan Perisa',
                'nominal' => 50000.00,
            ],
            [
                'jenis_biaya' => 'tenaga_kerja',
                'keterangan' => 'Upah 1 Orang Operator',
                'nominal' => 70000.00,
            ],
            [
                'jenis_biaya' => 'overhead',
                'keterangan' => 'Penyusutan dan listrik',
                'nominal' => 30000.00,
            ]
        ],
        'bahan_pakai' => [
            [
                'bahan_baku_id' => 1, // Susu murni
                'kuantitas_pakai' => 25.00, // 25 Liter
                'harga_satuan_saat_itu' => 8000.00,
            ],
            [
                'bahan_baku_id' => 2, // Gula Pasir
                'kuantitas_pakai' => 2.00, // 2 Kg
                'harga_satuan_saat_itu' => 15000.00,
            ],
            [
                'bahan_baku_id' => 5, // Botol
                'kuantitas_pakai' => 100.00,
                'harga_satuan_saat_itu' => 800.00,
            ],
            [
                'bahan_baku_id' => 6, // Label
                'kuantitas_pakai' => 100.00,
                'harga_satuan_saat_itu' => 200.00,
            ]
        ]
    ],
    [
        'nomor_produksi' => 'PROD-20260522-002',
        'tanggal_mulai' => '2026-05-22',
        'tanggal_selesai' => null,
        'nama_batch' => 'Batch Cokelat Siang',
        'produk_output_id' => 2, // Cokelat
        'jumlah_batch' => 3,
        'target_output' => 150,
        'actual_output' => null,
        'status' => 'Proses',
        'hpp_per_unit' => null,
        'catatan' => 'Sedang dalam proses pasteurisasi',
        'created_by' => 2,
        'biaya' => [],
        'bahan_pakai' => []
    ]
];
