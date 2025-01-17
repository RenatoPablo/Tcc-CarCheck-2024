document.addEventListener("DOMContentLoaded", function() {
    // Pegando os elementos dos campos
    const camposFisica1 = document.getElementById("campos-fisica1");
    const camposFisica2 = document.getElementById("campos-fisica2");
    const camposJuridica1 = document.getElementById("campos-juridica1");
    const camposJuridica2 = document.getElementById("campos-juridica2");
    const camposJuridica3 = document.getElementById("campos-juridica3");
    const camposJuridica4 = document.getElementById("campos-juridica4");

    // Pegando os elementos dos radio buttons
    const radioFisica = document.querySelector('input[name="tipo_pessoa"][value="fisica"]');
    const radioJuridica = document.querySelector('input[name="tipo_pessoa"][value="juridica"]');

    // Função para esconder ou mostrar os campos
    function toggleCampos() {
        if (radioFisica.checked) {
            camposFisica1.style.display = "block";
            camposFisica2.style.display = "block";
            
            camposJuridica1.style.display = "none";
            camposJuridica2.style.display = "none";
            camposJuridica3.style.display = "none";
            camposJuridica4.style.display = "none";
        } else if (radioJuridica.checked) {
            camposFisica1.style.display = "none";
            camposFisica2.style.display = "none";

            camposJuridica1.style.display = "block";
            camposJuridica2.style.display = "block";
            camposJuridica3.style.display = "block";
            camposJuridica4.style.display = "block";
        }
    }

    // Event listeners para quando o usuário selecionar um dos radio buttons
    radioFisica.addEventListener("change", toggleCampos);
    radioJuridica.addEventListener("change", toggleCampos);

    // Executa a função na inicialização para garantir que os campos estejam corretos
    toggleCampos();
});
