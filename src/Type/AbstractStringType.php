<?php
declare(strict_types=1);

namespace Gdbots\Pbj\Type;

use Gdbots\Pbj\Assertion;
use Gdbots\Pbj\Codec;
use Gdbots\Pbj\Enum\Format;
use Gdbots\Pbj\Field;
use Gdbots\Pbj\Util\DateUtil;
use Gdbots\Pbj\Util\HashtagUtil;
use Gdbots\Pbj\Util\NumberUtil;

abstract class AbstractStringType extends AbstractType
{
    public function guard($value, Field $field): void
    {
        Assertion::string($value, null, $field->getName());

        // intentionally using strlen to get byte length, not mb_strlen
        $length = strlen($value);
        $minLength = $field->getMinLength();
        $maxLength = NumberUtil::bound($field->getMaxLength(), $minLength, $this->getMaxBytes());
        $okay = $length >= $minLength && $length <= $maxLength;

        Assertion::true(
            $okay,
            sprintf(
                'Field [%s] must be between [%d] and [%d] bytes, [%d] bytes given.',
                $field->getName(),
                $minLength,
                $maxLength,
                $length
            ),
            $field->getName()
        );

        if ($pattern = $field->getPattern()) {
            Assertion::regex($value, $pattern, null, $field->getName());
        }

        switch ($field->getFormat()->getValue()) {
            case Format::UNKNOWN:
                break;

            case Format::DATE:
                Assertion::regex($value, '/^\d{4}-\d{2}-\d{2}$/', null, $field->getName());
                break;

            case Format::DATE_TIME:
                Assertion::true(
                    DateUtil::isValidISO8601Date($value),
                    sprintf(
                        'Field [%s] must be a valid ISO8601 date-time.  Format must match one of [%s], [%s] or [%s].',
                        $field->getName(),
                        DateUtil::ISO8601_ZULU,
                        DateUtil::ISO8601,
                        \DateTime::ISO8601
                    ),
                    $field->getName()
                );
                break;

            case Format::SLUG:
                Assertion::regex($value, '/^([\w\/-]|[\w-][\w\/-]*[\w-])$/', null, $field->getName());
                break;

            case Format::EMAIL:
                Assertion::email($value, null, $field->getName());
                break;

            case Format::HASHTAG:
                Assertion::true(
                    HashtagUtil::isValid($value),
                    sprintf('Field [%s] must be a valid hashtag.  @see HashtagUtil::isValid', $field->getName()),
                    $field->getName()
                );
                break;

            case Format::IPV4:
                Assertion::url(
                    'https://' . $value,
                    sprintf(
                        'Field [%s] must be a valid [%s].',
                        $field->getName(),
                        $field->getFormat()->getValue()
                    ),
                    $field->getName()
                );
                break;

            case Format::IPV6:
                Assertion::url(
                    'https://[' . $value . ']',
                    sprintf(
                        'Field [%s] must be a valid [%s].',
                        $field->getName(),
                        $field->getFormat()->getValue()
                    ),
                    $field->getName()
                );
                break;

            case Format::HOSTNAME:
            case Format::URI:
            case Format::URL:
                /*
                 * fixme: need better handling for HOSTNAME, URI and URL... assertion library just has one "url" handling
                 * but we really need separate ones for each of these formats.  right now we're just prefixing
                 * the value with a http so it looks like a url.  this won't work for thinks like mailto:
                 * urn:, etc.
                 */
                if (false === strpos($value, 'http')) {
                    $value = 'https://' . $value;
                }

                Assertion::url(
                    $value,
                    sprintf(
                        'Field [%s] must be a valid [%s].',
                        $field->getName(),
                        $field->getFormat()->getValue()
                    ),
                    $field->getName()
                );
                break;

            case Format::UUID:
                Assertion::uuid($value, null, $field->getName());
                break;

            default:
                break;
        }
    }

    public function encode($value, Field $field, ?Codec $codec = null)
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        return $value;
    }

    public function decode($value, Field $field, ?Codec $codec = null)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }

        return $value;
    }

    public function isString(): bool
    {
        return true;
    }
}
