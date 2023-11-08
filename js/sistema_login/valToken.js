document.addEventListener("DOMContentLoaded", async function() {
    const token = sessionStorage.getItem('token');
    if (!token) {
        redirecioneLogin();
        return;
    }

  async function validaToken() {
    try {
        const response = await fetch('http://localhost/FatecItaq-ProjetoWebII/backend/login.php', {
            method: 'GET',
            headers: {
                'Authorization':  token
            }
        });
        const jsonResponse = await response.json();
    
        if (!response.ok || !jsonResponse.status) {
            redirecioneLogin(jsonResponse.message);
        }
    } catch (error) {
        console.error("Erro ao validar token:", error);
        redirecioneLogin(error);
    }
}

validaToken();

setInterval(validaToken, 60000);
});

function redirecioneLogin() {
    alert("Token inválido ou expirado!")
    window.location.href = "login.html";
}