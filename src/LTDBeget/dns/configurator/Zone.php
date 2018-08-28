<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator;

use LTDBeget\dns\configurator\deserializer\ArrayDeserializer;
use LTDBeget\dns\configurator\deserializer\PlainDeserializer;
use LTDBeget\dns\configurator\errors\ErrorsStore;
use LTDBeget\dns\configurator\errors\ValidationError;
use LTDBeget\dns\configurator\traits\RecordsIterateTrait;
use LTDBeget\dns\configurator\validators\HostnameValidator;
use LTDBeget\dns\configurator\zoneEntities\Node;
use LTDBeget\dns\configurator\zoneEntities\record\base\Record;
use LTDBeget\dns\enums\eErrorCode;
use LTDBeget\dns\enums\eRecordType;

/**
 * Class Zone
 *
 * @package LTDBeget\dns\configurator
 */
class Zone
{
    use RecordsIterateTrait;

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
        return PlainDeserializer::deserialize(new static($origin), $plainData);
    }

    /**
     * @param string $origin
     * @param array  $arrayData
     * @return Zone
     */
    public static function fromArray(string $origin, array $arrayData) : Zone
    {
        return ArrayDeserializer::deserialize(new static($origin), $arrayData);
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

        return (string) implode("\n", $records);
    }

    /**
     * Sort zone by node names and record types
     */
    public function sort()
    {
        $nodes = $this->nodes;
        /** @var Node[] $_nodes */
        $_nodes = [];
        foreach (['@', 'www', '*'] as $node_name) {
            if (isset($nodes[$node_name])) {
                $_nodes[$node_name] = $nodes[$node_name];
                unset($nodes[$node_name]);
            }
        }

        ksort($nodes, SORT_STRING);
        foreach ($nodes as $name => $node) {
            $_nodes[$name] = $node;
        }
        $this->nodes = $_nodes;

        foreach ($this->iterateNodes() as $node) {
            $node->sort();
        }
    }

    /**
     * @return \Generator|Node[]
     */
    public function iterateNodes() : \Generator
    {
        foreach ($this->nodes as $node) {
            yield $node;
        }
    }

    /**
     * @param eRecordType|null $type
     * @return \Generator|Record[]
     */
    public function iterateRecords(eRecordType $type = NULL) : \Generator
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
        $this->sort();
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
        return array_map('strval', array_keys($this->nodes));
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
     *
     * @param $name
     * @return bool
     */
    public function isNodeExist($name) : bool
    {
        return array_key_exists(mb_strtolower(trim($name)), $this->nodes);
    }

    /**
     * @param eRecordType|NULL $type
     */
    public function removeRecords(eRecordType $type = NULL)
    {
        foreach ($this->iterateNodes() as $node) {
            foreach ($node->iterateRecords($type) as $record) {
                $record->remove();
            }
        }
    }

    /**
     * @return bool
     */
    public function isEmptyZone() : bool
    {
        $result = true;
        foreach ($this->iterateNodes() as $node) {
            if(! $node->isEmptyNode()) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * Full validate zone via build in validators
     *
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

        if (!HostnameValidator::validate($this->getOrigin())) {
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

    /**
     * @return Zone
     */
    public function clear() : Zone
    {
        $this
            ->dropNodes()
            ->getErrorsStore()
            ->clear();

        return $this;
    }

    /**
     * @return Zone
     */
    private function dropNodes() : Zone
    {
        $this->nodes = [];

        return $this;
    }
}
