<?php

use MargretSchroeder\ContaoStammBundle\Model\PersonModel;

// Frontend modules
$GLOBALS['FE_MOD']['miscellaneous']['helloWorld'] = 'MargretSchroeder\ContaoStammBundle\Module\HelloWorldModule';
$GLOBALS['FE_MOD']['miscellaneous']['listStamm'] = 'MargretSchroeder\ContaoStammBundle\Module\ListStammModule';



$GLOBALS['BE_MOD']['content']['my_new_module'] = [ 'tables' => ['tl_wohnung'],];
$GLOBALS['BE_MOD']['content']['my_new_person'] = [ 'tables' => ['tl_person'],];

// Model
$GLOBALS['TL_MODELS']['tl_person'] = PersonModel::class;


?>