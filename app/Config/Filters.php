<?php

namespace Config;

use App\Filters\AdminFilter;
use App\Filters\AuthFilter;
use App\Filters\ClienteFilter;
use App\Filters\GuestFilter;
use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Aliases de filtros customizados e nativos do CI4.
     *
     * @var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'auth'          => AuthFilter::class,
        'admin'         => AdminFilter::class,
        'cliente'       => ClienteFilter::class,
        'guest'         => GuestFilter::class,
    ];

    /**
     * Filtros obrigatórios do framework (não remover).
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    /**
     * Filtros globais aplicados em TODA requisição.
     *
     * - CSRF: Protege todos os formulários POST contra ataques de falsificação.
     * - SecureHeaders: Adiciona headers de segurança (X-Frame-Options, X-XSS-Protection, etc.)
     * - InvalidChars: Bloqueia caracteres inválidos/maliciosos na requisição.
     *
     * @var array{
     *     before: array<string, array{except: list<string>|string}>|list<string>,
     *     after: array<string, array{except: list<string>|string}>|list<string>
     * }
     */
    public array $globals = [
        'before' => [
            'cors',
            'invalidchars',
        ],
        'after' => [
            'secureheaders',
        ],
    ];

    /**
     * Filtros por método HTTP.
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * Filtros aplicados por padrão de URL.
     *
     * - 'guest': Redireciona usuários já logados para fora do login/cadastro.
     * - 'auth': Exige login para acessar rotas protegidas.
     * - 'admin': Restringe rotas /admin/* ao perfil administrador.
     * - 'cliente': Restringe rotas /cliente/* ao perfil cliente/apostador.
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [
        'guest'   => ['before' => ['auth/login', 'auth/register']],
        'auth'    => ['before' => ['admin/*', 'admin', 'cliente/*', 'cliente', 'apostas/*', 'apostas']],
        'admin'   => ['before' => ['admin/*', 'admin']],
        'cliente' => ['before' => ['cliente/*', 'cliente', 'apostas/*', 'apostas']],
    ];
}
