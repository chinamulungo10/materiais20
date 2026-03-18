<?php
$conn = new mysqli("localhost", "root", "", "loja_ferragem");

if ($conn->connect_error) {
    die("Erro de conexão");
}

// 🔥 ESSENCIAL para aceitar assentuação
$conn->set_charset("utf8mb4");




