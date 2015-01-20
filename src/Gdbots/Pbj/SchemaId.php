<?php

namespace Gdbots\Pbj;
use Gdbots\Pbj\Exception\InvalidSchemaIdException;

/**
 * Schemas have fully qualified names, similar to a "urn".  This is combination of ideas from:
 *
 * Amazon Resource Names (ARNs) and AWS Service Namespaces
 * @link http://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html
 *
 * SnowPlow Analytics (Iglu)
 * @link http://snowplowanalytics.com/blog/2014/07/01/iglu-schema-repository-released/
 *
 * @link http://en.wikipedia.org/wiki/CURIE
 *
 * And of course the various package managers like composer, npm, etc.
 *
 * Schema Id Format:
 *  vendor:package:category:message:version
 *
 * Formats:
 *  VENDOR:   [a-z0-9-]+
 *  PACKAGE:  [a-z0-9\.-]+
 *  CATEGORY: ([a-z0-9-]+)? (clarifies the intent of the message, e.g. command, request, event, response, etc.)
 *  MESSAGE:  [a-z0-9-]+
 *  VERSION:  @see SchemaVersion::VALID_PATTERN
 *
 * Examples of fully qualified schema ids:
 *  acme:videos:event:video-uploaded:1-0-0.0
 *  acme:users:command:register-user:1-1-0.2
 *  acme:api.videos:request:get-video:1-0-0.0
 *
 * The fully qualified schema identifier corresponds to a json schema implementing
 * the Gdbots PBJ Json Schema.
 *
 * The schema id must be resolveable to a php class that should be able to read and write
 * messages with payloads that validate using the json schema.  The target class is ideally
 * model revision specific.  As in GetVideoV1, GetVideoV2, etc.  Only "model" revisions
 * should require a unique class since all other schema changes should not break anything.
 *
 * @see SchemaVersion
 *
 */

final class SchemaId implements \JsonSerializable
{
    /**
     * Regular expression pattern for matching a valid SchemaId string.
     * @constant string
     */
    const VALID_PATTERN = '/^([a-z0-9-]+):([a-z0-9\.-]+):([a-z0-9-]+)?:([a-z0-9-]+):([0-9]+-[0-9]+-[0-9]+\.[0-9]+)$/';

    private static $instances = [];

    /** @var string */
    private $id;

    /** @var string */
    private $vendor;

    /** @var string */
    private $package;

    /** @var string */
    private $category;

    /** @var string */
    private $message;

    /** @var SchemaVersion */
    private $version;

    /**
     * @param string $vendor
     * @param string $package
     * @param string $category
     * @param string $message
     * @param string $version
     */
    private function __construct($vendor, $package, $category, $message, $version)
    {
        $this->vendor = $vendor;
        $this->package = $package;
        $this->category = $category ?: null;
        $this->message = $message;
        $this->version = SchemaVersion::fromString($version);
        $this->id = sprintf(
            '%s:%s:%s:%s:%s',
            $this->vendor,
            $this->package,
            $this->category,
            $this->message,
            $this->version->toString()
        );
    }

    /**
     * @param string $schemaId
     * @return SchemaId
     * @throws InvalidSchemaIdException
     */
    public static function fromString($schemaId)
    {
        if (isset(self::$instances[$schemaId])) {
            return self::$instances[$schemaId];
        }

        Assertion::maxLength($schemaId, 255, 'Schema id cannot be greater than 255 chars.', 'schemaId');
        if (!preg_match(self::VALID_PATTERN, $schemaId, $matches)) {
            throw new InvalidSchemaIdException(
                sprintf(
                    'Schema id [%s] is invalid.  It must match the pattern [%s].',
                    $schemaId,
                    self::VALID_PATTERN
                )
            );
        }

        self::$instances[$schemaId] = new self($matches[1], $matches[2], $matches[3], $matches[4], $matches[5]);
        return self::$instances[$schemaId];
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @return string
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return SchemaVersion
     */
    public function getVersion()
    {
        return $this->version;
    }
}
