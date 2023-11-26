<?php

namespace Unit\Helpers;

use App\Helpers\ResponseFormat;
use PHPUnit\Framework\TestCase;

class ResponseFormatTest extends TestCase
{
    /**
     * Test if the responseError method returns correct data format with correct values.
     */
    public function testResponseFormatError(): void
    {
        $testValue = json_decode(ResponseFormat::responseError(400,'test')->getContent(), true);
        $expectedItem = [
            'error' => [
                'code' => 400,
                'message' => 'test',
                'details' => null
            ]
            ];

        $this->assertEquals($expectedItem,$testValue);
    }

}