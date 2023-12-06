<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['files'] = 'files';

$route['files/chart/usg(/:any)?'] = 'chart/usg$1';

// specialist of ophthalmology only
$route['files/ophthalmology/hirschberg/od(/:any)?'] = 'ophthalmology/hirschberg_od$1';
$route['files/ophthalmology/hirschberg/os(/:any)?'] = 'ophthalmology/hirschberg_os$1';

// specialist of cardiology only
$route['files/cardiology/acyanotic/roentgen(/:any)?'] = 'cardiology/acyanotic_roentgen$1';
$route['files/cardiology/acyanotic/ekg(/:any)?'] = 'cardiology/acyanotic_ekg$1';
$route['files/cardiology/acyanotic/echocardiography(/:any)?'] = 'cardiology/acyanotic_echocardiography$1';

