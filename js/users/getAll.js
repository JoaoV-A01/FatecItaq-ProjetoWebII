document.getElementById('getAllButton').addEventListener('click', getAll);
function getAll() {
    fetch('../../backend/usuarios.php', {
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
        displayUsers(data);
    })
    .catch(error => alert('Erro na requisição: ' + error));
}

function displayUsers(data) {
    const users = data.usuarios;  
    const usersDiv = document.getElementById('usersList');
    usersDiv.innerHTML = ''; 

    const list = document.createElement('ul');
    users.forEach(users => {
        const listItem = document.createElement('li');
        listItem.textContent = `${users.id} - ${users.nome} - ${users.email}`;
        list.appendChild(listItem);
    });

    usersDiv.appendChild(list);
}
getAll();