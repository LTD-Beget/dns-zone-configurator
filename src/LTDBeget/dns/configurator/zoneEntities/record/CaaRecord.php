<?php

namespace LTDBeget\dns\configurator\zoneEntities\record;

use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\validators\Int16Validator;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class CaaRecord
 *
 * @package LTDBeget\dns\configurator\zoneEntities\record
 */
class CaaRecord extends Record
{
    /** @var string explicity authorizes a single certificate authority to issue a certificate (any type) for the hostname */
    const TAG_ISSUE = 'issue';
    /** @var string explicity authorizes a single certificate authority to issue a wildcard certificate (and only wildcard) for the hostname */
    const TAG_ISSUEWILD = 'issuewild';
    /** @var string specifies a URL to which a certificate authority may report policy violations */
    const TAG_IODEF = 'iodef';
    /** @var string  проверка по FQDN  rfc6844 5.2 */
    const PATTERN_FQDN = '/^([a-z0-9\-]([a-z0-9\-]{0,61}[a-z0-9])?\.)*([a-z0-9]([a-z0-9\-]{0,61}[a-z0-9])?\.)+[a-z0-9-]{2,30}$/i';

    /**
     * @var Int
     */
    protected $flags;
    /**
     * @var String
     */
    protected $tag;
    /**
     * @var String
     */
    protected $value;

    /**
     * CaaRecord constructor.
     *
     * @param Node   $node
     * @param        $ttl
     * @param int    $flags
     * @param string $tag
     * @param string $value
     */
    public function __construct(Node $node, $ttl, int $flags, string $tag, string $value)
    {
        $this->flags = $flags;
        $this->tag   = $tag;
        $this->value = $this->getFromQuotes($value);
        parent::__construct($node, eRecordType::CAA(), $ttl);
    }

    /**
     * @param $value
     *
     * @return string
     */
    private function getFromQuotes(string $value)
    {
        return str_replace(["\n", '"'], "", $value);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getMainRecordPart() . " {$this->getFlags()} {$this->getTag()} \"{$this->getValue()}\"";
    }

    /**
     * @return Int
     */
    public function getFlags(): Int
    {
        return $this->flags;
    }

    /**
     * @param Int $flags
     *
     * @return CaaRecord
     */
    public function setFlags(Int $flags): CaaRecord
    {
        return $this->setAttribute('flags', $flags);
    }

    /**
     * @return String
     */
    public function getTag(): String
    {
        return $this->tag;
    }

    /**
     * @param String $tag
     *
     * @return CaaRecord
     */
    public function setTag(String $tag): CaaRecord
    {
        return $this->setAttribute('tag', $tag);
    }

    /**
     * @return String
     */
    public function getValue(): String
    {
        return $this->value;
    }

    /**
     * @param String $value
     *
     * @return CaaRecord
     */
    public function setValue(String $value): CaaRecord
    {
        return $this->setAttribute('value', $value);
    }

    /**
     * @internal
     * @return bool
     */
    public function validate(): bool
    {
        $errorStorage = $this->getNode()->getZone()->getErrorsStore();

        if (!in_array($this->tag, [self::TAG_ISSUE, self::TAG_ISSUEWILD, self::TAG_IODEF])) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_CAA_TAG(), 'tag'));
        }

        if ($this->flags < 0 || $this->flags > 255) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_CAA_FLAGS(), 'flag'));
        }

        if (!Int16Validator::validate($this->getFlags())) {
            $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_INT16(), 'flags'));
        }

        if (in_array($this->getTag(), [self::TAG_ISSUEWILD, self::TAG_ISSUE]) &&
            $this->getValue() !== ';'
        ) {
            $parts = explode(";", $this->getValue());

            if (!preg_match(self::PATTERN_FQDN, $parts[0])) {
                $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_CAA_VALUE(), 'value'));
            }
        }

        if ($this->getTag() == self::TAG_IODEF) {
            if (!filter_var($this->getValue(), FILTER_VALIDATE_EMAIL) && !filter_var($this->getValue(), FILTER_VALIDATE_URL)) {
                $errorStorage->add(ValidationError::makeRecordError($this, eErrorCode::WRONG_CAA_VALUE(), 'value'));
            }
        }

        /** @noinspection PhpInternalEntityUsedInspection */
        return parent::validate();
    }

    /**
     * @return array
     */
    protected function recordDataToArray(): array
    {
        return [
            'FLAGS' => $this->getFlags(),
            'TAG'   => $this->getTag(),
            'VALUE' => $this->getValue()
        ];
    }
}
