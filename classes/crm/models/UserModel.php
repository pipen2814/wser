<?php

namespace crm\models;

use php\models\BaseModel;
use php\sql\DB;

use php\session;
use crm\managers\UserManager;
use crm\orm\Organizaciones;
use crm\orm\Instalaciones;
use crm\orm\Usuarios;

/**
 * Class UserModel
 *
 * Clase que genera datos de usuario en el modelo.
 *
 * @package crm
 * @subpackage models
 */
class UserModel extends BaseModel {

	/**
	 * Método que agrega al modelo la información completa de un usuario.
	 *
	 * @param $model
	 * @param $appNode
	 * @param bool $fromLogin
	 */
	public static function addCompleteUserInfomation($model, $appNode, $fromLogin = false){

		$userSessionId = UserManager::getDBUserSession(session::getSessionVar('idUsuario'));
		if(session::getSessionId() != $userSessionId){
			if(!$fromLogin){
				session::destroy();
				header("Location: /app/login");
				/*
				   TODO:: Avisar al usuario de que se ha chapado su sesion
				   por haberse abierto otra distinta pasando parametro al login.
				 */

				/*
				   TODO:: Agregar campo de fecha a las sesiones y caducarlas en un tiempo determinado
				 */
			}else{
				UserManager::deleteDBUserSession(session::getSessionVar('idUsuario'));
				UserManager::setDBUserSession(session::getSessionVar('idUsuario'), session::getSessionId());
			}
		}

		$rsUserInformation = UserManager::getCompleteUserInformation(session::getSessionVar('idUsuario'));


		$userNode = $model->createElement("user");
		$appNode->appendChild($userNode);

		$organizationNode = $model->createElement("organization");
		$userNode->appendChild($organizationNode);

		$count = 0;
		while($info = $rsUserInformation->next()){
			$count++;
			if($count == 1){
				$installationsNode = $model->createElement("installations");
				$userNode->appendChild($installationsNode);
				$userNode->setAttribute('dni', $info->dni);
				$userNode->setAttribute('userId', $info->id_usuario);
				$userNode->setAttribute('firstName', utf8_encode($info->nombre));
				$userNode->setAttribute('lastName', utf8_encode($info->apellidos));
				$userNode->setAttribute('profileId', $info->id_perfil);

				$organizationNode->setAttribute('id', $info->id_organizacion);
				$organizationNode->setAttribute('organization', utf8_encode($info->organizacion));
				$organizationNode->setAttribute('address', utf8_encode($info->direccion_organizacion));
				$organizationNode->setAttribute('zipCode', $info->codigo_postal_organizacion);

			}
			$installationNode = $model->createElement("installation");
			$installationsNode->appendChild($installationNode);
			$installationNode->setAttribute('id', $info->id_instalacion); 
			$installationNode->setAttribute('installation', utf8_encode($info->instalacion)); 
			$installationNode->setAttribute('address', utf8_encode($info->direccion_instalacion));
			$installationNode->setAttribute('zipCode', $info->codigo_postal_instalacion);
		}
	}
}
?>
