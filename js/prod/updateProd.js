function updateProd() {
    const prodId = document.getElementById("getProdId").value;
    const nomeProd = document.getElementById("inputNome").value;
    const precoProd = document.getElementById("inputPreco").value;
    const qntProd = document.getElementById("inputQnt").value;
    const prodAtualizado = {
        nome: nomeProd,
        preco: precoProd,
        quantidade: qntProd
    };

    fetch('http://localhost/FatecItaq-ProjetoWebII/backend/produtos.php?id=' + prodId, { 
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(prodAtualizado)
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
            alert("Não pode atualizar: ");
        }else{
            alert("Produto atualizado!");
        } 
        
    })
    .catch(error => alert('Erro na requisição: ' + error));
}
