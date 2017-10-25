<?php
/**
 * Created by PhpStorm.
 * User: hunostor
 * Date: 2017.09.12.
 * Time: 11:13
 */
require 'func.php';
require 'Template.php';


$hello = new StdClass();
$hello->world = 'Hello World';

$user = new StdClass();
$user->name = 'Attila';
$user->email = 'poroszkai.attila@gmail.com';


// datas
$datas = [
    'EmailLabel' => 'Email',
    'namePlaceholder' => 'Name',
    'NameLabel' => 'Name',
    'NameMessageText' => 'Donec rutrum congue leo eget malesuada.',
    'ButtonName' => 'Submit',
    'validation' => 'is-invalid',
    'EmailMessageText' => 'Email Message Text',
    'placeholder' => 'Ide ird az emailt',
    'selector1' => 'This is selector',
    'firstOption' => 'awdawdawdawdawd',
    'greetings' => 'Welcomme',
    //'hello' => $hello,
    'user' => $user,
];

$pla = 'hello.world';

//$datas = [];

$temp = new Template('crawler.html', $datas);



$temp->render();

//echo $html;