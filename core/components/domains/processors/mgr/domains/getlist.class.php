<?php

	/**
	 * Domains
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <info@oetzie.nl>
	 *
	 * This file is part of Domains, a real estate property listings component
	 * for MODX Revolution.
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

	class DomainsDomainsGetListProcessor extends modObjectGetListProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $classKey = 'DomainsDomains';
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $languageTopics = array('domains:default');
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $defaultSortField = 'id';
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $defaultSortDirection = 'DESC';
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $objectType = 'domains.domains';
		
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $domains;
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->domains = $this->modx->getService('domains', 'Domains', $this->modx->getOption('domains.core_path', null, $this->modx->getOption('core_path').'components/domains/').'model/domains/');

			$this->setDefaultProperties(array(
				'dateFormat' 	=> $this->modx->getOption('manager_date_format') .', '. $this->modx->getOption('manager_time_format')
			));
			
			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @param Object $c.
		 * @return Object.
		 */
		public function prepareQueryBeforeCount(xPDOQuery $c) {			
			$query = $this->getProperty('query');
			
			if (!empty($query)) {
				$c->where(array(
					'domain:LIKE' 	=> '%'.$query.'%'
				));
			}
			
			return $c;
		}
		
		/**
		 * @acces public.
		 * @param Object $query.
		 * @return Array.
		 */
		public function prepareRow(xPDOObject $object) {
			$array = array_merge($object->toArray(), array(
				'error'					=> false,
				'context_name'			=> '',
				'page_start_formatted'	=> '',
				'page_error_formatted'	=> ''
			));
			
			if (null !== ($context = $object->getOne('modContext'))) {
				$array['context_name'] = $context->name;
			}
			
			if (null !== ($resource = $object->getOne('modResourceStart'))) {
				$array['page_start_formatted'] = $resource->pagetitle.($this->modx->hasPermission('tree_show_resource_ids') ? ' ('.$resource->id.')' : '');
			} else {
				$array['error'] = true;
			}
			
			if (null !== ($resource = $object->getOne('modResourceError'))) {
				$array['page_error_formatted'] = $resource->pagetitle.($this->modx->hasPermission('tree_show_resource_ids') ? ' ('.$resource->id.')' : '');
			} else {
				$array['error'] = true;
			}

			if (in_array($array['editedon'], array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
				$array['editedon'] = '';
			} else {
				$array['editedon'] = date($this->getProperty('dateFormat'), strtotime($array['editedon']));
			}
			
			return $array;	
		}
	}

	return 'DomainsDomainsGetListProcessor';
	
?>