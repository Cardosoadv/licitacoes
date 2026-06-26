<?php

namespace App\Services;

use App\Repositories\OrgaoRepository;

class OrgaoService
{
    protected OrgaoRepository $orgaoRepo;

    public function __construct(?OrgaoRepository $orgaoRepo = null)
    {
        $this->orgaoRepo = $orgaoRepo ?? new OrgaoRepository();
    }
}
