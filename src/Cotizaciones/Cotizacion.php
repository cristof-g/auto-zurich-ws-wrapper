<?php
namespace WsZurich\Wrapper\Cotizaciones;

use WsZurich\Wrapper\Helper;
use WsZurich\Wrapper\WebServiceClient;

class Cotizacion extends WebServiceClient
{
    const TAG_PREFIX = "web:";

    protected $request = [
        'cotizar'             => '8',
        'seleccionarPaquete'  => '3',
        'recotizar'           => '6',
        'recuperarCotizacion' => '7',
    ];

    /**
     * Cotizar
     *
     * @param array $data
     * @return object
     */
    public function cotizar(array $data): object
    {
        $this->methodRequest = self::TAG_PREFIX . 'SOLICITUD_COT_AUTOS_REQ';

        $fechaInicio = $data['fechaIncio'] ?? Helper::date('Ymd');

        $fechaFinal = isset($data['fechaFinal']) ?
        Helper::date('Ymd', $data['fechaFinal']) :
        Helper::date('Ymd', $fechaInicio, '+1 year');

        $fields = [
            self::TAG_PREFIX . 'num_req'           => $this->request['cotizar'],
            self::TAG_PREFIX . 'usuario'           => $this->user,
            self::TAG_PREFIX . 'idOficina'         => $this->config['idOficina'] ?? '',
            self::TAG_PREFIX . 'programaComercial' => $this->config['programaComercial'] ?? '',
            self::TAG_PREFIX . 'tipoVehiculo'      => $data['tipoVehiculo'],
            self::TAG_PREFIX . 'cve_zurich'        => $data['claveZurich'],
            self::TAG_PREFIX . 'modelo'            => $data['modelo'],
            self::TAG_PREFIX . 'id_estado'         => $data['idEstado'] ?? '0',
            self::TAG_PREFIX . 'id_ciudad'         => $data['idCiudad'] ?? '0',
            self::TAG_PREFIX . 'id_tipoValor'      => $data['idTipoValor'] ?? '6',
            self::TAG_PREFIX . 'id_tipoUso'        => $data['idTipoValor'] ?? '1',
            self::TAG_PREFIX . 'cve_agente'        => $this->agente,
            self::TAG_PREFIX . 'tipo_producto'     => $data['tipoProducto'] ?? '0',
            self::TAG_PREFIX . 'tipo_carga'        => $data['tipoCarga'] ?? '0',
            self::TAG_PREFIX . 'tipo_persona'      => $data['tipoPersona'] ?? 'F',
            self::TAG_PREFIX . 'edad'              => $data['edad'],
            self::TAG_PREFIX . 'genero'            => $data['genero'],
            self::TAG_PREFIX . 'estadoCivil'       => $data['estadoCivil'] ?? '7',
            self::TAG_PREFIX . 'ocupacion'         => $data['ocupacion'] ?? '1',
            self::TAG_PREFIX . 'giro'              => $data['giro'] ?? '1',
            self::TAG_PREFIX . 'nacionalidad'      => $data['nacionalidad'] ?? '0',
            self::TAG_PREFIX . 'id_moneda'         => $data['moneda'] ?? '0',
            self::TAG_PREFIX . 'fecha_inicio'      => $fechaInicio,
            self::TAG_PREFIX . 'fecha_fin'         => $fechaFinal,
            self::TAG_PREFIX . 'monto_asegurado'   => $data['montoAsegurado'] ?? '0',
            self::TAG_PREFIX . 'codigoPostal'      => $data['codigoPostal'],
            self::TAG_PREFIX . 'situacionVehiculo' => $data['situacionVehiculo'] ?? '',
            self::TAG_PREFIX . 'mesesVigencia'     => 12,
            self::TAG_PREFIX . 'tipoMovimiento'    => $data['tipoMovimiento'] ?? '1',
            self::TAG_PREFIX . 'polizaAnterior'    => $data['polizaAnterior'] ?? '0',
        ];

        return $this->serviceResponse('getSolicitudCotizacionAutos', 'SOLICITUD_COT_AUTOS_RES', $fields);
    }

    /**
     * Seleccionar Paquete de cotización
     *
     * @param array $data
     * @return object
     */
    public function seleccionarPaquete(array $data): object
    {
        $this->methodRequest = self::TAG_PREFIX . 'PAQUETE_AUTOS_SELECCIONADO_REQ';

        $fields = [
            self::TAG_PREFIX . 'num_resquest'         => $this->request['seleccionarPaquete'],
            self::TAG_PREFIX . 'folio_cotizacion'     => $data['folioCotizacion'],
            self::TAG_PREFIX . 'id_emite_guarda'      => $data['idEmiteGuarda'] ?? '0',
            self::TAG_PREFIX . 'id_paquete'           => $data['idPaquete'],
            self::TAG_PREFIX . 'tipo_persona'         => $data['tipoPersona'] ?? 'F',
            self::TAG_PREFIX . 'apellidoPaterno'      => $data['apellidoPaterno'],
            self::TAG_PREFIX . 'apellidoMaterno'      => $data['apellidoMaterno'],
            self::TAG_PREFIX . 'nombre_s'             => $data['nombres'],
            self::TAG_PREFIX . 'descripcion_vehiculo' => $data['descripciónVehiculo'],
            self::TAG_PREFIX . 'razonSocial'          => $data['razonSocial'] ?? '',
            self::TAG_PREFIX . 'serie'                => $data['serie'],
        ];

        return $this->serviceResponse('getPqtSelCotAutos', 'PAQUETE_AUTOS_SELECCIONADO_RES', $fields);
    }

    /**
     * Recotizar
     *
     * @param array $data
     * @return object
     */
    public function reCotizar(array $data)
    {
        $this->methodRequest = self::TAG_PREFIX . 'RECOTIZACION_AUTOS_REQ';

        foreach ($data['coberturas'] as $cobertura) {
            $coberturas[] = [
                self::TAG_PREFIX . 'COBERTURA' => [
                    self::TAG_PREFIX . 'id_cobertura'         => $cobertura['idCobertura'],
                    self::TAG_PREFIX . 'monto_asegurado'      => $cobertura['montoAsegurado'],
                    self::TAG_PREFIX . 'porcentaje_deducible' => $cobertura['porcentajeDeducible'],
                    self::TAG_PREFIX . 'id_seleccion'         => $cobertura['idSeleccion'],
                ],
            ];
        }

        $fields = [
            self::TAG_PREFIX . 'num_resquest'         => $this->request['recotizar'],
            self::TAG_PREFIX . 'folio_cotizacion'     => $data['folioCotizacion'],
            self::TAG_PREFIX . 'porcentaje_descuento' => $data['porcentajeDescuento'] ?? '0',
            self::TAG_PREFIX . 'porcentaje_recargo'   => $data['porcentajeRecargo'] ?? '0',
            self::TAG_PREFIX . 'prima_objetivo'       => $data['primeObjectivo'] ?? '0',
            self::TAG_PREFIX . 'id_paquete'           => $data['idPaquete'],
        ];

        foreach ($coberturas as $tag => $value) {
            $fields[$tag] = $value;
        }

        return $this->serviceResponse('getReCotizacionAutos', 'RECOTIZACION_AUTOS_RES', $fields);
    }

    /**
     * Recuperar cotizacion
     *
     * @param array $data
     * @return object
     */
    public function recuperarCotizacion(array $data): object
    {
        $this->methodRequest = self::TAG_PREFIX . 'RecuperaCotizacionAutosReq';

        $fields = [
            self::TAG_PREFIX . 'num_req'          => $this->request['recuperarCotizacion'],
            self::TAG_PREFIX . 'usuario'          => $this->user,
            self::TAG_PREFIX . 'oficina'          => $this->config['idOficina'],
            self::TAG_PREFIX . 'folio_cotizacion' => $data['folioCotizacion'],
            self::TAG_PREFIX . 'apellidoPaterno'  => $data['apellidoPaterno'] ?? '',
            self::TAG_PREFIX . 'apellidoMaterno'  => $data['apellidoMaterno'] ?? '',
            self::TAG_PREFIX . 'nombre_s'         => $data['nombres'] ?? '',
            self::TAG_PREFIX . 'razonSocial'      => $data['razonSocial'] ?? '',
        ];

        return $this->serviceResponse('getRecuperaCotizacionAuto', 'RecuperaCotizacionAutosRes', $fields);
    }
}