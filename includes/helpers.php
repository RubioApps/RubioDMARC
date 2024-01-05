<?php
/**
 +-------------------------------------------------------------------------+
 | RubioDMARC  - An OpenDMARC Webapp                                       |
 | Version 1.0.0                                                           |
 |                                                                         |
 | This program is free software: you can redistribute it and/or modify    |
 | it under the terms of the GNU General Public License as published by    |
 | the Free Software Foundation.                                           |
 |                                                                         |
 | This file forms part of the RubioTV software.                           |
 |                                                                         |
 | If you wish to use this file in another project or create a modified    |
 | version that will not be part of the RubioTV Software, you              |
 | may remove the exception above and use this source code under the       |
 | original version of the license.                                        |
 |                                                                         |
 | This program is distributed in the hope that it will be useful,         |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of          |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            |
 | GNU General Public License for more details.                            |
 |                                                                         |
 | You should have received a copy of the GNU General Public License       |
 | along with this program.  If not, see http://www.gnu.org/licenses/.     |
 |                                                                         |
 +-------------------------------------------------------------------------+
 | Author: Jaime Rubio <jaime@rubiogafsi.com>                              |
 +-------------------------------------------------------------------------+
*/

namespace OpenDMARC\Framework;

defined('_ODEXEC') or die;

use OpenDMARC\Framework\Language\Text;

class Helpers{

    /**
     * Provides a name of the DMARC Policy depending on the value 'policy' of the recorded message
     * DMARC Policy: 14=Unknown , 15=Pass , 16=Reject , 17=Quarantine , 18=None
     * This is the parameter 'p' in the TXT record of the DMARC DNS
     * @param type $item
     * @return type
     */
    
    public static function getPolicy ( &$item )
    {
        switch ($item->policy){
            case 14:
                $item->policy_txt = Text::_('POLICY_UNKNOWN');
                $item->color = 'lightblue';
                break;
            case 15:
                $item->policy_txt = Text::_('POLICY_PASS');
                $item->color = '#4da358';            
                break;
            case 16:
                $item->policy_txt = Text::_('POLICY_REJECT');
                $item->color = 'lightred';            
                break;
            case 17:
                $item->policy_txt = Text::_('POLICY_QUARANTINE');
                $item->color = 'yellow';            
                break;
            case 18:
                $item->policy_txt = Text::_('POLICY_NONE');
                $item->color = 'lightblue';            
                break;   
            default:
                $item->policy_txt = Text::_('UNVALID');             
                $item->color = 'gray';            
        }  
        return $item;
    }
        
    /**
     * 
     * @param type $item
     * @return type
     */
    public static function getDisposition( &$item) 
    {
        switch ($item->disp){
            case 0:
            case 1:
                $item->disp_txt = Text::_('SPF_POLICY_REJECT');
                break;
            case 2:
                $item->disp_txt = Text::_('SPF_POLICY_NONE');
                break;
            case 3:
                $item->disp_txt = Text::_('SPF_POLICY_QUARANTINE');
                break;
            default:
                $item->disp_txt = Text::_('UNVALID');        
        }  
        return $item;
    }

    /**
     * 
     * @param type $item
     * @return type
     */
    public static function getAlignmentSet( &$item )
    {
        $item->align_spf_txt = ( $item->align_spf == 4 ? Text::_('ALIGNMENT_SET_YES') : Text::_('ALIGNMENT_SET_NO'));
        $item->align_dkim_txt = ( $item->align_dkim == 4 ? Text::_('ALIGNMENT_SET_YES') : Text::_('ALIGNMENT_SET_NO'));
        return $item;
    }
    

     /**
     * 
     * @param type $item
     * @return type
     */
    public static function getEvaluation( &$item )
    {
        switch ($item->spf){
            case 0:
                $item->spf_txt = Text::_('EVALUATION_PASS');
                break;
            case 2:
                $item->spf_txt = Text::_('EVALUATION_FAIL');
                break;
            case 6:
                $item->spf_txt = Text::_('EVALUATION_NONE');
                break;            
            default:
                $item->spf_txt = Text::_('EVALUATION_NOT_EVALUATED');        
        }  

        switch ($item->dkim){
            case 0:
                $item->dkim_txt = Text::_('EVALUATION_PASS');
                break;
            case 2:
                $item->dkim_txt = Text::_('EVALUATION_FAIL');
                break;
            case 6:
                $item->dkim_txt = Text::_('EVALUATION_NONE');
                break;            
            default:
                $item->dkim_txt = Text::_('EVALUATION_NOT_EVALUATED');         
        }  
        return $item;
    }
    /**
     * 
     * @param type $items
     * @param type $limit
     * @return \stdClass
     */
    public static function getTopItems( $items = array() , $limit = 5)
    {
        $ret = array();
        $i = $topfive = $total = 0;  
        
        if(is_array($items)){
            foreach($items as $item){            
                if($i < $limit) {
                    $topfive += $item->cnt;
                    $ret[] = $item;
                } 
                $total += $item->cnt;
                $i++;
            }
        }
        
        //Rest of World
        $row = new \stdClass();
        $row->name = Text::_('OTHERS');
        $row->cnt = $total - $topfive ;
        $ret[] = $row;        
        
        return $ret;
    }
    
    /**
     * Published policy's alignment rule for DKIM and SPF
     * 
     * @param type $item
     * @return type
     */
    public static function getAlignmentMode( &$item){

        switch ($item->aspf){
            case 114:
                $item->aspf_txt = Text::_('ALIGNMENT_MODE_RELAXED');
                break;
            case 115:
                $item->aspf_txt = Text::_('ALIGNMENT_MODE_STRICT');
                break;
            default:
                $item->aspf_txt = Text::_('ALIGNMENT_MODE_UNKNOWN');        
        }  

        switch ($item->adkim){
            case 114:
                $item->adkim_txt = Text::_('ALIGNMENT_MODE_RELAXED');
                break;
            case 115:
                $item->adkim_txt = Text::_('ALIGNMENT_MODE_STRICT');
                break;
            default:
                $item->adkim_txt = Text::_('ALIGNMENT_MODE_UNKNOWN');        
        }  
        
        return $item;        
    }

   
    /**
     * Domain policy
     */
    public static function getDomainPolicy(&$item)
    {
        switch ($item->policy){
            case 0:
                $item->policy_txt = Text::_('POLICY_UNKNOWN');
                break;                
            case 110:
                $item->policy_txt = Text::_('POLICY_NONE');
                break;
            case 113:
                $item->policy_txt = Text::_('POLICY_QUARANTINE');
                break;            
            case 114:
                $item->policy_txt = Text::_('POLICY_REJECT');
                break;
            default:
                $item->policy_txt = Text::_('UNVALID');        
        }  
        return $item;            
    }    

    /**
     * Subdomain policy
     */
    public static function getSubdomainPolicy(&$item)
    {
        switch ($item->spolicy){
            case 0:
                $item->spolicy_txt = Text::_('POLICY_UNKNOWN');
                break;                
            case 110:
                $item->spolicy_txt = Text::_('POLICY_NONE');
                break;
            case 113:
                $item->spolicy_txt = Text::_('POLICY_QUARANTINE');
                break;            
            case 114:
                $item->spolicy_txt = Text::_('POLICY_REJECT');
                break;
            default:
                $item->spolicy_txt = Text::_('UNVALID');        
        }  
        return $item;        
    }    
    
    /**
     * ARC evaluation (0 = pass, 2 = fail)
     */
    public static function getARCEvaluation(&$item)
    {
        switch ($item->arc){
            case 0:
                $item->arc_txt = Text::_('ARC_PASS');
                break;
            case 2:
                $item->arc_txt = Text::_('ARC_FAIL');
                break;
            default:
                $item->arc_txt = Text::_('UNVALID');        
        }         
        
        switch ($item->arc_policy){
            case 0:
                $item->arc_policy_txt = Text::_('ARC_PASS');
                break;
            case 2:
                $item->arc_policy_txt = Text::_('ARC_FAIL');
                break;
            default:
                $item->arc_policy_txt = Text::_('UNVALID');        
        }  
        return $item;        
    }    
    
    static public function rebuildDNS (&$item)
    {
        $ret = array();        
        $ret[] = "v=DMARC1"; 
        
        switch ($item->policy){
            case 110:
                $ret[] = "p=none";
                break;
            case 113:
                $ret[] = "p=quarantine";
                break;            
            case 114:
                $ret[] = "p=reject";                 
        }  
        
        switch ($item->spolicy){
            case 110:
                $ret[] = "p=none";
                break;
            case 113:
                $ret[] = "p=quarantine";
                break;            
            case 114:
                $ret[] = "p=reject";                 
        }  
        
        switch ($item->aspf){
            case 114:
                $ret[] = "aspf=r";
                break;
            case 115:
                $ret[] = "aspf=s";                 
        }  

        switch ($item->adkim){
            case 114:
                $ret[] = "adkim=r";   
                break;
            case 115:
                $ret[] = "adkim=s";     
        }                     
        
        $item->dns = join(", " , $ret); 
        return $item;
    }
        
    
}

