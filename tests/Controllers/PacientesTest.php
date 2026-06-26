<?php

namespace Tests\Controllers;

use Tests\Support\ControllerTestCase;
use App\Controllers\Pacientes;
use App\Services\PacientesService;
use CodeIgniter\Shield\Entities\User;

/**
 * @internal
 */
final class PacientesTest extends ControllerTestCase
{
    public function testIndex()
    {
        $this->setupUserSession();
        $this->mockRenderer('Pacientes');

        $mockService = $this->createMock(PacientesService::class);
        $mockService->method('getAllWithLastAppointment')->willReturn([]);

        $this->controller = new Pacientes($mockService);
        $this->controller->initController($this->request, $this->response, $this->logger);

        $result = $this->execute('index');

        $this->assertTrue($result->isOK());
        $this->assertStringContainsString('Pacientes', $result->response()->getBody());
    }

    public function testSearch()
    {
        $mockService = $this->createMock(PacientesService::class);
        $mockService->method('search')->willReturn([['pat_id' => 1, 'pat_nome' => 'John Doe']]);

        $this->controller = new Pacientes($mockService);
        $this->controller->initController($this->request, $this->response, $this->logger);
        $this->request->setGlobal('get', ['term' => 'john']);

        $result = $this->execute('search');

        $this->assertTrue($result->isOK());
        $this->assertJson($result->response()->getBody());
    }
}
