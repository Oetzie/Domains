<?php

    $action = $options[xPDOTransport::PACKAGE_ACTION];
    
    if ($object->xpdo) {
        switch ($action) {
            case xPDOTransport::ACTION_INSTALL:
            case xPDOTransport::ACTION_UPGRADE:
                $modx =& $object->xpdo;
                $modx->addPackage('domains', $modx->getOption('domains.core_path', null, $modx->getOption('core_path') . 'components/domains/') . 'model/');
    
                $manager = $modx->getManager();
    
                $manager->createObjectContainer('DomainsDomain');
    
                break;
        }
    }
    
    return true;

?>