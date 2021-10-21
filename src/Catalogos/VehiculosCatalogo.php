<?php

namespace WsZurich\Wrapper\Catalogos;

use WsZurich\Wrapper\WebServiceClient;

class VehiculosCatalogo extends WebServiceClient
{
    const TAG_PREFIX = "web:";

    protected $proceso = [
        'marcas'    => [
            'solicitud' => '11',
            'catalogo'  => 'MARCA',
        ],
        'subMarcas' => [
            'solicitud' => '12',
            'catalogo'  => 'SUBMA',
        ],
        'modelos'   => [
            'solicitud' => '13',
            'catalogo'  => 'MODEL',
        ],
        'claves'    => [
            'solicitud' => '14',
            'catalogo'  => 'CAUTO',
        ],
        'tipoValor' => [
            'solicitud' => '15',
            'catalogo'  => 'TIVAL',
        ],
        'detalle'   => [
            'solicitud' => '23',
            'catalogo'  => 'CLAZ',
        ],
    ];

    /**
     * Obtener Marcas de Vehiculos
     *
     * @param integer $tipoVehiculo
     * @return object
     */
    public function getMarcas(int $tipoVehiculo): object
    {
        $this->methodRequest = self::TAG_PREFIX . 'SolicitudCatalogoMarcas';

        $fields = [
            self::TAG_PREFIX . 'numRequest'   => $this->proceso['marcas']['solicitud'],
            self::TAG_PREFIX . 'catalogo'     => $this->proceso['marcas']['catalogo'],
            self::TAG_PREFIX . 'usuario'      => $this->user,
            self::TAG_PREFIX . 'agente'       => $this->agente,
            self::TAG_PREFIX . 'numRelacion'  => $this->config['numRelacion'],
            self::TAG_PREFIX . 'tipoVehiculo' => $tipoVehiculo,
        ];

        return $this->serviceResponse('consultaCatalogoMarcaVehiculo', 'RespuestaCatalogoMarcas', $fields);
    }

    /**
     * Obtener Submarcas
     *
     * @param integer $tipoVehiculo
     * @param integer $claveMarca
     * @return object
     */
    public function getSubMarcas(int $tipoVehiculo, int $claveMarca): object
    {
        $this->methodRequest = self::TAG_PREFIX . 'reqCatSubMarcasAuto';

        $fields = [
            self::TAG_PREFIX . 'numRequest'   => $this->proceso['marcas']['solicitud'],
            self::TAG_PREFIX . 'catalogo'     => $this->proceso['marcas']['catalogo'],
            self::TAG_PREFIX . 'usuario'      => $this->user,
            self::TAG_PREFIX . 'agente'       => $this->agente,
            self::TAG_PREFIX . 'claveMarca'   => $claveMarca,
            self::TAG_PREFIX . 'numRelacion'  => $this->config['numRelacion'],
            self::TAG_PREFIX . 'tipoVehiculo' => $tipoVehiculo,
        ];

        return $this->serviceResponse('consultaCatalogoSubMarcaVehiculo', 'resCatSubMarcasAuto', $fields);
    }

    /**
     * Obtener Modelos
     *
     * @param integer $tipoVehiculo
     * @param integer $claveMarca
     * @param integer $claveSubMarca
     * @return object
     */
    public function getModelos(int $tipoVehiculo, int $claveMarca, int $claveSubMarca): object
    {
        $this->methodRequest = self::TAG_PREFIX . 'SolicitudCatalogoModelos';

        $fields = [
            self::TAG_PREFIX . 'numRequest'   => $this->proceso['modelos']['solicitud'],
            self::TAG_PREFIX . 'catalogo'     => $this->proceso['modelos']['catalogo'],
            self::TAG_PREFIX . 'cveMarca'     => $claveMarca,
            self::TAG_PREFIX . 'cveSubMarca'  => $claveSubMarca,
            self::TAG_PREFIX . 'numRelacion'  => $this->config['numRelacion'],
            self::TAG_PREFIX . 'usuario'      => $this->user,
            self::TAG_PREFIX . 'agente'       => $this->agente,
            self::TAG_PREFIX . 'tipoVehiculo' => $tipoVehiculo,
        ];

        return $this->serviceResponse('ConsultaCatalogoModelos', 'RespuestaCatalogoModelos', $fields);
    }

    /**
     * Obtener Detalle de Vehiculo
     *
     * @param string $claveZurich
     * @return object
     */
    public function getDetalle(string $claveZurich): object
    {
        $this->methodRequest = self::TAG_PREFIX . 'reqClaveZurichAutoDetalle';

        $fields = [
            self::TAG_PREFIX . 'numRequest'  => $this->proceso['detalle']['solicitud'],
            self::TAG_PREFIX . 'catalogo'    => $this->proceso['detalle']['catalogo'],
            self::TAG_PREFIX . 'usuario'     => $this->user,
            self::TAG_PREFIX . 'agente'      => $this->agente,
            self::TAG_PREFIX . 'claveZurich' => $claveZurich,
            self::TAG_PREFIX . 'numRelacion' => $this->config['numRelacion'],
        ];

        return $this->serviceResponse('ConsultaDetalleVehiculo', 'resClaveZurichAutoDetalle', $fields);
    }

    /**
     * Obtener Claves de Vehiculo
     *
     * @param integer $tipoVehiculo
     * @param integer $marca
     * @param integer $subMarca
     * @param integer $modelo
     * @return object
     */
    public function getClaves(int $tipoVehiculo, int $marca, int $subMarca, int $modelo): object
    {
        $this->methodRequest = self::TAG_PREFIX . 'reqClaveZurichAuto';

        $fields = [
            self::TAG_PREFIX . 'numRequest'   => $this->proceso['claves']['solicitud'],
            self::TAG_PREFIX . 'catalogo'     => $this->proceso['claves']['catalogo'],
            self::TAG_PREFIX . 'tipoVehiculo' => $tipoVehiculo,
            self::TAG_PREFIX . 'marca'        => $marca,
            self::TAG_PREFIX . 'submarca'     => $subMarca,
            self::TAG_PREFIX . 'modelo'       => $modelo,
            self::TAG_PREFIX . 'numRelacion'  => $this->config['numRelacion'],
            self::TAG_PREFIX . 'usuario'      => $this->user,
            self::TAG_PREFIX . 'agente'       => $this->agente,
        ];

        return $this->serviceResponse('ConsultaClavesZurich', 'resClaveZurichAuto', $fields);
    }

    /**
     * Obtener Tipo de valor de Convenio Auto Residente
     *
     * @param integer $tipoVehiculo
     * @param integer $modelo
     * @return object
     */
    public function getTipoValor(int $tipoVehiculo, int $modelo): object
    {
        $this->methodRequest = self::TAG_PREFIX . 'reqCatTiposValorAuto';

        $fields = [
            self::TAG_PREFIX . 'numRequest'   => $this->proceso['tipoValor']['solicitud'],
            self::TAG_PREFIX . 'catalogo'     => $this->proceso['tipoValor']['catalogo'],
            self::TAG_PREFIX . 'producto'     => 0,
            self::TAG_PREFIX . 'numRelacion'  => $this->config['numRelacion'],
            self::TAG_PREFIX . 'tipoVehiculo' => $tipoVehiculo,
            self::TAG_PREFIX . 'usuario'      => $this->user,
            self::TAG_PREFIX . 'agente'       => $this->agente,
            self::TAG_PREFIX . 'modelo'       => $modelo,
        ];

        return $this->serviceResponse('ConsultaCatalogoTipoValor', 'resCatTiposValorAuto', $fields);
    }
}
