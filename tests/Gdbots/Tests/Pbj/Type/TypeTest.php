<?php

namespace Gdbots\Tests\Pbj\Type;

use Gdbots\Common\GeoPoint;
use Gdbots\Common\Util\DateUtils;
use Gdbots\Pbj\FieldBuilder;
use Gdbots\Pbj\Type\DateTimeType;
use Gdbots\Tests\Pbj\Fixtures\NestedMessage;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function testDateTimeType()
    {
        $expected = '2014-12-25T12:13:14.123456+00:00';
        $dateTime = \DateTime::createFromFormat(DateUtils::ISO8601, $expected);
        $field = FieldBuilder::create('date_time', DateTimeType::create())->build();

        $encoded = $field->getType()->encode($dateTime, $field);
        $dateTime = \DateTime::createFromFormat(DateUtils::ISO8601, $encoded);
        $this->assertSame($expected, $encoded);

        $decoded = $field->getType()->decode($encoded, $field);
        $this->assertSame(
            $dateTime->format(DateUtils::ISO8601),
            $decoded->format(DateUtils::ISO8601)
        );
    }

    public function testDateTimeTypeUtcConversion()
    {
        $notUtc = '2014-12-25T12:13:14.123456+08:00';
        $expected = '2014-12-25T04:13:14.123456+00:00';
        $dateTime = \DateTime::createFromFormat(DateUtils::ISO8601, $notUtc);
        $field = FieldBuilder::create('date_time', DateTimeType::create())->build();

        $encoded = $field->getType()->encode($dateTime, $field);
        $dateTime = \DateTime::createFromFormat(DateUtils::ISO8601, $encoded);
        $this->assertSame($expected, $encoded);

        $decoded = $field->getType()->decode($encoded, $field);
        $this->assertSame(
            $dateTime->format(DateUtils::ISO8601),
            $decoded->format(DateUtils::ISO8601)
        );
    }

    public function testGeoPointType()
    {
        $message = NestedMessage::create();
        $geoJson = '{"type":"Point","coordinates":[102.0,0.5]}';
        $point = GeoPoint::fromArray(json_decode($geoJson, true));
        $message->setLocation($point);

        $this->assertSame($message->getLocation()->getLongitude(), 102.0);
        $this->assertSame($message->getLocation()->getLatitude(), 0.5);
        $this->assertSame($message->toArray()[NestedMessage::LOCATION], $point->toArray());
    }
}