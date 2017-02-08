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

	if ($modx->loadClass('Domains', $modx->getOption('domains.core_path', null, $modx->getOption('core_path').'components/domains/').'model/domains/', true, true)) {
        $domains = new Domains($modx);
        
	    if ($domains instanceOf Domains) {
		    $output = array();
		    
		    $criterea = array(
		        'active'            => 1,
		        'context:NOT IN'    => explode(',', $modx->getOption('exclude', $scriptProperties, 'nc'))
		    );
		    
		    foreach ($modx->getCollection('DomainsDomains', $criterea) as $domain) {
		        $output[] = $domain->toArray();
		    }
		    
		    foreach ($output as $key => $value) {
		        $class = array();
					
				if (0 == $key) {
					$class[] = 'first';
				}
				
				if (count($output) - 1 == $key) {
					$class[] = 'last';
				}
				
				$class[] = 0 == $key % 2 ? 'odd' : 'even';
				
				$class[] = $value['language'];
				
				if ($value['context'] == $modx->context->key) {
					$class[] = 'active';
				}
				
				$output[$key] = $domains->getTemplate($modx->getOption('tpl', $scriptProperties), array_merge($value, array(
				    'class'     => implode(' ', $class),
				    'language'  => strtoupper($value['language']),
				    'url'       => $value['domain']
		        )));
		    }
		    
		    if (1 < count($output)) {
		        if (false !== ($tplWrapper = $modx->getOption('tplWrapper', $scriptProperties, false))) {
		            return $domains->getTemplate($tplWrapper, array(
		                'output' => implode(PHP_EOL, $output)
		            ));
		        } else {
		            return implode(PHP_EOL, $output);
		        }
		    }
		}
	}
	
	return;
	
?>