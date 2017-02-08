<?php

    /**
	 * Domains
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <info@oetzie.nl>
	 *
	 * This file is part of Domains, a real estate property listings component
	 * for MODX Revolution.
	 *
	 * Domains is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Domains is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Domains; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

    if ('mgr' != $modx->context->key) {
    	switch($modx->event->name) {
    		case 'OnHandleRequest':
    			if ($modx->loadClass('Domains', $modx->getOption('domains.core_path', null, $modx->getOption('core_path').'components/domains/').'model/domains/', true, true)) {
    		        $domains = new Domains($modx);
    		        
    			    if ($domains instanceOf Domains) {
    					if ('' == ($hostname = parse_url($_SERVER['REQUEST_URI'], PHP_URL_HOST))) {
    					    $hostname = $modx->getOption('http_host');
    					}
    					
    					$criterea = array(
    					    'domain'    => rtrim(str_replace('www.', '', $hostname), '/'),
    					    'active'    => 1
    					);
    					
    					if (null !== ($domain = $modx->getObject('DomainsDomains', $criterea))) {
					        $modx->switchContext($domain->context);
					        
					        $modx->setOption('site_status', $domain->site_status);
					        $modx->setOption('site_start', $domain->page_start);
					        $modx->setOption('error_page', $domain->page_error);
					        $modx->setOption('cultureKey', $domain->language);
					        
					        $modx->setPlaceholders(array(
					            'site_start' => $domain->page_start
					        ), '+');
    					}
    				}
    			}
    
    			break;
    	}
    }

	return;
	
?>