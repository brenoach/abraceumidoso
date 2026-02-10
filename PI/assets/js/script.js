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
