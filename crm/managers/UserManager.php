<?php

namespace crm\managers;

use php\managers\UserManager as parentClass;
use php\sql\DB;

/**
 * Class UserManager
 *
 * Manager de usuarios
 *
 * @package crm
 * @subpackage managers
 */
class UserManager extends parentClass {

	/**
	 * Método que comprueba si el usuario y la pass coinciden.
	 *
	 * @param $user
	 * @param $password
	 *
	 * @return \php\sql\MySQLResultSet
	 */
	public static function getUserByLogin($user, $password){
		$db = DB::getInstance();
		$password = md5($password);
		$db->query("select u.id_usuario, u.dni, u.id_organizacion, oh.host
				from usuarios u
				join organizaciones o using (id_organizacion)
				join organizaciones_hosts oh using (id_organizacion)
				where u.dni = '$user' and u.password = '$password'");
		$rs = $db->getRs();

		return $rs;
	}

	/**
	 * Metodo que devuelve los datos del usuario a partir de un id_usuario.
	 *
	 * @param $userId
	 *
	 * @return \php\sql\MySQLResultSet
	 */
	public static function getUserById($userId){
		$db = DB::getInstance();
		$db->query("select u.id_usuario, u.usuario, u.id_perfil, u.fecha_creacion, u.fecha_baja, u.activo, u.nombre, u.apellidos
				from usuarios u
				where u.id_usuario = $userId");
		$rs = $db->getRs();

		return $rs;
	}

	/**
	 * Método que recupera la sesión de un usuario.
	 *
	 * @param $userId
	 *
	 * @return null
	 */
	public static function getDBUserSession($userId){
		$db = DB::getInstance();

		$db->query("select sesion from usuarios_sesiones where id_usuario = $userId");
		$rs = $db->getRs();

		if($sess = $rs->next()){
			return $sess->sesion;
		}else{
			return null;
		}
	}
	
	/**
	 * Método que devuelve los permisos del usuario
	 *
	 * @param int $userId Id del usuario
	 *
	 * @return Array $permissions array con el id de los permisos del usuario
	 */
	public static function getUserPermissions($userId){
		$db = DB::getInstance();
		
		$db->query("select pp.* from perfiles_permisos pp join usuarios_perfiles up on (up.id_perfil = pp.id_perfil) where up.id_usuario = ".$userId);
		$rs = $db->getRs();
		
		$permissions = array();
		while($rs->next()) {
			$permissions[] = $rs->id_permiso;
		}
		return $permissions;
	}

	/**
	 * Método que devuelve todos los datos del usuario.
	 *
	 * @param $userId
	 *
	 * @return \php\sql\MySQLResultSet
	 */
	public static function getCompleteUserInformation($userId){
		$db = DB::getInstance();
		$db->query("select u.id_usuario, u.dni, u.nombre, u.apellidos, u.id_perfil, 
				o.id_organizacion, o.organizacion, o.direccion as direccion_organizacion, o.codigo_postal as codigo_postal_organizacion, 
				i.id_instalacion, i.instalacion, i.direccion as direccion_instalacion, i.codigo_postal as codigo_postal_instalacion 
				from usuarios u 
				join organizaciones o using (id_organizacion) 
				join instalaciones i using (id_organizacion) 
				join usuarios_instalaciones ui on (ui.id_usuario = u.id_usuario and ui.id_instalacion = i.id_instalacion) 
			where u.id_usuario = ".$userId);

		$rs = $db->getRs();

		return $rs;
	}

	/**
	 * Método que destruye la sesión de un usuario a partir de ella.
	 *
	 * @param $sessionM
	 */
	public static function destroyDBUserSession($session){
		$db = DB::getInstance();
		$db->query("delete from usuarios_sesiones where sesion = '$session'");
	}

	/**
	 * Método que setea la sesión del usuario.
	 *
	 * @param $userId
	 * @param $session
	 */
	public static function setDBUserSession($userId, $session){
		$db = DB::getInstance();
		$db->query("replace into usuarios_sesiones (id_usuario, sesion) values ($userId,'$session')");
	}

	/**
	 * Método que destruye la sesión de un usuario por id_usuario.
	 *
	 * @param $userId
	 */
	public static function deleteDBUserSession($userId){
		$db = DB::getInstance();
		$db->query("delete from usuarios_sesiones where id_usuario = $userId");
	}
}
?>
