<?php

    /**
     * Domains
     *
     * Copyright 2018 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */
    
    if ('mgr' != $modx->context->get('key')) {
        switch($modx->event->name) {
            case 'OnHandleRequest':
                $modx->getService('domainsPlugin', 'DomainsPlugin', $modx->getOption('domains.core_path', null, $modx->getOption('core_path') . 'components/domains/') . 'model/domains/');
                
                if ($modx->domainsPlugin instanceof DomainsPlugin) {
                    return $modx->domainsPlugin->run($scriptProperties);
                }
                        
                break;
        }
    }
    
    return;
    
?>