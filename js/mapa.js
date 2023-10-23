let latjs;
let lngjs;
const input = document.getElementById('endereco');

input.addEventListener('blur', () => {
 const cidade = document.getElementById("cidade")
  const street = input.value;
  const city = cidade.value;
  let endereco=street+','+city;
  updateMap(endereco);
});
async function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
      let address = conteudo.logradouro + ',' + conteudo.localidade;
      
        document.getElementById('endereco').value = conteudo.logradouro;
        document.getElementById('bairro').value = conteudo.bairro;
        document.getElementById('cidade').value = conteudo.localidade;
        document.getElementById('uf').value = conteudo.uf;
        console.log(address);
      try {
        
        fetch(`https://nominatim.openstreetmap.org/search?q=${address}&format=json&limit=1`, { 
            method: 'GET',
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
            console.log(data)
            latjs = data[0].lat;
            lngjs = data[0].lon;
            document.getElementById('utmy').value = latjs;
            document.getElementById('utmx').value = lngjs;
            createMap(latjs, lngjs);
        })
        .catch(error => alert('Erro na requisição: ' + error));
        
      } catch (error) {
        const messageText = document.getElementById("message-text");
        messageText.innerText = 'Erro ao obter latitude e longitude';
        //showMessage();
      }
    } else {
      //CEP não Encontrado.
      limpa_formulário_cep();
      const messageText = document.getElementById("message-text");
      messageText.innerText = 'CEP não encontrado';
      //showMessage();
    }
  }
  
  function createMap(lat, lng) {
    mymap = L.map('mapid').remove();
    mymap = L.map('mapid').setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
      maxZoom: 18
    }).addTo(mymap);
    L.marker([lat, lng]).addTo(mymap);
  }
  
  // Função para atualizar o mapa
  function updateMap(address) {
      fetch(`https://nominatim.openstreetmap.org/search?q=${address}&format=json&limit=1`, { 
            method: 'GET',
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
            latjs = data[0].lat;
            lngjs = data[0].lon;
            document.getElementById('utmy').value = latjs;
            document.getElementById('utmx').value = lngjs;
        if (mymap) {
          mymap.remove();
        }
        createMap(latjs, lngjs);
        })
        .catch(error => alert('Erro na requisição: ' + error));
  }

function limpa_formulário_cep() {
    //Limpa valores do formulário de cep.
    document.getElementById('endereco').value=("");
    document.getElementById('bairro').value=("");
    document.getElementById('cidade').value=("");
    document.getElementById('uf').value=("");
   //document.getElementById('ibge').value=("");
}



function pesquisacep(valor) {
//Nova variável "cep" somente com dígitos.
var cep = valor.replace(/\D/g, '');
//Verifica se campo cep possui valor informado.
if (cep != "") {
    //Expressão regular para validar o CEP.
    var validacep = /^[0-9]{8}$/;
    //Valida o formato do CEP.
    if(validacep.test(cep)) {
        //Preenche os campos com "..." enquanto consulta webservice.
        document.getElementById('endereco').value="...";
        document.getElementById('bairro').value="...";
        document.getElementById('cidade').value="...";
        document.getElementById('uf').value="...";
        //document.getElementById('ibge').value="...";
        //Cria um elemento javascript.
        var script = document.createElement('script');
        //Sincroniza com o callback.
        script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
        //Insere script no documento e carrega o conteúdo.
        document.body.appendChild(script);
    } //end if.
    else {
        //cep é inválido.
        limpa_formulário_cep();
        const messageText = document.getElementById("message-text");
                messageText.innerText = 'CEP invalido';
                //showMessage();
    }
} //end if.
else {
    //cep sem valor, limpa formulário.
    limpa_formulário_cep();
}
};