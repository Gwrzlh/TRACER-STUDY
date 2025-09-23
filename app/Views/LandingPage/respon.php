<?php
// Dummy data per tahun
$allData = [
     "2025" => [
         ["prodi"=>"DIII - Administrasi Bisnis","finish"=>2,"ongoing"=>12,"belum"=>46,"jumlah"=>60,"persentase"=>3.33],
        ["prodi"=>"DIII - Akuntansi","finish"=>4,"ongoing"=>16,"belum"=>40,"jumlah"=>60,"persentase"=>6.67],
        ["prodi"=>"DIII - Analis Kimia","finish"=>28,"ongoing"=>24,"belum"=>10,"jumlah"=>62,"persentase"=>45.16],
        ["prodi"=>"DIII - Bahasa Inggris","finish"=>16,"ongoing"=>12,"belum"=>28,"jumlah"=>56,"persentase"=>28.57],
        ["prodi"=>"DIII - Keuangan dan Perbankan","finish"=>10,"ongoing"=>9,"belum"=>45,"jumlah"=>64,"persentase"=>15.63],
        ["prodi"=>"DIII - Manajemen Pemasaran","finish"=>2,"ongoing"=>5,"belum"=>23,"jumlah"=>30,"persentase"=>6.67],
        ["prodi"=>"DIII - Teknik Aeronautika","finish"=>1,"ongoing"=>4,"belum"=>40,"jumlah"=>45,"persentase"=>2.22],
        ["prodi"=>"DIII - Teknik Elektronika","finish"=>4,"ongoing"=>22,"belum"=>37,"jumlah"=>63,"persentase"=>6.35],
        ["prodi"=>"DIII - Teknik Informatika","finish"=>9,"ongoing"=>25,"belum"=>31,"jumlah"=>65,"persentase"=>13.85],
        ["prodi"=>"DIII - Teknik Kimia","finish"=>16,"ongoing"=>29,"belum"=>45,"jumlah"=>90,"persentase"=>17.78],
        ["prodi"=>"DIII - Teknik Konstruksi Gedung","finish"=>5,"ongoing"=>21,"belum"=>34,"jumlah"=>60,"persentase"=>8.33],
        ["prodi"=>"DIII - Teknik Konstruksi Sipil","finish"=>9,"ongoing"=>12,"belum"=>40,"jumlah"=>61,"persentase"=>14.75],
        ["prodi"=>"DIII - Teknik Konversi Energi","finish"=>7,"ongoing"=>18,"belum"=>38,"jumlah"=>63,"persentase"=>11.11],
        ["prodi"=>"DIII - Teknik Listrik","finish"=>4,"ongoing"=>14,"belum"=>45,"jumlah"=>63,"persentase"=>6.35],
        ["prodi"=>"DIII - Teknik Mesin","finish"=>4,"ongoing"=>14,"belum"=>44,"jumlah"=>62,"persentase"=>6.45],
        ["prodi"=>"DIII - Teknik Pendingin dan Tata Udara","finish"=>7,"ongoing"=>17,"belum"=>35,"jumlah"=>59,"persentase"=>11.86],
        ["prodi"=>"DIII - Teknik Telekomunikasi","finish"=>8,"ongoing"=>20,"belum"=>32,"jumlah"=>60,"persentase"=>13.33],
        ["prodi"=>"DIII - Usaha Perjalanan Wisata","finish"=>50,"ongoing"=>4,"belum"=>6,"jumlah"=>60,"persentase"=>83.33],
        ["prodi"=>"DIV - Administrasi Bisnis","finish"=>3,"ongoing"=>19,"belum"=>34,"jumlah"=>56,"persentase"=>5.36],
        ["prodi"=>"DIV - Akuntansi","finish"=>3,"ongoing"=>13,"belum"=>45,"jumlah"=>61,"persentase"=>4.92],
        ["prodi"=>"DIV - Akuntansi Manajemen Pemerintahan","finish"=>0,"ongoing"=>18,"belum"=>44,"jumlah"=>62,"persentase"=>0],
        ["prodi"=>"DIV - Keuangan Syariah","finish"=>47,"ongoing"=>11,"belum"=>3,"jumlah"=>61,"persentase"=>77.05],
        ["prodi"=>"DIV - Manajemen Aset","finish"=>4,"ongoing"=>27,"belum"=>32,"jumlah"=>63,"persentase"=>6.35],
        ["prodi"=>"DIV - Manajemen Pemasaran","finish"=>3,"ongoing"=>10,"belum"=>47,"jumlah"=>60,"persentase"=>5],
        ["prodi"=>"DIV - Proses Manufaktur","finish"=>1,"ongoing"=>10,"belum"=>20,"jumlah"=>31,"persentase"=>3.23],
        ["prodi"=>"DIV - Teknik Elektronika","finish"=>3,"ongoing"=>8,"belum"=>14,"jumlah"=>25,"persentase"=>12],
        ["prodi"=>"DIV - Teknik Informatika","finish"=>9,"ongoing"=>27,"belum"=>26,"jumlah"=>62,"persentase"=>14.52],
        ["prodi"=>"DIV - Teknik Kimia Produksi Bersih","finish"=>23,"ongoing"=>0,"belum"=>0,"jumlah"=>23,"persentase"=>100],
        ["prodi"=>"DIV - Teknik Konservasi Energi","finish"=>1,"ongoing"=>9,"belum"=>23,"jumlah"=>33,"persentase"=>3.03],
        ["prodi"=>"DIV - Teknik Otomasi Industri","finish"=>3,"ongoing"=>8,"belum"=>17,"jumlah"=>28,"persentase"=>10.71],
        ["prodi"=>"DIV - Teknik Pendingin dan Tata Udara","finish"=>5,"ongoing"=>15,"belum"=>34,"jumlah"=>54,"persentase"=>9.26],
        ["prodi"=>"DIV - Teknik Perancangan dan Konstruksi Mesin","finish"=>14,"ongoing"=>9,"belum"=>4,"jumlah"=>27,"persentase"=>51.85],
        ["prodi"=>"DIV - Teknik Perancangan Jalan dan Jembatan","finish"=>2,"ongoing"=>9,"belum"=>43,"jumlah"=>54,"persentase"=>3.7],
        ["prodi"=>"DIV - Teknik Perawatan dan Perbaikan Gedung","finish"=>13,"ongoing"=>16,"belum"=>22,"jumlah"=>51,"persentase"=>25.49],
        ["prodi"=>"DIV - Teknik Telekomunikasi","finish"=>6,"ongoing"=>7,"belum"=>15,"jumlah"=>28,"persentase"=>21.43],
        ["prodi"=>"DIV - Teknologi Pembangkit Tenaga Listrik","finish"=>1,"ongoing"=>7,"belum"=>24,"jumlah"=>32,"persentase"=>3.13],
        ["prodi"=>"Keuangan dan Perbankan Syariah Terapan","finish"=>4,"ongoing"=>3,"belum"=>3,"jumlah"=>10,"persentase"=>40],
        ["prodi"=>"Rekayasa Infrastruktur (MRTI)","finish"=>2,"ongoing"=>5,"belum"=>8,"jumlah"=>15,"persentase"=>13.33],
    ],
    "2024" => [
        ["prodi"=>"DIII - Administrasi Bisnis","finish"=>2,"ongoing"=>12,"belum"=>46,"jumlah"=>60,"persentase"=>3.33],
        ["prodi"=>"DIII - Akuntansi","finish"=>4,"ongoing"=>16,"belum"=>40,"jumlah"=>60,"persentase"=>6.67],
        ["prodi"=>"DIII - Analis Kimia","finish"=>28,"ongoing"=>24,"belum"=>10,"jumlah"=>62,"persentase"=>45.16],
        ["prodi"=>"DIII - Bahasa Inggris","finish"=>16,"ongoing"=>12,"belum"=>28,"jumlah"=>56,"persentase"=>28.57],
        ["prodi"=>"DIII - Keuangan dan Perbankan","finish"=>10,"ongoing"=>9,"belum"=>45,"jumlah"=>64,"persentase"=>15.63],
        ["prodi"=>"DIII - Manajemen Pemasaran","finish"=>2,"ongoing"=>5,"belum"=>23,"jumlah"=>30,"persentase"=>6.67],
        ["prodi"=>"DIII - Teknik Aeronautika","finish"=>1,"ongoing"=>4,"belum"=>40,"jumlah"=>45,"persentase"=>2.22],
        ["prodi"=>"DIII - Teknik Elektronika","finish"=>4,"ongoing"=>22,"belum"=>37,"jumlah"=>63,"persentase"=>6.35],
        ["prodi"=>"DIII - Teknik Informatika","finish"=>9,"ongoing"=>25,"belum"=>31,"jumlah"=>65,"persentase"=>13.85],
        ["prodi"=>"DIII - Teknik Kimia","finish"=>16,"ongoing"=>29,"belum"=>45,"jumlah"=>90,"persentase"=>17.78],
        ["prodi"=>"DIII - Teknik Konstruksi Gedung","finish"=>5,"ongoing"=>21,"belum"=>34,"jumlah"=>60,"persentase"=>8.33],
        ["prodi"=>"DIII - Teknik Konstruksi Sipil","finish"=>9,"ongoing"=>12,"belum"=>40,"jumlah"=>61,"persentase"=>14.75],
        ["prodi"=>"DIII - Teknik Konversi Energi","finish"=>7,"ongoing"=>18,"belum"=>38,"jumlah"=>63,"persentase"=>11.11],
        ["prodi"=>"DIII - Teknik Listrik","finish"=>4,"ongoing"=>14,"belum"=>45,"jumlah"=>63,"persentase"=>6.35],
        ["prodi"=>"DIII - Teknik Mesin","finish"=>4,"ongoing"=>14,"belum"=>44,"jumlah"=>62,"persentase"=>6.45],
        ["prodi"=>"DIII - Teknik Pendingin dan Tata Udara","finish"=>7,"ongoing"=>17,"belum"=>35,"jumlah"=>59,"persentase"=>11.86],
        ["prodi"=>"DIII - Teknik Telekomunikasi","finish"=>8,"ongoing"=>20,"belum"=>32,"jumlah"=>60,"persentase"=>13.33],
        ["prodi"=>"DIII - Usaha Perjalanan Wisata","finish"=>50,"ongoing"=>4,"belum"=>6,"jumlah"=>60,"persentase"=>83.33],
        ["prodi"=>"DIV - Administrasi Bisnis","finish"=>3,"ongoing"=>19,"belum"=>34,"jumlah"=>56,"persentase"=>5.36],
        ["prodi"=>"DIV - Akuntansi","finish"=>3,"ongoing"=>13,"belum"=>45,"jumlah"=>61,"persentase"=>4.92],
        ["prodi"=>"DIV - Akuntansi Manajemen Pemerintahan","finish"=>0,"ongoing"=>18,"belum"=>44,"jumlah"=>62,"persentase"=>0],
        ["prodi"=>"DIV - Keuangan Syariah","finish"=>47,"ongoing"=>11,"belum"=>3,"jumlah"=>61,"persentase"=>77.05],
        ["prodi"=>"DIV - Manajemen Aset","finish"=>4,"ongoing"=>27,"belum"=>32,"jumlah"=>63,"persentase"=>6.35],
        ["prodi"=>"DIV - Manajemen Pemasaran","finish"=>3,"ongoing"=>10,"belum"=>47,"jumlah"=>60,"persentase"=>5],
        ["prodi"=>"DIV - Proses Manufaktur","finish"=>1,"ongoing"=>10,"belum"=>20,"jumlah"=>31,"persentase"=>3.23],
        ["prodi"=>"DIV - Teknik Elektronika","finish"=>3,"ongoing"=>8,"belum"=>14,"jumlah"=>25,"persentase"=>12],
        ["prodi"=>"DIV - Teknik Informatika","finish"=>9,"ongoing"=>27,"belum"=>26,"jumlah"=>62,"persentase"=>14.52],
        ["prodi"=>"DIV - Teknik Kimia Produksi Bersih","finish"=>23,"ongoing"=>0,"belum"=>0,"jumlah"=>23,"persentase"=>100],
        ["prodi"=>"DIV - Teknik Konservasi Energi","finish"=>1,"ongoing"=>9,"belum"=>23,"jumlah"=>33,"persentase"=>3.03],
        ["prodi"=>"DIV - Teknik Otomasi Industri","finish"=>3,"ongoing"=>8,"belum"=>17,"jumlah"=>28,"persentase"=>10.71],
        ["prodi"=>"DIV - Teknik Pendingin dan Tata Udara","finish"=>5,"ongoing"=>15,"belum"=>34,"jumlah"=>54,"persentase"=>9.26],
        ["prodi"=>"DIV - Teknik Perancangan dan Konstruksi Mesin","finish"=>14,"ongoing"=>9,"belum"=>4,"jumlah"=>27,"persentase"=>51.85],
        ["prodi"=>"DIV - Teknik Perancangan Jalan dan Jembatan","finish"=>2,"ongoing"=>9,"belum"=>43,"jumlah"=>54,"persentase"=>3.7],
        ["prodi"=>"DIV - Teknik Perawatan dan Perbaikan Gedung","finish"=>13,"ongoing"=>16,"belum"=>22,"jumlah"=>51,"persentase"=>25.49],
        ["prodi"=>"DIV - Teknik Telekomunikasi","finish"=>6,"ongoing"=>7,"belum"=>15,"jumlah"=>28,"persentase"=>21.43],
        ["prodi"=>"DIV - Teknologi Pembangkit Tenaga Listrik","finish"=>1,"ongoing"=>7,"belum"=>24,"jumlah"=>32,"persentase"=>3.13],
        ["prodi"=>"Keuangan dan Perbankan Syariah Terapan","finish"=>4,"ongoing"=>3,"belum"=>3,"jumlah"=>10,"persentase"=>40],
        ["prodi"=>"Rekayasa Infrastruktur (MRTI)","finish"=>2,"ongoing"=>5,"belum"=>8,"jumlah"=>15,"persentase"=>13.33],
    ],
   "2023" => [
        ["prodi"=>"DIII - Administrasi Bisnis","finish"=>55,"ongoing"=>1,"belum"=>6,"jumlah"=>62,"persentase"=>88.71],
        ["prodi"=>"DIII - Akuntansi","finish"=>52,"ongoing"=>4,"belum"=>7,"jumlah"=>63,"persentase"=>82.54],
        ["prodi"=>"DIII - Analis Kimia","finish"=>30,"ongoing"=>0,"belum"=>0,"jumlah"=>30,"persentase"=>100],
        ["prodi"=>"DIII - Bahasa Inggris","finish"=>58,"ongoing"=>1,"belum"=>5,"jumlah"=>64,"persentase"=>90.63],
        ["prodi"=>"DIII - Keuangan dan Perbankan","finish"=>51,"ongoing"=>3,"belum"=>5,"jumlah"=>59,"persentase"=>86.44],
        ["prodi"=>"DIII - Manajemen Pemasaran","finish"=>24,"ongoing"=>3,"belum"=>4,"jumlah"=>31,"persentase"=>77.42],
        ["prodi"=>"DIII - Teknik Aeronautika","finish"=>37,"ongoing"=>1,"belum"=>5,"jumlah"=>43,"persentase"=>86.05],
        ["prodi"=>"DIII - Teknik Elektronika","finish"=>32,"ongoing"=>9,"belum"=>14,"jumlah"=>55,"persentase"=>58.18],
        ["prodi"=>"DIII - Teknik Informatika","finish"=>48,"ongoing"=>0,"belum"=>0,"jumlah"=>48,"persentase"=>100],
        ["prodi"=>"DIII - Teknik Kimia","finish"=>90,"ongoing"=>0,"belum"=>0,"jumlah"=>90,"persentase"=>100],
        ["prodi"=>"DIII - Teknik Konstruksi Gedung","finish"=>59,"ongoing"=>2,"belum"=>9,"jumlah"=>70,"persentase"=>84.29],
        ["prodi"=>"DIII - Teknik Konstruksi Sipil","finish"=>56,"ongoing"=>0,"belum"=>0,"jumlah"=>56,"persentase"=>100],
        ["prodi"=>"DIII - Teknik Konversi Energi","finish"=>36,"ongoing"=>5,"belum"=>25,"jumlah"=>66,"persentase"=>54.55],
        ["prodi"=>"DIII - Teknik Listrik","finish"=>42,"ongoing"=>5,"belum"=>14,"jumlah"=>61,"persentase"=>68.85],
        ["prodi"=>"DIII - Teknik Mesin","finish"=>62,"ongoing"=>0,"belum"=>0,"jumlah"=>62,"persentase"=>100],
        ["prodi"=>"DIII - Teknik Pendingin dan Tata Udara","finish"=>58,"ongoing"=>0,"belum"=>3,"jumlah"=>61,"persentase"=>95.08],
        ["prodi"=>"DIII - Teknik Telekomunikasi","finish"=>63,"ongoing"=>0,"belum"=>0,"jumlah"=>63,"persentase"=>100],
        ["prodi"=>"DIII - Usaha Perjalanan Wisata","finish"=>57,"ongoing"=>0,"belum"=>0,"jumlah"=>57,"persentase"=>100],
        ["prodi"=>"DIV - Administrasi Bisnis","finish"=>48,"ongoing"=>4,"belum"=>5,"jumlah"=>57,"persentase"=>84.21],
        ["prodi"=>"DIV - Akuntansi","finish"=>42,"ongoing"=>5,"belum"=>11,"jumlah"=>58,"persentase"=>72.41],
        ["prodi"=>"DIV - Akuntansi Manajemen Pemerintahan","finish"=>53,"ongoing"=>3,"belum"=>1,"jumlah"=>57,"persentase"=>92.98],
        ["prodi"=>"DIV - Keuangan Syariah","finish"=>61,"ongoing"=>1,"belum"=>1,"jumlah"=>63,"persentase"=>96.83],
        ["prodi"=>"DIV - Manajemen Aset","finish"=>108,"ongoing"=>0,"belum"=>2,"jumlah"=>110,"persentase"=>98.18],
        ["prodi"=>"DIV - Manajemen Pemasaran","finish"=>49,"ongoing"=>0,"belum"=>2,"jumlah"=>51,"persentase"=>96.08],
        ["prodi"=>"DIV - Proses Manufaktur","finish"=>23,"ongoing"=>1,"belum"=>0,"jumlah"=>24,"persentase"=>95.83],
        ["prodi"=>"DIV - Teknik Elektronika","finish"=>29,"ongoing"=>0,"belum"=>0,"jumlah"=>29,"persentase"=>100],
        ["prodi"=>"DIV - Teknik Informatika","finish"=>50,"ongoing"=>1,"belum"=>1,"jumlah"=>52,"persentase"=>96.15],
        ["prodi"=>"DIV - Teknik Kimia Produksi Bersih","finish"=>28,"ongoing"=>0,"belum"=>0,"jumlah"=>28,"persentase"=>100],
        ["prodi"=>"DIV - Teknik Konservasi Energi","finish"=>32,"ongoing"=>0,"belum"=>1,"jumlah"=>33,"persentase"=>96.97],
        ["prodi"=>"DIV - Teknik Otomasi Industri","finish"=>27,"ongoing"=>0,"belum"=>2,"jumlah"=>29,"persentase"=>93.1],
        ["prodi"=>"DIV - Teknik Pendingin dan Tata Udara","finish"=>28,"ongoing"=>0,"belum"=>0,"jumlah"=>28,"persentase"=>100],
        ["prodi"=>"DIV - Teknik Perancangan dan Konstruksi Mesin","finish"=>24,"ongoing"=>0,"belum"=>0,"jumlah"=>24,"persentase"=>100],
        ["prodi"=>"DIV - Teknik Perancangan Jalan dan Jembatan","finish"=>31,"ongoing"=>3,"belum"=>3,"jumlah"=>37,"persentase"=>83.78],
        ["prodi"=>"DIV - Teknik Perawatan dan Perbaikan Gedung","finish"=>26,"ongoing"=>0,"belum"=>3,"jumlah"=>29,"persentase"=>89.66],
        ["prodi"=>"DIV - Teknik Telekomunikasi","finish"=>31,"ongoing"=>0,"belum"=>0,"jumlah"=>31,"persentase"=>100],
        ["prodi"=>"DIV - Teknologi Pembangkit Tenaga Listrik","finish"=>42,"ongoing"=>3,"belum"=>8,"jumlah"=>53,"persentase"=>79.25],
        ["prodi"=>"Keuangan dan Perbankan Syariah Terapan","finish"=>8,"ongoing"=>1,"belum"=>3,"jumlah"=>12,"persentase"=>66.67],
        ["prodi"=>"Rekayasa Infrastruktur","finish"=>8,"ongoing"=>0,"belum"=>4,"jumlah"=>12,"persentase"=>66.67],
    ], 
    "2022" => [
    ["prodi"=>"DIII - Administrasi Bisnis","finish"=>41,"ongoing"=>12,"belum"=>5,"jumlah"=>58,"persentase"=>70.69],
    ["prodi"=>"DIII - Akuntansi","finish"=>52,"ongoing"=>8,"belum"=>0,"jumlah"=>60,"persentase"=>86.67],
    ["prodi"=>"DIII - Analis Kimia","finish"=>24,"ongoing"=>6,"belum"=>1,"jumlah"=>31,"persentase"=>77.42],
    ["prodi"=>"DIII - Bahasa Inggris","finish"=>45,"ongoing"=>11,"belum"=>0,"jumlah"=>56,"persentase"=>80.36],
    ["prodi"=>"DIII - Keuangan dan Perbankan","finish"=>50,"ongoing"=>12,"belum"=>1,"jumlah"=>63,"persentase"=>79.37],
    ["prodi"=>"DIII - Manajemen Pemasaran","finish"=>17,"ongoing"=>4,"belum"=>8,"jumlah"=>29,"persentase"=>58.62],
    ["prodi"=>"DIII - Teknik Aeronautika","finish"=>49,"ongoing"=>8,"belum"=>0,"jumlah"=>57,"persentase"=>85.96],
    ["prodi"=>"DIII - Teknik Elektronika","finish"=>44,"ongoing"=>8,"belum"=>2,"jumlah"=>54,"persentase"=>81.48],
    ["prodi"=>"DIII - Teknik Informatika","finish"=>50,"ongoing"=>5,"belum"=>3,"jumlah"=>58,"persentase"=>86.21],
    ["prodi"=>"DIII - Teknik Kimia","finish"=>80,"ongoing"=>12,"belum"=>0,"jumlah"=>92,"persentase"=>86.96],
    ["prodi"=>"DIII - Teknik Konstruksi Gedung","finish"=>45,"ongoing"=>7,"belum"=>0,"jumlah"=>52,"persentase"=>86.54],
    ["prodi"=>"DIII - Teknik Konstruksi Sipil","finish"=>50,"ongoing"=>1,"belum"=>0,"jumlah"=>51,"persentase"=>98.04],
    ["prodi"=>"DIII - Teknik Konversi Energi","finish"=>44,"ongoing"=>11,"belum"=>5,"jumlah"=>60,"persentase"=>73.33],
    ["prodi"=>"DIII - Teknik Listrik","finish"=>39,"ongoing"=>9,"belum"=>12,"jumlah"=>60,"persentase"=>65.00],
    ["prodi"=>"DIII - Teknik Mesin","finish"=>53,"ongoing"=>6,"belum"=>2,"jumlah"=>61,"persentase"=>86.89],
    ["prodi"=>"DIII - Teknik Pendingin dan Tata Udara","finish"=>51,"ongoing"=>8,"belum"=>2,"jumlah"=>61,"persentase"=>83.61],
    ["prodi"=>"DIII - Teknik Telekomunikasi","finish"=>55,"ongoing"=>7,"belum"=>0,"jumlah"=>62,"persentase"=>88.71],
    ["prodi"=>"DIII - Usaha Perjalanan Wisata","finish"=>41,"ongoing"=>15,"belum"=>0,"jumlah"=>56,"persentase"=>73.21],
    ["prodi"=>"DIV - Administrasi Bisnis","finish"=>26,"ongoing"=>5,"belum"=>0,"jumlah"=>31,"persentase"=>83.87],
    ["prodi"=>"DIV - Akuntansi","finish"=>50,"ongoing"=>4,"belum"=>0,"jumlah"=>54,"persentase"=>92.59],
    ["prodi"=>"DIV - Akuntansi Manajemen Pemerintahan","finish"=>59,"ongoing"=>3,"belum"=>2,"jumlah"=>64,"persentase"=>92.19],
    ["prodi"=>"DIV - Keuangan Syariah","finish"=>50,"ongoing"=>12,"belum"=>1,"jumlah"=>63,"persentase"=>79.37],
    ["prodi"=>"DIV - Manajemen Aset","finish"=>47,"ongoing"=>9,"belum"=>0,"jumlah"=>56,"persentase"=>83.93],
    ["prodi"=>"DIV - Manajemen Pemasaran","finish"=>49,"ongoing"=>6,"belum"=>4,"jumlah"=>59,"persentase"=>83.05],
    ["prodi"=>"DIV - Proses Manufaktur","finish"=>25,"ongoing"=>4,"belum"=>0,"jumlah"=>29,"persentase"=>86.21],
    ["prodi"=>"DIV - Teknik Elektronika","finish"=>21,"ongoing"=>1,"belum"=>0,"jumlah"=>22,"persentase"=>95.45],
    ["prodi"=>"DIV - Teknik Informatika","finish"=>22,"ongoing"=>2,"belum"=>0,"jumlah"=>24,"persentase"=>91.67],
    ["prodi"=>"DIV - Teknik Kimia Produksi Bersih","finish"=>31,"ongoing"=>5,"belum"=>2,"jumlah"=>38,"persentase"=>81.58],
    ["prodi"=>"DIV - Teknik Konservasi Energi","finish"=>14,"ongoing"=>4,"belum"=>2,"jumlah"=>20,"persentase"=>70.00],
    ["prodi"=>"DIV - Teknik Otomasi Industri","finish"=>21,"ongoing"=>6,"belum"=>0,"jumlah"=>27,"persentase"=>77.78],
    ["prodi"=>"DIV - Teknik Pendingin dan Tata Udara","finish"=>28,"ongoing"=>2,"belum"=>0,"jumlah"=>30,"persentase"=>93.33],
    ["prodi"=>"DIV - Teknik Perancangan dan Konstruksi Mesin","finish"=>21,"ongoing"=>1,"belum"=>1,"jumlah"=>23,"persentase"=>91.30],
    ["prodi"=>"DIV - Teknik Perancangan Jalan dan Jembatan","finish"=>23,"ongoing"=>4,"belum"=>0,"jumlah"=>27,"persentase"=>85.19],
    ["prodi"=>"DIV - Teknik Perawatan dan Perbaikan Gedung","finish"=>21,"ongoing"=>2,"belum"=>0,"jumlah"=>23,"persentase"=>91.30],
    ["prodi"=>"DIV - Teknik Telekomunikasi","finish"=>23,"ongoing"=>4,"belum"=>0,"jumlah"=>27,"persentase"=>85.19],
    ["prodi"=>"DIV - Teknologi Pembangkit Tenaga Listrik","finish"=>21,"ongoing"=>5,"belum"=>1,"jumlah"=>27,"persentase"=>77.78],
    ["prodi"=>"Keuangan dan Perbankan Syariah Terapan","finish"=>5,"ongoing"=>0,"belum"=>3,"jumlah"=>8,"persentase"=>62.50],
    ["prodi"=>"Rekayasa Infrastruktur","finish"=>10,"ongoing"=>0,"belum"=>0,"jumlah"=>10,"persentase"=>100.00],
],
   "2021" => [
    ["prodi"=>"DIII - Administrasi Bisnis","finish"=>45,"ongoing"=>4,"belum"=>7,"jumlah"=>56,"persentase"=>80.36],
    ["prodi"=>"DIII - Akuntansi","finish"=>55,"ongoing"=>0,"belum"=>7,"jumlah"=>62,"persentase"=>88.71],
    ["prodi"=>"DIII - Analis Kimia","finish"=>28,"ongoing"=>0,"belum"=>0,"jumlah"=>28,"persentase"=>100],
    ["prodi"=>"DIII - Bahasa Inggris","finish"=>27,"ongoing"=>0,"belum"=>19,"jumlah"=>46,"persentase"=>58.7],
    ["prodi"=>"DIII - Keuangan dan Perbankan","finish"=>16,"ongoing"=>3,"belum"=>41,"jumlah"=>60,"persentase"=>26.67],
    ["prodi"=>"DIII - Manajemen Pemasaran","finish"=>9,"ongoing"=>2,"belum"=>20,"jumlah"=>31,"persentase"=>29.03],
    ["prodi"=>"DIII - Teknik Aeronautika","finish"=>18,"ongoing"=>6,"belum"=>25,"jumlah"=>49,"persentase"=>36.73],
    ["prodi"=>"DIII - Teknik Elektronika","finish"=>25,"ongoing"=>3,"belum"=>19,"jumlah"=>47,"persentase"=>53.19],
    ["prodi"=>"DIII - Teknik Informatika","finish"=>58,"ongoing"=>0,"belum"=>0,"jumlah"=>58,"persentase"=>100],
    ["prodi"=>"DIII - Teknik Kimia","finish"=>73,"ongoing"=>1,"belum"=>10,"jumlah"=>84,"persentase"=>86.9],
    ["prodi"=>"DIII - Teknik Konstruksi Gedung","finish"=>52,"ongoing"=>1,"belum"=>5,"jumlah"=>58,"persentase"=>89.66],
    ["prodi"=>"DIII - Teknik Konstruksi Sipil","finish"=>39,"ongoing"=>2,"belum"=>14,"jumlah"=>55,"persentase"=>70.91],
    ["prodi"=>"DIII - Teknik Konversi Energi","finish"=>15,"ongoing"=>4,"belum"=>20,"jumlah"=>39,"persentase"=>38.46],
    ["prodi"=>"DIII - Teknik Listrik","finish"=>45,"ongoing"=>4,"belum"=>14,"jumlah"=>63,"persentase"=>71.43],
    ["prodi"=>"DIII - Teknik Mesin","finish"=>13,"ongoing"=>4,"belum"=>34,"jumlah"=>51,"persentase"=>25.49],
    ["prodi"=>"DIII - Teknik Pendingin dan Tata Udara","finish"=>55,"ongoing"=>0,"belum"=>5,"jumlah"=>60,"persentase"=>91.67],
    ["prodi"=>"DIII - Teknik Telekomunikasi","finish"=>56,"ongoing"=>1,"belum"=>3,"jumlah"=>60,"persentase"=>93.33],
    ["prodi"=>"DIII - Usaha Perjalanan Wisata","finish"=>28,"ongoing"=>0,"belum"=>0,"jumlah"=>28,"persentase"=>100],
    ["prodi"=>"DIV - Administrasi Bisnis","finish"=>28,"ongoing"=>2,"belum"=>2,"jumlah"=>32,"persentase"=>87.5],
    ["prodi"=>"DIV - Akuntansi","finish"=>25,"ongoing"=>7,"belum"=>25,"jumlah"=>57,"persentase"=>43.86],
    ["prodi"=>"DIV - Akuntansi Manajemen Pemerintahan","finish"=>28,"ongoing"=>6,"belum"=>23,"jumlah"=>57,"persentase"=>49.12],
    ["prodi"=>"DIV - Keuangan Syariah","finish"=>46,"ongoing"=>5,"belum"=>10,"jumlah"=>61,"persentase"=>75.41],
    ["prodi"=>"DIV - Manajemen Aset","finish"=>37,"ongoing"=>0,"belum"=>23,"jumlah"=>60,"persentase"=>61.67],
    ["prodi"=>"DIV - Manajemen Pemasaran","finish"=>30,"ongoing"=>1,"belum"=>30,"jumlah"=>61,"persentase"=>49.18],
    ["prodi"=>"DIV - Proses Manufaktur","finish"=>7,"ongoing"=>0,"belum"=>25,"jumlah"=>32,"persentase"=>21.88],
    ["prodi"=>"DIV - Teknik Elektronika","finish"=>19,"ongoing"=>1,"belum"=>7,"jumlah"=>27,"persentase"=>70.37],
    ["prodi"=>"DIV - Teknik Informatika","finish"=>34,"ongoing"=>0,"belum"=>0,"jumlah"=>34,"persentase"=>100],
    ["prodi"=>"DIV - Teknik Kimia Produksi Bersih","finish"=>29,"ongoing"=>0,"belum"=>3,"jumlah"=>32,"persentase"=>90.63],
    ["prodi"=>"DIV - Teknik Konservasi Energi","finish"=>7,"ongoing"=>2,"belum"=>19,"jumlah"=>28,"persentase"=>25],
    ["prodi"=>"DIV - Teknik Otomasi Industri","finish"=>30,"ongoing"=>0,"belum"=>0,"jumlah"=>30,"persentase"=>100],
    ["prodi"=>"DIV - Teknik Pendingin dan Tata Udara","finish"=>29,"ongoing"=>1,"belum"=>0,"jumlah"=>30,"persentase"=>96.67],
    ["prodi"=>"DIV - Teknik Perancangan dan Konstruksi Mesin","finish"=>15,"ongoing"=>2,"belum"=>10,"jumlah"=>27,"persentase"=>55.56],
    ["prodi"=>"DIV - Teknik Perancangan Jalan dan Jembatan","finish"=>19,"ongoing"=>1,"belum"=>6,"jumlah"=>26,"persentase"=>73.08],
    ["prodi"=>"DIV - Teknik Perawatan dan Perbaikan Gedung","finish"=>23,"ongoing"=>0,"belum"=>7,"jumlah"=>30,"persentase"=>76.67],
    ["prodi"=>"DIV - Teknik Telekomunikasi","finish"=>29,"ongoing"=>0,"belum"=>0,"jumlah"=>29,"persentase"=>100],
    ["prodi"=>"DIV - Teknologi Pembangkit Tenaga Listrik","finish"=>15,"ongoing"=>1,"belum"=>11,"jumlah"=>27,"persentase"=>55.56],
],
    "2020" => [
        ["prodi"=>"DIII - Administrasi Bisnis","finish"=>16,"ongoing"=>22,"belum"=>22,"jumlah"=>60,"persentase"=>26.67],
        ["prodi"=>"DIII - Akuntansi","finish"=>19,"ongoing"=>21,"belum"=>20,"jumlah"=>60,"persentase"=>31.67],
        ["prodi"=>"DIII - Teknik Informatika","finish"=>25,"ongoing"=>25,"belum"=>10,"jumlah"=>60,"persentase"=>41.67],
        ["prodi"=>"DIII - Teknik Mesin","finish"=>12,"ongoing"=>30,"belum"=>18,"jumlah"=>60,"persentase"=>20],
        ["prodi"=>"DIV - Manajemen Aset","finish"=>20,"ongoing"=>20,"belum"=>20,"jumlah"=>60,"persentase"=>33.33],
        ["prodi"=>"DIV - Teknik Informatika","finish"=>28,"ongoing"=>18,"belum"=>14,"jumlah"=>60,"persentase"=>46.67],
        ["prodi"=>"DIV - Teknik Telekomunikasi","finish"=>22,"ongoing"=>20,"belum"=>18,"jumlah"=>60,"persentase"=>36.67],
        ["prodi"=>"DIV - Teknik Kimia","finish"=>27,"ongoing"=>15,"belum"=>18,"jumlah"=>60,"persentase"=>45],
        ["prodi"=>"DIV - Teknik Elektronika","finish"=>15,"ongoing"=>25,"belum"=>20,"jumlah"=>60,"persentase"=>25],
        ["prodi"=>"DIV - Manajemen Pemasaran","finish"=>10,"ongoing"=>20,"belum"=>30,"jumlah"=>60,"persentase"=>16.67],
    ],
    "2019" => [
        ["prodi"=>"DIII - Administrasi Bisnis","finish"=>14,"ongoing"=>26,"belum"=>20,"jumlah"=>60,"persentase"=>23.33],
        ["prodi"=>"DIII - Akuntansi","finish"=>20,"ongoing"=>20,"belum"=>20,"jumlah"=>60,"persentase"=>33.33],
        ["prodi"=>"DIII - Teknik Informatika","finish"=>24,"ongoing"=>18,"belum"=>18,"jumlah"=>60,"persentase"=>40],
        ["prodi"=>"DIII - Teknik Mesin","finish"=>18,"ongoing"=>22,"belum"=>20,"jumlah"=>60,"persentase"=>30],
        ["prodi"=>"DIV - Manajemen Aset","finish"=>19,"ongoing"=>21,"belum"=>20,"jumlah"=>60,"persentase"=>31.67],
        ["prodi"=>"DIV - Teknik Informatika","finish"=>26,"ongoing"=>16,"belum"=>18,"jumlah"=>60,"persentase"=>43.33],
        ["prodi"=>"DIV - Teknik Telekomunikasi","finish"=>21,"ongoing"=>19,"belum"=>20,"jumlah"=>60,"persentase"=>35],
        ["prodi"=>"DIV - Teknik Kimia","finish"=>23,"ongoing"=>17,"belum"=>20,"jumlah"=>60,"persentase"=>38.33],
        ["prodi"=>"DIV - Teknik Elektronika","finish"=>17,"ongoing"=>23,"belum"=>20,"jumlah"=>60,"persentase"=>28.33],
        ["prodi"=>"DIV - Manajemen Pemasaran","finish"=>12,"ongoing"=>18,"belum"=>30,"jumlah"=>60,"persentase"=>20],
    ],
    "2018" => [
        ["prodi"=>"DIII - Administrasi Bisnis","finish"=>10,"ongoing"=>25,"belum"=>25,"jumlah"=>60,"persentase"=>16.67],
        ["prodi"=>"DIII - Akuntansi","finish"=>18,"ongoing"=>22,"belum"=>20,"jumlah"=>60,"persentase"=>30],
        ["prodi"=>"DIII - Teknik Informatika","finish"=>22,"ongoing"=>20,"belum"=>18,"jumlah"=>60,"persentase"=>36.67],
        ["prodi"=>"DIII - Teknik Mesin","finish"=>16,"ongoing"=>24,"belum"=>20,"jumlah"=>60,"persentase"=>26.67],
        ["prodi"=>"DIV - Manajemen Aset","finish"=>20,"ongoing"=>20,"belum"=>20,"jumlah"=>60,"persentase"=>33.33],
        ["prodi"=>"DIV - Teknik Informatika","finish"=>24,"ongoing"=>18,"belum"=>18,"jumlah"=>60,"persentase"=>40],
        ["prodi"=>"DIV - Teknik Telekomunikasi","finish"=>21,"ongoing"=>19,"belum"=>20,"jumlah"=>60,"persentase"=>35],
        ["prodi"=>"DIV - Teknik Kimia","finish"=>25,"ongoing"=>15,"belum"=>20,"jumlah"=>60,"persentase"=>41.67],
        ["prodi"=>"DIV - Teknik Elektronika","finish"=>18,"ongoing"=>22,"belum"=>20,"jumlah"=>60,"persentase"=>30],
        ["prodi"=>"DIV - Manajemen Pemasaran","finish"=>14,"ongoing"=>16,"belum"=>30,"jumlah"=>60,"persentase"=>23.33],
    ],
];

// default tahun 2025 
$selectedYear = $_GET['tahun'] ?? '2025';
$data = $allData[$selectedYear];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Respon Tracer Study <?= $selectedYear ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
        }
        h1, h2, h3 { font-weight: 700; }

        /* Hero persis seperti tentang.php / kontak.php */
        .hero {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #fff;
            padding: 100px 20px 70px;
            text-align: center;
            border-radius: 0 0 40px 40px;
            margin-bottom: 40px;
        }
        .hero h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .hero p {
            font-size: 1.2rem;
            color: #e5e7eb;
            max-width: 700px;
            margin: 0 auto;
        }

        .card-custom {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 30px;
        }
        table {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        }
        th {
            background: #1e40af;
            color: #fff;
            text-align: center;
        }
        td {
            text-align: center;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?= view('layout/navbar') ?>

<!-- Hero Section -->
<section class="hero animate__animated animate__fadeIn">
  <h1 class="animate__animated animate__fadeInDown">Respon Tracer Study <?= $selectedYear ?></h1>
  <p class="animate__animated animate__fadeInUp animate__delay-1s">Update per <?= date("d F Y"); ?></p>
</section>

<div class="container">

    <!-- Dropdown Tahun -->
    <form method="get" class="mb-4 animate__animated animate__fadeInDown animate__delay-1s">
        <label for="tahun" class="form-label fw-semibold">Pilih Tahun:</label>
        <select id="tahun" name="tahun" class="form-select shadow-sm" onchange="this.form.submit()">
            <?php foreach(array_keys($allData) as $tahun): ?>
                <option value="<?= $tahun ?>" <?= ($tahun==$selectedYear)?'selected':''; ?>>
                    <?= $tahun ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- Chart -->
    <div class="card-custom animate__animated animate__fadeInUp animate__delay-2s">
        <h3 class="mb-3">Grafik Respon</h3>
        <canvas id="myChart" style="max-height: 400px;"></canvas>
    </div>

    <script>
    const data = <?php echo json_encode($data); ?>;

    const labels = data.map(d => d.prodi);
    const finish = data.map(d => d.finish);
    const ongoing = data.map(d => d.ongoing);
    const belum = data.map(d => d.belum);

    new Chart(document.getElementById('myChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Finish', data: finish, backgroundColor: '#16a34a' },
                { label: 'Ongoing', data: ongoing, backgroundColor: '#facc15' },
                { label: 'Belum', data: belum, backgroundColor: '#dc2626' }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                x: { stacked: true, ticks: { maxRotation: 45, minRotation: 45 }},
                y: { stacked: true, beginAtZero: true }
            }
        }
    });
    </script>

    <!-- Tabel -->
    <div class="card-custom animate__animated animate__fadeInUp animate__delay-3s">
        <h3 class="mb-3">Detail Progress Per Prodi</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>PRODI</th>
                        <th>FINISH</th>
                        <th>ONGOING</th>
                        <th>BELUM</th>
                        <th>JUMLAH</th>
                        <th>PERSENTASE</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($data as $d): ?>
                    <tr>
                        <td><?= $d['prodi']; ?></td>
                        <td><?= $d['finish']; ?></td>
                        <td><?= $d['ongoing']; ?></td>
                        <td><?= $d['belum']; ?></td>
                        <td><?= $d['jumlah']; ?></td>
                        <td><?= $d['persentase']; ?>%</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<?= view('layout/footer') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
