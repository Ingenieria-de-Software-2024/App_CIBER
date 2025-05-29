<?php

namespace Model;

class Bitacora extends ActiveRecord
{
    protected static $tabla = 'bitacora';
    protected static $idTabla = 'bita_id';
    protected static $columnasDB = ['bita_fecha', 'bita_malware', 'bita_pishing', 'bita_coman_cont', 'bita_cryptomineria', 'bita_ddos', 'bita_conex_bloq', 'bita_total', 'bita_situacion'];

    public $bita_id;
    public $bita_fecha;
    public $bita_malware;
    public $bita_pishing;
    public $bita_coman_cont;
    public $bita_cryptomineria;
    public $bita_ddos;
    public $bita_conex_bloq;
    public $bita_total;
    public $bita_situacion;

    public function __construct($args = [])
    {
        $this->bita_id = $args['bita_id'] ?? null;
        $this->bita_fecha = $args['bita_fecha'] ?? '';
        $this->bita_malware = $args['bita_malware'] ?? '';
        $this->bita_pishing = $args['bita_pishing'] ?? '';
        $this->bita_coman_cont = $args['bita_coman_cont'] ?? '';
        $this->bita_cryptomineria = $args['bita_cryptomineria'] ?? '';
        $this->bita_ddos = $args['bita_ddos'] ?? '';
        $this->bita_conex_bloq = $args['bita_conex_bloq'] ?? '';
        $this->bita_total = $args['bita_total'] ?? '';
        $this->bita_situacion = $args['bita_situacion'] ?? 1;
    }

    public static function buscar()
    {
        $sql = "SELECT * FROM BITACORA WHERE BITA_SITUACION = 1";
        return self::fetchArray($sql);
    }

    // public static function obtenerAntivirus()
    // {
    //     $sql = "SELECT * FROM " . self::$tabla . " WHERE BITA_SITUACION = 1";
    //     return self::fetchArray($sql);
    // }

}