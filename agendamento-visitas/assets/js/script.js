// --- PARTE 1: Configurações que rodam assim que a página carrega ---
document.addEventListener('DOMContentLoaded', function() {
    
    // Tenta achar o campo de data
    const dataInput = document.getElementById('data');
    const form = document.querySelector('form');

    // SÓ roda a lógica de data se o campo 'data' realmente existir na tela
    if (dataInput) {
        
        // 1. Define a data mínima como "hoje"
        const hoje = new Date();
        const dia = String(hoje.getDate()).padStart(2, '0');
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const ano = hoje.getFullYear();
        
        const dataAtualFormatada = `${ano}-${mes}-${dia}`;
        dataInput.setAttribute('min', dataAtualFormatada);

        // 2. Validação extra ao enviar o formulário
        if (form) {
            form.addEventListener('submit', function(event) {
                const dataSelecionada = new Date(dataInput.value);
                
                // Zerar horas para comparar apenas as datas (Dia/Mês/Ano)
                const dataHoje = new Date();
                dataHoje.setHours(0,0,0,0);
                dataSelecionada.setHours(0,0,0,0); // Ajuste aqui para garantir UTC/Local correto na comparação simples

                // Precisamos somar o fuso horário ou usar getUTCDate se der diferença, 
                // mas para validação simples local, isso costuma bastar.
                if (dataSelecionada < dataHoje) {
                    event.preventDefault();
                    alert("Atenção: A data não pode ser no passado.");
                    dataInput.focus();
                }
            });
        }
    }
});

// --- PARTE 2: Funções chamadas pelo HTML (onblur, onclick, etc) ---

// Esta função precisa ficar FORA do 'DOMContentLoaded' para o HTML conseguir enxergá-la
function buscarCep() {
    let cep = document.getElementById('cep').value;

    // Remove tudo que não é número
    cep = cep.replace(/\D/g, '');

    if (cep.length === 8) {
        // Mostra pro usuário que está carregando (opcional, mas legal)
        document.getElementById('rua').value = "...";
        
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(res => res.json())
            .then(data => {
                if (!data.erro) {
                    // Preenche os campos se os IDs existirem
                    if(document.getElementById('rua')) document.getElementById('rua').value = data.logradouro;
                    if(document.getElementById('bairro')) document.getElementById('bairro').value = data.bairro;
                    if(document.getElementById('cidade')) document.getElementById('cidade').value = data.localidade;
                    if(document.getElementById('uf')) document.getElementById('uf').value = data.uf; // Note que no seu HTML o ID era 'uf' ou 'estado'? Confirme.
                    
                    // Foca no número após preencher
                    const numeroInput = document.querySelector('input[name="numero"]');
                    if(numeroInput) numeroInput.focus();
                } else {
                    alert("CEP não encontrado.");
                    limparFormularioCep();
                }
            })
            .catch(error => {
                console.error("Erro na API:", error);
                alert("Erro ao buscar CEP.");
            });
    } else {
        alert("Formato de CEP inválido.");
    }
}

function limparFormularioCep() {
    document.getElementById('rua').value = "";
    document.getElementById('bairro').value = "";
    document.getElementById('cidade').value = "";
    document.getElementById('uf').value = "";
}


