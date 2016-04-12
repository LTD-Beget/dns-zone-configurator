<?php
/**
 * @author: Viskov Sergey
 * @date: 05.04.16
 * @time: 0:43
 */

namespace LTDBeget\dns\configurator;


use BadMethodCallException;
use InvalidArgumentException;
use LTDBeget\dns\configurator\deserializer\ArrayDeserializer;
use LTDBeget\dns\configurator\deserializer\PlainDeserializer;
use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\errors\ErrorsStore;
use LTDBeget\dns\configurator\validators\OriginValidator;
use LTDBeget\dns\configurator\validators\SoaNumberCheck;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\AaaaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\ARecord;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\configurator\zoneEntities\record\CnameRecord;
use LTDBeget\dns\configurator\zoneEntities\record\MxRecord;
use LTDBeget\dns\configurator\zoneEntities\record\NsRecord;
use LTDBeget\dns\configurator\zoneEntities\record\PtrRecord;
use LTDBeget\dns\configurator\zoneEntities\record\SoaRecord;
use LTDBeget\dns\configurator\zoneEntities\record\SrvRecord;
use LTDBeget\dns\configurator\zoneEntities\record\TxtRecord;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class Zone
 * @package LTDBeget\dns\configurator
 * @method ARecord iterateA()
 * @method AaaaRecord iterateAaaa()
 * @method CnameRecord iterateCname()
 * @method MxRecord iterateMx()
 * @method NsRecord iterateNs()
 * @method PtrRecord iteratePtr()
 * @method SoaRecord iterateSoa()
 * @method SrvRecord iterateSrv()
 * @method TxtRecord iterateTxt()
 */
class Zone
{
    /**
     * @var String
     */
    private $origin;
    /**
     * @var Node[] $nodes
     */
    private $nodes = [];
    /**
     * @var ErrorsStore
     */
    private $errorsStore;

    /**
     * @param $origin
     */
    public function __construct(string $origin)
    {
        $this->origin      = mb_strtolower(trim($origin));
        $this->errorsStore = new ErrorsStore();
    }

    /**
     * @param string $origin
     * @param string $plainData
     * @return Zone
     */
    public static function fromString(string $origin, string $plainData) : Zone
    {
        return PlainDeserializer::deserialize(new self($origin), $plainData);
    }

    /**
     * @param string $origin
     * @param array $arrayData
     * @return Zone
     */
    public static function fromArray(string $origin, array $arrayData) : Zone
    {
        return ArrayDeserializer::deserialize(new self($origin), $arrayData);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        $this->sort();
        $records = [];
        foreach ($this->iterateRecords() as $record) {
            $records[] = (string) $record;
        }

        return implode("\n", $records);
    }

    /**
     * Sort zone by node names and record types
     */
    public function sort()
    {
        $nodes  = $this->nodes;
        $_nodes = [];
        foreach (['@', 'www', '*'] as $node_name) {
            if (isset($nodes[$node_name])) {
                $_nodes[$node_name] = $nodes[$node_name];
                unset($nodes[$node_name]);
            }
        }

        ksort($nodes);
        $this->nodes = array_merge($_nodes, $nodes);

        foreach ($this->iterateNodes() as $node) {
            $node->sort();
        }
    }

    /**
     * @return Node[]
     */
    public function iterateNodes()
    {
        foreach ($this->nodes as $node) {
            yield $node;
        }
    }

    /**
     * @param eRecordType|null $type
     * @return Record[]
     */
    public function iterateRecords(eRecordType $type = null)
    {
        foreach ($this->iterateNodes() as $node) {
            foreach ($node->iterateRecords($type) as $record) {
                yield $record;
            }
        }
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $records = [];
        foreach ($this->iterateRecords() as $record) {
            $records[] = $record->toArray();
        }

        return $records;
    }

    /**
     * @return array
     */
    public function getNodeNames() : array
    {
        return array_keys($this->nodes);
    }

    /**
     * @param $name
     * @return Node
     */
    public function getNode(string $name) : Node
    {
        $name = mb_strtolower(trim($name));

        if (!$this->isNodeExist($name)) {
            $this->nodes[$name] = new Node($this, $name);
        }

        return $this->nodes[$name];
    }

    /**
     * Check is node by given name already exists
     * @param $name
     * @return bool
     */
    public function isNodeExist($name) : bool
    {
        return array_key_exists(mb_strtolower(trim($name)), $this->nodes);
    }

    /**
     * @internal
     * @param $name
     * @param $arguments
     * @return zoneEntities\record\base\Record[]
     */
    public function __call($name, $arguments)
    {
        try {
            $type = eRecordType::get(mb_strtoupper(str_replace("iterate", "", $name)));

            return $this->iterateRecords($type);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException("Method {$name} not found");
        }
    }

    /**
     * Full validate zone via build in validators
     * @return bool
     */
    public function validate() : bool
    {
        $errorsStore = $this->getErrorsStore();
        $errorsStore->clear();

        foreach ($this->iterateNodes() as $node) {
            /** @noinspection PhpInternalEntityUsedInspection */
            $node->validate();
        }

        if (!SoaNumberCheck::validate($this)) {
            $errorsStore->add(ValidationError::makeZoneError($this, eErrorCode::SOA_ERROR()));
        }

        if (!OriginValidator::validate($this->getOrigin())) {
            $errorsStore->add(ValidationError::makeZoneError($this, eErrorCode::WRONG_ORIGIN()));
        }

        return !$errorsStore->isHasErrors();
    }

    /**
     * @return ErrorsStore
     */
    public function getErrorsStore()
    {
        return $this->errorsStore;
    }

    /**
     * @return String
     */
    public function getOrigin() : string
    {
        return $this->origin;
    }
}