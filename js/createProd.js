document.getElementById('submitButton').addEventListener('click', createProd);
var token = localStorage.getItem('token');
function createProd() {
    const nomeProduto = document.getElementById('nomeprod').value;
    const precoProduto = document.getElementById('precoprod').value;
    const qntProduto = document.getElementById('qntprod').value;
    

    if (!nomeProduto) {
        alert("Por favor, insira um nome!");
        return;
    }

    const produto = {
        nome: nomeProduto,
        preco: precoProduto,
        quantidade: qntProduto
        
    };

    fetch('/backend/produtos.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(produto)
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
            alert('Produto já registrado')
        }else{
            alert("Produto registrado");
        } 
       
    })
    .catch(error => alert('Erro na requisição: ' + error));
}
