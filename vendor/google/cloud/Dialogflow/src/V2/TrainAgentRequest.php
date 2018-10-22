<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dialogflow/v2/agent.proto

namespace Google\Cloud\Dialogflow\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The request message for [Agents.TrainAgent][google.cloud.dialogflow.v2.Agents.TrainAgent].
 *
 * Generated from protobuf message <code>google.cloud.dialogflow.v2.TrainAgentRequest</code>
 */
class TrainAgentRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The project that the agent to train is associated with.
     * Format: `projects/<Project ID>`.
     *
     * Generated from protobuf field <code>string parent = 1;</code>
     */
    private $parent = '';

    public function __construct() {
        \GPBMetadata\Google\Cloud\Dialogflow\V2\Agent::initOnce();
        parent::__construct();
    }

    /**
     * Required. The project that the agent to train is associated with.
     * Format: `projects/<Project ID>`.
     *
     * Generated from protobuf field <code>string parent = 1;</code>
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Required. The project that the agent to train is associated with.
     * Format: `projects/<Project ID>`.
     *
     * Generated from protobuf field <code>string parent = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setParent($var)
    {
        GPBUtil::checkString($var, True);
        $this->parent = $var;

        return $this;
    }

}

