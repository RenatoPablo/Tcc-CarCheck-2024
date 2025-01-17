<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("location: login.php");
    exit();
}

require '../config/config.php';

// Obtém o ID da pessoa logada
$id_pessoa = $_SESSION['id_pessoa'];

// // Consulta para buscar os agendamentos do usuário logado
// $sql = "SELECT * FROM agendamentos WHERE id_pessoa = :id_pessoa ORDER BY data, hora";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['id_pessoa' => $id_pessoa]);
// $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Serviço</title>
    <link rel="stylesheet" href="../css/padrao-grid.css">
    <link rel="stylesheet" href="../css/padrao-formularios.css">
    <link rel="stylesheet" href="../css/agendamento.css">

</head>
<?php
include '../includes/header-cliente.php';
?>
<body>
<div class="agendamento-form">
    <h2>Agendamento de Serviço</h2>
    <form id="agendamentoForm">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" value="<?php echo $_SESSION['nomeUsuario']; ?>" readonly>
        <label class="periodo"> Selecione um período no qual você tem disponibilidade ou deseja que o serviço seja realizado. A oficina verificará a data livre mais próxima dentro desse intervalo para agendar o seu atendimento</label>
        <br>
        <label for="dataInicio">Data e Hora Inicial</label>
        <input type="datetime-local" id="dataInicio" name="data_inicio" required>

        <label for="dataFim">Data e Hora Final</label>
        <input type="datetime-local" id="dataFim" name="data_fim" required>

        <label for="servico">Serviço Desejado</label>
        <input type="text" id="servico" name="servico" required placeholder="Descreva o serviço">

        <label for="observacoes">Observações</label>
        <textarea id="observacoes" name="observacoes" placeholder="Observações adicionais"></textarea>

        <button type="button" class="botao-agendamento" onclick="enviarWhatsApp()">Agendar via WhatsApp</button>
    </form>
</div>

<script>
function enviarWhatsApp() {
    // Obtém os valores dos campos do formulário
    const nome = document.getElementById("nome").value;
    const dataInicio = document.getElementById("dataInicio").value;
    const dataFim = document.getElementById("dataFim").value;
    const servico = document.getElementById("servico").value;
    const observacoes = document.getElementById("observacoes").value;

    // Validação de preenchimento
    if (!dataInicio || !dataFim || !servico) {
        alert("Por favor, preencha todos os campos obrigatórios.");
        return;
    }

    // Formata as datas para uma leitura mais fácil no WhatsApp
    const inicioFormatado = new Date(dataInicio).toLocaleString("pt-BR");
    const fimFormatado = new Date(dataFim).toLocaleString("pt-BR");

    // Mensagem para enviar via WhatsApp
    const mensagem = `Olá, gostaria de agendar um serviço com as seguintes informações:
    - Nome: ${nome}
    - Período: de ${inicioFormatado} até ${fimFormatado}
    - Serviço desejado: ${servico}
    - Observações: ${observacoes}`;

    // Número de telefone da oficina (substitua pelo número correto)
    const telefoneOficina = "5518997610162"; // Exemplo de número com DDD 55 (Brasil) e DDD local 11

    // Gera o link do WhatsApp com a mensagem
    const linkWhatsApp = `https://wa.me/${telefoneOficina}?text=${encodeURIComponent(mensagem)}`;

    // Abre o link em uma nova aba para enviar a mensagem pelo WhatsApp
    window.open(linkWhatsApp, "_blank");
}
</script>

</body>
</html>