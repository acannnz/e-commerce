<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//$route['reservations(/:any)'] = "Reservations$1";

$route['reservations/reports/patient-reservations'] = "reports/patient_reservations";
$route['reservations/reports/patient-reservations(/:any)'] = "reports/patient_reservations$1";
$route['reservations/reports/patient-reservations(/:any)(/:any)'] = "reports/patient_reservations$1$2";
