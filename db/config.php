<?php
// Configurações do banco de dados
$servername = "localhost"; // Servidor MySQL (no XAMPP, geralmente é "localhost")
$username = "root";        // Nome de usuário padrão do XAMPP
$password = "";            // Senha padrão do XAMPP (vazia)
$dbname = "mangaflow";     // Nome do banco de dados que você criou

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Encerra o script e exibe o erro
}


// Fechar conexão (não é necessário fechar aqui se você for usar a conexão em outras partes do código)
// $conn->close();
?>