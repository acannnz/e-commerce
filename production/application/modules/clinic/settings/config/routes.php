<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// general setting
$route['settings/general'] = 'general/index';
$route['settings/general(/:any)?'] = 'general$1';
// system setting
$route['settings/system'] = 'system/index';
$route['settings/system(/:any)?'] = 'system$1';
// database setting
$route['settings/database'] = 'database/index';
$route['settings/database(/:any)?'] = 'database$1';
// email setting
$route['settings/email'] = 'email/index';
$route['settings/email(/:any)?'] = 'email$1';
// permission setting
$route['settings/permissions'] = 'permissions/index';
$route['settings/permissions(/:any)?'] = 'permissions$1';
// theme setting
$route['settings/theme'] = 'theme/index';
$route['settings/theme(/:any)?'] = 'theme$1';
// translations setting
$route['settings/translations'] = 'translations/index';
$route['settings/translations(/:any)?'] = 'translations$1';
// templates setting
$route['settings/templates'] = 'templates/index';
$route['settings/templates(/:any)?'] = 'templates$1';
// departments setting
$route['settings/departments'] = 'departments/index';
$route['settings/departments(/:any)?'] = 'departments$1';
// fields setting
$route['settings/fields'] = 'fields/index';
$route['settings/fields/department'] = 'fields/department';
$route['settings/fields(/:any)?'] = 'fields$1';
// api setting
$route['settings/api'] = 'api/index';
$route['settings/api(/:any)?'] = 'api$1';

// module
$route['settings'] = 'settings/index';
$route['settings(/:any)?'] = 'settings$1';
