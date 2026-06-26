<?php

namespace Tests\Controllers;

use Tests\Support\ControllerTestCase;
use App\Controllers\Servicos;
use App\Services\ServicosService;

/**
 * @internal
 */
final class ServicosTest extends ControllerTestCase
{
    public function testIndex()
    {
        $this->setupUserSession();
        $this->mockRenderer('serviços');

        $mockService = $this->createMock(ServicosService::class);
        $this->controller = new Servicos($mockService);
        $this->controller->initController($this->request, $this->response, $this->logger);

        $result = $this->execute('index');

        $this->assertTrue($result->isOK());
        $this->assertStringContainsString('serviços', $result->response()->getBody());
    }
}
