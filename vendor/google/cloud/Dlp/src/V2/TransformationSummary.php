<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/privacy/dlp/v2/dlp.proto

namespace Google\Cloud\Dlp\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Summary of a single tranformation.
 * Only one of 'transformation', 'field_transformation', or 'record_suppress'
 * will be set.
 *
 * Generated from protobuf message <code>google.privacy.dlp.v2.TransformationSummary</code>
 */
class TransformationSummary extends \Google\Protobuf\Internal\Message
{
    /**
     * Set if the transformation was limited to a specific info_type.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.InfoType info_type = 1;</code>
     */
    private $info_type = null;
    /**
     * Set if the transformation was limited to a specific FieldId.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.FieldId field = 2;</code>
     */
    private $field = null;
    /**
     * The specific transformation these stats apply to.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.PrimitiveTransformation transformation = 3;</code>
     */
    private $transformation = null;
    /**
     * The field transformation that was applied.
     * If multiple field transformations are requested for a single field,
     * this list will contain all of them; otherwise, only one is supplied.
     *
     * Generated from protobuf field <code>repeated .google.privacy.dlp.v2.FieldTransformation field_transformations = 5;</code>
     */
    private $field_transformations;
    /**
     * The specific suppression option these stats apply to.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.RecordSuppression record_suppress = 6;</code>
     */
    private $record_suppress = null;
    /**
     * Generated from protobuf field <code>repeated .google.privacy.dlp.v2.TransformationSummary.SummaryResult results = 4;</code>
     */
    private $results;
    /**
     * Total size in bytes that were transformed in some way.
     *
     * Generated from protobuf field <code>int64 transformed_bytes = 7;</code>
     */
    private $transformed_bytes = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Cloud\Dlp\V2\InfoType $info_type
     *           Set if the transformation was limited to a specific info_type.
     *     @type \Google\Cloud\Dlp\V2\FieldId $field
     *           Set if the transformation was limited to a specific FieldId.
     *     @type \Google\Cloud\Dlp\V2\PrimitiveTransformation $transformation
     *           The specific transformation these stats apply to.
     *     @type \Google\Cloud\Dlp\V2\FieldTransformation[]|\Google\Protobuf\Internal\RepeatedField $field_transformations
     *           The field transformation that was applied.
     *           If multiple field transformations are requested for a single field,
     *           this list will contain all of them; otherwise, only one is supplied.
     *     @type \Google\Cloud\Dlp\V2\RecordSuppression $record_suppress
     *           The specific suppression option these stats apply to.
     *     @type \Google\Cloud\Dlp\V2\TransformationSummary\SummaryResult[]|\Google\Protobuf\Internal\RepeatedField $results
     *     @type int|string $transformed_bytes
     *           Total size in bytes that were transformed in some way.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Privacy\Dlp\V2\Dlp::initOnce();
        parent::__construct($data);
    }

    /**
     * Set if the transformation was limited to a specific info_type.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.InfoType info_type = 1;</code>
     * @return \Google\Cloud\Dlp\V2\InfoType
     */
    public function getInfoType()
    {
        return $this->info_type;
    }

    /**
     * Set if the transformation was limited to a specific info_type.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.InfoType info_type = 1;</code>
     * @param \Google\Cloud\Dlp\V2\InfoType $var
     * @return $this
     */
    public function setInfoType($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dlp\V2\InfoType::class);
        $this->info_type = $var;

        return $this;
    }

    /**
     * Set if the transformation was limited to a specific FieldId.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.FieldId field = 2;</code>
     * @return \Google\Cloud\Dlp\V2\FieldId
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set if the transformation was limited to a specific FieldId.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.FieldId field = 2;</code>
     * @param \Google\Cloud\Dlp\V2\FieldId $var
     * @return $this
     */
    public function setField($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dlp\V2\FieldId::class);
        $this->field = $var;

        return $this;
    }

    /**
     * The specific transformation these stats apply to.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.PrimitiveTransformation transformation = 3;</code>
     * @return \Google\Cloud\Dlp\V2\PrimitiveTransformation
     */
    public function getTransformation()
    {
        return $this->transformation;
    }

    /**
     * The specific transformation these stats apply to.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.PrimitiveTransformation transformation = 3;</code>
     * @param \Google\Cloud\Dlp\V2\PrimitiveTransformation $var
     * @return $this
     */
    public function setTransformation($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dlp\V2\PrimitiveTransformation::class);
        $this->transformation = $var;

        return $this;
    }

    /**
     * The field transformation that was applied.
     * If multiple field transformations are requested for a single field,
     * this list will contain all of them; otherwise, only one is supplied.
     *
     * Generated from protobuf field <code>repeated .google.privacy.dlp.v2.FieldTransformation field_transformations = 5;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getFieldTransformations()
    {
        return $this->field_transformations;
    }

    /**
     * The field transformation that was applied.
     * If multiple field transformations are requested for a single field,
     * this list will contain all of them; otherwise, only one is supplied.
     *
     * Generated from protobuf field <code>repeated .google.privacy.dlp.v2.FieldTransformation field_transformations = 5;</code>
     * @param \Google\Cloud\Dlp\V2\FieldTransformation[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setFieldTransformations($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Dlp\V2\FieldTransformation::class);
        $this->field_transformations = $arr;

        return $this;
    }

    /**
     * The specific suppression option these stats apply to.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.RecordSuppression record_suppress = 6;</code>
     * @return \Google\Cloud\Dlp\V2\RecordSuppression
     */
    public function getRecordSuppress()
    {
        return $this->record_suppress;
    }

    /**
     * The specific suppression option these stats apply to.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.RecordSuppression record_suppress = 6;</code>
     * @param \Google\Cloud\Dlp\V2\RecordSuppression $var
     * @return $this
     */
    public function setRecordSuppress($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dlp\V2\RecordSuppression::class);
        $this->record_suppress = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .google.privacy.dlp.v2.TransformationSummary.SummaryResult results = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Generated from protobuf field <code>repeated .google.privacy.dlp.v2.TransformationSummary.SummaryResult results = 4;</code>
     * @param \Google\Cloud\Dlp\V2\TransformationSummary\SummaryResult[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setResults($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Dlp\V2\TransformationSummary\SummaryResult::class);
        $this->results = $arr;

        return $this;
    }

    /**
     * Total size in bytes that were transformed in some way.
     *
     * Generated from protobuf field <code>int64 transformed_bytes = 7;</code>
     * @return int|string
     */
    public function getTransformedBytes()
    {
        return $this->transformed_bytes;
    }

    /**
     * Total size in bytes that were transformed in some way.
     *
     * Generated from protobuf field <code>int64 transformed_bytes = 7;</code>
     * @param int|string $var
     * @return $this
     */
    public function setTransformedBytes($var)
    {
        GPBUtil::checkInt64($var);
        $this->transformed_bytes = $var;

        return $this;
    }

}

