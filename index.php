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

$email = $fluent->length(5, 20)
                ->notBlank()
                ->email()
         ->factory();

var_dump($email->validate('leo@teste.com')->count());
var_dump($email->validate('wrongemail')->count());

$fluent->collection([
    'nome' => $fluent
                ->type('string')
                ->length(5)
              ->end(),
    'email' => $fluent
                 ->email()
               ->end(),
    'sexo' => $fluent
                ->type('string')
                ->choice(['M', 'F'])
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
