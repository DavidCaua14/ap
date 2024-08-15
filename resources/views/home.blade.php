<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <title>Home</title>
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
        <section id="hero" class="d-flex align-items-center">
            <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
              <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-9 text-center">
                  <h1>Achados e Perdidos</h1>
                  <h2>Encontre os seus objetos aqui</h2>
                </div>
              </div>

              @if($objetos->isEmpty())
                <div class="container">
                    <div class="alert alert-warning mt-4 text-center">
                        Não existe objeto cadastrado.
                    </div>
                </div>
              @else
                  @foreach($objetos as $objeto)
                      <div class="card bg-success mt-4">
                          @if($objeto->imagem)
                              <img src="{{ asset('storage/' . $objeto->imagem) }}" class="card-img-top" alt="Imagem do Objeto">
                          @endif
                          <div class="card-body">
                              <div class="text-section">
                                  <h5 class="card-title text-white">
                                      @if($objeto->status == 0)
                                         Situação: Aguardando Retirada
                                      @else
                                         Situação: Entregue
                                      @endif
                                  </h5>
                                  <p class="card-text text-white">{{ $objeto->descricao }}</p>
                              </div>
                              <div class="cta-section text-white">
                                  <div>{{ $objeto->data_encontrada }}</div>
                                  <div>{{ $objeto->hora_encontrada }}</div>
                              </div>
                          </div>
                      </div>
                  @endforeach
              @endif          


            </div>
            </div>
        </section>
    </main>
</body>
</html>