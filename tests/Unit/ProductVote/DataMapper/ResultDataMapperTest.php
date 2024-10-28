<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Tests\Unit\ProductVote\DataMapper;

use Generator;
use OxidEsales\ModuleTemplate\ProductVote\DataMapper\ResultDataMapper;
use OxidEsales\ModuleTemplate\ProductVote\DataType\Result;
use OxidEsales\ModuleTemplate\ProductVote\Exception\MapDataTypeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResultDataMapper::class)]
final class ResultDataMapperTest extends TestCase
{
    #[Test]
    #[DataProvider('mapMalformedDataProvider')]
    public function mapMalformedData(array $data): void
    {
        $sut = new ResultDataMapper();

        $this->expectException(MapDataTypeException::class);
        $sut->map($data);
    }

    public static function mapMalformedDataProvider(): Generator
    {
        yield ['data' => []];
        yield ['data' => [
            'foo' => 'bar'
        ]];
        yield ['data' => [
            'Up' => 1,
            'Down' => 2,
        ]];
    }

    #[Test]
    #[DataProvider('mapDataProvider')]
    public function mapData(Result $expectedResult, array $data): void
    {
        $sut = new ResultDataMapper();
        $this->assertEquals($expectedResult, $sut->map($data));
    }

    public static function mapDataProvider(): Generator
    {
        yield [
            'expectedResult' => new Result(
                'test_product_id',
                2,
                3,
            ),
            'data' => [
                'ProductId' => 'test_product_id',
                'VoteUp' => '2',
                'VoteDown' => '3',
            ],
        ];

        yield [
            'expectedResult' => new Result(
                'another_product_id',
                100,
                0,
            ),
            'data' => [
                'ProductId' => 'another_product_id',
                'VoteUp' => '100',
                'VoteDown' => '0',
            ],
        ];
    }
}
