<?php

namespace App\Http\Controllers\Bandas;

use App\Http\Controllers\Controller;
use App\Models\Bandas;
use App\Models\Generos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BandaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['bandas'] = Bandas::orderBy('id')->paginate(5);
        return view('Bandas.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['generos']=Generos::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'nombre' => 'required|unique:bandas',
            'genero_id'=>'required',
             'logo'=>'image'
        ]);

        $mensajes = array(
            'estatus' => 'success',
            'mensaje' => 'Banda agregado correctamente'
        );

        if ($validador->fails()) {
            $mensajes = array(
                'estatus' => 'error',
                'mensaje' => 'Banda ya se encuentra registrado!'
            );
            return response()->json($mensajes);
        }
        $path = 'bandas/';
        $file = $request->file('logo');
        $nombre="";
        if(!is_null($file)){
            $nombre = time().'_'.$file->getClientOriginalName();
            $upload = $file->storeAs($path, $nombre, 'public');
        }
        $data=$request->all();
        $data['logo']=$nombre;
        Bandas::create($data);

        return response()->json($mensajes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bandas  $bandas
     * @return \Illuminate\Http\Response
     */
    public function show(Bandas $bandas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bandas  $bandas
     * @return \Illuminate\Http\Response
     */
    public function edit(Bandas $bandas,$id)
    {
        $data['bandas']=$bandas::where('id', $id)->first();
        $data['generos']=Generos::all();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bandas  $bandas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bandas $banda)
    {
        die($banda);
        $validador = Validator::make($request->all(), [
            'nombre' => 'unique:bandas'
         ]);

         $mensajes = array(
             'estatus' => 'success',
             'mensaje' => 'Banda editada correctamente'
         );

         if ($validador->fails()) {
             $mensajes = array(
                 'estatus' => 'error',
                 'mensaje' => 'Banda ya se encuentra registrado!'
             );
        }

         $resp=$request->all();

         if(!empty($request->file('logo'))){
            $path = 'bandas/';
            $file = $request->file('logo');
            $nombre="";
            if(!is_null($file)){
                $nombre = time().'_'.$file->getClientOriginalName();
                $upload = $file->storeAs($path, $nombre, 'public');
            }
            $resp['logo']=$nombre;
            Storage::delete('bandas/'.$request->logo_ant);
        }

        $data = $banda::where('id', $request->id)->first();
        dd($request);
        $resp = $request->except('_token', 'id');
        $data->update($resp);

         /*$data=$generos::where('id',$request->id)->first();
         $data->genero=$request->genero;
         $data->update();*/

         return response()->json($mensajes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bandas  $bandas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bandas $bandas,Request $request)
    {
        $bandas::where('id', $request->id)->delete();

        $mensajes = array(
            'estatus' => 'success',
            'mensaje' => 'Banda eliminada correctamente'
        );
        return response()->json($mensajes);
    }
}
