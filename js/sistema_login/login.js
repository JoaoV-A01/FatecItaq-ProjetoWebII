document.getElementById('login').addEventListener('click', loginRota);
function loginRota() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("senha").value;

    fetch('http://localhost/FatecItaq-ProjetoWebII/backend/login.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({email, senha: password})
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
        if(data.status){
            console.log(data);
            sessionStorage.setItem('token', data.token);
            window.location.href = "sistema_login.html";
            alert('Login Bem-Sucedido!');
        }else{
            alert("Senha Incorreta!!");
        } 
       
    })
    .catch(error => alert('Erro na requisição: ' + error));
}