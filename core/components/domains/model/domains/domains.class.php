<?php

    /**
     * Domains
     *
     * Copyright 2018 by Oene Tjeerd de Bruin <modx@oetzie.nl>
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
        public $config = [];
        
        /**
         * @access public.
         * @param Object $modx.
         * @param Array $config.
         */
        public function __construct(modX &$modx, array $config = []) {
            $this->modx =& $modx;
            
            $corePath 		= $this->modx->getOption('domains.core_path', $config, $this->modx->getOption('core_path') . 'components/domains/');
            $assetsUrl 		= $this->modx->getOption('domains.assets_url', $config, $this->modx->getOption('assets_url') . 'components/domains/');
            $assetsPath 	= $this->modx->getOption('domains.assets_path', $config, $this->modx->getOption('assets_path') . 'components/domains/');
            
            $this->config = array_merge([
                'namespace'         => $this->modx->getOption('namespace', $config, 'domains'),
                'lexicons'          => ['domains:default'],
                'base_path'         => $corePath,
                'core_path'         => $corePath,
                'model_path'        => $corePath . 'model/',
                'processors_path'   => $corePath . 'processors/',
                'elements_path'     => $corePath . 'elements/',
                'chunks_path'       => $corePath . 'elements/chunks/',
                'plugins_path'      => $corePath . 'elements/plugins/',
                'snippets_path'     => $corePath . 'elements/snippets/',
                'templates_path'    => $corePath . 'templates/',
                'assets_path'       => $assetsPath,
                'js_url'            => $assetsUrl . 'js/',
                'css_url'           => $assetsUrl . 'css/',
                'assets_url'        => $assetsUrl,
                'connector_url'     => $assetsUrl . 'connector.php',
                'version'           => '1.0.5',
                'branding_url'      => $this->modx->getOption('domains.branding_url', null, ''),
                'branding_help_url' => $this->modx->getOption('domains.branding_url_help', null, ''),
                'context'           => $this->getContexts()
            ], $config);
            
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
                return $this->config['branding_help_url'] . '?v=' . $this->config['version'];
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
            return 1 == $this->modx->getCount('modContext', [
                'key:!=' => 'mgr'
            ]);
        }
    }
	
?>