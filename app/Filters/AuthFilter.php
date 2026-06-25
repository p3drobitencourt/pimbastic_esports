<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');
        
        if (empty($header) || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return \Config\Services::response()
                ->setJSON(['status' => 401, 'error' => 'Token não fornecido ou inválido'])
                ->setStatusCode(401);
        }

        $token = $matches[1];
        $parts = explode('.', $token);

        if (count($parts) !== 2) {
            return \Config\Services::response()
                ->setJSON(['status' => 401, 'error' => 'Token malformado'])
                ->setStatusCode(401);
        }

        $payloadBase64 = $parts[0];
        $signature = $parts[1];

        $expectedSignature = hash_hmac('sha256', $payloadBase64, getenv('JWT_SECRET') ?: 'pimbastic_secret_key');

        if (!hash_equals($expectedSignature, $signature)) {
            return \Config\Services::response()
                ->setJSON(['status' => 401, 'error' => 'Assinatura do token inválida'])
                ->setStatusCode(401);
        }

        $payload = json_decode(base64_decode($payloadBase64), true);

        if (!$payload || !isset($payload['exp']) || time() > $payload['exp']) {
            return \Config\Services::response()
                ->setJSON(['status' => 401, 'error' => 'Token expirado'])
                ->setStatusCode(401);
        }

        // Injeta na sessão para retrocompatibilidade com os controllers
        session()->set([
            'logado' => true,
            'logged_in' => true,
            'usuario_id' => $payload['id'],
            'usuario_nome' => $payload['nome'],
            'usuario_email' => $payload['email'] ?? '',
            'usuario_perfil' => $payload['perfil'],
            'perfil' => $payload['perfil'],
            'cliente_id' => $payload['cliente_id'] ?? null,
        ]);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}

