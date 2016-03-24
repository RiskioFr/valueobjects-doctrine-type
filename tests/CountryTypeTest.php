<?php
namespace Riskio\ValueObjects\DoctrineType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit_Framework_TestCase;
use ValueObjects\Geography\Country;

class CountryTypeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!Type::hasType('country')) {
            Type::addType('country', CountryType::class);
        }
    }

    /**
     * @test
     */
    public function convertToPHPValue_GivenEmptyValue_ShouldReturnNull()
    {
        $type = Type::getType('country');
        $platform = $this->prophesize(AbstractPlatform::class);
        $emptyValue = null;

        $convertedValue = $type->convertToPHPValue($emptyValue, $platform->reveal());

        $this->assertNull($convertedValue);
    }

    /**
     * @test
     */
    public function convertToPHPValue_GivenCountryCodeAsString_ShouldReturnRelatedCountryInstance()
    {
        $type = Type::getType('country');
        $platform = $this->prophesize(AbstractPlatform::class);
        $countryCodeAsString = 'FR';

        $convertedValue = $type->convertToPHPValue($countryCodeAsString, $platform->reveal());

        $this->assertInstanceOf(Country::class, $convertedValue);
        $this->assertSame($countryCodeAsString, $convertedValue->getCode()->toNative());
    }

    /**
     * @test
     */
    public function convertToPHPValue_GivenInvalidCountryCodeAsString_ShouldThrowException()
    {
        $type = Type::getType('country');
        $platform = $this->prophesize(AbstractPlatform::class);
        $countryCodeAsString = 'ZZ';

        $this->setExpectedException(ConversionException::class);
        $type->convertToPHPValue($countryCodeAsString, $platform->reveal());
    }

    /**
     * @test
     */
    public function convertToDatabaseValue_GivenEmptyValue_ShouldReturnNull()
    {
        $type = Type::getType('country');
        $platform = $this->prophesize(AbstractPlatform::class);
        $emptyValue = null;

        $convertedValue = $type->convertToDatabaseValue($emptyValue, $platform->reveal());

        $this->assertNull($convertedValue);
    }

    /**
     * @test
     */
    public function convertToDatabaseValue_GivenCountryInstance_ShouldReturnReturnRelatedCountryCodeAsString()
    {
        $type = Type::getType('country');
        $platform = $this->prophesize(AbstractPlatform::class);
        $countryCodeAsString = 'FR';
        $country = Country::fromNative($countryCodeAsString);

        $convertedValue = $type->convertToDatabaseValue($country, $platform->reveal());

        $this->assertSame($countryCodeAsString, $convertedValue);
    }

    /**
     * @test
     */
    public function convertToDatabaseValue_GivenCountryCodeAsString_ShouldReturnSameCountryCodeAsString()
    {
        $type = Type::getType('country');
        $platform = $this->prophesize(AbstractPlatform::class);
        $countryCodeAsString = 'FR';

        $convertedValue = $type->convertToDatabaseValue($countryCodeAsString, $platform->reveal());

        $this->assertSame($countryCodeAsString, $convertedValue);
    }

    /**
     * @test
     */
    public function convertToDatabaseValue_GivenInvalidCountryCodeAsString_ShouldThrowException()
    {
        $type = Type::getType('country');
        $platform = $this->prophesize(AbstractPlatform::class);
        $countryCodeAsString = 'ZZ';

        $this->setExpectedException(ConversionException::class);
        $type->convertToDatabaseValue($countryCodeAsString, $platform->reveal());
    }
}
