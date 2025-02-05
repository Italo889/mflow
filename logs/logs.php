<?php
function logError($message) {
    error_log("[" . date("Y-m-d H:i:s") . "] ERROR: " . $message . "\n", 3, "logs/errors.log");
}

function logRequest($endpoint, $executionTime) {
    error_log("[" . date("Y-m-d H:i:s") . "] REQUEST: $endpoint - Tempo: " . number_format($executionTime, 4) . "s\n", 3, "logs/requests.log");
}

$startTime = microtime(true); // Início da execução

// Código do endpoint...

$endTime = microtime(true);
logRequest("avaliacoes.php?action=media&manga_id=" . $manga_id, $endTime - $startTime);


?>
