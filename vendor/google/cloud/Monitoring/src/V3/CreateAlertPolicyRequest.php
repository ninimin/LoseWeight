<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/monitoring/v3/alert_service.proto

namespace Google\Cloud\Monitoring\V3;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The protocol for the `CreateAlertPolicy` request.
 *
 * Generated from protobuf message <code>google.monitoring.v3.CreateAlertPolicyRequest</code>
 */
class CreateAlertPolicyRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * The project in which to create the alerting policy. The format is
     * `projects/[PROJECT_ID]`.
     * Note that this field names the parent container in which the alerting
     * policy will be written, not the name of the created policy. The alerting
     * policy that is returned will have a name that contains a normalized
     * representation of this name as a prefix but adds a suffix of the form
     * `/alertPolicies/[POLICY_ID]`, identifying the policy in the container.
     *
     * Generated from protobuf field <code>string name = 3;</code>
     */
    private $name = '';
    /**
     * The requested alerting policy. You should omit the `name` field in this
     * policy. The name will be returned in the new policy, including
     * a new [ALERT_POLICY_ID] value.
     *
     * Generated from protobuf field <code>.google.monitoring.v3.AlertPolicy alert_policy = 2;</code>
     */
    private $alert_policy = null;

    public function __construct() {
        \GPBMetadata\Google\Monitoring\V3\AlertService::initOnce();
        parent::__construct();
    }

    /**
     * The project in which to create the alerting policy. The format is
     * `projects/[PROJECT_ID]`.
     * Note that this field names the parent container in which the alerting
     * policy will be written, not the name of the created policy. The alerting
     * policy that is returned will have a name that contains a normalized
     * representation of this name as a prefix but adds a suffix of the form
     * `/alertPolicies/[POLICY_ID]`, identifying the policy in the container.
     *
     * Generated from protobuf field <code>string name = 3;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The project in which to create the alerting policy. The format is
     * `projects/[PROJECT_ID]`.
     * Note that this field names the parent container in which the alerting
     * policy will be written, not the name of the created policy. The alerting
     * policy that is returned will have a name that contains a normalized
     * representation of this name as a prefix but adds a suffix of the form
     * `/alertPolicies/[POLICY_ID]`, identifying the policy in the container.
     *
     * Generated from protobuf field <code>string name = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * The requested alerting policy. You should omit the `name` field in this
     * policy. The name will be returned in the new policy, including
     * a new [ALERT_POLICY_ID] value.
     *
     * Generated from protobuf field <code>.google.monitoring.v3.AlertPolicy alert_policy = 2;</code>
     * @return \Google\Cloud\Monitoring\V3\AlertPolicy
     */
    public function getAlertPolicy()
    {
        return $this->alert_policy;
    }

    /**
     * The requested alerting policy. You should omit the `name` field in this
     * policy. The name will be returned in the new policy, including
     * a new [ALERT_POLICY_ID] value.
     *
     * Generated from protobuf field <code>.google.monitoring.v3.AlertPolicy alert_policy = 2;</code>
     * @param \Google\Cloud\Monitoring\V3\AlertPolicy $var
     * @return $this
     */
    public function setAlertPolicy($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Monitoring\V3\AlertPolicy::class);
        $this->alert_policy = $var;

        return $this;
    }

}

