document.getElementById('submitButton').addEventListener('click', createVenda);
function createVenda() {
    const idUser = document.getElementById('iduser').value;
    const idProd = document.getElementById('idprod').value;

    if (!idUser) {
        alert("Por favor, insira um id de usuário!");
        return;
    }

    const venda = {
        idusuario: idUser,
        idproduto: idProd,
    };

    fetch('./backend/venda.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(venda)
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
            alert('Produto já registrado');
        }else{
            alert("Registro criado: " + JSON.stringify(data));
        } 
       
    })
    .catch(error => alert('Erro na requisição: ' + error));
}