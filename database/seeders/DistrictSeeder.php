<?php
// database/seeders/DistrictSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ── JAKARTA BARAT (31.74) ──────────────────────────
            ['id'=>'31.74.01','regency_id'=>'31.74','name'=>'TAMBORA'],
            ['id'=>'31.74.02','regency_id'=>'31.74','name'=>'GROGOL PETAMBURAN'],
            ['id'=>'31.74.03','regency_id'=>'31.74','name'=>'PALMERAH'],
            ['id'=>'31.74.04','regency_id'=>'31.74','name'=>'KEBON JERUK'],
            ['id'=>'31.74.05','regency_id'=>'31.74','name'=>'KEMBANGAN'],
            ['id'=>'31.74.06','regency_id'=>'31.74','name'=>'CENGKARENG'],
            ['id'=>'31.74.07','regency_id'=>'31.74','name'=>'KALIDERES'],
            ['id'=>'31.74.08','regency_id'=>'31.74','name'=>'TAMAN SARI'],
            // ── JAKARTA SELATAN (31.71) ────────────────────────
            ['id'=>'31.71.01','regency_id'=>'31.71','name'=>'TEBET'],
            ['id'=>'31.71.02','regency_id'=>'31.71','name'=>'SETIABUDI'],
            ['id'=>'31.71.03','regency_id'=>'31.71','name'=>'MAMPANG PRAPATAN'],
            ['id'=>'31.71.04','regency_id'=>'31.71','name'=>'PASAR MINGGU'],
            ['id'=>'31.71.05','regency_id'=>'31.71','name'=>'KEBAYORAN LAMA'],
            ['id'=>'31.71.06','regency_id'=>'31.71','name'=>'CILANDAK'],
            ['id'=>'31.71.07','regency_id'=>'31.71','name'=>'KEBAYORAN BARU'],
            ['id'=>'31.71.08','regency_id'=>'31.71','name'=>'PESANGGRAHAN'],
            ['id'=>'31.71.09','regency_id'=>'31.71','name'=>'JAGAKARSA'],
            ['id'=>'31.71.10','regency_id'=>'31.71','name'=>'PANCORAN'],
            // ── JAKARTA TIMUR (31.72) ──────────────────────────
            ['id'=>'31.72.01','regency_id'=>'31.72','name'=>'MATRAMAN'],
            ['id'=>'31.72.02','regency_id'=>'31.72','name'=>'PULO GADUNG'],
            ['id'=>'31.72.03','regency_id'=>'31.72','name'=>'JATINEGARA'],
            ['id'=>'31.72.04','regency_id'=>'31.72','name'=>'KRAMAT JATI'],
            ['id'=>'31.72.05','regency_id'=>'31.72','name'=>'PASAR REBO'],
            ['id'=>'31.72.06','regency_id'=>'31.72','name'=>'CIRACAS'],
            ['id'=>'31.72.07','regency_id'=>'31.72','name'=>'CIPAYUNG'],
            ['id'=>'31.72.08','regency_id'=>'31.72','name'=>'CAKUNG'],
            ['id'=>'31.72.09','regency_id'=>'31.72','name'=>'DUREN SAWIT'],
            ['id'=>'31.72.10','regency_id'=>'31.72','name'=>'MAKASAR'],
            // ── JAKARTA PUSAT (31.73) ──────────────────────────
            ['id'=>'31.73.01','regency_id'=>'31.73','name'=>'TANAH ABANG'],
            ['id'=>'31.73.02','regency_id'=>'31.73','name'=>'MENTENG'],
            ['id'=>'31.73.03','regency_id'=>'31.73','name'=>'SENEN'],
            ['id'=>'31.73.04','regency_id'=>'31.73','name'=>'CEMPAKA PUTIH'],
            ['id'=>'31.73.05','regency_id'=>'31.73','name'=>'JOHAR BARU'],
            ['id'=>'31.73.06','regency_id'=>'31.73','name'=>'KEMAYORAN'],
            ['id'=>'31.73.07','regency_id'=>'31.73','name'=>'SAWAH BESAR'],
            ['id'=>'31.73.08','regency_id'=>'31.73','name'=>'GAMBIR'],
            // ── JAKARTA UTARA (31.75) ──────────────────────────
            ['id'=>'31.75.01','regency_id'=>'31.75','name'=>'PENJARINGAN'],
            ['id'=>'31.75.02','regency_id'=>'31.75','name'=>'PADEMANGAN'],
            ['id'=>'31.75.03','regency_id'=>'31.75','name'=>'TANJUNG PRIOK'],
            ['id'=>'31.75.04','regency_id'=>'31.75','name'=>'KOJA'],
            ['id'=>'31.75.05','regency_id'=>'31.75','name'=>'KELAPA GADING'],
            ['id'=>'31.75.06','regency_id'=>'31.75','name'=>'CILINCING'],
            // ── TANGERANG KOTA (36.71) ─────────────────────────
            ['id'=>'36.71.01','regency_id'=>'36.71','name'=>'TANGERANG'],
            ['id'=>'36.71.02','regency_id'=>'36.71','name'=>'JATIUWUNG'],
            ['id'=>'36.71.03','regency_id'=>'36.71','name'=>'BATUCEPER'],
            ['id'=>'36.71.04','regency_id'=>'36.71','name'=>'BENDA'],
            ['id'=>'36.71.05','regency_id'=>'36.71','name'=>'CIPONDOH'],
            ['id'=>'36.71.06','regency_id'=>'36.71','name'=>'CILEDUG'],
            ['id'=>'36.71.07','regency_id'=>'36.71','name'=>'KARAWACI'],
            ['id'=>'36.71.08','regency_id'=>'36.71','name'=>'PERIUK'],
            ['id'=>'36.71.09','regency_id'=>'36.71','name'=>'CIBODAS'],
            ['id'=>'36.71.10','regency_id'=>'36.71','name'=>'NEGLASARI'],
            ['id'=>'36.71.11','regency_id'=>'36.71','name'=>'PINANG'],
            ['id'=>'36.71.12','regency_id'=>'36.71','name'=>'LARANGAN'],
            ['id'=>'36.71.13','regency_id'=>'36.71','name'=>'KARANG TENGAH'],
            // ── TANGERANG SELATAN (36.74) ──────────────────────
            ['id'=>'36.74.01','regency_id'=>'36.74','name'=>'SERPONG'],
            ['id'=>'36.74.02','regency_id'=>'36.74','name'=>'SERPONG UTARA'],
            ['id'=>'36.74.03','regency_id'=>'36.74','name'=>'CIPUTAT'],
            ['id'=>'36.74.04','regency_id'=>'36.74','name'=>'CIPUTAT TIMUR'],
            ['id'=>'36.74.05','regency_id'=>'36.74','name'=>'PAMULANG'],
            ['id'=>'36.74.06','regency_id'=>'36.74','name'=>'PONDOK AREN'],
            ['id'=>'36.74.07','regency_id'=>'36.74','name'=>'SETU'],
            // ── BEKASI KOTA (32.75) ────────────────────────────
            ['id'=>'32.75.01','regency_id'=>'32.75','name'=>'BEKASI TIMUR'],
            ['id'=>'32.75.02','regency_id'=>'32.75','name'=>'BEKASI SELATAN'],
            ['id'=>'32.75.03','regency_id'=>'32.75','name'=>'BEKASI BARAT'],
            ['id'=>'32.75.04','regency_id'=>'32.75','name'=>'BEKASI UTARA'],
            ['id'=>'32.75.05','regency_id'=>'32.75','name'=>'RAWALUMBU'],
            ['id'=>'32.75.06','regency_id'=>'32.75','name'=>'BANTARGEBANG'],
            ['id'=>'32.75.07','regency_id'=>'32.75','name'=>'PONDOKGEDE'],
            ['id'=>'32.75.08','regency_id'=>'32.75','name'=>'JATIASIH'],
            ['id'=>'32.75.09','regency_id'=>'32.75','name'=>'MUSTIKAJAYA'],
            ['id'=>'32.75.10','regency_id'=>'32.75','name'=>'MEDANSATRIA'],
            ['id'=>'32.75.11','regency_id'=>'32.75','name'=>'JATISAMPURNA'],
            ['id'=>'32.75.12','regency_id'=>'32.75','name'=>'PONDOK MELATI'],
            // ── DEPOK (32.76) ──────────────────────────────────
            ['id'=>'32.76.01','regency_id'=>'32.76','name'=>'BEJI'],
            ['id'=>'32.76.02','regency_id'=>'32.76','name'=>'PANCORAN MAS'],
            ['id'=>'32.76.03','regency_id'=>'32.76','name'=>'SUKMAJAYA'],
            ['id'=>'32.76.04','regency_id'=>'32.76','name'=>'CIMANGGIS'],
            ['id'=>'32.76.05','regency_id'=>'32.76','name'=>'SAWANGAN'],
            ['id'=>'32.76.06','regency_id'=>'32.76','name'=>'LIMO'],
            ['id'=>'32.76.07','regency_id'=>'32.76','name'=>'CIPAYUNG'],
            ['id'=>'32.76.08','regency_id'=>'32.76','name'=>'CINERE'],
            ['id'=>'32.76.09','regency_id'=>'32.76','name'=>'CILODONG'],
            ['id'=>'32.76.10','regency_id'=>'32.76','name'=>'TAPOS'],
            ['id'=>'32.76.11','regency_id'=>'32.76','name'=>'BOJONGSARI'],
            // ── SURABAYA (35.78) ───────────────────────────────
            ['id'=>'35.78.01','regency_id'=>'35.78','name'=>'KARANG PILANG'],
            ['id'=>'35.78.02','regency_id'=>'35.78','name'=>'WONOCOLO'],
            ['id'=>'35.78.03','regency_id'=>'35.78','name'=>'RUNGKUT'],
            ['id'=>'35.78.04','regency_id'=>'35.78','name'=>'WONOKROMO'],
            ['id'=>'35.78.05','regency_id'=>'35.78','name'=>'TEGALSARI'],
            ['id'=>'35.78.06','regency_id'=>'35.78','name'=>'SAWAHAN'],
            ['id'=>'35.78.07','regency_id'=>'35.78','name'=>'GENTENG'],
            ['id'=>'35.78.08','regency_id'=>'35.78','name'=>'GUBENG'],
            ['id'=>'35.78.09','regency_id'=>'35.78','name'=>'GUNUNG ANYAR'],
            ['id'=>'35.78.10','regency_id'=>'35.78','name'=>'SUKOLILO'],
            ['id'=>'35.78.11','regency_id'=>'35.78','name'=>'TAMBAKSARI'],
            ['id'=>'35.78.12','regency_id'=>'35.78','name'=>'SIMOKERTO'],
            ['id'=>'35.78.13','regency_id'=>'35.78','name'=>'PABEAN CANTIAN'],
            ['id'=>'35.78.14','regency_id'=>'35.78','name'=>'BUBUTAN'],
            ['id'=>'35.78.15','regency_id'=>'35.78','name'=>'TANDES'],
            ['id'=>'35.78.16','regency_id'=>'35.78','name'=>'KREMBANGAN'],
            ['id'=>'35.78.17','regency_id'=>'35.78','name'=>'SEMAMPIR'],
            ['id'=>'35.78.18','regency_id'=>'35.78','name'=>'KENJERAN'],
            ['id'=>'35.78.19','regency_id'=>'35.78','name'=>'LAKAR SANTRI'],
            ['id'=>'35.78.20','regency_id'=>'35.78','name'=>'BENOWO'],
            ['id'=>'35.78.21','regency_id'=>'35.78','name'=>'WIYUNG'],
            ['id'=>'35.78.22','regency_id'=>'35.78','name'=>'DUKUH PAKIS'],
            ['id'=>'35.78.23','regency_id'=>'35.78','name'=>'GAYUNGAN'],
            ['id'=>'35.78.24','regency_id'=>'35.78','name'=>'JAMBANGAN'],
            ['id'=>'35.78.25','regency_id'=>'35.78','name'=>'TENGGILIS MEJOYO'],
            ['id'=>'35.78.26','regency_id'=>'35.78','name'=>'MULYOREJO'],
            ['id'=>'35.78.27','regency_id'=>'35.78','name'=>'SUKOMANUNGGAL'],
            ['id'=>'35.78.28','regency_id'=>'35.78','name'=>'ASEMROWO'],
            ['id'=>'35.78.29','regency_id'=>'35.78','name'=>'BULAK'],
            ['id'=>'35.78.30','regency_id'=>'35.78','name'=>'PAKAL'],
            ['id'=>'35.78.31','regency_id'=>'35.78','name'=>'SAMBIKEREP'],
        ];

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('districts')->insertOrIgnore($chunk);
        }
    }
}
