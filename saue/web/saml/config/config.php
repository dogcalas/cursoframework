<?php

$config = array(
    'baseurlpath' => 'ecotec/',
    'certdir' => 'cert/',
    'loggingdir' => 'log/',
    'datadir' => 'data/',
    'ecotec.config' => array('appname' => 'ecotec',
        'idpmetadataonsamlspremote' => 'http://curso.ecotec.edu.ec',
        'spmetadataonsamlidpremote' => 'http://curso.ecotec.edu.ec',
		'authtype' => array('sqlauth:SQL',
            'connparameters' => array('gestor' => 'pgsql',
                'usuario' => '',
                'password' => '',
                'host' => 'localhost',
                'port' => '5432',
                'bd' => 'sauxe',
                'query' => ''
        )),
        'secure' => array('privatekey' => 'cakey.pem',
            'certificate' => 'cacert.cer',
            'certFingerprint' => '64e4a96b45ff331c25b97b9bc06d589edeb92a79'
        ),
        'IdpAuth' => 'ecotecsql',
        'authsource' => 'saml:SP'
    ),
    'tempdir' => '/tmp/simplesaml',
    'debug' => FALSE,
    'showerrors' => TRUE,
    'debug.validatexml' => FALSE,
    'auth.adminpassword' => '',
    'admin.protectindexpage' => false,
    'admin.protectmetadata' => false,
    'secretsalt' => '',
    'technicalcontact_name' => 'Administrator',
    'technicalcontact_email' => 'na@example.org',
    'timezone' => NULL,
    'logging.level' => LOG_NOTICE,
    'logging.handler' => 'syslog',
    'logging.facility' => defined('LOG_LOCAL5') ? constant('LOG_LOCAL5') : LOG_USER,
    'logging.processname' => 'simplesamlphp',
    'logging.logfile' => 'simplesamlphp.log',
    'enable.saml20-idp' => true,
    'enable.shib13-idp' => true,
    'enable.adfs-idp' => false,
    'enable.wsfed-sp' => false,
    'enable.authmemcookie' => false,
    'session.duration' => 8 * (60 * 60),
    'session.requestcache' => 4 * (60 * 60),
    'session.datastore.timeout' => (4*60*60),
    'session.cookie.lifetime' => 0,
    'session.cookie.path' => '/',
    'session.cookie.domain' => NULL,
    'session.cookie.secure' => FALSE,
    'session.phpsession.cookiename' => null,
    'session.phpsession.savepath' => null,
    'session.phpsession.httponly' => FALSE,
    'language.available' => array('en', 'no', 'nn', 'se', 'da', 'de', 'sv', 'fi', 'es', 'fr', 'it', 'nl', 'lb', 'cs', 'sl', 'lt', 'hr', 'hu', 'pl', 'pt', 'pt-BR', 'tr', 'ja', 'zh-tw'),
    'language.default' => 'en',
    'attributes.extradictionary' => NULL,
    'theme.use' => 'default',
    'default-wsfed-idp' => 'urn:federation:pingfederate:localhost',
    'idpdisco.enableremember' => TRUE,
    'idpdisco.rememberchecked' => TRUE,
    'idpdisco.validate' => TRUE,
    'idpdisco.extDiscoveryStorage' => NULL,
    'idpdisco.layout' => 'dropdown',
    'shib13.signresponse' => TRUE,
    'authproc.idp' => array(
        30 => 'core:LanguageAdaptor',
        45 => array(
            'class' => 'core:StatisticsWithAttribute',
            'attributename' => 'realm',
            'type' => 'saml20-idp-SSO',
        ),
        50 => 'core:AttributeLimit',
        99 => 'core:LanguageAdaptor',
    ),
    'authproc.sp' => array(
        50 => 'core:AttributeLimit',
        60 => array('class' => 'core:GenerateGroups', 'eduPersonAffiliation'),
        61 => array('class' => 'core:AttributeAdd', 'groups' => array('users', 'members')),
        90 => 'core:LanguageAdaptor',
    ),
    'metadata.sources' => array(
        array('type' => 'flatfile'),
    ),
    'store.type' => 'phpsession',
    'store.sql.dsn' => 'sqlite:/path/to/sqlitedatabase.sq3',
    'store.sql.username' => NULL,
    'store.sql.password' => NULL,
    'store.sql.prefix' => 'simpleSAMLphp',
    'memcache_store.servers' => array(
        array(
            array('hostname' => 'localhost'),
        ),
    ),
    'memcache_store.expires' => 36 * (60 * 60),
    'metadata.sign.enable' => FALSE,
    'metadata.sign.privatekey' => NULL,
    'metadata.sign.privatekey_pass' => NULL,
    'metadata.sign.certificate' => NULL,
    'proxy' => NULL,
    'enable.http_post' => FALSE
);
