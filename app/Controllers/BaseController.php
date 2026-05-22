<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController centraliza:
 * - Carregamento de helpers globais (form, url, text)
 * - Inicialização da sessão como propriedade protegida
 * - Compartilhamento de dados de sessão com todas as views
 */
abstract class BaseController extends Controller
{
    /**
     * Instância da sessão disponível para todos os controllers.
     */
    protected $session;

    /**
     * Helpers carregados automaticamente em todos os controllers.
     */
    protected $helpers = ['form', 'url', 'text'];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Inicializa a sessão como propriedade reutilizável
        $this->session = service('session');
    }

    /**
     * Wrapper para renderizar views com dados globais de sessão
     * automaticamente injetados (nome, perfil, status de login).
     */
    protected function renderView(string $view, array $data = []): string
    {
        // Injeta dados globais do usuário logado em todas as views
        $perfil = strtolower((string) ($this->session->get('usuario_perfil') ?? $this->session->get('perfil')));

        $data['usuario_logado'] = [
            'id'     => $this->session->get('usuario_id') ?? $this->session->get('id'),
            'nome'   => $this->session->get('usuario_nome') ?? $this->session->get('nome'),
            'email'  => $this->session->get('usuario_email') ?? $this->session->get('email'),
            'perfil' => $perfil,
        ];

        return view($view, $data);
    }

    /**
     * Verifica se o usuário logado é admin.
     */
    protected function isAdmin(): bool
    {
        $perfil = strtolower((string) ($this->session->get('usuario_perfil') ?? $this->session->get('perfil')));
        return $perfil === 'admin';
    }

    /**
     * Verifica se o usuário logado é cliente.
     */
    protected function isCliente(): bool
    {
        $perfil = strtolower((string) ($this->session->get('usuario_perfil') ?? $this->session->get('perfil')));
        return $perfil === 'cliente';
    }

    protected function getClienteSaldo(): float
    {
        $clienteId = $this->session->get('cliente_id');

        if (!$clienteId) {
            return 0.0;
        }

        $clienteModel = new \App\Models\ClienteModel();

        return $clienteModel->getSaldoAtual((int) $clienteId);
    }
}
