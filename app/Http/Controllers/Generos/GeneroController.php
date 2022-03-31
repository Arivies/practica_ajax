<?php

namespace App\Http\Controllers\Generos;

use App\Http\Controllers\Controller;
use App\Models\Generos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['generos']=Generos::orderBy('id')->paginate(5);

        return view('index',$data);
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
        $validador=Validator::make($request->all(),[
            'genero'=>'unique:generos'
        ]);

        $mensajes=array(
            'estatus'=>'success',
            'mensaje'=>'Genero agregado correctamente'
        );

        if($validador->fails()){
            $mensajes=array(
                'estatus'=>'error',
                'mensaje'=>'Genero ya se encuentra registrado!'
            );
        }
        Generos::create($request->all());

        return response()->json($mensajes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Generos  $generos
     * @return \Illuminate\Http\Response
     */
    public function show(Generos $generos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Generos  $generos
     * @return \Illuminate\Http\Response
     */
    public function edit(Generos $generos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Generos  $generos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Generos $generos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Generos  $generos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Generos $generos)
    {
        //
    }
}
