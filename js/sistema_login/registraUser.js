document.getElementById('registrationForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const resenha = document.getElementById('resenha').value;

    const usuario = {
        email: email,
        senha: senha
    };

    fetch('http://localhost/FatecItaq-ProjetoWebII/backend/usuarios.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(usuario)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            document.getElementById('message').textContent = 'Usuário registrado com sucesso!';
            document.getElementById('email').value='';
            document.getElementById('senha').value='';
            document.getElementById('resenha').value='';
        } else {
            document.getElementById('message').textContent = 'Erro ao registrar o usuário.';
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        document.getElementById('message').textContent = 'Erro ao registrar o usuário.';
    });
});
