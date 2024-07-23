<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Exception;

class LoginController extends Controller
{
    const API_SUAP = 'https://suap.ifrn.edu.br/api/v2';

    // Função para mostrar o formulário de login
    public function mostrarFormularioDeLogin()
    {
        return view('auth.login');
    }

    // Função para autenticar o usuário
    public function autenticar(Request $request)
    {
        try {
            // Validando o Request
            $this->validarRequest($request);

            // Autenticando com SUAP
            $token = $this->autenticarComSuap($request->email, $request->password);

            // Resgatando Dados do SUAP
            $dadosUsuario = $this->buscarDadosDoUsuario($token);

            // Verifico se o usuário já existe ou cria um novo
            $usuario = $this->encontrarOuCriarUsuario($dadosUsuario);

            // Iniciando sessão
            $this->logarUsuario($request, $usuario);

            // Redirecionando para a página inicial
            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    // Função para validar os dados do Request
    private function validarRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    // Função para autenticar no SUAP e obter o token
    private function autenticarComSuap($email, $password)
    {
        $response = Http::post(self::API_SUAP . '/autenticacao/token/', [
            'username' => $email,
            'password' => $password,
        ]);

        if ($response->failed()) {
            throw new Exception('Credenciais inválidas');
        }

        return $response['access'];
    }

    // Função para buscar os dados do usuário no SUAP
    private function buscarDadosDoUsuario($token)
    {
        $response = Http::withToken($token)->get(self::API_SUAP . '/minhas-informacoes/meus-dados/');

        if ($response->failed()) {
            throw new Exception('Ocorreu um erro ao resgatar dados do SUAP');
        }

        return $response->json();
    }

    // Função para encontrar ou criar um usuário no banco de dados
    private function encontrarOuCriarUsuario($dadosUsuario)
    {
        // Tenta encontrar um usuário existente
        $usuario = User::where('matricula', $dadosUsuario['matricula'])->first();

        // Se não encontrar, cria um novo usuário
        if (!$usuario) {
            $usuario = User::create([
                'matricula' => $dadosUsuario['matricula'],
                'nome_completo' => $dadosUsuario['nome'] ?? $dadosUsuario['vinculo']['nome'],
                'tipo_vinculo' => $dadosUsuario['tipo_vinculo'],
                'nome_curso' => $dadosUsuario['vinculo']['curso'],
            ]);
        }

        // Retorna o usuário, seja ele existente ou recém-criado
        return $usuario;
    }

    // Função para logar o usuário
    private function logarUsuario(Request $request, $usuario)
    {
        // Faz login do usuário
        Auth::login($usuario);

        // Regenera a sessão para garantir que a sessão do usuário seja atualizada
        $request->session()->regenerate();
    }

    // Função para logout
    public function destruir(Request $request)
    {
        // Faz logout do usuário
        Auth::logout();

        // Remove todas as informações da sessão
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redireciona para a página de login após o logout
        return redirect()->route('login');
    }
}