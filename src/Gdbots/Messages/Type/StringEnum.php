<?php

namespace Gdbots\Messages\Type;

use Gdbots\Common\Enum;
use Gdbots\Messages\Assertion;
use Gdbots\Messages\Field;

final class StringEnum extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function guard($value, Field $field)
    {
        /** @var Enum $value */
        Assertion::isInstanceOf($value, $field->getClassName(), null, $field->getName());
        Assertion::string($value->getValue(), null, $field->getName());
        Assertion::betweenLength($value->getValue(), 1, 100, null, $field->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function encode($value, Field $field)
    {
        if ($value instanceof Enum) {
            return (string) $value->getValue();
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($value, Field $field)
    {
        /** @var Enum $className */
        $className = $field->getClassName();
        if (empty($value)) {
            return $field->getDefault();
        }
        return $className::create((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isString()
    {
        return true;
    }
}