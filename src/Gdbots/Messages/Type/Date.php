<?php

namespace Gdbots\Messages\Type;

use Gdbots\Messages\Assertion;
use Gdbots\Messages\Field;

final class Date extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function guard($value, Field $field)
    {
        /** @var \DateTime $value */
        Assertion::isInstanceOf($value, 'DateTime', null, $field->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function encode($value, Field $field)
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($value, Field $field)
    {
        if ($value instanceof \DateTime) {
            return $value;
        }
        return \DateTime::createFromFormat('!Y-m-d', $value) ?: null;
    }

    /**
     * @see Type::isScalar
     */
    public function isScalar()
    {
        return false;
    }
}
