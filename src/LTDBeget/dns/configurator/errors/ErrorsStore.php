<?php
/**
 * @author: Viskov Sergey
 * @date  : 4/12/16
 * @time  : 1:00 PM
 */

namespace LTDBeget\dns\configurator\errors;

/**
 * Class ErrorsStore
 *
 * @package LTDBeget\dns\configurator\errors
 */
class ErrorsStore
{
    /**
     * @var ValidationError[]
     */
    private $errors = [];

    /**
     * @return ErrorsStore
     */
    public function clear() : ErrorsStore
    {
        $this->errors = [];

        return $this;
    }

    /**
     * @param ValidationError $error
     */
    public function add(ValidationError $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @return bool
     */
    public function isHasErrors() : bool
    {
        return count($this->errors) > 0;
    }

    /**
     * @return  \Generator|ValidationError[]
     */
    public function iterate() : \Generator
    {
        foreach ($this->errors as $error) {
            yield $error;
        }
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $errorsAsArray = [];
        foreach ($this->errors as $error) {
            $errorsAsArray[] = $error->toArray();
        }

        return $errorsAsArray;
    }
}