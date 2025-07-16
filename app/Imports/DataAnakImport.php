<?php

namespace App\Imports;

use App\Models\DataAnak;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataAnakImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function model(array $row)
    {
        // dd($row);
        // Convert Excel date to Y-m-d if needed
        $tanggalLahir = $this->transformDate($row['tgl_lahir']);
        $tanggalUkur  = $this->transformDate($row['tanggal_pengukuran']);

        // Hitung usia ukur dari tanggal lahir & pengukuran
        $diff = Carbon::parse($tanggalLahir)->diff(Carbon::parse($tanggalUkur));
        $usia_ukur = ($diff->y * 12) + $diff->m + ($diff->d >= 15 ? 1 : 0);
        return new DataAnak([
            'nik'            => $this->getValue($row, 'nik'),
            'nama'           => $this->getValue($row, 'nama'),
            'jk'             => $this->getValue($row, 'jk'),
            'tanggal_lahir'  => $tanggalLahir,
            'nama_ortu'      => $this->getValue($row, 'nama_ortu'),
            'prov'           => $this->getValue($row, 'prov'),
            'kab'            => $this->getValue($row, 'kabkota'),
            'kec'            => $this->getValue($row, 'kec'),
            'desa'           => $this->getValue($row, 'desakel'),
            'puskesmas'      => $this->getValue($row, 'pukesmas'),
            'posyandu'       => $this->getValue($row, 'posyandu'),
            'alamat'         => $this->getValue($row, 'alamat'),
            'usia_ukur'      => $usia_ukur,
            'tgl_pengukuran' => $tanggalUkur,
            'berat'          => $this->getValue($row, 'berat', 'number'),
            'tinggi'         => $this->getValue($row, 'tinggi', 'number'),
            'cara_ukur'      => $this->getValue($row, 'cara_ukur'),
            'lila'           => $this->getValue($row, 'lila', 'number'),
            'bb_u'           => $this->getValue($row, 'bbu'),
            'zs_bb_u'        => $this->getValue($row, 'zs_bbu', 'number'),
            'tb_u'           => $this->getValue($row, 'tbu'),
            'zs_tb_u'        => $this->getValue($row, 'zs_tbu', 'number'),
            'bb_tb'          => $this->getValue($row, 'bbtb'),
            'zs_bb_tb'       => $this->getValue($row, 'zs_bbtb', 'number'),
            'label_gizi'     => $this->klasifikasiGizi($row['bbtb'] ?? '-'),
        ]);
    }

    private function getValue($row, $key, $type = 'string')
    {
        $value = $row[$key] ?? null;

        if (is_null($value) || $value === '') {
            return $type === 'number' ? 0 : '-';
        }

        return $type === 'number' ? floatval($value) : $value;
    }
    private function transformDate($value)
    {
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return Carbon::parse($value)->format('Y-m-d');
        }
    }

    private function klasifikasiGizi($bb_tb)
    {
        // Implementasi sederhana, sesuaikan dengan kebutuhan
        if ($bb_tb == "Gizi Buruk") return 0;
        if ($bb_tb == "Gizi Kurang") return 1;
        if ($bb_tb == "Gizi Baik") return 2;
        return 3; // Risiko Gizi Lebih
    }
}
