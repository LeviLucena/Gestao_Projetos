<?php
// Desenvolvido por Levi Lucena - https://www.linkedin.com/in/levilucena/
$servername = "172.17.0.55";
$username = "usu_gestao";
$password = "gst_db_user26g1";
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