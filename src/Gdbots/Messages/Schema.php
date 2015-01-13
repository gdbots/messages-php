<?php

namespace Gdbots\Messages;

use Gdbots\Messages\Exception\FieldAlreadyDefinedException;
use Gdbots\Messages\Exception\FieldNotDefinedException;

// todo: implement toArray and JsonSerializable
final class Schema
{
    /** @var string */
    private $className;

    /** @var SchemaVersion */
    private $version;

    /** @var Field[] */
    private $fields = [];

    /**
     * @param string $className
     * @param SchemaVersion $version
     */
    private function __construct($className, SchemaVersion $version)
    {
        $this->className = $className;
        $this->version = $version;
    }

    /**
     * @param string $className
     * @param string $version
     * @param Field[] $fields
     * @return Schema
     */
    public static function create($className, $version = '1-0-0', array $fields = [])
    {
        $version = SchemaVersion::fromString($version);
        Assertion::string($className, null, 'className');
        Assertion::allIsInstanceOf($fields, 'Gdbots\Messages\Field', null, 'fields');

        $schema = new self($className, $version);
        foreach ($fields as $field) {
            $schema->addField($field);
        }
    }

    /**
     * @param Field $field
     * @throws FieldAlreadyDefinedException
     */
    private function addField(Field $field)
    {
        if ($this->hasField($field->getName())) {
            throw new FieldAlreadyDefinedException($this, $field->getName());
        }
        $this->fields[$field->getName()] = $field;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return SchemaVersion
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $fieldName
     * @return bool
     */
    public function hasField($fieldName)
    {
        return isset($this->fields[$fieldName]);
    }

    /**
     * @param string $fieldName
     * @return Field
     * @throws FieldNotDefinedException
     */
    public function getField($fieldName)
    {
        if (!isset($this->fields[$fieldName])) {
            throw new FieldNotDefinedException($this, $fieldName);
        }
        return $this->fields[$fieldName];
    }
}