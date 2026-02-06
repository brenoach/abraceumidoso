document.addEventListener('DOMContentLoaded', function() {
    const dataInput = document.getElementById('data');
    const form = document.querySelector('form');

    // 1. Define a data mínima no calendário como "hoje"
    const hoje = new Date();
    const dia = String(hoje.getDate()).padStart(2, '0');
    const mes = String(hoje.getMonth() + 1).padStart(2, '0'); // Janeiro é 0!
    const ano = hoje.getFullYear();
    
    const dataAtualFormatada = `${ano}-${mes}-${dia}`;
    dataInput.setAttribute('min', dataAtualFormatada);

    // 2. Validação extra ao enviar o formulário
    form.addEventListener('submit', function(event) {
        const dataSelecionada = new Date(dataInput.value);
        // Zerar horas para comparar apenas as datas
        const dataHoje = new Date();
        dataHoje.setHours(0,0,0,0);
        dataSelecionada.setHours(0,0,0,0);

        // Se a data for anterior a hoje (mesmo que o input min falhe em browsers antigos)
        if (dataSelecionada < dataHoje) {
            event.preventDefault(); // Impede o envio do formulário
            alert("Atenção: A data da visita não pode ser no passado.");
            dataInput.focus();
        }
    });
});