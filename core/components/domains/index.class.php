<?php

    /**
     * Domains
     *
     * Copyright 2018 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */
    
    abstract class DomainsManagerController extends modExtraManagerController {
        /**
         * @access public.
         * @return Mixed.
         */
        public function initialize() {
            $this->modx->getService('domains', 'Domains', $this->modx->getOption('domains.core_path', null, $this->modx->getOption('core_path') . 'components/domains/') . 'model/domains/');
            
            $this->addJavascript($this->modx->domains->config['js_url'] . 'mgr/domains.js');
            
            $this->addHtml('<script type="text/javascript">
                Ext.onReady(function() {
                    MODx.config.help_url = "' . $this->modx->domains->getHelpUrl() . '";
                    
                    Domains.config = ' . $this->modx->toJSON(array_merge($this->modx->domains->config, [
                        'branding_url'          => $this->modx->domains->getBrandingUrl(),
                        'branding_url_help'     => $this->modx->domains->getHelpUrl()
                    ])) . ';
                });
            </script>');
            
            return parent::initialize();
        }
        
        /**
         * @access public.
         * @return Array.
         */
        public function getLanguageTopics() {
            return $this->modx->domains->config['lexicons'];
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