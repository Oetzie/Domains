<?php

    /**
     * Domains
     *
     * Copyright 2018 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */
     
    require_once __DIR__ . '/domains.class.php';
    
    class DomainsPlugin extends Domains {
        /**
         * @access public.
         * @param Array $properties.
         * @return String.
         */
        public function run($properties = []) {
            $base   = '/';
            $param  = $this->modx->getOption('request_param_alias', null, 'q');
            $uri    = $_REQUEST[$param];
            
            if ('' != ($path = trim($_REQUEST[$param], '/'))) {
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
            
            $c = $this->modx->newQuery('DomainsDomain');
            
            $c->where([
                [
                    'domain:='      => trim($_SERVER['HTTP_HOST'], '/'),
                    'OR:domain:='   => str_replace('www.', '', trim($_SERVER['HTTP_HOST'], '/'))
                ],
                'active' => 1
            ]);
            
            $c->sortby('domain', 'ASC');
            $c->sortby('base', 'DESC');
            
            foreach ($this->modx->getCollection('DomainsDomain', $c) as $object) {
                if ('/' == $object->get('base') || $base == $object->get('base')) {
                    if ($object->get('context') != $this->modx->context->get('key')) {
                        $this->modx->switchContext($object->get('context'));
                    }
                    
                    $settings = [
                        'cultureKey'        => 'language',
                        'emailsender'       => 'emailsender',
                        'site_status'       => 'site_status',
                        'site_start'        => 'page_start',
                        'error_page'        => 'page_error',
                        'base_url'          => 'base',
                        'link_tag_scheme'   => 'scheme',
                        'site_url'          => $object->get('scheme') . '://' . $object->get('domain') . $object->get('base')
                    ];
                    
                    foreach ($settings as $key => $value) {
                        if ('site_url' !== $key) {
                            $settings[$key] = $object->get($value); 
                            
                            $this->modx->setOption($key, $object->get($value));
                        }
                    }
                    
                    $this->modx->setPlaceholders($settings, '+');
                    
                    if ('/' != $object->get('base')) {
                        $_REQUEST[$param] = trim($uri, '/');
                    }

                    break;
                }
            }
        }
    }
	
?>