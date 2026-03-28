<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/src/Infrastructure/Database/DatabaseConnector.php';

use PimbasticEsports\Infrastructure\Database\DatabaseConnector;

$schemaPath = dirname(__DIR__) . '/database/schema.sql';

if (!is_file($schemaPath)) {
    fwrite(STDERR, "Arquivo schema nao encontrado em: {$schemaPath}" . PHP_EOL);
    exit(1);
}

$schema = file_get_contents($schemaPath);
if ($schema === false) {
    fwrite(STDERR, "Falha ao ler o schema." . PHP_EOL);
    exit(1);
}

try {
    $connector = new DatabaseConnector();
    $pdo = $connector->getConnection();
    $pdo->exec($schema);
    fwrite(STDOUT, "Schema aplicado com sucesso." . PHP_EOL);
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, "Erro ao aplicar schema: {$e->getMessage()}" . PHP_EOL);
    exit(1);
}
