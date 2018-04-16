<?php

    /**
     * Domains
     *
     * Copyright 2018 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */
    
    require_once dirname(__DIR__) . '/index.class.php';
    
    class DomainsHomeManagerController extends DomainsManagerController {
        /**
         * @access public.
         */
        public function loadCustomCssJs() {
            $this->addCss($this->modx->domains->config['css_url'] . 'mgr/domains.css');
            
            $this->addJavascript($this->modx->domains->config['js_url'] . 'mgr/widgets/home.panel.js');
            
            $this->addJavascript($this->modx->domains->config['js_url'] . 'mgr/widgets/domains.grid.js');
            
            $this->addLastJavascript($this->modx->domains->config['js_url'] . 'mgr/sections/home.js');
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
            return $this->modx->domains->config['templates_path'] . 'home.tpl';
        }
    }

?>