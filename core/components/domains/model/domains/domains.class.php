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
    
    class Domains {
        /**
        * @access public.
        * @var Object.
        */
        public $modx;
        
        /**
        * @access public.
        * @var Array.
        */
        public $config = array();
        
        /**
         * @access public.
         * @param Object $modx.
         * @param Array $config.
         */
        public function __construct(modX &$modx, array $config = array()) {
            $this->modx =& $modx;
            
            $corePath 		= $this->modx->getOption('domains.core_path', $config, $this->modx->getOption('core_path').'components/domains/');
            $assetsUrl 		= $this->modx->getOption('domains.assets_url', $config, $this->modx->getOption('assets_url').'components/domains/');
            $assetsPath 	= $this->modx->getOption('domains.assets_path', $config, $this->modx->getOption('assets_path').'components/domains/');
            
            $this->config = array_merge(array(
                'namespace'             => $this->modx->getOption('namespace', $config, 'domains'),
                'lexicons'              => array('domains:default'),
                'base_path'             => $corePath,
                'core_path'             => $corePath,
                'model_path'            => $corePath.'model/',
                'processors_path'       => $corePath.'processors/',
                'elements_path'         => $corePath.'elements/',
                'chunks_path'           => $corePath.'elements/chunks/',
                'cronjobs_path'         => $corePath.'elements/cronjobs/',
                'plugins_path'          => $corePath.'elements/plugins/',
                'snippets_path'         => $corePath.'elements/snippets/',
                'templates_path'        => $corePath.'templates/',
                'assets_path'           => $assetsPath,
                'js_url'                => $assetsUrl.'js/',
                'css_url'               => $assetsUrl.'css/',
                'assets_url'            => $assetsUrl,
                'connector_url'         => $assetsUrl.'connector.php',
                'version'               => '1.0.4',
                'branding_url'          => $this->modx->getOption('domains.branding_url', null, ''),
                'branding_help_url'     => $this->modx->getOption('domains.branding_url_help', null, ''),
                'context'               => $this->getContexts()
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
         * @access public.
         * @return String|Boolean.
         */
        public function getHelpUrl() {
            if (!empty($this->config['branding_help_url'])) {
                return $this->config['branding_help_url'].'?v=' . $this->config['version'];
            }
    
            return false;
        }
    
        /**
         * @access public.
         * @return String|Boolean.
         */
        public function getBrandingUrl() {
            if (!empty($this->config['branding_url'])) {
                return $this->config['branding_url'];
            }
            
            return false;
        }
        
        /**
         * @access private.
         * @return Boolean.
         */
        private function getContexts() {
            return 1 == $this->modx->getCount('modContext', array(
                'key:!=' => 'mgr'
            ));
        }
        
        /**
         * @access public.
         * @param Array $properties.
         * @return String.
         */
        public function run($properties = array()) {
            $base   = '/';
            $uri    = $_SERVER['REQUEST_URI'];
            
            if ('' != ($path = trim($_SERVER['REQUEST_URI'], '/'))) {
                $path = explode('/', $path, 2);
            
                if (isset($path[0])) {
                    $base = '/'.trim($path[0], '/').'/';
                }
            
                if (isset($path[1])) {
                    $uri = trim($path[1]);
                } else {
                    $uri = '/';
                }
            }
            
            $c = $this->modx->newQuery('DomainsDomains');
            
            $c->where(array(
                array(
                    'domain:='      => trim($_SERVER['HTTP_HOST'], '/'),
                    'OR:domain:='   => str_replace('www.', '', trim($_SERVER['HTTP_HOST'], '/'))
                ),
                'active' => 1
            ));
            
            $c->sortby('domain', 'ASC');
            $c->sortby('base', 'DESC');
            
            foreach ($this->modx->getCollection('DomainsDomains', $c) as $object) {
                if ('/' == $object->get('base') || $base == $object->get('base')) {
                    $this->modx->switchContext($object->get('context'));
                    
                    $this->modx->setOption('cultureKey', $object->get('language'));
                    $this->modx->setOption('site_status', $object->get('site_status'));
                    $this->modx->setOption('site_start', $object->get('page_start'));
                    $this->modx->setOption('error_page', $object->get('page_error'));
                    $this->modx->setOption('site_url', $object->get('scheme').'://'.$object->get('domain').$object->get('base'));
                    $this->modx->setOption('base_url', $object->get('base'));
                    $this->modx->setOption('link_tag_scheme', $object->get('scheme'));
                    
                    $this->modx->setPlaceholders(array(
                        'site_start' => $object->get('page_start')
                    ), '+');
                    
                    if ('/' != $object->get('base')) {
                        $_REQUEST[$this->modx->getOption('request_param_alias', null, 'q')] = trim($uri, '/');
                    }
                    
                    break;
                }
            }
        }
    }
	
?>