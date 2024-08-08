<?php

namespace Unit\App\Service\NBP\ExchangeRate\DTO;

use App\DTO\NBP\ExchangeRates\DTO;
use App\DTO\NBP\ExchangeRates\RequestDTO;
use App\Service\NBP\ExchangeRate\DTO\Factories\FactoryInterface;
use App\Service\NBP\ExchangeRate\DTO\FactoryService;
use DateTime;
use Integration\ExchangeRates\ExchangeRatesTest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FactoryServiceTest extends KernelTestCase
{
    /** @var MockObject|ParameterBagInterface */
    private $parameterBagMock;

    /** @var FactoryInterface|MockObject */
    private $factoryMock;

    public function setUp(): void
    {
        parent::setUp();
        static::bootKernel();

        $this->parameterBagMock = $this->createMock(ParameterBagInterface::class);

        $this->factoryMock = $this->createMock(FactoryInterface::class);
    }

    public function testCreateExchangeRatesDTO(): void
    {
        $this->parameterBagMock
            ->expects($this->once())
            ->method('get')
            ->with('nbp')
            ->willReturn([
                'exchangeRates' => [
                    'supportedCurrencies' => ['USD', 'EUR', 'CZK', 'IDR', 'BRL'],
                    'buyableCurrencies' => ['USD', 'EUR',],
                ]
            ]);

        $factoryServiceMock = $this->getMockedFactoryService([
            $this->factoryMock
        ]);

        $result = $factoryServiceMock->createExchangeRatesDTO(
            json_decode(
                $this->getTestFileContent(ExchangeRatesTest::FILEPATH_EXAMPLE_NBP_API_RESPONSE_SUCCESS),
                true
            ),
            $this->getMockedRequestDTO()
        );

        $this->assertInstanceOf(DTO::class, $result);
    }

    private function getMockedFactoryService(array $factories): FactoryService
    {
        return new FactoryService(
            $this->parameterBagMock,
            $factories
        );
    }

    private function getMockedRequestDTO(): RequestDTO
    {
        return (new RequestDTO())
            ->setDate(DateTime::createFromFormat(RequestDTO::DATE_FORMAT, '2024-08-07'));
    }

    protected function getTestFileContent(string $filepath): string
    {
        return file_get_contents(
            static::$kernel->getProjectDir() . DIRECTORY_SEPARATOR . 'tests/File' . $filepath
        );
    }
}