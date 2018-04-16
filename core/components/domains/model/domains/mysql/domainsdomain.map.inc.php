<?php

    /**
     * Domains
     *
     * Copyright 2018 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */
    
    $xpdo_meta_map['DomainsDomain'] = [
        'package'       => 'domains',
        'version'       => '1.0',
        'table'         => 'domains_domain',
        'extends'       => 'xPDOSimpleObject',
        'fields'        => [
            'id'            => null,
            'domain'        => null,
            'scheme'        => null,
            'base'          => null,
            'context'       => null,
            'language'      => null,
            'site_status'   => null,
            'page_start'    => null,
            'page_error'    => null,
            'emailsender'   => null,
            'primary'       => null,
            'active'        => null,
            'editedon'      => null
        ],
        'fieldMeta'     => [
            'id'            => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'null'          => false,
                'index'         => 'pk',
                'generated'     => 'native'
            ],
            'domain'        => [
                'dbtype'        => 'varchar',
                'precision'     => '255',
                'phptype'       => 'string',
                'null'          => false,
                'default'       => ''
            ],
            'scheme'        => [
                'dbtype'        => 'varchar',
                'precision'     => '5',
                'phptype'       => 'string',
                'null'          => false,
                'default'       => ''
            ],
            'base'          => [
                'dbtype'        => 'varchar',
                'precision'     => '75',
                'phptype'       => 'string',
                'null'          => false,
                'default'       => '/'
            ],
            'context'       => [
                'dbtype'        => 'varchar',
                'precision'     => '75',
                'phptype'       => 'string',
                'null'          => false,
                'default'       => ''
            ],
            'language'      => [
                'dbtype'        => 'varchar',
                'precision'     => '3',
                'phptype'       => 'string',
                'null'          => false,
                'default'       => ''
            ],
            'site_status'   => [
                'dbtype'        => 'int',
                'precision'     => '1',
                'phptype'       => 'integer',
                'null'          => false,
                'default'       => 0
            ],
            'page_start'    => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'null'          => false,
                'default'       => 0
            ],
            'page_error'    => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'null'          => false,
                'default'       => 0
            ],
            'emailsender'   => [
                'dbtype'        => 'varchar',
                'precision'     => '100',
                'phptype'       => 'string',
                'null'          => true,
                'default'       => ''
            ],
            'primary'       => [
                'dbtype'        => 'int',
                'precision'     => '1',
                'phptype'       => 'integer',
                'null'          => false,
                'default'       => 0
            ],
            'active'        => [
                'dbtype'        => 'int',
                'precision'     => '1',
                'phptype'       => 'integer',
                'null'          => false,
                'default'       => 1
            ],
            'editedon'      => [
                'dbtype'        => 'timestamp',
                'phptype'       => 'timestamp',
                'attributes'    => 'ON UPDATE CURRENT_TIMESTAMP',
                'null'          => false
            ]
        ],
        'indexes'       => [
            'PRIMARY'       => [
                'alias'         => 'PRIMARY',
                'primary'       => true,
                'unique'        => true,
                'columns'       => [
                    'id'            => [
                        'collation'     => 'A',
                        'null'          => false
                    ]
                ]
            ]
        ],
        'aggregates'    => [
            'modContext'    => [
                'local'         => 'context',
                'class'         => 'modContext',
                'foreign'       => 'key',
                'owner'         => 'local',
                'cardinality'   => 'one'	
            ],
            'modPageStart'  => [
                'local'         => 'page_start',
                'class'         => 'modResource',
                'foreign'       => 'id',
                'owner'         => 'local',
                'cardinality'   => 'one'	
            ],
            'modPageError'  => [
                'local'         => 'page_error',
                'class'         => 'modResource',
                'foreign'       => 'id',
                'owner'         => 'local',
                'cardinality'   => 'one'	
            ]
        ]
    ];

?>