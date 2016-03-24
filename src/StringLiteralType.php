<?php
namespace Riskio\ValueObjects\DoctrineType;

use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;
use ValueObjects\StringLiteral\StringLiteral;

class StringLiteralType extends Type
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'string_literal';
    }

    /**
     * {@inheritdoc}
     *
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getStringLiteralQuoteCharacter();
    }

    /**
     * {@inheritdoc}
     *
     * @param string $value
     * @param AbstractPlatform $platform
     * @return StringLiteral
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        try {
            return StringLiteral::fromNative($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param StringLiteral|string|null $value
     * @param AbstractPlatform $platform
     * @return string|null
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof StringLiteral) {
            return $value->toNative();
        }

        try {
            $string = StringLiteral::fromNative($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $string->toNative();
    }

    /**
     * {@inheritdoc}
     *
     * @param AbstractPlatform $platform
     * @return boolean
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
