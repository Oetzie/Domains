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

	class Domains {
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $modx;
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $config = array();
		
		/**
		 * @acces public.
		 * @param Object $modx.
		 * @param Array $config.
		 */
		public function __construct(modX &$modx, array $config = array()) {
			$this->modx =& $modx;
		
			$corePath 		= $this->modx->getOption('domains.core_path', $config, $this->modx->getOption('core_path').'components/domains/');
			$assetsUrl 		= $this->modx->getOption('domains.assets_url', $config, $this->modx->getOption('assets_url').'components/domains/');
			$assetsPath 	= $this->modx->getOption('domains.assets_path', $config, $this->modx->getOption('assets_path').'components/domains/');
		
			$this->config = array_merge(array(
				'namespace'				=> $this->modx->getOption('namespace', $config, 'domains'),
				'helpurl'				=> $this->modx->getOption('namespace', $config, 'domains'),
				'lexicons'				=> array('domains:default'),
				'base_path'				=> $corePath,
				'core_path' 			=> $corePath,
				'model_path' 			=> $corePath.'model/',
				'processors_path' 		=> $corePath.'processors/',
				'elements_path' 		=> $corePath.'elements/',
				'chunks_path' 			=> $corePath.'elements/chunks/',
				'cronjobs_path' 		=> $corePath.'elements/cronjobs/',
				'plugins_path' 			=> $corePath.'elements/plugins/',
				'snippets_path' 		=> $corePath.'elements/snippets/',
				'templates_path' 		=> $corePath.'templates/',
				'assets_path' 			=> $assetsPath,
				'js_url' 				=> $assetsUrl.'js/',
				'css_url' 				=> $assetsUrl.'css/',
				'assets_url' 			=> $assetsUrl,
				'connector_url'			=> $assetsUrl.'connector.php',
				'context'				=> $this->getContexts()
			), $config);
		
			$this->modx->addPackage('domains', $this->config['model_path']);
			
			if (is_array($this->config['lexicons'])) {
				foreach ($this->config['lexicons'] as $lexicon) {
					$this->modx->lexicon->load($lexicon);
				}
			} else {
				$this->modx->lexicon->load($this->config['lexicons']);
			}
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getHelpUrl() {
			return $this->config['helpurl'];
		}
		
		/**
		 * @acces private.
		 * @return Boolean.
		 */
		private function getContexts() {
			return 1 == $this->modx->getCount('modContext', array(
				'key:!=' => 'mgr'
			));
		}
		
		/**
		 * @acces public.
		 * @param String $tpl.
		 * @param Array $properties.
		 * @param String $type.
		 * @return String.
		 */
		public function getTemplate($template, $properties = array(), $type = 'CHUNK') {
			if (0 === strpos($template, '@')) {
				$type 		= substr($template, 1, strpos($template, ':') - 1);
				$template	= substr($template, strpos($template, ':') + 1, strlen($template));
			}
			
			switch (strtoupper($type)) {
				case 'INLINE':
					$chunk = $this->modx->newObject('modChunk', array(
						'name' => $this->config['namespace'].uniqid()
					));
				
					$chunk->setCacheable(false);
				
					$output = $chunk->process($properties, ltrim($template));
				
					break;
				case 'CHUNK':
					$output = $this->modx->getChunk(ltrim($template), $properties);
				
					break;
			}
			
			return $output;
		}
	}
	
?>