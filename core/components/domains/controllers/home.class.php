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

	class DomainsHomeManagerController extends DomainsManagerController {
		/**
		 * @access public.
		 */
		public function loadCustomCssJs() {
			$this->addCss($this->domains->config['css_url'].'mgr/domains.css');
			
			$this->addJavascript($this->domains->config['js_url'].'mgr/widgets/home.panel.js');
			
			$this->addJavascript($this->domains->config['js_url'].'mgr/widgets/domains.grid.js');
			
			$this->addLastJavascript($this->domains->config['js_url'].'mgr/sections/home.js');
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function getPageTitle() {
			return $this->modx->lexicon('domains');
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function getTemplateFile() {
			return $this->domains->config['templates_path'].'home.tpl';
		}
	}

?>