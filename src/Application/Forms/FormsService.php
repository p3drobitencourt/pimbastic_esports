<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Forms;

use PDO;

final class FormsService
{
    public function __construct(private PDO $pdo)
    {
    }

    public function handleSubmission(array $server, array $post): void
    {
        $method = isset($server['REQUEST_METHOD']) ? (string) $server['REQUEST_METHOD'] : 'GET';
        if (strtoupper($method) !== 'POST') {
            return;
        }

        $formType = $this->postString($post, 'form_type');

        if ($formType === 'campeonato') {
            $this->saveCampeonato($post);
        }

        if ($formType === 'time') {
            $this->saveTime($post);
        }

        if ($formType === 'jogo') {
            $this->saveJogo($post);
        }

        if ($formType === 'cliente') {
            $this->saveCliente($post);
        }

        if ($formType === 'aposta') {
            $this->saveAposta($post);
        }

        $this->redirectWithFeedback('error', 'Formulario nao reconhecido.');
    }

    /**
     * @return array{
     *   campeonatos: array<int, array<string, mixed>>,
     *   times: array<int, array<string, mixed>>,
     *   clientes: array<int, array<string, mixed>>,
     *   jogos: array<int, array<string, mixed>>
     * }
     */
    public function fetchViewData(): array
    {
        return [
            'campeonatos' => $this->pdo->query('SELECT id, nome, pais FROM campeonato ORDER BY id DESC')->fetchAll(),
            'times' => $this->pdo->query('SELECT id, nome, sigla FROM time ORDER BY id DESC')->fetchAll(),
            'clientes' => $this->pdo->query('SELECT id, nome, saldo_carteira FROM cliente ORDER BY id DESC')->fetchAll(),
            'jogos' => $this->pdo->query(
                'SELECT
                    j.id,
                    c.nome AS campeonato_nome,
                    tc.nome AS time_casa_nome,
                    tf.nome AS time_fora_nome,
                    j.data_horario,
                    j.odd_casa,
                    j.odd_empate,
                    j.odd_fora
                FROM jogo j
                INNER JOIN campeonato c ON c.id = j.campeonato_id
                INNER JOIN time tc ON tc.id = j.time_casa_id
                INNER JOIN time tf ON tf.id = j.time_fora_id
                ORDER BY j.id DESC'
            )->fetchAll(),
        ];
    }

    private function saveCampeonato(array $post): void
    {
        $nome = $this->postString($post, 'nome');
        $pais = $this->postString($post, 'pais');

        if ($nome === '') {
            $this->redirectWithFeedback('error', 'Informe o nome do campeonato.');
        }

        if (strlen($nome) < 3) {
            $this->redirectWithFeedback('error', 'Nome do campeonato deve ter no mínimo 3 caracteres.');
        }

        if (strlen($nome) > 255) {
            $this->redirectWithFeedback('error', 'Nome do campeonato não pode exceder 255 caracteres.');
        }

        if ($pais !== '' && strlen($pais) > 100) {
            $this->redirectWithFeedback('error', 'País não pode exceder 100 caracteres.');
        }

        $stmt = $this->pdo->prepare('INSERT INTO campeonato (nome, pais) VALUES (:nome, :pais)');
        $stmt->execute([
            ':nome' => $nome,
            ':pais' => $pais !== '' ? $pais : null,
        ]);

        $this->redirectWithFeedback('success', 'Campeonato cadastrado com sucesso.');
    }

    private function saveTime(array $post): void
    {
        $nome = $this->postString($post, 'nome');
        $tecnico = $this->postString($post, 'tecnico');
        $sigla = $this->postString($post, 'sigla');

        if ($nome === '') {
            $this->redirectWithFeedback('error', 'Informe o nome do time.');
        }

        if ($tecnico === '') {
            $this->redirectWithFeedback('error', 'Informe o nome do técnico.');
        }

        if (strlen($nome) < 3 || strlen($nome) > 255) {
            $this->redirectWithFeedback('error', 'Nome do time deve ter entre 3 e 255 caracteres.');
        }

        if (strlen($tecnico) < 3 || strlen($tecnico) > 255) {
            $this->redirectWithFeedback('error', 'Nome do técnico deve ter entre 3 e 255 caracteres.');
        }

        if ($sigla !== '' && strlen($sigla) > 10) {
            $this->redirectWithFeedback('error', 'Sigla não pode exceder 10 caracteres.');
        }

        $stmt = $this->pdo->prepare('INSERT INTO time (nome, tecnico, sigla) VALUES (:nome, :tecnico, :sigla)');
        $stmt->execute([
            ':nome' => $nome,
            ':tecnico' => $tecnico,
            ':sigla' => $sigla !== '' ? strtoupper($sigla) : null,
        ]);

        $this->redirectWithFeedback('success', 'Time cadastrado com sucesso.');
    }

    private function saveJogo(array $post): void
    {
        $campeonatoId = $this->postInt($post, 'campeonato_id');
        $timeCasaId = $this->postInt($post, 'time_casa_id');
        $timeForaId = $this->postInt($post, 'time_fora_id');
        $dataHorario = $this->postString($post, 'data_horario');
        $oddCasa = $this->postFloat($post, 'odd_casa');
        $oddEmpate = $this->postFloat($post, 'odd_empate');
        $oddFora = $this->postFloat($post, 'odd_fora');

        if ($campeonatoId <= 0 || $timeCasaId <= 0 || $timeForaId <= 0 || $dataHorario === '') {
            $this->redirectWithFeedback('error', 'Preencha todos os campos obrigatorios do jogo.');
        }

        if ($timeCasaId === $timeForaId) {
            $this->redirectWithFeedback('error', 'Selecione times diferentes para casa e fora.');
        }

        if ($oddCasa <= 0 || $oddEmpate <= 0 || $oddFora <= 0) {
            $this->redirectWithFeedback('error', 'As odds devem ser valores positivos.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO jogo (
                campeonato_id,
                time_casa_id,
                time_fora_id,
                data_horario,
                odd_casa,
                odd_empate,
                odd_fora
            ) VALUES (
                :campeonato_id,
                :time_casa_id,
                :time_fora_id,
                :data_horario,
                :odd_casa,
                :odd_empate,
                :odd_fora
            )'
        );

        $stmt->execute([
            ':campeonato_id' => $campeonatoId,
            ':time_casa_id' => $timeCasaId,
            ':time_fora_id' => $timeForaId,
            ':data_horario' => str_replace('T', ' ', $dataHorario) . ':00',
            ':odd_casa' => number_format($oddCasa, 2, '.', ''),
            ':odd_empate' => number_format($oddEmpate, 2, '.', ''),
            ':odd_fora' => number_format($oddFora, 2, '.', ''),
        ]);

        $this->redirectWithFeedback('success', 'Jogo cadastrado com sucesso.');
    }

    private function saveCliente(array $post): void
    {
        $nome = $this->postString($post, 'nome');
        $saldo = $this->postFloat($post, 'saldo_carteira');

        if ($nome === '') {
            $this->redirectWithFeedback('error', 'Informe o nome do cliente.');
        }

        if (strlen($nome) < 3 || strlen($nome) > 255) {
            $this->redirectWithFeedback('error', 'Nome do cliente deve ter entre 3 e 255 caracteres.');
        }

        if ($saldo < 0) {
            $this->redirectWithFeedback('error', 'Saldo deve ser maior ou igual a zero.');
        }

        if ($saldo > 999999999.99) {
            $this->redirectWithFeedback('error', 'Saldo não pode ser tão grande.');
        }

        $stmt = $this->pdo->prepare('INSERT INTO cliente (nome, saldo_carteira) VALUES (:nome, :saldo_carteira)');
        $stmt->execute([
            ':nome' => $nome,
            ':saldo_carteira' => number_format($saldo, 2, '.', ''),
        ]);

        $this->redirectWithFeedback('success', 'Cliente cadastrado com sucesso.');
    }

    private function saveAposta(array $post): void
    {
        $clienteId = $this->postInt($post, 'cliente_id');
        $jogoId = $this->postInt($post, 'jogo_id');
        $valor = $this->postFloat($post, 'valor');
        $tipoEscolhido = $this->postString($post, 'tipo_escolhido');
        $oddEscolhida = $this->postFloat($post, 'odd_escolhida');

        $tiposValidos = ['vitoria_casa', 'empate', 'vitoria_fora'];

        if ($clienteId <= 0) {
            $this->redirectWithFeedback('error', 'Selecione um cliente válido.');
        }

        if ($jogoId <= 0) {
            $this->redirectWithFeedback('error', 'Selecione um jogo válido.');
        }

        if ($valor <= 0) {
            $this->redirectWithFeedback('error', 'Valor deve ser maior que zero.');
        }

        if ($valor > 999999.99) {
            $this->redirectWithFeedback('error', 'Valor da aposta muito alto.');
        }

        if (!in_array($tipoEscolhido, $tiposValidos, true)) {
            $this->redirectWithFeedback('error', 'Tipo de aposta inválido.');
        }

        if ($oddEscolhida <= 0 || $oddEscolhida > 1000) {
            $this->redirectWithFeedback('error', 'Odd inválida.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO aposta (
                cliente_id,
                jogo_id,
                valor,
                tipo_escolhido,
                odd_escolhida,
                status
            ) VALUES (
                :cliente_id,
                :jogo_id,
                :valor,
                :tipo_escolhido,
                :odd_escolhida,
                :status
            )'
        );

        $stmt->execute([
            ':cliente_id' => $clienteId,
            ':jogo_id' => $jogoId,
            ':valor' => number_format($valor, 2, '.', ''),
            ':tipo_escolhido' => $tipoEscolhido,
            ':odd_escolhida' => number_format($oddEscolhida, 2, '.', ''),
            ':status' => 'aberta',
        ]);

        $this->redirectWithFeedback('success', 'Aposta registrada com sucesso.');
    }

    private function redirectWithFeedback(string $type, string $message): never
    {
        $target = sprintf(
            '?feedback_type=%s&feedback_message=%s',
            rawurlencode($type),
            rawurlencode($message)
        );

        header('Location: ' . $target);
        exit;
    }

    private function postString(array $post, string $key): string
    {
        if (!isset($post[$key])) {
            return '';
        }

        return trim((string) $post[$key]);
    }

    private function postInt(array $post, string $key): int
    {
        if (!isset($post[$key])) {
            return 0;
        }

        $value = filter_var($post[$key], FILTER_VALIDATE_INT);
        return $value === false ? 0 : (int) $value;
    }

    private function postFloat(array $post, string $key): float
    {
        $raw = $this->postString($post, $key);
        $normalized = str_replace(',', '.', $raw);

        if (!is_numeric($normalized)) {
            return -1.0;
        }

        return (float) $normalized;
    }
}
