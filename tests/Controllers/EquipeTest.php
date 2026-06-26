<?php

namespace Tests\Controllers;

use Tests\Support\ControllerTestCase;
use App\Controllers\Equipe;
use App\Services\EquipeService;

/**
 * @internal
 */
final class EquipeTest extends ControllerTestCase
{
    public function testGetAll()
    {
        $mockService = $this->createMock(EquipeService::class);
        $mockService->method('getAll')->willReturn([['equ_id' => 1, 'equ_nome' => 'Dr. Smith']]);

        $this->controller = new Equipe($mockService);
        $this->controller->initController($this->request, $this->response, $this->logger);

        $result = $this->execute('getAll');

        $this->assertTrue($result->isOK());
    }
}
