<?php

namespace Tests\Controllers;

use Tests\Support\ControllerTestCase;
use App\Controllers\Agenda;
use App\Services\AgendaService;
use CodeIgniter\Shield\Entities\User;

/**
 * @internal
 */
final class AgendaTest extends ControllerTestCase
{
    /**
     * Testa a exibição da página de agendamentos.
     */
    public function testIndex()
    {
        $user = new User(['id' => 1, 'username' => 'testuser']);
        $this->actingAs($user);
        
        $this->mockRenderer('agendamentos dashboard');

        $mockService = $this->createMock(AgendaService::class);
        $this->controller = new Agenda($mockService);
        $this->controller->initController($this->request, $this->response, $this->logger);

        $result = $this->execute('index');

        $this->assertTrue($result->isOK());
        $this->assertStringContainsString('agendamentos', $result->response()->getBody());
    }

    /**
     * API: Testa a obtenção de dados de um dia específico.
     */
    public function testGetDayData()
    {
        $mockService = $this->createMock(AgendaService::class);
        $mockService->method('getDayData')->willReturn(['appointments' => [], 'stats' => []]);

        $this->controller = new Agenda($mockService);
        $this->controller->initController($this->request, $this->response, $this->logger);

        $this->request->setGlobal('get', ['date' => '2024-03-31']);
        $result = $this->execute('getDayData');

        $this->assertTrue($result->isOK());
        $this->assertJson($result->response()->getBody());
    }
}
