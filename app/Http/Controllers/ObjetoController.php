<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormStoreRequest;
use App\Models\Objeto;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ObjetoController extends Controller
{
    public function indexDashboard()
    {
        $objetos = Objeto::orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('objetos'));
    }

    public function indexHome(Request $request)
    {
        $query = Objeto::query();

        if ($search = $request->input('search')) {
            $query->where('descricao', 'like', '%' . $search . '%');
        }

        $objetos = $query->orderBy('created_at', 'desc')->get();

        return view('home', compact('objetos'));
    }

    public function search(Request $request)
    {
        $query = Objeto::query();

        if ($search = $request->input('search')) {
            $query->where('descricao', 'like', '%' . $search . '%');
        }

        $objetos = $query->orderBy('created_at', 'desc')->get();

        if ($objetos->isEmpty()) {
            return view('admin.dashboard', compact('objetos'))
                ->with('message', 'Nenhum objeto encontrado para "' . $search . '"');
        }

        return view('admin.dashboard', compact('objetos'))
            ->with('message', 'Resultado para a busca por "' . $search . '"');
    }

    
    public function create(){
        return view('admin.form');
    }

    public function store(FormStoreRequest $request)
    {
        $request->validated();
        $data = $this->armazenaImagem($request);
        $data["matricula"] = Auth::user()->matricula;
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
        $data["matricula"] = Auth::user()->matricula;
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
