<?php
// Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestao";

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Define o conjunto de caracteres
mysqli_set_charset($conn, "utf8");
?>