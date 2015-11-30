<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use DSLPAL\DSLValidator;


//$constraint = [
//    new Assert\Length([
//        'min' => 3,
//        'max' => 20
//    ]),
//    new Assert\NotBlank,
//    new Assert\Email
//];
//
//$validator = Validation::createValidator();
//
//$errors = $validator->validate('teste@gmail.com', $constraint);
//
//var_dump($errors->count());

$fluent = new DSLValidator;

$res = $fluent->length(['min' => 5, 'max' => 20])
              ->notBlank()
              ->email()
              ->validate('teste@gmail.com')
;

var_dump($res->count());

$fluent->collection([
    'nome' => $fluent->length(['min' => 5])
                     ->type('string')
                    ->end(),
    'email' => $fluent->email()
                    ->end(),
    'sexo' => $fluent->choice(['M', 'F'])
                    ->end()
]);

$res = $fluent->validate([
    'nome' => 'Leonardo',
    'email' => 'teste@teste.com',
    'sexo' => 'N' // Wrong!
]);

foreach($res as $error) {
    echo $error . '<br>';
}
