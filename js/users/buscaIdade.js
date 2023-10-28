function getAll() {
    fetch('././backend/graficoIdade.php', {
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
    let legendas = [];
    let valores = [];
    const idades = data.idades;  
    console.log(idades);
    idades.forEach(usuario => {
        legendas.push(usuario.idades);
        valores.push(usuario.pessoas);
    });
    const barColors = ["red", "green","blue","orange","brown"];
                    
            new Chart("myChart", {
           // type: "bar",
            type: "pie",
            data: {
                labels: legendas,
                datasets: [{
                backgroundColor: barColors,
                data: valores
                }]
            },
            options: {
                legend: {display: false},
                title: {
                display: true,
                text: "Idades cadastradas"
                }
            }
            });

}
getAll();

