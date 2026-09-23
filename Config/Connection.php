<?php

class Connection
{
    private string $host = "localhost";
    private string $database = "diario_cacador";
    private string $usuario = "root";
    private string $senha = "admin";

    public function conectar(): PDO
    {
        try {
            $conexao = new PDO(
                "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4",
                $this->usuario,
                $this->senha
            );

            $conexao->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $conexao;
        } catch (PDOException $erro) {
            error_log($erro->getMessage());

            die("Não foi possível conectar ao banco de dados. Tente novamente mais tarde.");
        }
    }
}