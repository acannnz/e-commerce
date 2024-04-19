<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['registrations/create-from-reservation(/:any)'] = "registrations/create_from_reservation$1";

$route['registrations/reports/polyclinic-registrations'] = "reports/polyclinic_registrations";
$route['registrations/reports/polyclinic-registrations(/:any)'] = "reports/polyclinic_registrations$1";
$route['registrations/reports/polyclinic-registrations(/:any)(/:any)'] = "reports/polyclinic_registrations$1$2";

$route['registrations/reports/registration-patient-types'] = "reports/registration_patient_types";
$route['registrations/reports/registration-patient-types(/:any)'] = "reports/registration_patient_types$1";
$route['registrations/reports/registration-patient-types(/:any)(/:any)'] = "reports/registration_patient_types$1$2";

$route['registrations/reports/registration-patient'] = "reports/registration_patient";
$route['registrations/reports/registration-patient(/:any)'] = "reports/registration_patient$1";
$route['registrations/reports/registration-patient(/:any)(/:any)'] = "reports/registration_patient$1$2";

