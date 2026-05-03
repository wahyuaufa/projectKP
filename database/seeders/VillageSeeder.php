<?php
// database/seeders/VillageSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VillageSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ── KALIDERES (31.74.07) ───────────────────────────
            ['id'=>'31.74.07.1001','district_id'=>'31.74.07','name'=>'KALIDERES'],
            ['id'=>'31.74.07.1002','district_id'=>'31.74.07','name'=>'SEMANAN'],
            ['id'=>'31.74.07.1003','district_id'=>'31.74.07','name'=>'TEGAL ALUR'],
            ['id'=>'31.74.07.1004','district_id'=>'31.74.07','name'=>'PEGADUNGAN'],
            ['id'=>'31.74.07.1005','district_id'=>'31.74.07','name'=>'CENGKARENG BARAT'],
            // ── KEBON JERUK (31.74.04) ────────────────────────
            ['id'=>'31.74.04.1001','district_id'=>'31.74.04','name'=>'KEBON JERUK'],
            ['id'=>'31.74.04.1002','district_id'=>'31.74.04','name'=>'KEDOYA UTARA'],
            ['id'=>'31.74.04.1003','district_id'=>'31.74.04','name'=>'KEDOYA SELATAN'],
            ['id'=>'31.74.04.1004','district_id'=>'31.74.04','name'=>'DURI KEPA'],
            ['id'=>'31.74.04.1005','district_id'=>'31.74.04','name'=>'KELAPA DUA'],
            ['id'=>'31.74.04.1006','district_id'=>'31.74.04','name'=>'SUKABUMI UTARA'],
            ['id'=>'31.74.04.1007','district_id'=>'31.74.04','name'=>'SUKABUMI SELATAN'],
            // ── CENGKARENG (31.74.06) ─────────────────────────
            ['id'=>'31.74.06.1001','district_id'=>'31.74.06','name'=>'CENGKARENG TIMUR'],
            ['id'=>'31.74.06.1002','district_id'=>'31.74.06','name'=>'CENGKARENG BARAT'],
            ['id'=>'31.74.06.1003','district_id'=>'31.74.06','name'=>'DURI KOSAMBI'],
            ['id'=>'31.74.06.1004','district_id'=>'31.74.06','name'=>'KAPUK'],
            ['id'=>'31.74.06.1005','district_id'=>'31.74.06','name'=>'RAWA BUAYA'],
            ['id'=>'31.74.06.1006','district_id'=>'31.74.06','name'=>'KEDAUNG KALI ANGKE'],
            // ── KEMBANGAN (31.74.05) ──────────────────────────
            ['id'=>'31.74.05.1001','district_id'=>'31.74.05','name'=>'KEMBANGAN UTARA'],
            ['id'=>'31.74.05.1002','district_id'=>'31.74.05','name'=>'KEMBANGAN SELATAN'],
            ['id'=>'31.74.05.1003','district_id'=>'31.74.05','name'=>'MERUYA UTARA'],
            ['id'=>'31.74.05.1004','district_id'=>'31.74.05','name'=>'MERUYA SELATAN'],
            ['id'=>'31.74.05.1005','district_id'=>'31.74.05','name'=>'JOGLO'],
            ['id'=>'31.74.05.1006','district_id'=>'31.74.05','name'=>'SRENGSENG'],
            // ── TAMBORA (31.74.01) ────────────────────────────
            ['id'=>'31.74.01.1001','district_id'=>'31.74.01','name'=>'TAMBORA'],
            ['id'=>'31.74.01.1002','district_id'=>'31.74.01','name'=>'KALI ANYAR'],
            ['id'=>'31.74.01.1003','district_id'=>'31.74.01','name'=>'DURI UTARA'],
            ['id'=>'31.74.01.1004','district_id'=>'31.74.01','name'=>'TANAH SEREAL'],
            ['id'=>'31.74.01.1005','district_id'=>'31.74.01','name'=>'KERENDANG'],
            ['id'=>'31.74.01.1006','district_id'=>'31.74.01','name'=>'ANGKE'],
            ['id'=>'31.74.01.1007','district_id'=>'31.74.01','name'=>'DURI SELATAN'],
            ['id'=>'31.74.01.1008','district_id'=>'31.74.01','name'=>'JEMBATAN BESI'],
            ['id'=>'31.74.01.1009','district_id'=>'31.74.01','name'=>'JEMBATAN LIMA'],
            ['id'=>'31.74.01.1010','district_id'=>'31.74.01','name'=>'PEKOJAN'],
            ['id'=>'31.74.01.1011','district_id'=>'31.74.01','name'=>'ROA MALAKA'],
            // ── GROGOL PETAMBURAN (31.74.02) ──────────────────
            ['id'=>'31.74.02.1001','district_id'=>'31.74.02','name'=>'GROGOL'],
            ['id'=>'31.74.02.1002','district_id'=>'31.74.02','name'=>'TANJUNG DUREN UTARA'],
            ['id'=>'31.74.02.1003','district_id'=>'31.74.02','name'=>'TANJUNG DUREN SELATAN'],
            ['id'=>'31.74.02.1004','district_id'=>'31.74.02','name'=>'TOMANG'],
            ['id'=>'31.74.02.1005','district_id'=>'31.74.02','name'=>'JELAMBAR'],
            ['id'=>'31.74.02.1006','district_id'=>'31.74.02','name'=>'JELAMBAR BARU'],
            ['id'=>'31.74.02.1007','district_id'=>'31.74.02','name'=>'WIJAYA KUSUMA'],
            // ── PALMERAH (31.74.03) ───────────────────────────
            ['id'=>'31.74.03.1001','district_id'=>'31.74.03','name'=>'PALMERAH'],
            ['id'=>'31.74.03.1002','district_id'=>'31.74.03','name'=>'KOTA BAMBU UTARA'],
            ['id'=>'31.74.03.1003','district_id'=>'31.74.03','name'=>'KOTA BAMBU SELATAN'],
            ['id'=>'31.74.03.1004','district_id'=>'31.74.03','name'=>'JATI PULO'],
            ['id'=>'31.74.03.1005','district_id'=>'31.74.03','name'=>'KEMANGGISAN'],
            ['id'=>'31.74.03.1006','district_id'=>'31.74.03','name'=>'SLIPI'],
            // ── TAMAN SARI (31.74.08) ─────────────────────────
            ['id'=>'31.74.08.1001','district_id'=>'31.74.08','name'=>'TAMAN SARI'],
            ['id'=>'31.74.08.1002','district_id'=>'31.74.08','name'=>'MANGGA BESAR'],
            ['id'=>'31.74.08.1003','district_id'=>'31.74.08','name'=>'KEAGUNGAN'],
            ['id'=>'31.74.08.1004','district_id'=>'31.74.08','name'=>'MAPHAR'],
            ['id'=>'31.74.08.1005','district_id'=>'31.74.08','name'=>'TANGKI'],
            ['id'=>'31.74.08.1006','district_id'=>'31.74.08','name'=>'GLODOK'],
            ['id'=>'31.74.08.1007','district_id'=>'31.74.08','name'=>'PINANGSIA'],
            ['id'=>'31.74.08.1008','district_id'=>'31.74.08','name'=>'KRUKUT'],
            // ── PENJARINGAN - JAKARTA UTARA (31.75.01) ────────
            ['id'=>'31.75.01.1001','district_id'=>'31.75.01','name'=>'PENJARINGAN'],
            ['id'=>'31.75.01.1002','district_id'=>'31.75.01','name'=>'PEJAGALAN'],
            ['id'=>'31.75.01.1003','district_id'=>'31.75.01','name'=>'KAMAL MUARA'],
            ['id'=>'31.75.01.1004','district_id'=>'31.75.01','name'=>'PLUIT'],
            ['id'=>'31.75.01.1005','district_id'=>'31.75.01','name'=>'KAPUK MUARA'],
            // ── TANJUNG PRIOK (31.75.03) ──────────────────────
            ['id'=>'31.75.03.1001','district_id'=>'31.75.03','name'=>'TANJUNG PRIOK'],
            ['id'=>'31.75.03.1002','district_id'=>'31.75.03','name'=>'SUNTER AGUNG'],
            ['id'=>'31.75.03.1003','district_id'=>'31.75.03','name'=>'SUNTER JAYA'],
            ['id'=>'31.75.03.1004','district_id'=>'31.75.03','name'=>'PAPANGGO'],
            ['id'=>'31.75.03.1005','district_id'=>'31.75.03','name'=>'KEBON BAWANG'],
            ['id'=>'31.75.03.1006','district_id'=>'31.75.03','name'=>'SUNGAI BAMBU'],
            // ── KELAPA GADING (31.75.05) ──────────────────────
            ['id'=>'31.75.05.1001','district_id'=>'31.75.05','name'=>'KELAPA GADING BARAT'],
            ['id'=>'31.75.05.1002','district_id'=>'31.75.05','name'=>'KELAPA GADING TIMUR'],
            ['id'=>'31.75.05.1003','district_id'=>'31.75.05','name'=>'PEGANGSAAN DUA'],
            // ── SERPONG - TANGSEL (36.74.01) ──────────────────
            ['id'=>'36.74.01.1001','district_id'=>'36.74.01','name'=>'SERPONG'],
            ['id'=>'36.74.01.1002','district_id'=>'36.74.01','name'=>'BUARAN'],
            ['id'=>'36.74.01.1003','district_id'=>'36.74.01','name'=>'CIATER'],
            ['id'=>'36.74.01.1004','district_id'=>'36.74.01','name'=>'RAWABUNTU'],
            ['id'=>'36.74.01.1005','district_id'=>'36.74.01','name'=>'RAWA MEKAR JAYA'],
            ['id'=>'36.74.01.1006','district_id'=>'36.74.01','name'=>'CILENGGANG'],
            ['id'=>'36.74.01.1007','district_id'=>'36.74.01','name'=>'LENGKONG GUDANG'],
            ['id'=>'36.74.01.1008','district_id'=>'36.74.01','name'=>'LENGKONG GUDANG TIMUR'],
            ['id'=>'36.74.01.1009','district_id'=>'36.74.01','name'=>'LENGKONG WETAN'],
            // ── PAMULANG - TANGSEL (36.74.05) ─────────────────
            ['id'=>'36.74.05.1001','district_id'=>'36.74.05','name'=>'PAMULANG BARAT'],
            ['id'=>'36.74.05.1002','district_id'=>'36.74.05','name'=>'PAMULANG TIMUR'],
            ['id'=>'36.74.05.1003','district_id'=>'36.74.05','name'=>'BENDA BARU'],
            ['id'=>'36.74.05.1004','district_id'=>'36.74.05','name'=>'PONDOK BENDA'],
            ['id'=>'36.74.05.1005','district_id'=>'36.74.05','name'=>'KEDAUNG'],
            ['id'=>'36.74.05.1006','district_id'=>'36.74.05','name'=>'BAMBU APUS'],
            ['id'=>'36.74.05.1007','district_id'=>'36.74.05','name'=>'PONDOK CABE ILIR'],
            ['id'=>'36.74.05.1008','district_id'=>'36.74.05','name'=>'PONDOK CABE UDIK'],
            // ── CIPUTAT - TANGSEL (36.74.03) ──────────────────
            ['id'=>'36.74.03.1001','district_id'=>'36.74.03','name'=>'CIPUTAT'],
            ['id'=>'36.74.03.1002','district_id'=>'36.74.03','name'=>'CIPAYUNG'],
            ['id'=>'36.74.03.1003','district_id'=>'36.74.03','name'=>'SARUA'],
            ['id'=>'36.74.03.1004','district_id'=>'36.74.03','name'=>'JOMBANG'],
            ['id'=>'36.74.03.1005','district_id'=>'36.74.03','name'=>'SAWAH'],
            ['id'=>'36.74.03.1006','district_id'=>'36.74.03','name'=>'SERUA'],
            ['id'=>'36.74.03.1007','district_id'=>'36.74.03','name'=>'SARUA MAKMUR'],
        ];

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('villages')->insertOrIgnore($chunk);
        }
    }
}
