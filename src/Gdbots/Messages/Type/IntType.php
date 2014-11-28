<?php

namespace Gdbots\Messages\Type;

use Gdbots\Messages\Field;

final class IntType extends AbstractIntType
{
    /**
     * @see Type::guard
     */
    public function guard($value, Field $field)
    {
        \Assert\that($value, null, $field->getName())
            ->integer()
            ->range(0, 4294967295);
    }
}