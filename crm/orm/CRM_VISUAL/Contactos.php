<?

namespace crm\orm;

use php\orm\BaseORM;
/**
 * Class Contactos
 *
 * ORM para la tabla contactos
 *
 * @package crm
 * @subpackage orm
 */
class Contactos extends \crm\orm\defs\Contactos {
	/**
	 * Metodo para recuperar todos los contactos (o a partir de un limite) de una organizacion
	 *
	 * @param $limitFrom = 0 Limite desde el que queremos empezar la busqueda
	 *
	 * @return $rs ResultSet con los contactos
	 */
	public function getAllContacts($limitFrom = 0){
		return $this->getContactsList("", "nombre,apellidos", "c.id_contacto, c.nombre, c.apellidos, c.dni, cmc.valor as contacto, c.id_estado_negociacion", $limitFrom, DEFAULT_LIST_SIZE);
	}

	/**
	 * Metodo para recuperar la cantidad de contactos guardados por una organizacion
	 *
	 * @param $conds = "" Condiciones para la busqueda de contactos
	 *
	 * @return $count Numero de contactos encontrados
	 */
	public function getTotalContactsCount($conds = ""){
		$rs = $this->getContactsList($conds, false, "count(*) as count", false);
		if($rs->next()){
			return $rs->count;
		}else{
			return 0;
		}
	}

	/**
	 * Metodo para obtener una lista de contactos a partir de una serie de parametros
	 *
	 * @param $conds = "" Condiciones para la busqueda de contactos
	 * @param $orderBy = "" Condicion de orden para la busqueda
	 * @param $fields = "*" Campos a devolver en la consulta
	 * @param $limitFrom = 0 Limite desde el que queremos empezar la busqueda
	 * @param $limitCount = DEFAULT_LIST_SIZE Numero de contactos a devolver
	 *
	 * @return $rs ResultSet con la lista de contactos buscados
	 */
	protected function getContactsList($conds = "", $orderBy="", $fields = "*", $limitFrom=0, $limitCount= DEFAULT_LIST_SIZE){

		if($orderBy!== false && !is_null($orderBy) && $orderBy!='') $orderBy = " order by ".$orderBy;
		else $orderBy = "";
		if($limitFrom!== false && $limitCount !== false) $limit = " limit $limitFrom, $limitCount";
		else $limit = '';
		$query = "select $fields from contactos c 
					left join contactos_modos_contacto cmc on (cmc.id_contacto=c.id_contacto and cmc.principal = 1 ) 
			where 1=1 $conds $orderBy $limit";

		$this->db->query($query);
		$rs = $this->db->getRs();
		return $rs;
	}

	/**
	 * Metodo para generar dinamicamente las condiciones de busqueda por nombre, apellido o dni
	 *
	 * @param $string Cadena a buscar
	 *
	 * @return $conds Cadena con las condiciones ya formadas
	 */
	protected function createSearchConds($string){
		if($string!= '' && !is_null($string) && $string!= false){
			$substrings = explode(' ', $string);
			$combinedConds = array();
			foreach($substrings as $str){
				$combinedConds[] = " c.nombre like '%$str%'";
				$combinedConds[] = " c.apellidos like '%$str%'";
				$combinedConds[] = " c.dni like '%$str%'";
			}
			$conds = "";
			foreach($combinedConds as $cond){
				$conds.= ($conds==""?" and ($cond":" or $cond");
			}
			if(count($combinedConds)>0) $conds.=") ";
		}else{
			$conds = "";
		}

		return $conds;
	}

	/**
	 * Metodo que devuelve los contactos buscados a traves de una cadena de texto
	 *
	 * @param $string Cadena de busqueda
	 * @param $limitFrom Limite desde el que queremos empezar la busqueda
	 *
	 * @return $rs ResultSet con los contactos buscados
	 */
	public function getFoundContacts($string, $limitFrom){
		$conds = $this->createSearchConds($string);
		return $this->getContactsList($conds, "nombre,apellidos", "c.id_contacto, c.nombre, c.apellidos, c.dni, cmc.valor as contacto, c.id_estado_negociacion", $limitFrom, DEFAULT_LIST_SIZE);
	}

	/**
	 * Metodo que devuelve la cantidad de contactos encontrados buscados a traves de una cadena de texto
	 *
	 * @param $string Cadena de busqueda
	 *
	 * @return $count Cantidad de contactos encontrados1
	 */
	public function getTotalFoundContactsCount($string){
		$conds = $this->createSearchConds($string);
		$rs = $this->getContactsList($conds, false, "count(*) as count", false);
		if($rs->next()){
			return $rs->count;
		}else{
			return 0;
		}
	}
}
?>
