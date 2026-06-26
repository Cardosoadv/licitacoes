<?php

namespace Tests\Controllers;

use Tests\Support\ControllerTestCase;
use App\Controllers\Prontuarios;
use App\Services\ProntuariosService;
use CodeIgniter\Shield\Entities\User;

/**
 * @internal
 */
final class ProntuariosTest extends ControllerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $user = new User(['id' => 1, 'username' => 'testuser']);
        $this->actingAs($user);
    }

    /**
     * Testa a listagem de pets para seleção de prontuário.
     */
    public function testList()
    {
        $mockService = $this->createMock(ProntuariosService::class);
        $mockService->method('getPetsList')->willReturn([]);

        $this->controller = new Prontuarios($mockService);
        $this->controller->initController($this->request, $this->response, $this->logger);

        $result = $this->execute('list');

        $this->assertTrue($result->isOK());
        $this->assertStringContainsString('Prontuários', $result->response()->getBody());
    }

    /**
     * Testa a visualização do prontuário completo de um pet.
     */
    public function testIndexSuccess()
    {
        $mockService = $this->createMock(ProntuariosService::class);
        $mockService->method('getFullRecord')->willReturn([
            'pet' => ['pet_id' => 1, 'pet_nome' => 'John'],
            'anamnese' => [],
            'evolucao' => [],
            'odontograma' => [],
            'imagens' => []
        ]);

        $this->controller = new Prontuarios($mockService);
        $this->controller->initController($this->request, $this->response, $this->logger);

        $result = $this->execute('index', 1);

        $this->assertTrue($result->isOK());
        $this->assertStringContainsString('John', $result->response()->getBody());
    }

    /**
     * API: Testa o salvamento da anamnese.
     */
    public function testSaveAnamnese()
    {
        $mockService = $this->createMock(ProntuariosService::class);
        $mockService->method('updateAnamnese')->willReturn(['status' => 'success']);

        $this->controller = new Prontuarios($mockService);
        $this->controller->initController($this->request, $this->response, $this->logger);

        $result = $this->execute('saveAnamnese', 1);

        $this->assertTrue($result->isOK());
        $this->assertStringContainsString('success', $result->response()->getBody());
    }
}
