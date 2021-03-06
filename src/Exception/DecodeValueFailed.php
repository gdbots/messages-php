<?php
declare(strict_types=1);

namespace Gdbots\Pbj\Exception;

use Gdbots\Pbj\Field;
use Gdbots\Pbj\Util\StringUtil;

final class DecodeValueFailed extends \InvalidArgumentException implements GdbotsPbjException
{
    private $value;
    private Field $field;

    public function __construct($value, Field $field, ?string $message = null)
    {
        $this->value = $value;
        $this->field = $field;
        $message = sprintf(
            'Failed to decode [%s] for field [%s] to a [%s].  Detail: %s',
            is_scalar($this->value) ? $this->value : StringUtil::varToString($this->value),
            $this->field->getName(),
            $this->field->getType()->getTypeValue(),
            $message
        );
        parent::__construct($message);
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getField(): Field
    {
        return $this->field;
    }

    public function getFieldName(): string
    {
        return $this->field->getName();
    }
}

