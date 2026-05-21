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
        if (session()->get('usuario_perfil') !== 'cliente') {
            return redirect()->to('/admin/dashboard')->with('error', 'Acesso restrito a clientes/apostadores.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nada a fazer após a resposta
    }
}
