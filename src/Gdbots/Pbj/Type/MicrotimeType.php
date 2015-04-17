<?php

namespace Gdbots\Pbj\Type;

use Gdbots\Common\Microtime;
use Gdbots\Pbj\Assertion;
use Gdbots\Pbj\Field;

final class MicrotimeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function guard($value, Field $field)
    {
        /** @var Microtime $value */
        Assertion::isInstanceOf($value, 'Gdbots\Common\Microtime', null, $field->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function encode($value, Field $field)
    {
        if ($value instanceof Microtime) {
            return $value->toString();
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($value, Field $field)
    {
        if ($value instanceof Microtime) {
            return $value;
        }

        if (empty($value)) {
            return null;
        }

        return Microtime::fromString((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isScalar()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault()
    {
        return Microtime::create();
    }

    /**
     * {@inheritdoc}
     */
    public function isNumeric()
    {
        return true;
    }
}
