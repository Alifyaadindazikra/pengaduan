<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
//ambil data db
use Maatwebsite\Excel\Concerns\WithHeadings;
//ngtur nama column header di excel
use Maatwebsite\Excel\Concerns\WithMapping; 
//ngatur data yg diunculkan tiap column di excel

class reportsExport implements FromCollection, WithHeadings, WithMapping 
{
    /**
    * @return \Illuminate\Support\Collection
    */
    //ngambil data dri db
    public function collection()
    {
        return Report::with('response')->orderby('created_at','DESC')->get();
        //disni boleh menyertakan perintah eloquent lain (where, all)
    }
     //mengatur nama-nama column header:diambil dri withheading
     public function headings():array 
    {
        return [
            'ID',
            'Nik Pelapor',
            'Nama Pelapor',
            'No Telp Pelapor',
            'Tanggal Pelaporan',
            'Pengaduan',
            'Status Response',
            'Pesan Response',
        ];
    }
    //mengatur dta yg ditampilkan per column d excel
    public function map($item): array
    {
        return [
            $item->id,
            $item->nik,
            $item->nama,
            $item->no_telp,
            \Carbon\Carbon::parse($item->cretaed_at)->format('j F, Y'),
            $item->pengaduan,
            //: ternary(?=if :=else)
            $item->response ? $item->response['status'] : '.',
            $item->response ? $item->response['pesan'] : '.',
        ];

    }
     
   

}
