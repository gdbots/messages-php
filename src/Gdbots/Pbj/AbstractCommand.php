<?php

namespace Gdbots\Pbj;

use Gdbots\Common\Microtime;
use Gdbots\Identifiers\TimeUuidIdentifier;

abstract class AbstractCommand extends AbstractMessage implements Command
{
    /**
     * {@inheritdoc}
     */
    final public function getCommandId()
    {
        return $this->get(CommandSchema::COMMAND_ID_FIELD_NAME);
    }

    /**
     * {@inheritdoc}
     */
    final public function setCommandId(TimeUuidIdentifier $id)
    {
        return $this->setSingleValue(CommandSchema::COMMAND_ID_FIELD_NAME, $id);
    }

    /**
     * {@inheritdoc}
     */
    final public function getMicrotime()
    {
        return $this->get(CommandSchema::MICROTIME_FIELD_NAME);
    }

    /**
     * {@inheritdoc}
     */
    final public function setMicrotime(Microtime $microtime)
    {
        return $this->setSingleValue(CommandSchema::MICROTIME_FIELD_NAME, $microtime);
    }
}