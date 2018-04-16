<?php

    /**
     * Domains
     *
     * Copyright 2018 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */
    
    class DomainsDomainsGetListProcessor extends modObjectGetListProcessor {
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
        public $defaultSortField = 'id';
        
        /**
         * @access public.
         * @var String.
         */
        public $defaultSortDirection = 'DESC';
        
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
            
            $this->setDefaultProperties([
                'dateFormat' => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format')
            ]);
            
            return parent::initialize();
        }
    
        /**
         * @access public.
         * @param Object $c.
         * @return Object.
         */
        public function prepareQueryBeforeCount(xPDOQuery $c) {			
            $query = $this->getProperty('query');
            
            if (!empty($query)) {
                $c->where([
                    'domain:LIKE' => '%' . $query . '%'
                ]);
            }
            
            return $c;
        }
    
        /**
         * @access public.
         * @param Object $object.
         * @return Array.
         */
        public function prepareRow(xPDOObject $object) {
            $array = array_merge($object->toArray(), [
                'domain'                => $object->get('scheme') . '://' . $object->get('domain') . $object->get('base'),
                'error'                 => false,
                'context_name'          => '',
                'page_start_formatted'  => '',
                'page_error_formatted'  => ''
            ]);
        
            if (null !== ($context = $object->getOne('modContext'))) {
                $array['context_name'] = $context->get('name');
            }
        
            if (null !== ($resource = $object->getOne('modPageStart'))) {
                $array['page_start_formatted'] = $resource->get('pagetitle') . ($this->modx->hasPermission('tree_show_resource_ids') ? ' (' . $resource->get('id') . ')' : '');
            } else {
                $array['error'] = true;
            }
        
            if (null !== ($resource = $object->getOne('modPageError'))) {
                $array['page_error_formatted'] = $resource->get('pagetitle').($this->modx->hasPermission('tree_show_resource_ids') ? ' (' . $resource->get('id') . ')' : '');
            } else {
                $array['error'] = true;
            }
        
            if (in_array($object->get('editedon'), ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null])) {
                $array['editedon'] = '';
            } else {
                $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));
            }
            
            return $array;	
        }
    }
    
    return 'DomainsDomainsGetListProcessor';
	
?>