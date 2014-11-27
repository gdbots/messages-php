<?php

namespace Gdbots\Messages;

interface Message
{
    /**
     * @return Field[]
     * @throws \LogicException
     */
    public static function fields();

    /**
     * @param string $name
     * @return Field
     * @throws \InvalidArgumentException
     */
    public static function field($name);

    /**
     * Returns a new message from the provided array.  The array
     * should be data returned from toArray or at least match
     * that signature.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data = []);

    /**
     * Returns the message as an associative array.
     *
     * @return array
     */
    public function toArray();
}