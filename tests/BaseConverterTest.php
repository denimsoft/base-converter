<?php

namespace Denimsoft\Test\Stdlib\BaseConverter;

use Denimsoft\Stdlib\BaseConverter\BaseConverter;
use PHPUnit\Framework\TestCase;

class BaseConverterTest extends TestCase
{
    /**
     * @covers BaseConverter::encode
     * @dataProvider baseProvider
     */
    public function testEncode($value, $expected, $outputBase, $inputBase)
    {
        $baseConverter = new BaseConverter($outputBase, $inputBase);
        $actual = $baseConverter->encode($value);
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers BaseConverter::decode
     * @dataProvider baseProvider
     */
    public function testDecode($expected, $value, $outputBase, $inputBase)
    {
        $baseConverter = new BaseConverter($outputBase, $inputBase);
        $actual = $baseConverter->decode($value);
        $this->assertSame($expected, $actual);
    }

    public function baseProvider()
    {
        return [
            'base16 signed 32-bit' => ['2147483647', '7FFFFFFF', 16, 10],
            'base16 unsigned 32-bit' => ['4294967295', 'FFFFFFFF', 16, 10],
            'base16 signed 64-bit' => ['9223372036854775807', '7FFFFFFFFFFFFFFF', 16, 10],
            'base16 unsigned 64-bit' => ['18446744073709551615', 'FFFFFFFFFFFFFFFF', 16, 10],
            'base36 signed 32-bit' => ['2147483647', 'ZIK0ZJ', 36, 10],
            'base36 unsigned 32-bit' => ['4294967295', '1Z141Z3', 36, 10],
            'base36 signed 64-bit' => ['9223372036854775807', '1Y2P0IJ32E8E7', 36, 10],
            'base36 unsigned 64-bit' => ['18446744073709551615', '3W5E11264SGSF', 36, 10],
            'base62 signed 32-bit' => ['2147483647', '2LKcb1', 62, 10],
            'base62 unsigned 32-bit' => ['4294967295', '4gfFC3', 62, 10],
            'base62 signed 64-bit' => ['9223372036854775807', 'AzL8n0Y58m7', 62, 10],
            'base62 unsigned 64-bit' => ['18446744073709551615', 'LygHa16AHYF', 62, 10],
        ];
    }
}
