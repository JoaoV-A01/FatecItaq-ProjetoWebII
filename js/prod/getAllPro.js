document.getElementById('getAllButton').addEventListener('click', getAll);
var token = localStorage.getItem('token');
function getAll() {
    fetch('./../backend/produtos.php', {
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
        displayProds(data);
    })
    .catch(error => alert('Erro na requisição: ' + error));
}

function displayProds(data) {
    const prods = data.produtos;  
    const prodsDiv = document.getElementById('prodsList');
    prodsDiv.innerHTML = ''; 

    const list = document.createElement('ul');
    prods.forEach(produto => {
        const listItem = document.createElement('li');
        listItem.textContent = `${produto.id} - ${produto.nome} - ${produto.preco} - ${produto.quantidade}`;
        list.appendChild(listItem);
    });

    prodsDiv.appendChild(list);
}
getAll();