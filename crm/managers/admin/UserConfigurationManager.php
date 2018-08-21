<?php

namespace crm\managers\admin;

use crm\factories\ORM;
use php\Hashtable;
use php\managers\BaseManager;
use php\Session;
use php\sql\ConnectionFactory;
use php\sql\DB;

/**
 * Class UserConfigurationManager
 *
 * Clase encargada de gestionar el almacenamiento/recuperacion de los datos de configuración del usuario.
 *
 * @package crm
 * @subpackage managers
 * @subpackage admin
 */
class UserConfigurationManager extends BaseManager {

	/**
	 * Este método se encargará de almacenar donde corresponda los datos de configuración del CRM para el usuario
	 * que está en sesión.
	 *
	 * @param Hashtable $data Datos a guardar.
	 */
	public function saveCrmConfigurationData(Hashtable $data, $userId){

		// Por un lado los datos de usuarios_configuracion.
		$usuariosConfiguracion = ORM::UsuariosConfiguracion()->getById($userId);
		if (is_null($usuariosConfiguracion->idUsuario) ){
			$usuariosConfiguracion->idUsuario = $userId;
		}
		if ($data->has('agendaView')){
			$usuariosConfiguracion->agendaView = $data->agendaView;
		}

		$usuariosConfiguracion->save();

	}
}