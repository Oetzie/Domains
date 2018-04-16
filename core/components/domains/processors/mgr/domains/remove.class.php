<?php

    /**
     * Domains
     *
     * Copyright 2018 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */
    
    class DomainsDomainsRemoveProcessor extends modObjectRemoveProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DomainsDomain';
        
        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = ['domains:default'];
        
        /**
         * @access public.
         * @var String.
         */
        public $objectType = 'domains.domain';
        
        /**
         * @access public.
         * @return Mixed.
         */
        public function initialize() {
            $this->modx->getService('domains', 'Domains', $this->modx->getOption('domains.core_path', null, $this->modx->getOption('core_path') . 'components/domains/') . 'model/domains/');
        
            return parent::initialize();
        }
        
        /**
         * @acces public.
         * @return Mixed.
         */
        public function beforeRemove() {
            if (1 == $this->object->get('primary')) {
                $this->failure($this->modx->lexicon('domains.error_remove_primary'));
            }
        
            return parent::beforeRemove();
        }
    }
    
    return 'DomainsDomainsRemoveProcessor';
	
?>