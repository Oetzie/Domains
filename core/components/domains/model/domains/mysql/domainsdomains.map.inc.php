<?php

	/**
	 * Domains
	 *
	 * Copyright 2017 by Oene Tjeerd de Bruin <modx@oetzie.nl>
	 *
	 * Domains is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Domains is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Domains; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	$xpdo_meta_map['DomainsDomains']= array(
		'package' 	=> 'domains',
		'version' 	=> '1.0',
		'table' 	=> 'domains_domains',
		'extends' 	=> 'xPDOSimpleObject',
		'fields' 	=> array(
			'id'			=> null,
			'domain'		=> null,
			'context'		=> null,
			'language'		=> null,
			'site_status'	=> null,
			'page_start' 	=> null,
			'page_error' 	=> null,
			'primary'		=> null,
			'active'		=> null,
			'editedon' 		=> null
		),
		'fieldMeta'	=> array(
			'id' 		=> array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'index' 	=> 'pk',
				'generated'	=> 'native'
			),
			'domain' 	=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '255',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'context' 	=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '75',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'language' 	=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '3',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'site_status' => array(
				'dbtype' 	=> 'int',
				'precision' => '1',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'default'	=> 0
			),
			'page_start' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'page_error' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'primary'	=> array(
				'dbtype' 	=> 'int',
				'precision' => '1',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'default'	=> 0
			),
			'active'	=> array(
				'dbtype' 	=> 'int',
				'precision' => '1',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'default'	=> 1
			),
			'editedon' 	=> array(
				'dbtype' 	=> 'timestamp',
				'phptype' 	=> 'timestamp',
				'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
				'null' 		=> false
			)
		),
		'indexes'	=> array(
			'PRIMARY'	=> array(
				'alias' 	=> 'PRIMARY',
				'primary' 	=> true,
				'unique' 	=> true,
				'columns' 	=> array(
					'id' 		=> array(
						'collation' => 'A',
						'null' 		=> false,
					)
				)
			)
		),
		'aggregates' => array(
			'modContext' => array(
				'local'			=> 'context',
				'class'			=> 'modContext',
				'foreign'		=> 'key',
				'owner'			=> 'local',
				'cardinality'	=> 'one'	
			),
			'modResourceStart' => array(
				'local'			=> 'page_start',
				'class'			=> 'modResource',
				'foreign'		=> 'id',
				'owner'			=> 'local',
				'cardinality'	=> 'one'	
			),
			'modResourceError' => array(
				'local'			=> 'page_error',
				'class'			=> 'modResource',
				'foreign'		=> 'id',
				'owner'			=> 'local',
				'cardinality'	=> 'one'	
			)
		)
	);

?>