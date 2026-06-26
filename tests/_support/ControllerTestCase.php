<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Shield\Test\AuthenticationTesting;
use CodeIgniter\Config\Factories;
use Config\Services;

abstract class ControllerTestCase extends CIUnitTestCase
{
    use ControllerTestTrait;
    use AuthenticationTesting;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mocking Settings globally 
        $mockSettings = $this->getMockBuilder(\CodeIgniter\Settings\Settings::class)
            ->disableOriginalConstructor()
            ->getMock();
        Services::injectMock('settings', $mockSettings);

        // Mocking EquipeService globally via Factories
        $mockEquipeService = $this->getMockBuilder(\App\Services\EquipeService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEquipeService->method('getUserNameAndHonorific')->willReturn(['name' => 'User', 'honorific' => 'Dr.']);
        
        Factories::injectMock('service', \App\Services\EquipeService::class, $mockEquipeService);

        $_POST = [];
        $_GET = [];
    }

    /**
     * Mocks the renderer to avoid executing view code/helpers that hit the DB.
     */
    protected function mockRenderer(string $expectedContent = 'MOCKED_VIEW')
    {
        $renderer = $this->getMockBuilder(\CodeIgniter\View\View::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $renderer->method('render')->willReturn($expectedContent);
        $renderer->method('setData')->willReturn($renderer);
        $renderer->method('setVar')->willReturn($renderer);
        
        Services::injectMock('renderer', $renderer);
    }

    /**
     * Helper provide anonymous user session for testing.
     */
    protected function setupUserSession()
    {
        $user = new \CodeIgniter\Shield\Entities\User([
            'id' => 1,
            'username' => 'testuser',
            'email'    => 'test@example.com'
        ]);
        $this->actingAs($user);
        return $user;
    }
}
