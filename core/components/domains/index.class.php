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

	abstract class DomainsManagerController extends modExtraManagerController {
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
			
			$this->addJavascript($this->domains->config['js_url'].'mgr/domains.js');
			
			$this->addHtml('<script type="text/javascript">
				Ext.onReady(function() {
					MODx.config.help_url = "'.$this->domains->getHelpUrl().'";
			
					Domains.config = '.$this->modx->toJSON($this->domains->config).';
				});
			</script>');
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Array.
		 */
		public function getLanguageTopics() {
			return $this->domains->config['lexicons'];
		}
		
		/**
		 * @access public.
		 * @returns Boolean.
		 */	    
		public function checkPermissions() {
			return $this->modx->hasPermission('domains');
		}
	}
		
	class IndexManagerController extends DomainsManagerController {
		/**
		 * @access public.
		 * @return String.
		 */
		public static function getDefaultController() {
			return 'home';
		}
	}

?>