document.getElementById('submitButton').addEventListener('click', createUser);
function createUser() { 
    const emailUsuario = document.getElementById('email').value;
    const senhaUsuario = document.getElementById('senha').value;
    
    const usuario = {
        email: emailUsuario,
        senha: senhaUsuario,
    };

    fetch('././backend/usuarios.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(usuario)
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                throw new Error('Não autorizado');
            } else {
                throw new Error('Sem rede ou não conseguiu localizar o recurso');
            }
        }
        return response.json();
    })
    .then(data => {
        if(!data.status){
            alert('Usuário já existe!')
        }else{
            alert("Usuário criado");
        } 
       
    })
    .catch(error => alert('Erro na requisição: ' + error));
}
