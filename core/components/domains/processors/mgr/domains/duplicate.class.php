<?php
	
    /**
     * Domains
     *
     * Copyright 2018 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */
    
    class DomainsDomainsDuplicateProcessor extends modProcessor {
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
         * @access public
         * @return Mixed.
         */
        public function process() {
            $scheme     = 'http';
            $base       = '/';
            $domain     = $this->getProperty('domain');
            
            if (!preg_match('/^(http|https)/si', $domain)) {
                $domain = $scheme.'//'.$domain;
            }
            
            if (false !== ($parts = parse_url($domain))) {
                if (isset($parts['scheme'])) {
                    $scheme = $parts['scheme'];
                }
                
                if (isset($parts['host'])) {
                    $domain = trim($parts['host'], '/');
                }
                
                if (isset($parts['path'])) {
                    if ('' != ($path = trim($parts['path'], '/'))) {
                        $base = '/'.$path.'/';
                    }
                }
            }
            
            $c = [
                'id' => $this->getProperty('id')	
            ];
            
            if (null !== ($object = $this->modx->getObject($this->classKey, $c))) {
                if (null !== ($newObject = $this->modx->newObject($this->classKey))) {
                    $newObject->fromArray(array_merge($object->toArray(), [
                        'domain'    => $domain,
                        'scheme'    => $scheme,
                        'base'      => $base,
                        'primary'   => 0
                    ]));
                    
                    if ($newObject->save()) {
                        return $this->success('', [
                            'id'=> $newObject->get('id')
                        ]);
                    }
                }
            }
            
            return $this->failure();
        }
    }
    
    return 'DomainsDomainsDuplicateProcessor';
	
?>