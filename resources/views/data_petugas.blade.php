<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Masyarakat</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>
    <h2 class="title-table">Laporan Keluhan Petugas</h2>
<div style="display: flex; justify-content: center; margin-bottom: 30px">
    <a href="/logout" style="text-align: center">Logout</a> 
    <div style="margin: 0 10px"> | </div>
    <a href="/" style="text-align: center">Home</a>
</div>
<div style="display: flex; justify-content: flex-end; align-item: center;"> 
    <form action="" method="GET">
        @csrf {{--pake get krna route buat masuk ke hlman ini pake get--}}
        <input type="text" name="search" placeholder="Cari berdasarkan nama...">
        <button type="submit" class="btn-login" style="margin-top: -2px">Cari</button>
    </form>
    <a href="{{route('data')}}" style="margin-left: 10px; margin-top: -10px">Refresh</a>
    

</div>
<div style="padding: 0 30px">
    <table>
        <thead>
        <tr>
            <th width="5%">No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Pengaduan</th>
            <th>Gambar</th>
            <th>Status Response</th>
            <th>Pesan Response</th>
            <th>Aksi</th>
        </tr>
        </thead>

        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($reports as $report)
             <tr>
                <td>{{$no++}}</td>
                <td>{{$report['nik']}}</td>
                <td>{{$report['nama']}}</td>
                <td>{{$report['no_telp']}}</td>
                <td>{{$report['pengaduan']}}</td>
                <td>
                    <img src="{{asset('assets/image/'.$report->foto)}}" width="120">
                </td>
                <td>
                    {{--cek apakah data report ini sudah memiliki relasi dngan dta with(response)--}}
                @if ($report->response)
                    {{ $report->response['status']}}
                        {{--kalau ada hasil relasinya, tampilkan dibagian pesan--}}
                @else
                     {{--kalau gda tampilkan tanda ini --}}   
                     -
                @endif
                    
                </td>
                <td>
                    {{--cek apakah data report ini sudah memiliki relasi dngan dta with(response)--}}
                    @if ($report->response)
                    {{$report->response['pesan']}}
                        {{--kalau ada hasil relasinya, tampilkan dibagian pesan--}}
                    @else
                     {{--kalau gda tampilkan tanda ini --}}   
                     -
                    @endif
                    
                </td>
                <td style="display: flex; justify-content: center;">
                    <a href="{{route('response.edit' , $report->id)}}" class="back-btn">Send Response</a>
                </td>
             </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>