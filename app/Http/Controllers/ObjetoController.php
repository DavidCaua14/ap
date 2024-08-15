<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormStoreRequest;
use App\Models\Objeto;
use Illuminate\Http\Request;

class ObjetoController extends Controller
{
    public function indexDashboard()
    {
        $objetos = Objeto::orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('objetos'));
    }

    public function indexHome()
    {
        $objetos = Objeto::orderBy('created_at', 'desc')->get();
        return view('home', compact('objetos'));
    }
    
    
    
    public function create(){
        return view('admin.form');
    }

    public function store(FormStoreRequest $request)
    {
        $request->validated();
        $data = $this->armazenaImagem($request);
        Objeto::create($data);
        return redirect()->route('dashboard')->with('success', 'Objeto publicado com sucesso!');
    }
    

    private function armazenaImagem(Request $request)
    {
        $data = $request->all();
        if ($request->file('imagem') != null) {
            $path = $request->file('imagem')->store("objetos", "public");
            $data["imagem"] = $path;
        }
        return $data;
    }
    public function edit($id)
    {
        $objeto = Objeto::findOrFail($id);
        return view('admin.form', compact('objeto'));
    }

    public function update(FormStoreRequest $request, $id)
    {
        $objeto = Objeto::findOrFail($id);
        $request->validated(); 
        $data = $this->armazenaImagem($request);
        $objeto->update($data);
        
        return redirect()->route('dashboard')->with('success', 'Objeto atualizado com sucesso!');
    } 

    public function entregar(Request $request, $id)
    {
        $objeto = Objeto::findOrFail($id);
        $objeto->status = 1;
        $objeto->save();
    }    

    public function destroy($id)
    {
        $objeto = Objeto::findOrFail($id);
        $objeto->delete();
        return redirect()->route('dashboard')->with('success', 'Objeto excluido com sucesso.');
    }
}
