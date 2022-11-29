<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    
    public function __construct(Marca $marca){
        $this->marca = $marca;
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            
        if($request->has('atributos_modelos')){
            $atributos_modelos = $request->atributos_modelos;
            $marcas = $this->marca->with('modelos:id,'.$atributos_modelos); //estou apensas montando a queryBuilder, nao uso o get ainda
        }else{
            $marcas = $this->marca->with('modelos');
        }
        
        //------------------------------------------------
        $marcas = array();
        
        if($request->has('atributos_modelos')){
            $atributos_modelos = $request->atributos_modelos;
            $marcas = $this->marca->with('modelos:id,'.$atributos_modelos); //estou apensas montando a queryBuilder, nao uso o get ainda
        }else{
            $marcas = $this->marca->with('modelos');
        }
        
        if($request->has('filtro')){
            $filtros = explode(';',$request->filtro);//usamos ponto e vírgula mas podia ser outro caractere
        foreach($filtros as $key => $condicao){//pode ter inumeras condicoes de filtro
                $c = explode(':', $condicao);//aqui eu divido o filtro em 3 partes
                $marcas = $marcas->where($c[0],$c[1], $c[2]);
            }
        }
        if($request->has('atributos')){
            
            $atributos = $request->atributos;
            $marcas = $marcas->selectRaw($atributos)->get();//aqui continuamos montando a query
            
        }else{
            $marcas = $marcas->get();
        }
        return response()->json($marcas,200);
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
        $request->validate($this->marca->rules(), $this->marca->feedback());
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');

        
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        return response()->json($marca,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Recurso pesquisado nao existe'],404);
        }
        return response()->json($marca,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);
        
        if($marca === null){
            return response()->json(['erro' => 'Recurso pesquisado nao existe e nao foi possível atualizá-lo'],404);
        }
        
        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            foreach($marca->rules() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());

        }else{
            $request->validate($marca->rules(), $marca->feedback());
        };
        
        //exlui a imagem antiga da pasta public
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens', 'public');
        }

        $marca->fill($request->all());
        $request->file('imagem') ? $marca->imagem = $imagem_urn : '';
        $marca->save();

        /* $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]); */
        return response()->json($marca,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        
        if($marca === null){
            return response()->json(['erro' => 'Recurso pesquisado nao existe e nao foi possível deleta-lo'],404);
        }
        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();
        return response()->json($marca,200);
    }
}
