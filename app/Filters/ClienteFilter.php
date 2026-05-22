<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Verifica se o usuário logado possui perfil 'cliente'.
 * Se não for cliente, redireciona para o dashboard do admin.
 */
class ClienteFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $perfil = (string) (session()->get('perfil') ?? session()->get('usuario_perfil'));
        $perfil = strtolower($perfil);

        if (!session()->get('logado') && !session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }

        if ($perfil !== 'cliente') {
            return redirect()->to('/admin/dashboard')->with('error', 'Acesso restrito a clientes/apostadores.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nada a fazer após a resposta
    }
}
