<?php

namespace Gdbots\Messages\Type;

use Gdbots\Messages\Field;

interface Type
{
    /**
     * @return Type
     */
    public static function create();

    /**
     * @param mixed $value
     * @param Field $field
     * @throws \Exception
     */
    public function guard($value, Field $field);

    /**
     * @param mixed $value
     * @param Field $field
     * @return mixed
     */
    public function encode($value, Field $field);

    /**
     * @param mixed $value
     * @param Field $field
     * @return mixed
     */
    public function decode($value, Field $field);

    /**
     * @return mixed
     */
    public function getDefault();

    /**
     * @return bool
     */
    public function isScalar();

    /**
     * @return bool
     */
    public function isNumeric();

    /**
     * @return bool
     */
    public function isString();
}