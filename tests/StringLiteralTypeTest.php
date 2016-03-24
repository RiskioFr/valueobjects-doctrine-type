<?php
namespace Riskio\ValueObjects\DoctrineType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit_Framework_TestCase;
use ValueObjects\StringLiteral\StringLiteral;

class StringLiteralTypeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!Type::hasType('string_literal')) {
            Type::addType('string_literal', StringLiteralType::class);
        }
    }

    /**
     * @test
     */
    public function convertToPHPValue_GivenAnyString_ShouldReturnStringLiteralInstance()
    {
        $type = Type::getType('string_literal');
        $platform = $this->prophesize(AbstractPlatform::class);
        $stringValue = 'foo';

        $convertedValue = $type->convertToPHPValue($stringValue, $platform->reveal());

        $this->assertInstanceOf(StringLiteral::class, $convertedValue);
        $this->assertSame($stringValue, $convertedValue->toNative());
    }

    /**
     * @test
     */
    public function convertToPHPValue_GivenValueWithoutStringType_ShouldThrowException()
    {
        $type = Type::getType('string_literal');
        $platform = $this->prophesize(AbstractPlatform::class);
        $invalidStringValue = 123;

        $this->setExpectedException(ConversionException::class);
        $type->convertToPHPValue($invalidStringValue, $platform->reveal());
    }

    /**
     * @test
     */
    public function convertToDatabaseValue_GivenEmptyValue_ShouldReturnNull()
    {
        $type = Type::getType('string_literal');
        $platform = $this->prophesize(AbstractPlatform::class);
        $emptyValue = null;

        $convertedValue = $type->convertToDatabaseValue($emptyValue, $platform->reveal());

        $this->assertNull($convertedValue);
    }

    /**
     * @test
     */
    public function convertToDatabaseValue_GivenStringLiteralInstance_ShouldReturnRelatedStringValue()
    {
        $type = Type::getType('string_literal');
        $platform = $this->prophesize(AbstractPlatform::class);
        $stringValue = 'foo';
        $stringLiteral = StringLiteral::fromNative($stringValue);

        $convertedValue = $type->convertToDatabaseValue($stringLiteral, $platform->reveal());

        $this->assertSame($stringValue, $convertedValue);
    }

    /**
     * @test
     */
    public function convertToDatabaseValue_GivenAnyString_ShouldReturnSameStringValue()
    {
        $type = Type::getType('string_literal');
        $platform = $this->prophesize(AbstractPlatform::class);
        $stringValue = 'foo';

        $convertedValue = $type->convertToDatabaseValue($stringValue, $platform->reveal());

        $this->assertSame($stringValue, $convertedValue);
    }

    /**
     * @test
     */
    public function convertToDatabaseValue_GivenInvalidStringLiteralCodeAsString_ShouldThrowException()
    {
        $type = Type::getType('string_literal');
        $platform = $this->prophesize(AbstractPlatform::class);
        $invalidStringValue = 123;

        $this->setExpectedException(ConversionException::class);
        $type->convertToDatabaseValue($invalidStringValue, $platform->reveal());
    }
}
