function updateProd() {
    const prodId = document.getElementById("getProdId").value;
    const prodNome = document.getElementById("inpuNome").value;
    const prodPreco = document.getElementById("inpuPreco").value;
    const prodQnt = document.getElementById("inpuQnt").value;
    const produtoAtualizado = {
        nome: prodNome,
        preco: prodPreco,
        prodQnt: prodQnt
    };

    fetch('/backend/produtos.php?id=' + prodId, { 
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(produtoAtualizado)
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
            alert("Produto atualizado: " + JSON.stringify(data));
        } 
        
    })
    .catch(error => alert('Erro na requisição: ' + error));
}
