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

	class DomainsDomainsDuplicateProcessor extends modProcessor {
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
		
			if (null !== ($domain = $this->getProperty('domain'))) {
				$this->setProperty('domain', str_replace(array('http://', 'https://'), '', rtrim($domain, '/')));
			}

			return parent::initialize();
		}
		
		/**
		 * @acces public
		 * @return Mixed.
		 */
		public function process() {
			$criterea = array(
				'id' => $this->getProperty('id')	
			);
			
			if (null !== ($original = $this->modx->getObject($this->classKey, $criterea))) {
				if (null !== ($duplicate = $this->modx->newObject($this->classKey))) {
					$duplicate->fromArray(array_merge($original->toArray(), array(
						'domain' => $this->getProperty('domain')
					)));
					
					if ($duplicate->save()) {
						return $this->success('', array(
							'id'=> $duplicate->id
						));
					}
				}
			}
			
			return $this->failure();
		}
	}
	
	return 'DomainsDomainsDuplicateProcessor';
	
?>