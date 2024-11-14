<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Tests\Unit\ProductVote\DataMapper;

use Generator;
use OxidEsales\ModuleTemplate\ProductVote\DataMapper\ProductVoteDataMapper;
use OxidEsales\ModuleTemplate\ProductVote\DataType\ProductVote;
use OxidEsales\ModuleTemplate\ProductVote\Exception\MapDataTypeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProductVoteDataMapper::class)]
final class ProductVoteDataMapperTest extends TestCase
{
    #[Test]
    #[DataProvider('mapMalformedDataProvider')]
    public function mapMalformedDataThrowsException(array $data): void
    {
        $sut = new ProductVoteDataMapper();

        $this->expectException(MapDataTypeException::class);
        $sut->mapFromDbRow($data);
    }

    public static function mapMalformedDataProvider(): Generator
    {
        yield ['data' => []];
        yield ['data' => [
            'foo' => 'bar'
        ]];
        yield ['data' => [
            'ProductId' => 'test_product_id',
        ]];
    }

    #[Test]
    #[DataProvider('mapDataProvider')]
    public function mapCorrectData(ProductVote $expectedVote, array $data): void
    {
        $sut = new ProductVoteDataMapper();
        $this->assertEquals($expectedVote, $sut->mapFromDbRow($data));
    }

    public static function mapDataProvider(): Generator
    {
        yield [
            'expectedVote' => new ProductVote(
                'test_product_id',
                'test_user_id',
                false
            ),
            'data' => [
                'ProductId' => 'test_product_id',
                'UserId' => 'test_user_id',
                'Vote' => 0,
            ],
        ];

        yield [
            'expectedVote' => new ProductVote(
                'another_product_id',
                'another_user_id',
                true
            ),
            'data' => [
                'ProductId' => 'another_product_id',
                'UserId' => 'another_user_id',
                'Vote' => 1,
            ],
        ];
    }
}
