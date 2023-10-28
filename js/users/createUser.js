document.getElementById('submitButton').addEventListener('click', createUser);
function createUser() {
    const nomeUsuario = document.getElementById('nome').value;
    const emailUsuario = document.getElementById('email').value;
    const senhaUsuario = document.getElementById('senha').value;
    const nascUsuario = document.getElementById('nasc').value;
    const cepUsuario = document.getElementById('cep').value;
    const ruaUsuario = document.getElementById('rua').value;
    const bairroUsuario = document.getElementById('bairro').value;
    const cidadeUsuario = document.getElementById('cidade').value;
    const ufUsuario = document.getElementById('uf').value;
    
    if (!nomeUsuario) {
        alert("Por favor, insira um nome!");
        return;
    }
    if (!ruaUsuario || !bairroUsuario || !cidadeUsuario || !ufUsuario || !cepUsuario) {
        Swal.fire('Por favor, insira Dados do endereço')
        document.getElementById('id01').style.display='block';
        return;
    }
    const usuario = {
        nome: nomeUsuario,
        email: emailUsuario,
        senha: senhaUsuario,
        datanasc: nascUsuario,
        cep: cepUsuario,
        rua: ruaUsuario,
        bairro: bairroUsuario,
        cidade: cidadeUsuario,
        uf: ufUsuario,
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
