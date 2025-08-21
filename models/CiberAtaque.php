<?php

namespace Model;

use Exception;

class CiberAtaque extends ActiveRecord
{
    protected static $tabla   = 'ciber_ataque';
    protected static $idTabla = 'ata_id';
    protected static $errores = [];

    protected static $columnasDB = [
        'ata_id',
        'ata_nombre',
        'ata_descripcion', 
        'ata_situacion'
    ];

    public $ata_id;
    public $ata_nombre;
    public $ata_descripcion;
    public $ata_situacion;

    public function __construct($args = [])
    {
        $this->ata_id = $args['ata_id'] ?? null;
        $this->ata_nombre = trim($args['ata_nombre'] ?? '');
        $this->ata_descripcion = trim($args['ata_descripcion'] ?? '');
        $this->ata_situacion = (int)($args['ata_situacion'] ?? 1); 
    }

    public static function buscar()
    {
        $sql = "SELECT * FROM ciber_ataque WHERE ata_situacion = 1";
        return self::fetchArray($sql);
    }  
    
}
