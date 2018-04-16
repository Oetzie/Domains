<?php

    /**
     * Domains
     *
     * Copyright 2018 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */
    
    require_once dirname(dirname(dirname(__DIR__))) . '/config.core.php';
    
    require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
    require_once MODX_CONNECTORS_PATH . 'index.php';
    
    $modx->getService('domains', 'Domains', $modx->getOption('domains.core_path', null, $modx->getOption('core_path') . 'components/domains/') . 'model/domains/');
    
    if ($modx->domains instanceOf Domains) {
        $modx->request->handleRequest([
            'processors_path'   => $modx->domains->config['processors_path'],
            'location'          => ''
        ]);
    }

?>