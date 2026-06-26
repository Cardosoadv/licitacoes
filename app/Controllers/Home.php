<?php

namespace App\Controllers;

use App\Services\PetsService;

/**
 * Controller responsável pela página inicial e dashboard do sistema.
 * Centraliza os principais KPIs e indicadores de atendimento.
 */
class Home extends BaseController
{
    /**
     * @var PetsService Serviço de gerenciamento de pets
     */
    protected PetsService $petsService;

    /**
     * @var \App\Repositories\AgendamentosRepository Repositório de agendamentos
     */
    protected \App\Repositories\AgendamentosRepository $agendamentosRepo;

    /**
     * @var \App\Repositories\PetsRepository Repositório de pets
     */
    protected \App\Repositories\PetsRepository $petsRepo;

    /**
     * @var \App\Repositories\FatCobrancaRepository Repositório de faturamento e cobranças
     */
    protected \App\Repositories\FatCobrancaRepository $cobrancaRepo;

    /**
     * Injeta as dependências necessárias para o dashboard.
     */
    public function __construct(
        ?PetsService $petsService = null,
        ?\App\Repositories\AgendamentosRepository $agendamentosRepo = null,
        ?\App\Repositories\PetsRepository $petsRepo = null,
        ?\App\Repositories\FatCobrancaRepository $cobrancaRepo = null
    ) {
        $this->petsService = $petsService ?? new PetsService();
        $this->agendamentosRepo = $agendamentosRepo ?? new \App\Repositories\AgendamentosRepository();
        $this->petsRepo = $petsRepo ?? new \App\Repositories\PetsRepository();
        $this->cobrancaRepo = $cobrancaRepo ?? new \App\Repositories\FatCobrancaRepository();
    }

    /**
     * Renderiza o Dashboard principal com estatísticas e próximos agendamentos.
     * 
     * @return string HTML da view 'index'
     */
    public function index(): string
    {
        $month = date('m');
        $year = date('Y');

        $agendaStats = $this->agendamentosRepo->getStats();
        
        $data = [
            'kpis' => [
                'hoje'      => $agendaStats['hoje'],
                'ativos'    => $this->petsRepo->getModel()->countAllResults(),
                'receita'   => $this->cobrancaRepo->getTotalMonth($month, $year),
                'pendentes' => $agendaStats['pendentes']
            ],
            'upcoming' => $this->agendamentosRepo->getUpcoming(168), // 7 dias
            'hoje'     => $this->agendamentosRepo->getByDate(date('Y-m-d')),
            'servicos' => $this->agendamentosRepo->getServiceStats($month, $year),
            'recentes' => $this->petsRepo->getRecent(3)
        ];

        return view('index', $data);
    }


}
