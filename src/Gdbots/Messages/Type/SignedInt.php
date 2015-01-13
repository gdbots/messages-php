<?php

namespace Gdbots\Messages\Type;

use Gdbots\Messages\Assertion;
use Gdbots\Messages\Field;

final class SignedInt extends AbstractInt
{
    /**
     * {@inheritdoc}
     */
    public function guard($value, Field $field)
    {
        Assertion::integer($value, null, $field->getName());
        Assertion::range($value, -2147483648, 2147483647, null, $field->getName());
    }
}