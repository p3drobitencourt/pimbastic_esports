<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Redireciona usuários já logados para fora das páginas de login/cadastro.
 * Evita que um usuário autenticado veja a tela de login novamente.
 */
class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('logado') || session()->get('logged_in')) {
            $perfil = (string) (session()->get('perfil') ?? session()->get('usuario_perfil'));
            $perfil = strtolower($perfil);

            if ($perfil === 'admin') {
                return redirect()->to('/admin/dashboard');
            }

            return redirect()->to('/cliente/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nada a fazer
    }
}
