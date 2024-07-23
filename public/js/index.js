document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('alternarSenha').addEventListener('click', function (e) {
        const senha = document.getElementById('password');
        const tipo = senha.getAttribute('type') === 'password' ? 'text' : 'password';
        senha.setAttribute('type', tipo);
    });
});