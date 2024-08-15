<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achados e Perdidos</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="{{ asset('js/index.js') }}" defer></script>
</head>
<body>
    <main>
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="card">
                <h1 id="titulo">Login</h1>
                <p id="texto">*Faça o login com o SUAP</p>
                @if(session('msg'))
                    <p class="error-message">{{ session('msg') }}</p>
                @endif
                <input type="text" placeholder="Matrícula" id="matricula" name="matricula" class="@error('matricula') is-invalid @enderror" autofocus>
                @error('matricula')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="container-senha">
                    <input type="password" placeholder="Senha" id="password" name="password" class="@error('password') is-invalid @enderror">
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    <span id="alternarSenha" style="cursor: pointer;"><img src="{{ asset('img/olho.png') }}" alt=""></span>
                </div>
                <button id="acessar" type="submit">ACESSAR</button>
                <img src="{{ asset('img/ifrn.png') }}" alt="" id="logoIFRN">               
            </div>
        </form>
    </main>
</body>
</html>
