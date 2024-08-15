<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <title>{{ isset($objeto) ? 'Editar Objeto' : 'Cadastrar Objeto' }}</title>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light fixed-top py-4">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="{{ asset('img/logo.png') }}" alt="Logo AP" class="rounded-circle" width="60" height="60">
            </a>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Pesquisar objeto" aria-label="Search">
            </form>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="nav navbar-nav ms-auto w-100 justify-content-end">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->nome_completo }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Sair</button>
                            </form>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <br>
        <br>
        @if($errors->any())
        <div class="container">
            <p class="alert alert-danger my-3 text-center">Verifique os campos do formulário!</p>
        </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mx-3 mt-5 mb-4">
                    <div class="card-header" style="background-color: #28a745; color: white;">
                        {{ isset($objeto) ? 'Editar Objeto' : 'Cadastrar Objeto' }}
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ isset($objeto) ? route('objeto.update', $objeto->id) : route('objeto.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if(isset($objeto))
                                @method('PUT')
                            @endif

                            <div class="row mb-3">
                                <label for="imagem" class="col-md-4 col-form-label text-md-end">Imagem</label>
                                <div class="col-md-6">
                                    <input id="imagem" type="file" class="form-control @error('imagem') is-invalid @enderror" name="imagem">
                                    @error('imagem')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @if(isset($objeto->imagem))
                                        <img src="{{ asset('storage/' . $objeto->imagem) }}" alt="Imagem do Objeto" class="img-fluid mt-2">
                                    @endif
                                </div>
                            </div>                            

                            <div class="row mb-3">
                                <label for="descricao" class="col-md-4 col-form-label text-md-end">Descrição</label>
                                <div class="col-md-6">
                                    <textarea id="descricao" class="form-control @error('descricao') is-invalid @enderror" name="descricao">{{ old('descricao', isset($objeto) ? $objeto->descricao : '') }}</textarea>
                                    @error('descricao')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <label for="data" class="col-md-4 col-form-label text-md-end">Data que o Objeto foi Encontrado</label>
                                <div class="col-md-6">
                                    <input id="data_encontrada" type="date" class="form-control @error('data_encontrada') is-invalid @enderror" name="data_encontrada" value="{{ old('data_encontrada', isset($objeto) ? $objeto->data_encontrada : '') }}">
                                    @error('data_encontrada')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="hora" class="col-md-4 col-form-label text-md-end">Hora que o Objeto foi encontrado</label>
                                <div class="col-md-6">
                                    <input id="hora_encontrada" type="text" class="form-control @error('hora_encontrada') is-invalid @enderror" name="hora_encontrada" value="{{ old('hora_encontrada', isset($objeto) ? $objeto->hora_encontrada : '') }}">
                                    @error('hora_encontrada')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-success mt-4">
                                    {{ isset($objeto) ? 'Atualizar' : 'Cadastrar' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
