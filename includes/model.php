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

use OpenDMARC\Framework\Pagination;

class Model
{
    protected static $database;
    
    public static function setDatabase ($db)
    {
        static::$database = $db;
    }
    
    public static function getMessages ( $offset=0 , $limit=30)
    {        
        
        if(!static::$database){
            @trigger_error(
                    'this function needs to set a database to the mode. Please, use setDatabase first.',
                    E_USER_ERROR
                );   
            return false;
        }
        
        $sql = "SELECT m.*, a.addr , e.name AS eval_domain  , d.name as from_domain , r.name AS reporter "
                . " FROM messages AS m , ipaddr AS a , domains AS e , domains AS d  , reporters AS r"
                . " WHERE m.from_domain = d.id "
                . " AND m.policy_domain = e.id "
                . " AND m.ip = a.id"
                . " AND m.reporter = r.id"
                . " ORDER BY m.date DESC"
                . " LIMIT " . $offset . "," . $limit        
                ;
        $rows = static::$database->loadRows($sql);
            
        foreach($rows as $row)
        {                
            Helpers::getDisposition($row);
            Helpers::getPolicy($row);
            Helpers::getAlignmentSet($row);   
            Helpers::getARCEvaluation($row);  
        }
        return $rows;

    }

    public static function getPagination ( $offset=0 , $limit=30)
    {        
        if(!static::$database){
            @trigger_error(
                    'this function needs to set a database to the mode. Please, use setDatabase first.',
                    E_USER_ERROR
                );   
            return false;
        }
                
        $total = static::$database->getNumRows("SELECT * FROM messages");
        $pagination = new Pagination( $total, $offset, $limit);
        return $pagination;
    }
    
    public static function getAggregate()
    {
        if(!static::$database){
            @trigger_error(
                    'this function needs to set a database to the mode. Please, use setDatabase first.',
                    E_USER_ERROR
                );   
            return false;
        }
        
        $sql = "SELECT policy , COUNT(*) AS cnt FROM messages GROUP BY policy";
        $rows = static::$database->loadRows( $sql);
        foreach($rows as $row){
            $row = Helpers::getPolicy($row);
        }    
        return $rows;
    }
    
    public static function getTimeline ()
    {
        if(!static::$database){
            @trigger_error(
                    'this function needs to set a database to the mode. Please, use setDatabase first.',
                    E_USER_ERROR
                );   
            return false;
        }
        
        $sql = "SELECT DATE_FORMAT(date,'%d-%m') AS mydate, "
            . " SUM(CASE WHEN policy = 15 THEN 1 ELSE 0 END) AS pass, "  
            . " SUM(CASE WHEN policy = 16 THEN 1 ELSE 0 END) AS reject, "   
            . " SUM(CASE WHEN policy = 17 THEN 1 ELSE 0 END) AS quarantine, "  
            . " SUM(CASE WHEN policy = 14 THEN 1 ELSE 0 END) AS unknown, "          
            . " SUM(CASE WHEN policy = 18 THEN 1 ELSE 0 END) AS none "
            . " FROM messages "
            . " GROUP BY mydate"
            . " ORDER BY date";        
        $rows = static::$database->loadRows( $sql);        
        return $rows;
    }
    
    public static function getTopDomains ($limit)
    {
        if(!static::$database){
            @trigger_error(
                    'this function needs to set a database to the mode. Please, use setDatabase first.',
                    E_USER_ERROR
                );   
            return false;
        }
                
        //Domains
        $sql = "SELECT d.name , COUNT(m.id) AS cnt "
            . " FROM messages AS m , domains AS d "
            . " WHERE m.from_domain = d.id "
            . " GROUP BY d.name "
            . " ORDER BY cnt DESC ";
        $rows = static::$database->loadRows( $sql);
        return Helpers::getTopItems( $rows , $limit);        
    }
    
    public static function getNonCompliantDomains ()
    {    
        if(!static::$database){
            @trigger_error(
                    'this function needs to set a database to the mode. Please, use setDatabase first.',
                    E_USER_ERROR
                );   
            return false;
        }
                
        $sql = "SELECT d.name , COUNT(m.id) AS cnt"
            . " FROM messages AS m , domains AS d "
            . " WHERE m.from_domain = d.id "
            . " AND m.policy <> 15"
            . " GROUP BY d.name "  
            . " ORDER BY cnt DESC ";
        $rows= static::$database->loadRows( $sql);    

        foreach($rows as $row)
        {
            $row->dns = dns_get_record($row->name, DNS_TXT);       
        }   
        return $rows;
    }
    
    public static function getRequests() 
    {
        if(!static::$database){
            @trigger_error(
                    'this function needs to set a database to the mode. Please, use setDatabase first.',
                    E_USER_ERROR
                );   
            return false;
        }
                
        $sql = "SELECT d.name AS requester , r.* " 
            . " FROM requests AS r , domains AS d " 
            . " WHERE r.domain = d.id";
        $rows= static::$database->loadRows( $sql);   

        foreach($rows as $row){
            $row = Helpers::getAlignmentMode($row);      
            $row = Helpers::getDomainPolicy($row);    
            $row = Helpers::getSubdomainPolicy($row);                
        }
     
        return $rows;            
    }
    
    public static function getARC() {

        $sql = "SELECT a.*, d.name , m.policy FROM arcseals AS a , messages AS m , domains AS d , selectors AS s"
        . " WHERE a.message = m.id " 
        . " AND a.domain = d.id "
        . " AND a.selector = s.id";
        
        $rows= static::$database->loadRows( $sql);   
        return $rows;
    }

    public static function getMessageID( $id = null) 
    {
        if(is_numeric($id))
        {        
            if(!static::$database)
            {            
                @trigger_error(
                    'this function needs to set a database to the mode. Please, use setDatabase first.',
                    E_USER_ERROR
                );   
                return false;
            }
        
            $sql = " SELECT m.date , m.jobid , m.policy , m.disp , m.ip , m.spf , m.align_dkim, m.align_spf , m.arc , m.arc_policy , " 
            . " d.name as env_domain , e.name as from_domain , p.name as policy_domain , q.name AS signed_domain , "
            . " s.name as selector_name , s.firstseen, t.addr , x.pass as dkim"
            . " FROM messages as m , domains AS d , domains AS e ,  domains AS p  , domains AS q , selectors AS s , signatures AS x , ipaddr as t" 
            . " WHERE m.env_domain = d.id "
            . " AND m.from_domain = e.id "
            . " AND m.policy_domain = p.id "
            . " AND ip = t.id " 
            . " AND m.id = x.message "
            . " AND x.domain = q.id "        
            . " AND x.selector = s.id " 
            . " AND m.id = " . (int) $id;

            $row= static::$database->loadObject( $sql);   
            
            //Get DKIM DNS record
            $row->dns_dkim =  '';            
            if($dns = dns_get_record($row->selector_name . "._domainkey." . $row->env_domain, DNS_TXT)){
                $row->dns_dkim = $dns[0]['txt'];  
            }

            //Get SPF DNS record 
            $row->dns_spf =  '';
            if($dns = dns_get_record($row->env_domain, DNS_TXT)){                
                foreach($dns as $line)
                {                
                    $txt = $line['txt'];
                    $parts = explode(" ",$txt);
                    if(is_array($parts)){               
                        $parts = array_map('trim' , $parts);
                        $parts = array_map('strtolower' , $parts);
                        foreach($parts as $part)
                        {
                            if(!strcmp($part , "v=spf1"))
                            {
                              $row->dns_spf = $txt;  
                            }
                        }
                    } else {
                        /*row->dns_spf*/
                    }               
                }
            }                        
            
            //Complete with text
            Helpers::getDisposition($row);
            Helpers::getPolicy($row);
            Helpers::getAlignmentSet($row);   
            Helpers::getEvaluation($row);  
            Helpers::getARCEvaluation($row);  
            
            return $row;
        }
    }    
  
}

