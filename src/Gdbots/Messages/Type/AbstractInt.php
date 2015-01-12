<?php

namespace Gdbots\Messages\Type;

use Gdbots\Messages\Field;

abstract class AbstractInt extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function encode($value, Field $field)
    {
        return (int) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($value, Field $field)
    {
        return (int) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function isNumeric()
    {
        return true;
    }
}