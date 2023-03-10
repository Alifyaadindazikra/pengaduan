<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function show(Response $response)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function edit($report_id)
    { 
        //ambil data response yang bakal dimunculin, data yang diambil data response yang report_id nya sama kaya 
        //$report)id dari patch dinamis(report_id)
        //kalau ada, datanya diambil satu/first()
        //kalau ga pake firstOrFail() karena nanti bakal munculin not found view, kalau pake first() view nya ttp bakal ditmpilin
        $report = Response :: where('report_id', $report_id)->first();
        // karena mau kirim data (report_id)buat di route updatenya, jadi biar bisa dipakedi blade kita simpen data patch dinamis 4report_id nya ke variabel baru yg bakal di compact dan dipggil di blade nya
        $reportId = $report_id;
        return view('response', compact('report', 'reportId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Response $response, $report_id)
    {
        $request->validate([
            'status' => 'required',
            'pesan' => 'required',
        ]);
        //apdateorcreate -melakukan update data kalau emang di db responsenya uda ada dta yang ounya report_id sama dengan $report_id dri patch dinamis, kalau gda data itu maka di create 
        //array pertama, acuan cari dtanya
        //array kedua, data yang dikirim 
        //kenapa pake updateorcreate? karena response ini kan klo tdnya gda mau di tambahin tpi kalao ada mau di update aja
        Response::updateOrCreate(
            [
                'report_id'=> $report_id,//yang di cari
            ],
            [
                'status' => $request->status,
                'pesan' => $request->pesan,
            ]

        );
        //setelah berhasil, arahkan ke route yang namenya data.petugas dengan pesan alert
        return redirect()->route('data.petugas')->with('responseSuccess', 'Berhasil mengubah respon');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function destroy(Response $response)
    {
        //
    }
}
