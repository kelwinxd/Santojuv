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

$eventos = ler_dados_json($json_file);
?>

<?php get_header(); ?>

<body>
    <h1>Eventos</h1>
  
    <div class="cards">

  
    <ul>
        <?php foreach ($eventos as $evento): ?>

            
            <li class='card'>
                <div class="dia">
               <strong><?php echo htmlspecialchars($evento['dia_numero']); ?></strong> 
                <p><?php echo htmlspecialchars($evento['dia_nome']); ?></p>
                </div>
           
                
                <strong>Evento:</strong> <?php echo htmlspecialchars($evento['evento']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
    </div>

    <?php get_footer(); ?>



</html>
