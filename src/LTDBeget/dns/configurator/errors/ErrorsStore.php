<?php
/**
 * @author: Viskov Sergey
 * @date: 12.04.16
 * @time: 1:57
 */

namespace LTDBeget\dns\configurator\errors;

/**
 * Class ErrorsStore
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