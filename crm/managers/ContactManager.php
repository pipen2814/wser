<?php

namespace crm\managers;

use php\Hashtable;
use php\managers\BaseManager;
use php\sql\DB;
use php\session;

/**
 * Class ContactManager
 *
 * Manager de contactos.
 *
 * @package crm
 * @subpackage managers
 */
class ContactManager extends BaseManager {

	/**
	 * Método para modificar los datos de un contacto.
	 *
	 * @param Hashtable $postData Datos con los que actualizaremos al contacto.
	 */
	public static function modifyContactData($postData){
		
		if(!is_null($postData->changeType)){
			$hostname = session::getSessionVar('hostnameOrganization');
			$database = "organization_".session::getSessionVar('idOrganizacion');
			$db = DB::getInstance(false,$hostname,$database);
			switch($postData->changeType){
				case "contactName":
					$db->query("update contactos set nombre = '".$postData->firstName."', apellidos = '".$postData->lastName."' where id_contacto = ".$postData->contactId);	
					break;
				case "contactDNI":
					$db->query("update contactos set dni = '".$postData->DNI."' where id_contacto = ".$postData->contactId);	
					break;
				case "contactBirthdate":
					$formatter = \DateTime::createFromFormat("d/m/Y", $postData->birthdate);
					$birthdate = $formatter->format("Y-m-d 00:00:00");
					$db->query("update contactos set fecha_nacimiento = '".$birthdate."' where id_contacto = ".$postData->contactId);	
					break;
				case "contactLOPD":
					$LOPD = ($postData->LOPD?1:0);
					$db->query("update contactos_otros set info_comercial = $LOPD where id_contacto = ".$postData->contactId);	
					break;
				case "contactMode":
					foreach($postData as $key => $value){
						if(preg_match("/contactMode/",$key)){
						$exploded = explode("contactMode",$key);
						$contactModeId = $exploded[1];
							$db->query("update contactos_modos_contacto set valor = '".$value."' where id_contacto = ".$postData->contactId." and id_modo_contacto = ".$contactModeId);
						}
					}
					break;
				default:
					break;
			}


		}
	}
}
?>
