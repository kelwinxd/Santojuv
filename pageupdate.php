<?php
/**
 * Template Name: pageupdate
 * Description: Template personalizado para atualizar informações específicas.
 */
?>

<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: /login-juv/');
    exit();
}

// Lida com o logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); // Destrói a sessão
    header('Location: /login-juv/');
    exit();
}

?>



<?php
// Caminho para o arquivo JSON na raiz do tema
$json_file = __DIR__ . '/dados.json'; // __DIR__ retorna o diretório atual do arquivo PHP

// Função para ler dados do JSON
function ler_dados_json($json_file) {
    if (!file_exists($json_file)) {
        return array(); // Arquivo não existe, retorna array vazio
    }
    
    $json_data = file_get_contents($json_file);
    return json_decode($json_data, true); // Decodifica o JSON para um array associativo
}

// Função para salvar dados no JSON
function salvar_dados_json($json_file, $dados) {
    $json_data = json_encode($dados, JSON_PRETTY_PRINT);
    file_put_contents($json_file, $json_data);
}

// Adicionar novo evento
// Adicionar novo evento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    // Recuperar os dados enviados pelo formulário
    $novo_evento = array(
        "id" => uniqid(), // Gerar um ID único para o evento
        "dia_numero" => $_POST['dia_numero'], // Dia em número extraído do Flatpickr
        "dia_nome" => $_POST['dia_nome'], // Nome do dia extraído do Flatpickr
        "evento" => $_POST['evento']
    );

    // Ler os dados existentes do JSON
    $dados = ler_dados_json($json_file);
    
    // Adicionar o novo evento aos dados
    $dados[] = $novo_evento;

    // Salvar os dados atualizados no JSON
    salvar_dados_json($json_file, $dados);
    
    // Redirecionar após adicionar o evento
    header("Location: http://juvsantoantonio.test/page-update/");
    exit();
}


// Remover evento
if (isset($_GET['remove'])) {
    $id_remove = $_GET['remove'];
    $dados = ler_dados_json($json_file);
    $dados = array_filter($dados, function($evento) use ($id_remove) {
        return $evento['id'] !== $id_remove;
    });

    salvar_dados_json($json_file, array_values($dados));
    header("Location: http://juvsantoantonio.test/page-update/");
    exit();
}

$eventos = ler_dados_json($json_file);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <title>Gerenciar Eventos</title>
</head>
<body>
    <h1>Gerenciar Eventos</h1>
    <form method="POST">
    <button type="submit" name="logout">Logout</button>
</form>


    <h2>Adicionar Novo Evento</h2>
<form method="POST">
    <input type="hidden" name="acao" value="adicionar">

    <label for="datepicker">Escolha a Data:</label>
    <input type="text" id="datepicker" required>

    <input type="hidden" id="dia_numero" name="dia_numero">
    <input type="hidden" id="dia_nome" name="dia_nome">

    <label for="evento">Evento:</label>
    <input type="text" id="evento" name="evento" required>

    <button type="submit">Adicionar Evento</button>
</form>

    <h2>Eventos Existentes</h2>
    <ul>
        <?php foreach ($eventos as $evento): ?>
            <li>
                <strong>Dia (Número):</strong> <?php echo htmlspecialchars($evento['dia_numero']); ?><br>
                <strong>Dia (Nome):</strong> <?php echo htmlspecialchars($evento['dia_nome']); ?><br>
                <strong>Evento:</strong> <?php echo htmlspecialchars($evento['evento']); ?><br>
                <a href="?remove=<?php echo urlencode($evento['id']); ?>">Remover</a>
            </li>
        <?php endforeach; ?>
    </ul>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Tradução para Português -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar o Flatpickr com a configuração de idioma em português
        flatpickr("#datepicker", {
            dateFormat: "Y-m-d", // Formato da data (ano-mês-dia)
            locale: "pt", // Define o idioma para português
            onChange: function(selectedDates, dateStr, instance) {
                const date = new Date(dateStr);
                document.getElementById('dia_numero').value = date.getDate(); // Extrair dia em número
                document.getElementById('dia_nome').value = instance.formatDate(date, "l"); // Extrair nome do dia (ex: Segunda-feira)
            }
        });
    });
</script>

</body>
</html>
