<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ClienteFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $perfil = (string) (session()->get('perfil') ?? session()->get('usuario_perfil'));
        $perfil = strtolower($perfil);

        if ($perfil !== 'cliente') {
            return \Config\Services::response()
                ->setJSON(['status' => 403, 'error' => 'Acesso restrito a clientes/apostadores.'])
                ->setStatusCode(403);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}

