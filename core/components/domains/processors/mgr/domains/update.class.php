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

	class DomainsDomainsUpdateProcessor extends modObjectUpdateProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'DomainsDomains';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('domains:default');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'domains.domains';
		
		/**
		 * @access public.
		 * @var Object.
		 */
		public $domains;
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->domains = $this->modx->getService('domains', 'Domains', $this->modx->getOption('domains.core_path', null, $this->modx->getOption('core_path').'components/domains/').'model/domains/');
			
			if (null === $this->getProperty('active')) {
				$this->setProperty('active', 0);
			}
			
			if (null === $this->getProperty('primary')) {
				$this->setProperty('primary', 0);
			}

			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function beforeSave() {
			$domain = $this->getProperty('domain');
			$context = $this->getProperty('context');
			
			if (!preg_match('/^(http|https)/si', $domain)) {
				$domain = 'http://'.rtrim($domain, '/').'/';
			} else {
				$domain = rtrim($domain, '/').'/';
			}
			
			$criterea = array(
				'id' => $this->getProperty('page_start')
			);

			if (null !== ($object = $this->modx->getObject('modResource', $criterea))) {
				if ($context != $object->context_key) {
					$this->addFieldError('site_start_formatted', $this->modx->lexicon('domains.error_site_start_context'));
				} else if (1 == $object->deleted) {
					$this->addFieldError('site_start_formatted', $this->modx->lexicon('domains.error_site_start_deleted'));
				} else if (0 == $object->published) {
					$this->addFieldError('site_start_formatted', $this->modx->lexicon('domains.error_site_start_published'));
				}
			} else {
				$this->addFieldError('site_start_formatted', $this->modx->lexicon('domains.error_site_start'));
			}
			
			$criterea = array(
				'id' => $this->getProperty('page_error')
			);
			
			if (null !== ($object = $this->modx->getObject('modResource', $criterea))) {
				if ($context != $object->context_key) {
					$this->addFieldError('site_error_formatted', $this->modx->lexicon('domains.error_site_error_context'));
				} else if (1 == $object->deleted) {
					$this->addFieldError('site_error_formatted', $this->modx->lexicon('domains.error_site_error_deleted'));
				} else if (0 == $object->published) {
					$this->addFieldError('site_error_formatted', $this->modx->lexicon('domains.error_site_error_published'));
				}
			} else {
				$this->addFieldError('site_error_formatted', $this->modx->lexicon('domains.error_site_error'));
			}
			
			$this->setProperty('domain', str_replace(array('http://', 'https://'), '', rtrim($domain, '/')));

			if (1 == $this->getProperty('primary')) {
				$criterea = array(
					'context_key' 	=> $context,
					'key'			=> 'site_url'
				);
		
				if (null === ($setting = $this->modx->getObject('modContextSetting', $criterea))) {
					$setting = $this->modx->newObject('modContextSetting');
				}
				
				$setting->fromArray(array_merge($criterea, array(
	                'xtype' 		=> 'textfield',
	                'namespace' 	=> 'core',
	                'area' 			=> 'site',
	                'value'			=> $domain
	            )), null, true);
	            
	            $setting->save();
	            
	            $criterea = array(
		            'id:!='		=> $this->object->id,
					'context' 	=> $context
				);
				
				foreach ($this->modx->getCollection('DomainsDomains', $criterea) as $domain) {
					$domain->fromArray(array(
						'primary' => 0
					));
					
					$domain->save();
				}
	        }
	        
	        $this->modx->cacheManager->refresh(array(
                'db' 				=> array(),
                'context_settings' 	=> array(
                	'contexts' 			=> array($context)
                ),
                'resource' 			=> array(
                	'contexts' 			=> array($context)
                )
            ));
			
			return parent::beforeSave();
		}
	}
	
	return 'DomainsDomainsUpdateProcessor';
	
?>