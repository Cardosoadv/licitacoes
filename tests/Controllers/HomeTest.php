<?php

namespace Tests\Controllers;

use Tests\Support\ControllerTestCase;
use App\Controllers\Home;
use App\Services\PacientesService;
use App\Repositories\AgendamentosRepository;
use App\Repositories\PacientesRepository;
use App\Repositories\FatCobrancaRepository;
use CodeIgniter\Shield\Entities\User;

/**
 * @internal
 */
final class HomeTest extends ControllerTestCase
{
    /**
     * Testa a renderização da página inicial do Dashboard.
     */
    public function testIndex()
    {
        $user = new User(['id' => 1, 'username' => 'testuser']);
        $this->actingAs($user);

        // Mock Renderer to return dummy data for index.php view
        $this->mockRenderer('DashBody 1.5k');

        // Mock do repositório de agendamentos
        $mockAgenda = $this->createMock(AgendamentosRepository::class);
        $mockAgenda->method('getStats')->willReturn(['hoje' => 5, 'pendentes' => 2]);
        $mockAgenda->method('getUpcoming')->willReturn([]);
        $mockAgenda->method('getByDate')->willReturn([]);
        $mockAgenda->method('getServiceStats')->willReturn([]);

        // Mock do repositório de pacientes
        $mockPacientesRepo = $this->getMockBuilder(PacientesRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockModel = $this->getMockBuilder(\App\Models\PacientesModel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockModel->method('countAllResults')->willReturn(10);
        
        $mockPacientesRepo->method('getModel')->willReturn($mockModel);
        $mockPacientesRepo->method('getRecent')->willReturn([]);

        // Mock do repositório de faturamento
        $mockCobranca = $this->createMock(FatCobrancaRepository::class);
        $mockCobranca->method('getTotalMonth')->willReturn(1500.50);

        // Mock do serviço de pacientes
        $mockService = $this->createMock(PacientesService::class);
        $mockService->method('getAtivosCount')->willReturn(10);

        $this->controller = new Home($mockService, $mockAgenda, $mockPacientesRepo, $mockCobranca);
        $this->controller->initController($this->request, $this->response, $this->logger);

        $result = $this->execute('index');

        $this->assertTrue($result->isOK());
        $this->assertStringContainsString('1.5k', $result->response()->getBody());
    }
}
