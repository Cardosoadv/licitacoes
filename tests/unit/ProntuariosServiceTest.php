<?php

namespace Tests\Unit;

use App\Services\ProntuariosService;
use App\Repositories\PetsRepository;
use App\Repositories\OdontogramasRepository;
use App\Repositories\EvolucoesRepository;
use App\Repositories\ImagensRepository;
use App\Repositories\AgendamentosRepository;
use App\Repositories\FatCobrancaRepository;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ProntuariosServiceTest extends CIUnitTestCase
{
    public function testCanBeInstantiatedWithMocks(): void
    {
        $petsRepo = $this->createMock(PetsRepository::class);
        $odontogramasRepo = $this->createMock(OdontogramasRepository::class);
        $evolucoesRepo = $this->createMock(EvolucoesRepository::class);
        $imagensRepo = $this->createMock(ImagensRepository::class);
        $agendamentosRepo = $this->createMock(AgendamentosRepository::class);
        $fatCobrancaRepo = $this->createMock(FatCobrancaRepository::class);

        $service = new ProntuariosService(
            $petsRepo,
            $odontogramasRepo,
            $evolucoesRepo,
            $imagensRepo,
            $agendamentosRepo,
            $fatCobrancaRepo
        );

        $this->assertInstanceOf(ProntuariosService::class, $service);
    }

    public function testGetFullRecordUsesMockedRepo(): void
    {
        $petsRepo = $this->createMock(PetsRepository::class);
        $odontogramasRepo = $this->createMock(OdontogramasRepository::class);
        $evolucoesRepo = $this->createMock(EvolucoesRepository::class);
        $imagensRepo = $this->createMock(ImagensRepository::class);
        $agendamentosRepo = $this->createMock(AgendamentosRepository::class);
        $fatCobrancaRepo = $this->createMock(FatCobrancaRepository::class);

        $petId = 123;
        $petData = ['pet_id' => $petId, 'pet_nome' => 'Teste'];

        $petsRepo->expects($this->once())
            ->method('findById')
            ->with($petId)
            ->willReturn($petData);

        $odontogramasRepo->method('findLatestByPet')->willReturn([]);
        $evolucoesRepo->method('findByPet')->willReturn([]);
        $imagensRepo->method('findByPet')->willReturn([]);

        // Mocking the model because getCombinedHistory uses $repo->getModel()
        $mockModel = $this->getMockBuilder(\CodeIgniter\Model::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first', 'countAllResults', 'findAll'])
            ->addMethods(['where', 'orderBy', 'selectSum'])
            ->getMock();

        $agendamentosRepo->method('getModel')->willReturn($mockModel);
        $fatCobrancaRepo->method('getModel')->willReturn($mockModel);
        $imagensRepo->method('getModel')->willReturn($mockModel);

        $mockModel->method('where')->willReturnSelf();
        $mockModel->method('orderBy')->willReturnSelf();
        $mockModel->method('findAll')->willReturn([]);
        $mockModel->method('countAllResults')->willReturn(0);
        $mockModel->method('selectSum')->willReturnSelf();
        $mockModel->method('first')->willReturn(['valor' => 0]);

        $service = new ProntuariosService(
            $petsRepo,
            $odontogramasRepo,
            $evolucoesRepo,
            $imagensRepo,
            $agendamentosRepo,
            $fatCobrancaRepo
        );

        $result = $service->getFullRecord($petId);

        $this->assertEquals($petData, $result['pet']);
    }
}
