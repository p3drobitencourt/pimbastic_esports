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
        $data['usuario_logado'] = [
            'id'     => $this->session->get('usuario_id'),
            'nome'   => $this->session->get('usuario_nome'),
            'email'  => $this->session->get('usuario_email'),
            'perfil' => $this->session->get('usuario_perfil'),
        ];

        return view($view, $data);
    }
}
