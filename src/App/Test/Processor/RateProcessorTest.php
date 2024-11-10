<?php

namespace App\Test\Processor;

use App\Config\RatesConfigProvider;
use App\Processor\RateProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RateProcessorTest extends TestCase
{
    public function testHappyPath()
    {
        $ratesConfigProvider = $this->createMock(RatesConfigProvider::class);

        $ratesConfigProvider
            ->method('getRelativeRates')
            ->willReturn([
                    [
                        'currencies' => ['EUR', 'USD'],
                        'buy' => -0.05,
                        'sell' => 0.07,
                    ],
                    [
                        'currencies' => ['CZK', 'IDR', 'BRL'],
                        'buy' => null,
                        'sell' => 0.15,
                    ]
            ]);

        $rateProcessor = new RateProcessor($ratesConfigProvider);

        $returned = $rateProcessor->execute([
            'code' => 'EUR',
            'mid' => 1.0
        ]);

        self::assertEquals($returned->getBuy(), 0.95);
        self::assertEquals($returned->getSell(), 1.07);
    }

    public function testNotFoundHandling()
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        $response = $framework->handle(new Request());

        $this->assertEquals(404, $response->getStatusCode());
    }

    private function getFrameworkForException($exception)
    {
        $matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);
        // use getMock() on PHPUnit 5.3 or below
        // $matcher = $this->getMock(Routing\Matcher\UrlMatcherInterface::class);

        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception))
        ;
        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->createMock(Routing\RequestContext::class)))
        ;
        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

        return new Framework($matcher, $controllerResolver, $argumentResolver);
    }
}