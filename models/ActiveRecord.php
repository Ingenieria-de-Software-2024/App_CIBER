<?php
namespace Model;
use PDO;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    protected static $idTabla = '';

    // Alertas y Mensajes
    protected static $alertas = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }
    // Validación
    public static function getAlertas() {
        return static::$alertas;
    }

    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    // Registros - CRUD
    public function guardar() {
        $resultado = '';
        $id = static::$idTabla ?? 'id';
        if(!is_null($this->$id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {+
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);

        // debuguear($resultado);
        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id = []) {
        $idQuery = static::$idTabla ?? 'id';
        $query = "SELECT * FROM " . static::$tabla ;

        if(is_array(static::$idTabla)){
            foreach (static::$idTabla as $key => $value) {
                if($value == reset(static::$idTabla)){
                    $query.= " WHERE $value = " . self::$db->quote( $id[$value] );
                }else{
                    $query.= " AND $value = " . self::$db->quote($id[$value] );

                }
            }
        }else{

           $query.= " WHERE $idQuery = $id";
        }
                
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Obtener Registro
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT ${limite}";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Busqueda Where con Columna 
    public static function where($columna, $valor, $condicion = '=') {
        $query = "SELECT * FROM " . static::$tabla . " WHERE ${columna} ${condicion} '${valor}'";
        $resultado = self::consultarSQL($query);
        return  $resultado ;
    }

    // SQL para Consultas Avanzadas.
    public static function SQL($consulta) {
        $query = $consulta;
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // crea un nuevo registro
    public function crear() {
    $atributos = $this->sanitizarAtributos();

    $query = "INSERT INTO " . static::$tabla . " (";
    $query .= join(', ', array_keys($atributos));
    $query .= ") VALUES (";
    $query .= join(", ", array_values($atributos));
    $query .= ")";

    try {
        $resultado = self::$db->exec($query);

        return [
            'resultado' => $resultado,
            'id' => self::$db->lastInsertId(static::$tabla)
        ];
    } catch (\PDOException $e) {
        // Imprime error en consola y lanza para que el controlador lo capture
        error_log("Error en ActiveRecord::crear() => " . $e->getMessage());
        throw new \Exception("Error al ejecutar INSERT: " . $e->getMessage());
    }
}


    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}={$value}";
        }
        $id = static::$idTabla ?? 'id';

        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );

        if(is_array(static::$idTabla)){

            foreach (static::$idTabla as $key => $value) {
                if($value == reset(static::$idTabla)){
                    $query.= " WHERE $value = " . self::$db->quote( $this->$value );
                }else{
                    $query.= " AND $value = " . self::$db->quote($this->$value );

                }
            }
        }else{
            $query .= " WHERE " . $id . " = " . self::$db->quote($this->$id) . " ";
            
        }

        // debuguear($query);

        $resultado = self::$db->exec($query);
        return [
            'resultado' =>  $resultado,
        ];
    }

    // Eliminar un registro - Toma el ID de Active Record
    public function eliminar() {
        $idQuery = static::$idTabla ?? 'id';
        $query = "DELETE FROM "  . static::$tabla . " WHERE $idQuery = " . self::$db->quote($this->id);
        $resultado = self::$db->exec($query);
        return $resultado;
    }

    public static function consultarSQL($query, $params = []) {
        $stmt = self::$db->prepare($query);
        $stmt->execute($params);

        $array = [];
        while($registro = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $array[] = static::crearObjeto($registro);
        }

        $stmt->closeCursor();
        return $array;
    }


    public static function fetchArray($query){
        $resultado = self::$db->query($query);
        $respuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);
        foreach ($respuesta as $value) {
            $data[] = array_change_key_case( array_map( 'utf8_encode', $value) ); 
        }
        $resultado->closeCursor();
        return $data;
    }

        
    public static function fetchFirst($query){
        $resultado = self::$db->query($query);
        $respuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        foreach ($respuesta as $value) {
            $data[] = array_change_key_case( array_map( 'utf8_encode', $value) ); 
        }
        $resultado->closeCursor();
        return array_shift($data);
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value ) {
            $key = strtolower($key);
            if(property_exists( $objeto, $key  )) {
                $objeto->$key = utf8_encode($value);
            }
        }

        return $objeto;
    }



    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            $columna = strtolower($columna);
            if($columna === static::$idTabla) continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach($atributos as $key => $value) {
            if ($key === 'bita_fecha' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                // Para Informix: usar TO_DATE para insertar la fecha correctamente
                $sanitizado[$key] = "TO_DATE(" . self::$db->quote($value) . ", '%Y-%m-%d')";
            } else {
                $sanitizado[$key] = self::$db->quote($value);
            }
        }

        return $sanitizado;
    }


    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
