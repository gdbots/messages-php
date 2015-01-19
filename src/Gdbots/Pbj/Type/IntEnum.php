<?php

namespace Gdbots\Pbj\Type;

use Gdbots\Common\Enum;
use Gdbots\Pbj\Assertion;
use Gdbots\Pbj\Field;

final class IntEnum extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function guard($value, Field $field)
    {
        /** @var Enum $value */
        Assertion::isInstanceOf($value, $field->getClassName(), null, $field->getName());
        Assertion::integer($value->getValue(), null, $field->getName());
        Assertion::range($value->getValue(), 0, 65535, null, $field->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function encode($value, Field $field)
    {
        if ($value instanceof Enum) {
            return (int) $value->getValue();
        }
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($value, Field $field)
    {
        /** @var Enum $className */
        $className = $field->getClassName();
        if (null === $value) {
            return $field->getDefault();
        }
        return $className::create((int) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function decodesToScalar()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isNumeric()
    {
        return true;
    }
}
