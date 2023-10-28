function getUser() {
    const userId = document.getElementById("getUserId").value;
    fetch('./../backend/usuarios.php?id=' + userId, {
        method: 'GET'
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
            alert('Usuário não encontrado')
            document.getElementById("inputNome").value = ''; 
        }else{
            document.getElementById("inputNome").value = data.usuarios.nome; 
            document.getElementById("inputEmail").value = data.usuarios.email; 
        } 
       
    })
    .catch(error => alert('Erro na requisição: ' + error));
}
document.getElementById('submitButton').addEventListener('click', createEndereco);
function createEndereco() {
    const idUsuario = document.getElementById('getUserId').value;
    const cepUsuario = document.getElementById('cep').value;
    const ruaUsuario = document.getElementById('rua').value;
    const bairroUsuario = document.getElementById('bairro').value;
    const cidadeUsuario = document.getElementById('cidade').value;
    const ufUsuario = document.getElementById('uf').value;

    
    if (!ruaUsuario || !bairroUsuario || !cidadeUsuario || !ufUsuario || !cepUsuario) {
        alert("Por favor, insira Dados do endereço");
        document.getElementById('id01').style.display='block';
        return;
    }
    const endereco = {
        iduser: idUsuario,
        cep: cepUsuario,
        rua: ruaUsuario,
        bairro: bairroUsuario,
        cidade: cidadeUsuario,
        uf: ufUsuario,
    };

    fetch('/backend/endereco.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(endereco)
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
            Swal.fire('Usuário já existe!')
        }else{
            Swal.fire('Usuário criado!')
        } 
       
    })
    .catch(error => alert('Erro na requisição: ' + error));
}