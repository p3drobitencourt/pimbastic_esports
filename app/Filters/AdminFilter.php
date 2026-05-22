<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Verifica se o usuário logado possui perfil 'admin'.
 * Se não for admin, redireciona para o sportsbook do cliente.
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $perfil = (string) (session()->get('perfil') ?? session()->get('usuario_perfil'));
        $perfil = strtolower($perfil);

        if (!session()->get('logado') && !session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }

        if ($perfil !== 'admin') {
            return redirect()->to('/cliente/dashboard')->with('error', 'Acesso restrito a administradores.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nada a fazer após a resposta
    }
}
