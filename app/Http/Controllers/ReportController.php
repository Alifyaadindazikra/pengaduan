<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;
use App\Exports\ReportsExport;
use App\Models\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPDF() {
        // ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jangan gunakan pagination
        // konvert ke array dengan toArray()
        // toArray() untuk mengubah objek jd array, krn pdfnya hanya bisa nerima $data (array)
        $data = Report::with('response')->get()->toArray();//pake where karena ada search
        view()->share('reports',$data);
        // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial 
        // samain 'inisial' dgn $ di foreach
        $pdf = PDF::loadView('print', $data); 
        // panggil view blade yg akan dicetak pdf serta data yg akan digunakan
        $pdf = PDF::loadView('print', $data)->setPaper('a4', 'landscape');

        // download PDF file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf');
    }
    public function printPDF($id){
        $data = Report::with('response')->where('id', $id)->get()->toArray();
        view()->share('reports',$data);
        $pdf = PDF::loadView('print', $data)->setPaper('a4', 'landscape'); 
        return $pdf->download('data_pengaduan.pdf');

    }

    public function exportExcel()//samakan web.php
    {
        //nama file yang akan terdownload 
        $file_name = 'data_keseluruhan_pengaduan.xlsx';
        //mmgil file reportexport dan mendownloadnya dengan nama spetri di $file_name
        return Excel::download(new reportsExport, $file_name);
    }

    public function index()
    {
        
        $reports= Report::orderBy('created_at', 'DESC')->simplePaginate(2);
        return view('index', compact ('reports'));
    }
    //dalam () disebut parameter
    public function data(Request $request){
        //ambil data yg diimput ke imputan search
        $search = $request->search;
        //where akan mencari data berdasarkan column nma 
        //data yang diambil mrupkan data yg LIKE (terdapat) teks yang dmskan ke inpt seach
        //contoh ngisi fem
        //bakal nyari ke db cplumn yang ada femnya 
        $reports = Report::with('response')->where('nama', 'LIKE' , '%' . $search . '%')->orderby('created_at', 'DESC')->get();
        return view('data', compact('reports'));
    }

    public function dataPetugas(Request $request){
        $search = $request->search;
        //where akan mencari data berdasarkan column nma 
        //data yang diambil mrupkan data yg LIKE (terdapat) teks yang dmskan ke inpt seach
        //contoh ngisi fem
        //bakal nyari ke db cplumn yang ada femnya 
        $reports = Report::with('response')->where('nama', 'LIKE' , '%' . $search . '%')->orderby('created_at', 'DESC')->get();
        return view('data_petugas', compact('reports'));
    }

    public function auth(Request $request){
        $request->validate([
            'email'=>'required',
            'password' =>'required',
        ]);

        $user = $request->only('email', 'password');
        if(Auth::attempt($user)){
            if (Auth::user()->role== 'admin'){
            return redirect()->route('data');
            }elseif (Auth::user()->role == 'petugas'){
                return redirect()->route('data.petugas');
            }
        
        }else {
            return redirect()->back()->with('gagal', 'Gagal login, coba lagi');
        }

    }
    public function logout()
   {
        Auth::logout();
        return redirect()->route('login');   
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request -> validate([
            'nik' => 'required',
            'nama' =>'required',
            'no_telp' =>'required|max:13',
            'pengaduan' =>'required|min:5',
            'foto'=>'required|image|mimes:jpg,jpeg,png,svg',
        ]);

        $image = $request->file('foto');
        $imgName = rand() . '.' . $image->extension();
        $path =public_path('assets/image/');
        $image->move($path,$imgName);

        Report::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'pengaduan' => $request->pengaduan,
            'foto' => $imgName,
            
             

        ]);
        return redirect()->back()->with('sucessAdd','Berhasil Menambahkan Data Baru!');
//halaman home sama tambah data sama, pakai back

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Report::where('id', $id)->firstOrFail();
        $image = public_path('assets/image/'.$data['foto']);
        unlink($image);//hapus data dari database
        $data->delete();
        Response::where('report_id')->delete();
        return redirect()->back();
    }
}
