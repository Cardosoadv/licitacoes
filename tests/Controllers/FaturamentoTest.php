<?php

namespace Tests\Controllers;

use Tests\Support\ControllerTestCase;
use App\Controllers\Faturamento;
use App\Services\FatService;

/**
 * @internal
 */
final class FaturamentoTest extends ControllerTestCase
{
    public function testCreateNota()
    {
        $this->setupUserSession();
        $this->mockRenderer('success');

        $mockService = $this->createMock(FatService::class);
        $mockService->method('createNota')->willReturn(['status' => 'success', 'id' => 42]);

        $this->controller = new Faturamento($mockService);
        $this->controller->initController($this->request, $this->response, $this->logger);

        $result = $this->execute('createNota');

        $this->assertTrue($result->isOK());
    }
}
