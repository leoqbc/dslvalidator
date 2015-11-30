<?php

/*
 * Created by Leonardo Tumadjian
 * GitHub User: leoqbc
 * Email: tumadjian@gmail.com
 */

namespace DSLPAL;

use Symfony\Component\Validator\Validation;

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

    public function collection(array $asserts)
    {
        $collection = self::CONSTRAINT_NAMESPACE . 'Collection';
        return $this->assertList = new $collection($asserts);
    }

    public function validate($value)
    {
        $violations = $this->validator->validate($value, $this->assertList);
        $this->end();
        return $violations;
    }

    public function end()
    {
        $assertList = $this->assertList;
        $this->assertList = null;
        return $assertList;
    }
}