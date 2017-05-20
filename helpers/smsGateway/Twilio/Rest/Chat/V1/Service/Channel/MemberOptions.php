<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Chat\V1\Service\Channel;

use Twilio\Options;
use Twilio\Values;

abstract class MemberOptions {
    /**
     * @param string $roleSid The role_sid
     * @return CreateMemberOptions Options builder
     */
    public static function create($roleSid = Values::NONE) {
        return new CreateMemberOptions($roleSid);
    }

    /**
     * @param string $identity The identity
     * @return ReadMemberOptions Options builder
     */
    public static function read($identity = Values::NONE) {
        return new ReadMemberOptions($identity);
    }

    /**
     * @param string $roleSid The role_sid
     * @param integer $lastConsumedMessageIndex The last_consumed_message_index
     * @return UpdateMemberOptions Options builder
     */
    public static function update($roleSid = Values::NONE, $lastConsumedMessageIndex = Values::NONE) {
        return new UpdateMemberOptions($roleSid, $lastConsumedMessageIndex);
    }
}

class CreateMemberOptions extends Options {
    /**
     * @param string $roleSid The role_sid
     */
    public function __construct($roleSid = Values::NONE) {
        $this->options['roleSid'] = $roleSid;
    }

    /**
     * The role_sid
     * 
     * @param string $roleSid The role_sid
     * @return $this Fluent Builder
     */
    public function setRoleSid($roleSid) {
        $this->options['roleSid'] = $roleSid;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Chat.V1.CreateMemberOptions ' . implode(' ', $options) . ']';
    }
}

class ReadMemberOptions extends Options {
    /**
     * @param string $identity The identity
     */
    public function __construct($identity = Values::NONE) {
        $this->options['identity'] = $identity;
    }

    /**
     * The identity
     * 
     * @param string $identity The identity
     * @return $this Fluent Builder
     */
    public function setIdentity($identity) {
        $this->options['identity'] = $identity;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Chat.V1.ReadMemberOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateMemberOptions extends Options {
    /**
     * @param string $roleSid The role_sid
     * @param integer $lastConsumedMessageIndex The last_consumed_message_index
     */
    public function __construct($roleSid = Values::NONE, $lastConsumedMessageIndex = Values::NONE) {
        $this->options['roleSid'] = $roleSid;
        $this->options['lastConsumedMessageIndex'] = $lastConsumedMessageIndex;
    }

    /**
     * The role_sid
     * 
     * @param string $roleSid The role_sid
     * @return $this Fluent Builder
     */
    public function setRoleSid($roleSid) {
        $this->options['roleSid'] = $roleSid;
        return $this;
    }

    /**
     * The last_consumed_message_index
     * 
     * @param integer $lastConsumedMessageIndex The last_consumed_message_index
     * @return $this Fluent Builder
     */
    public function setLastConsumedMessageIndex($lastConsumedMessageIndex) {
        $this->options['lastConsumedMessageIndex'] = $lastConsumedMessageIndex;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Chat.V1.UpdateMemberOptions ' . implode(' ', $options) . ']';
    }
}