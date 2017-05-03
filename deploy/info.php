<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'mssql';
$app['version'] = '1.0.0';
$app['release'] = '1';
$app['vendor'] = 'ClearFoundation';
$app['packager'] = 'ClearFoundation';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('mssql_app_description');
$app['tooltip'] = lang('mssql_app_tooltip');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('mssql_app_name');
$app['category'] = lang('base_category_server');
$app['subcategory'] = lang('base_subcategory_database');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['core_requires'] = array(
    'app-base-core >= 1:1.2.6',
    'app-network-core', 
    'mssql-server >= 11.0',
);

$app['core_file_manifest'] = array( 
    //'mssql_default.conf' => array ( 'target' => '/etc/clearos/storage.d/mssql_default.conf' ),
    //'mssql.php'=> array( 'target' => '/var/clearos/base/daemon/mssql.php' ),
);

$app['core_directory_manifest'] = array(
    '/var/clearos/mssql' => array(),
    '/var/clearos/mssql/backup' => array(),
);

$app['delete_dependency'] = array(
    'app-mssql-core',
    'mssql-server',
);
