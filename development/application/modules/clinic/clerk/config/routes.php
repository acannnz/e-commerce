<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['transaction/update(/:any)?'] = 'transaction/index$1';

$route['common/zones/(country|province|county|district|area)(/:num)?'] = 'zones/index/$1$2';
$route['common/zones/(country|province|county|district|area)/create(/:num)?'] = 'zones/create/$1$2';
$route['common/zones/(country|province|county|district|area)/edit(/:num)?'] = 'zones/edit/$1$2';
$route['common/zones/(country|province|county|district|area)/delete(/:num)?'] = 'zones/delete/$1$2';

//datatables
$route['common/patients/datatable-collection(/:any)?'] = 'patients/datatable_collection$1';
$route['common/patients(/:any)/datatable-collection'] = 'patients/datatable_collection/$1';
//lookup
$route['common/patients/lookup(/:any)?'] = 'patients/lookup$1';