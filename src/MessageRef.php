<?php

namespace Gdbots\Pbj;

use Gdbots\Common\FromArray;
use Gdbots\Common\ToArray;
use Gdbots\Pbj\Exception\InvalidArgumentException;
use Gdbots\Pbj\Exception\LogicException;

/**
 * Represents a reference to a message.  Typically used to link messages
 * together via a correlator or "links".  Format for a reference:
 * vendor:package:category:message:id#tag (tag is optional)
 */
final class MessageRef implements FromArray, ToArray, \JsonSerializable
{
    /** @var SchemaCurie */
    private $curie;

    /**
     * Any string matching pattern /^[\w\/\.:-]+$/
     * @var string
     */
    private $id;

    /** @var string */
    private $tag;

    /**
     * todo: review random failures from YamlSerializer on $id when no tag is present.
     *
     * @param SchemaCurie $curie
     * @param string $id
     * @param string $tag The tag will be automatically fixed to a slug-formatted-string.
     * @throws \Exception
     */
    public function __construct(SchemaCurie $curie, $id, $tag = null)
    {
        $this->curie = $curie;
        $this->id = trim((string) $id) ?: 'null';
        Assertion::regex($this->id, '/^[\w\/\.:-]+$/', null, 'MessageRef.id');

        if (null !== $tag) {
            $this->tag = strtolower(preg_replace('/[^\w\.-]/', '-', $tag)) ?: null;
        }

        if ($this->curie->isMixin()) {
            throw new LogicException('Mixins cannot be used in a MessageRef.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $data = [])
    {
        if (isset($data['curie'])) {
            $id = isset($data['id']) ? $data['id'] : 'null';
            $tag = isset($data['tag']) ? $data['tag'] : null;
            return new self(SchemaCurie::fromString($data['curie']), $id, $tag);
        }
        throw new InvalidArgumentException('Payload must be a MessageRef type.');
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        if (null !== $this->tag) {
            return ['curie' => $this->curie->toString(), 'id' => $this->id, 'tag' => $this->tag];
        }
        return ['curie' => $this->curie->toString(), 'id' => $this->id];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @param string $string A string with format curie:id#tag
     * @return self
     */
    public static function fromString($string)
    {
        list($ref, $tag) = explode('#', $string, 2);
        $parts = explode(':', $ref, 5);
        $id = array_pop($parts);
        $curie = SchemaCurie::fromString(implode(':', $parts));
        return new self($curie, $id, $tag);
    }

    /**
     * @return string
     */
    public function toString()
    {
        if (null !== $this->tag) {
            return $this->curie->toString() . ':' . $this->id . '#' . $this->tag;
        }
        return $this->curie->toString() . ':' . $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return SchemaCurie
     */
    public function getCurie()
    {
        return $this->curie;
    }

    /**
     * @return bool
     */
    public function hasId()
    {
        return 'null' != $this->id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function hasTag()
    {
        return null !== $this->tag;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param MessageRef $other
     * @return bool
     */
    public function equals(MessageRef $other)
    {
        return $this->toString() === $other->toString();
    }
}
