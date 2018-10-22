<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/privacy/dlp/v2/dlp.proto

namespace Google\Cloud\Dlp\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request message for UpdateDeidentifyTemplate.
 *
 * Generated from protobuf message <code>google.privacy.dlp.v2.UpdateDeidentifyTemplateRequest</code>
 */
class UpdateDeidentifyTemplateRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Resource name of organization and deidentify template to be updated, for
     * example `organizations/433245324/deidentifyTemplates/432452342` or
     * projects/project-id/deidentifyTemplates/432452342.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     */
    private $name = '';
    /**
     * New DeidentifyTemplate value.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.DeidentifyTemplate deidentify_template = 2;</code>
     */
    private $deidentify_template = null;
    /**
     * Mask to control which fields get updated.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 3;</code>
     */
    private $update_mask = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *           Resource name of organization and deidentify template to be updated, for
     *           example `organizations/433245324/deidentifyTemplates/432452342` or
     *           projects/project-id/deidentifyTemplates/432452342.
     *     @type \Google\Cloud\Dlp\V2\DeidentifyTemplate $deidentify_template
     *           New DeidentifyTemplate value.
     *     @type \Google\Protobuf\FieldMask $update_mask
     *           Mask to control which fields get updated.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Privacy\Dlp\V2\Dlp::initOnce();
        parent::__construct($data);
    }

    /**
     * Resource name of organization and deidentify template to be updated, for
     * example `organizations/433245324/deidentifyTemplates/432452342` or
     * projects/project-id/deidentifyTemplates/432452342.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Resource name of organization and deidentify template to be updated, for
     * example `organizations/433245324/deidentifyTemplates/432452342` or
     * projects/project-id/deidentifyTemplates/432452342.
     *
     * Generated from protobuf field <code>string name = 1;</code>
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
     * New DeidentifyTemplate value.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.DeidentifyTemplate deidentify_template = 2;</code>
     * @return \Google\Cloud\Dlp\V2\DeidentifyTemplate
     */
    public function getDeidentifyTemplate()
    {
        return $this->deidentify_template;
    }

    /**
     * New DeidentifyTemplate value.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.DeidentifyTemplate deidentify_template = 2;</code>
     * @param \Google\Cloud\Dlp\V2\DeidentifyTemplate $var
     * @return $this
     */
    public function setDeidentifyTemplate($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dlp\V2\DeidentifyTemplate::class);
        $this->deidentify_template = $var;

        return $this;
    }

    /**
     * Mask to control which fields get updated.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 3;</code>
     * @return \Google\Protobuf\FieldMask
     */
    public function getUpdateMask()
    {
        return $this->update_mask;
    }

    /**
     * Mask to control which fields get updated.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 3;</code>
     * @param \Google\Protobuf\FieldMask $var
     * @return $this
     */
    public function setUpdateMask($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\FieldMask::class);
        $this->update_mask = $var;

        return $this;
    }

}

