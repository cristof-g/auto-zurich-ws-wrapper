<?php
namespace WsZurich\Wrapper\Catalogos;

use WsZurich\Wrapper\WebServiceClient;

class EntidadesCatalogo extends WebServiceClient
{
    const TAG_PREFIX = "web:";

    protected $proceso = [
        'estados'       => '01',
        'municipios'    => '02',
        'asentamientos' => '03',
        'direcciones'   => '04',
        'codigoPostal'  => '05',
    ];

    /**
     * Obtener Estados
     *
     * @return object
     */
    public function getEstados()
    {
        $this->methodRequest = self::TAG_PREFIX . 'CatalogosEstadosReq';

        $fields = [
            self::TAG_PREFIX . 'id_proceso' => $this->proceso['estados'],
        ];

        return $this->serviceResponse('ObtenerCatalogoEstados', 'CatalogosEstadosRes', $fields);
    }

    /**
     * Obtener Municipios
     *
     * @param string $claveEstado
     * @return object
     */
    public function getMunicipios(string $claveEstado)
    {
        $this->methodRequest = self::TAG_PREFIX . 'CatalogosMunicipiosReq';

        $fields = [
            self::TAG_PREFIX . 'id_proceso'   => $this->proceso['municipios'],
            self::TAG_PREFIX . 'clave_estado' => $claveEstado,
        ];

        return $this->serviceResponse('ObtenerCatalogoMunicipios', 'CatalogosMunicipiosRes', $fields);
    }

    /**
     * Obtener Asentamientos
     *
     * @param string $claveEstado
     * @param string $claveMunicipio
     * @return object
     */
    public function getAsentamientos(string $claveEstado, string $claveMunicipio)
    {
        $this->methodRequest = self::TAG_PREFIX . 'CatalogosAsentamientosReq';

        $fields = [
            self::TAG_PREFIX . 'id_proceso'      => $this->proceso['asentamientos'],
            self::TAG_PREFIX . 'clave_estado'    => $claveEstado,
            self::TAG_PREFIX . 'clave_municipio' => $claveMunicipio,
            self::TAG_PREFIX . 'numero_Relacion' => '0',
        ];

        return $this->serviceResponse('ObtenerCatalogoAsentamientos', 'CatalogosAsentamientosRes', $fields);
    }

    /**
     * Obtener Direcciones (Estado, Municipio, Asentamiento) por Codigo Postal
     *
     * @param string $codigoPostal
     * @return object
     */
    public function getDireccionByCodigoPostal(string $codigoPostal)
    {
        $this->methodRequest = self::TAG_PREFIX . 'CatAsentamientosPorCpReq';

        $fields = [
            self::TAG_PREFIX . 'id_proceso'      => $this->proceso['direcciones'],
            self::TAG_PREFIX . 'codigo_postal'   => $codigoPostal,
            self::TAG_PREFIX . 'numero_relacion' => 0,
            self::TAG_PREFIX . 'usuario'         => '',
            self::TAG_PREFIX . 'clave_agente'    => 0,
            self::TAG_PREFIX . 'clave_ramo'      => 0,
        ];

        return $this->serviceResponse('ObtenerCatalogoEstMunAsentPorCp', 'CatAsentamientosPorCpRes', $fields);
    }

    /**
     * Obtener Codigos Postales
     *
     * @param string $claveEstado
     * @param string $claveMunicipio
     * @param string $claveAsentamiento
     * @return object
     */
    public function getCodigosPostales(string $claveEstado, string $claveMunicipio, string $claveAsentamiento)
    {
        $this->methodRequest = self::TAG_PREFIX . 'CatCodigosPostalesReq';

        $fields = [
            self::TAG_PREFIX . 'id_proceso'         => $this->proceso['codigoPostal'],
            self::TAG_PREFIX . 'numero_relacion'    => 0,
            self::TAG_PREFIX . 'clave_estado'       => $claveEstado,
            self::TAG_PREFIX . 'clave_municipio'    => $claveMunicipio,
            self::TAG_PREFIX . 'clave_asentamiento' => $claveAsentamiento,
        ];

        return $this->serviceResponse('ObtenerCatalogoCodigoPostales', 'CatCodigosPostalesRes', $fields);
    }
}