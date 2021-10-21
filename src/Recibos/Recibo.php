<?php
namespace WsZurich\Wrapper\Recibos;

use WsZurich\Wrapper\WebServiceClient;

class Recibo extends WebServiceClient
{
    const TAG_PREFIX = "web:";

    /**
     * Imprimir Recibo
     *
     * @param string $numeroPoliza
     * @param integer $endoso
     * @return object
     */
    public function imprimir(string $numeroPoliza, $endoso = 0): object
    {
        $soapClient = new \SoapClient($this->urlService() . '?wsdl', [
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
            'WSImpresionReciboReq' => [
                'agente'   => $this->agente,
                'endoso'   => $endoso,
                'poliza'   => $numeroPoliza,
                'usuario'  => $this->user,
                'password' => $this->password,
            ],
        ];

        $response = $soapClient->__soapCall('getWSImpresionrecibosAutos', $data);
        return $response->WSImpresionReciboRes;
    }
}