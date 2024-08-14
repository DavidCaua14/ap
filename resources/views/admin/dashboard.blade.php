<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <title>Dashboard</title>
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

        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmEntregaModal" tabindex="-1" aria-labelledby="confirmEntregaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="confirmEntregaModalLabel">Confirmação de Entrega</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                Você tem certeza de que o objeto foi devolvido ao seu respectivo dono?
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmEntregaBtn" onclick="confirmEntrega()">Confirmar Entrega</button>
                </div>
            </div>
            </div>
        </div>
  


        <a href="{{ route('objeto.create') }}">
            <button type="button" class="btn bg-success add-new"><i class="fa fa-plus"></i> Adicionar Objeto</button>
        </a>

        <table class="table table-striped containerTable">
            <thead>
                <tr>
                    <th scope="col">Descrição</th>
                    <th scope="col">Imagem</th>
                    <th scope="col">Status</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($objetos as $objeto)
                    <tr>
                        <td>{{ $objeto->descricao }}</td>
                        <td>
                            @if($objeto->imagem)
                                <img src="{{ asset('storage/' . $objeto->imagem) }}" alt="Imagem do Objeto" style="width: 150px; height: auto;">
                            @endif
                        </td>
                        <td>
                            {{ $objeto->status == 0 ? 'Aguardando Retirada' : 'Entregue' }}
                        </td>
                        <td>
                            <div class="btnOpcoes">
                                <!-- Botão de "Entregue" -->
                                @if($objeto->status == 0)
                                    <button type="button" class="btn btn-success btn-custom" onclick="confirmEntregaModal({{ $objeto->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-up d-none d-md-inline" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5m-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5"/>
                                        </svg> Entregue<span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary btn-custom" disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle d-none d-md-inline" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                            <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                                        </svg> Entregue<span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                @endif                            

                                <!-- Botão de "Editar" -->
                                <a href="{{ route('objeto.edit', $objeto->id) }}" class="btn btn-primary btn-custom">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square d-none d-md-inline" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg> Editar
                                </a>
        
                                <!-- Botão de "Excluir" -->
                                <form method="POST" action="{{ route('objeto.destroy', $objeto->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs btn-custom">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash d-none d-md-inline" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                        </svg> Excluir
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Nenhum objeto cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
    </main>

    <script>
        // Armazena o ID do objeto para confirmação
        let objetoIdParaConfirmacao = null;
    
        function confirmEntregaModal(id) {
            objetoIdParaConfirmacao = id;
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmEntregaModal'));
            confirmModal.show();
        }
    
        function confirmEntrega() {
            if (objetoIdParaConfirmacao) {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                
                fetch(`/objeto/${objetoIdParaConfirmacao}/entregue`, {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Erro ao atualizar o status. Tente novamente.');
                    }
                }).catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao atualizar o status. Tente novamente.');
                });
            } else {
                console.error('Objeto ID não definido');
            }
        }
    </script>
    
</body>
</html>