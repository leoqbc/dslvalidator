<?php

/*
 * Created by Leonardo Tumadjian
 * GitHub User: leoqbc
 * Email: tumadjian@gmail.com
 */

namespace DSLPAL;

use Symfony\Component\Validator\Validation;

/**
 * Class DSLValidator
 * @package DSLPAL
 *
 * @method DSLValidator notBlank()
 * @method DSLValidator email()
 * @method DSLValidator type($type)
 * @method DSLValidator choice(array $choice)
 */
class DSLValidator
{
    protected $assertList;

    protected $validator;

    const CONSTRAINT_NAMESPACE = 'Symfony\\Component\\Validator\\Constraints\\';

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    public function __call($constraint, $parameters)
    {
        $class = self::CONSTRAINT_NAMESPACE . ucfirst($constraint);
        $refClass = new \ReflectionClass($class);
        $this->assertList[] = $refClass->newInstanceArgs($parameters);
        return $this;
    }

    public function length($min = null, $max = null)
    {
        $options = [];
        if (null !== $min) {
            $options['min'] = $min;
        }
        if (null !== $min) {
            $options['max'] = $max;
        }
        return $this->__call(__METHOD__, $options);
    }

    public function collection(array $asserts)
    {
        $collection = self::CONSTRAINT_NAMESPACE . 'Collection';
        return $this->assertList = new $collection($asserts);
    }

    public function validate($value)
    {
        $violations = $this->validator->validate($value, $this->assertList);
        return $violations;
    }

    public function end()
    {
        $assertList = $this->assertList;
        $this->assertList = null;
        return $assertList;
    }

    public function factory()
    {
        $clone = clone $this;
        $this->end();
        return $clone;
    }
}