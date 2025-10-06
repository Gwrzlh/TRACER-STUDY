<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Templates
     * --------------------------------------------------------------------------
     *
     * Pagination links are rendered out using views to configure their
     * appearance. This array contains aliases and the view names to
     * use when rendering the links.
     *
     * Within each view, the Pager object will be available as $pager,
     * and the desired group as $pagerGroup.
     *
     * @var array<string, string>
     */
    public array $templates = [
        // Default bawaan CodeIgniter
        'default_full'   => 'CodeIgniter\Pager\Views\default_full',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'   => 'CodeIgniter\Pager\Views\default_head',

        // Custom milik kamu
        'paginations'       => 'App\Views\layout\paginations',
        'pagination2'       => 'App\Views\layout\pagination2',
        'custom_pagination' => 'App\Views\Pagers\custom_pagination',

        // ✅ Tambahan Bootstrap
        'bootstrap_full' => 'App\Views\Pagers\bootstrap_full',
        'bootstrap5'     => 'App\Views\Pagers\bootstrap5',
        'pagination'     => 'App\Views\Pagers\pagination',
    ];

    /**
     * --------------------------------------------------------------------------
     * Query String Segment
     * --------------------------------------------------------------------------
     *
     * The default query string segment to keep track of pagination.
     */
    public string $queryStringSegment = 'page';

    /**
     * --------------------------------------------------------------------------
     * Retain Query Strings
     * --------------------------------------------------------------------------
     *
     * If TRUE, all query strings will be retained during pagination links.
     */
    public bool $retainQueryStrings = true;

    /**
     * --------------------------------------------------------------------------
     * Items Per Page
     * --------------------------------------------------------------------------
     *
     * The default number of results shown in a single page.
     */
    public int $perPage = 20;
}
