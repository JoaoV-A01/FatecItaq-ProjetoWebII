function deleteProd() {
    const prodId = document.getElementById("getProdId").value;
    if (!prodId) {
        Swal.fire('Por favor, insira um id!')
        return;
    }
    fetch('http://localhost/FatecItaq-ProjetoWebII/backend/produtos.php?id=' + prodId, {
        method: 'DELETE'
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
            alert("Não pode Deletar: ");
        }else{
            alert("Produto excluido!");
            document.getElementById("inputNome").value = ''; 
        } 
        
    })
    .catch(error => alert('Erro na requisição: ' + error));
}