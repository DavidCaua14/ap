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

    // Exibe o formulário de login
    public function mostrarFormularioDeLogin()
    {
        return view('auth.login');
    }

    // Realiza a autenticação do usuário
    public function autenticar(Request $request)
    {
        try {
            // Valida os dados do request
            $this->validarDados($request);

            // Obtém o token do SUAP
            $token = $this->obterTokenSuap($request->email, $request->password);

            // Busca os dados do usuário no SUAP
            $dadosUsuario = $this->buscarDadosDoUsuarioNoSuap($token);

            // Encontra ou cria o usuário no banco de dados
            $usuario = $this->encontrarOuCriarUsuario($dadosUsuario);

            // Realiza o login do usuário
            $this->logarUsuario($request, $usuario);

            // Redireciona para a página inicial
            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    // Valida os dados do request
    private function validarDados(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    // Autentica no SUAP e obtém o token
    private function obterTokenSuap($email, $password)
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

    // Busca os dados do usuário no SUAP
    private function buscarDadosDoUsuarioNoSuap($token)
    {
        $response = Http::withToken($token)->get(self::API_SUAP . '/minhas-informacoes/meus-dados/');

        if ($response->failed()) {
            throw new Exception('Ocorreu um erro ao resgatar dados do SUAP');
        }

        return $response->json();
    }

    // Encontra ou cria um usuário no banco de dados
    private function encontrarOuCriarUsuario($dadosUsuario)
    {
        // Tenta encontrar um usuário existente
        $usuario = User::firstOrCreate(
            ['matricula' => $dadosUsuario['matricula']],
            [
                'nome_completo' => $dadosUsuario['nome'] ?? $dadosUsuario['vinculo']['nome'],
                'nome_curso' => $dadosUsuario['vinculo']['curso'],
            ]
        );

        // Retorna o usuário, seja ele existente ou recém-criado
        return $usuario;
    }

    // Realiza o login do usuário
    private function logarUsuario(Request $request, $usuario)
    {
        // Faz login do usuário
        Auth::login($usuario);

        // Regenera a sessão para garantir que a sessão do usuário seja atualizada
        $request->session()->regenerate();
    }

    // Realiza o logout do usuário
    public function deslogar(Request $request)
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
