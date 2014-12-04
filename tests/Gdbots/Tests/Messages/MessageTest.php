<?php

namespace Gdbots\Tests\Messages;

use Gdbots\Tests\Messages\Enum\Priority;
use Gdbots\Tests\Messages\Enum\Provider;
use Moontoast\Math\BigNumber;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateMessageFromArray()
    {
        $fromName = 'Homer  ';
        $fromEmail = 'homer@simpson.com';

        $message = EmailMessage::fromArray([
                EmailMessage::FROM_NAME  => $fromName,
                EmailMessage::FROM_EMAIL => $fromEmail,
                EmailMessage::LABELS => [$fromEmail, $fromEmail, 'donuts'],
            ]);

        $message
            ->setPriority(Priority::HIGH())
            ->setABigInt(new BigNumber('1337'))
            ->addLabel('DoNuts')
            ->addLabel('Donuts')
            ->addLabel('mmmm')
            ->addLabel('mmmM')
            ->addLabel('Mmmm');

        $this->assertTrue($message->getPriority()->equals(Priority::HIGH));
        $this->assertTrue($fromName === $message->getFromName());
        $this->assertTrue(Priority::HIGH() === $message->getPriority());

        $json = json_encode($message, JSON_PRETTY_PRINT) . PHP_EOL;
        echo $json;

        $arr = json_decode($json, true);
        $message2 = EmailMessage::fromArray($arr);
        $message2
            ->markAsSent()
            ->setPriority(Priority::LOW())
            ->setProvider(Provider::AOL());

        echo json_encode($message2, JSON_PRETTY_PRINT) . PHP_EOL;
    }
}