<?php

namespace WsZurich\Wrapper\Polizas;

use WsZurich\Wrapper\Helper;
use WsZurich\Wrapper\WebServiceClient;

class Poliza extends WebServiceClient
{
    const TAG_PREFIX = "web:";

    protected $request = [
        'emitir'  => '4',
        'detalle' => '9',
    ];

    /**
     * Emitir Poliza
     *
     * @param array $data
     * @return object
     */
    public function emitir(array $data): object
    {
        $this->methodRequest = self::TAG_PREFIX . 'DatosGralesCotAutoReq';

        $fields = [
            self::TAG_PREFIX . 'num_request'           => $this->request['emitir'],
            self::TAG_PREFIX . 'folio_cotizacion'      => $data['folioCotizacion'],
            self::TAG_PREFIX . 'id_forma_pago'         => $data['idFormaPago'] ?? 'T',
            self::TAG_PREFIX . 'id_domiciliacion'      => $data['idDomiciliacion'] ?? '0',
            self::TAG_PREFIX . 'placa'                 => $data['placas'],
            self::TAG_PREFIX . 'num_serie'             => $data['numeroSerie'],
            self::TAG_PREFIX . 'num_motor'             => $data['motor'] ?? '',
            self::TAG_PREFIX . 'num_repuve'            => $data['repuve'] ?? '',
            self::TAG_PREFIX . 'descripcion_vehiculo'  => $data['descripcionVehiculo'],
            self::TAG_PREFIX . 'apellidoPaterno'       => $data['apellidoPaterno'],
            self::TAG_PREFIX . 'apellidoMaterno'       => $data['apellidoMaterno'],
            self::TAG_PREFIX . 'nombre_s'              => $data['nombres'],
            self::TAG_PREFIX . 'tipoPersona'           => $data['tipoPersona'],
            self::TAG_PREFIX . 'rfc'                   => $data['rfc'],
            self::TAG_PREFIX . 'correo_electronico'    => $data['correo'],
            self::TAG_PREFIX . 'calle'                 => $data['calle'],
            self::TAG_PREFIX . 'num_ext'               => $data['numExt'],
            self::TAG_PREFIX . 'num_int'               => $data['numInt'] ?? '',
            self::TAG_PREFIX . 'codigo_postal'         => $data['codigoPostal'],
            self::TAG_PREFIX . 'numero_asentamiento'   => $data['numeroAsentamiento'],
            self::TAG_PREFIX . 'telefono'              => str_ireplace(['(', ')', '-', ' '], '', $data['telefono']),
            self::TAG_PREFIX . 'nombre_beneficiario'   => $data['nombreBeneficiario'] ?? '',
            self::TAG_PREFIX . 'rfc_beneficiario'      => $data['rfcBeneficiario'] ?? '',
            self::TAG_PREFIX . 'nombre_conductor'      => $data['nombreConductor'] ?? '',
            self::TAG_PREFIX . 'rfc_conductor'         => $data['rfcConductor'] ?? '',
            self::TAG_PREFIX . 'numero_tarjeta'        => $data['numeroTarjeta'] ?? '',
            self::TAG_PREFIX . 'anio_vencimiento'      => $data['anioVencimiento'] ?? '0',
            self::TAG_PREFIX . 'mes_vencimiento'       => $data['mesVencimiento'] ?? '0',
            self::TAG_PREFIX . 'banco_emisor'          => $data['bancoEmisor'] ?? '0',
            self::TAG_PREFIX . 'tipoCuenta'            => $data['tipoCuenta'] ?? '0',
            self::TAG_PREFIX . 'nombre_cuentaHabiente' => $data['nombreCuentaHabiente'] ?? '',
            self::TAG_PREFIX . 'idTipoIden'            => $data['idTipoIdentificacion'] ?? '0',
            self::TAG_PREFIX . 'numeroIdentificacion'  => $data['numeroIdentificacion'] ?? '',
            self::TAG_PREFIX . 'claveAgencia'          => $data['claveAgencia'] ?? '0',
            self::TAG_PREFIX . 'descripcionColonia'    => $data['descripcionColonia'] ?? '',
            self::TAG_PREFIX . 'razonSocial'           => $data['razonSocial'] ?? '',
            self::TAG_PREFIX . 'fechaIniVigencia'      => Helper::date('Ymd', $data['inicioVigencia']),
            self::TAG_PREFIX . 'fechaFinVigencia'      => Helper::date('Ymd', $data['inicioVigencia'], '+1 year'),
        ];

        return $this->serviceResponse('getDatosGralesCotizacionAutos', 'DatosGralesCotAutoRes', $fields);
    }

    /**
     * Consultar Detalle de la Póliza
     *
     * @param string $numeroPoliza
     * @return object
     */
    public function getDetalle(string $numeroPoliza): object
    {
        $this->methodRequest = 'DatosGralesCotAutoReq';

        $fields = [
            'tipoProceso'  => $this->request['detalle'],
            'numRelacion'  => $this->config['numRelacion'],
            'numPoliza'    => $numeroPoliza,
            'claveUsuario' => $this->password,
        ];

        return $this->serviceResponse('ConsultaPoliza', 'ConsultaPolizaResponse', $fields);
    }

    /**
     * Imprimir Poliza
     *
     * @param string $numeroPoliza
     * @param integer $endoso
     * @return object
     */
    public function imprimir(string $numeroPoliza, $endoso = 0): object
    {
       
        $soapClient = new \SoapClient($this->urlService(). '?wsdl', [
            'trace'              => 1,
            'exception'          => 0,
            'uri'                => 'http://test-uri/',
            'location'           => $this->urlService(),
            'connection_timeout' => 5000,
            'cache_wsdl'         => WSDL_CACHE_NONE,
            'keep_alive'         => true,
            'compression'        => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
        ]);

        $data = [
            'getWSImpresionPolizaAutos' => [
                'WSImpresionPolizaReq' => [
                    'agente'          => $this->agente,
                    'endoso'          => $endoso,
                    'papelMembretado' => 'true',
                    'poliza'          => $numeroPoliza,
                    'usuario'         => $this->user,
                    'password'        => $this->password,
                ],
            ],
        ];

        $response = $soapClient->__soapCall('getWSImpresionPolizaAutos', $data);
        return $response->WSImpresionPolizaRes;
    }
}