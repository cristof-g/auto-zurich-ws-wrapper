<?php

namespace WsZurich\Wrapper;

use DOMDocument;

class WebServiceClient
{
    const URL_WEB_SERVICES      = 'https://www.zurich.com.mx/ZurichWS';
    const URL_TEST_WEB_SERVICES = 'http://pruebas.autolinea.ezurich.com.mx/ZurichWS_QA';

    protected $agente;
    protected $client;
    protected $clientCommon;
    protected $password;
    protected $user;
    protected $location;
    protected $url;
    protected $isTestMode = false;
    protected $resource;
    protected $config;

    /**
     * Construct
     *
     * @param string $user
     * @param string $password
     * @param string $agente
     * @param array  $config
     * @return void
     */
    public function __construct(string $user, string $password, string $agente, $config = [])
    {
        $this->user     = $user;
        $this->password = $password;
        $this->agente   = $agente;
        $this->config   = $config;
    }

    /**
     * Set if the env is Test
     *
     * @param bool $isTestMode
     * @return void
     */
    public function testMode($isTestMode): void
    {
        $this->isTestMode = $isTestMode;
    }

    /**
     * Initialize Web Services
     *
     * @param string $resource
     *
     * @return void
     */
    public function init(string $resource): void
    {
        $this->resource = $resource;

        $url    = $this->urlService() . '?wsdl';
        $config = $this->config();

        $this->client = new \Laminas\Soap\Client(
            $url,
            $config,
        );

        $this->clientCommon = new \Laminas\Soap\Client\Common(
            $this->client,
            $url,
            $config,
        );
    }

    /**
     * Web Services Configuration
     *
     * @return array
     */
    protected function config(): array
    {
        return [
            'soap_version' => SOAP_1_1,
            'encoding'     => 'UTF-8',
            'compression'  => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_DEFLATE,
            'location'     => $this->urlService(),
        ];
    }

    /**
     * Soap do Request
     *
     * @param string $method
     * @param string $request
     * @return object
     */
    protected function soapDoRequest(string $method, string $request): object
    {
        $xml = $this->cleanXML(
            $this->client->_doRequest(
                $this->clientCommon,
                $request,
                $this->urlService(),
                $method,
                SOAP_1_1
            )
        );

        return $xml->Body;
    }

    /**
     * Create XML
     *
     * @param array $body
     * @param boolean $security
     * @return string
     */
    protected function soapXML($body, $security = true): string
    {
        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://webservices.zurich.com/">
            ' . $this->createHeader($security) . '
            <soapenv:Body>
                ' . $this->createBody($body) . '
            </soapenv:Body>
        </soapenv:Envelope>';
    }

    /**
     * Create XML Header
     *
     * @param boolean $security
     * @return string
     */
    protected function createHeader($security): string
    {
        if (!$security) {
            return '<soapenv:Header/>';
        }

        return '<soapenv:Header>
                    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                        <UsernameToken xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                            <Username>' . $this->user . '</Username>
                            <Password>' . $this->password . '</Password>
                        </UsernameToken>
                    </wsse:Security>
            </soapenv:Header>';
    }

    /**
     * Create XML Body
     *
     * @param array $body
     * @return string
     */
    protected function createBody(array $body): string
    {
        $document = new DOMDocument;
        $node     = $document->createElement($this->methodRequest);
        $node     = $document->appendChild($node);

        foreach ($body as $fieldName => $fieldValue) {
            if (!is_array($fieldValue)) {
                $element = $document->createElement($fieldName, $fieldValue);
                $node->appendChild($element);
            } else {
                $this->createBodyChild($document, $node, $fieldValue);
            }
        }

        return preg_replace("/<\\?xml.*\\?>/", "", $document->saveXML(), 1);
    }

    /**
     * Create additional body child
     *
     * @param object $doc
     * @param object $node
     * @param mixed $child
     * @return void
     */
    protected function createBodyChild($doc, $node, $child): void
    {
        foreach ($child as $fieldName => $fieldValue) {
            $node = $node->appendChild(
                $doc->createElement($fieldName)
            );

            if (is_array($fieldValue)) {
                foreach ($fieldValue as $key => $value) {
                    $node->appendChild(
                        $doc->createElement($key, $value)
                    );
                }
            } else {
                $node->appendChild(
                    $doc->createElement($fieldName, $fieldValue)
                );
            }
        }
    }

    /**
     * Client String XML
     *
     * @param string $xml
     * @return object
     */
    protected function cleanXML($xml)
    {
        return simplexml_load_string(
            str_ireplace(['SOAP-ENV:', 'SOAP:', 'tns:', 'xmlns:', 'web:', 'soapenv:', 'env:'], '', $xml)
        );
    }

    /**
     * Get Base URL (Test or Production)
     *
     * @return string
     */
    protected function getBaseURL(): string
    {
        if ($this->isTestMode) {
            return self::URL_TEST_WEB_SERVICES;
        }

        return self::URL_WEB_SERVICES;
    }

    /**
     * URL Service
     *
     * @return string
     */
    public function urlService(): string
    {
        $service = [
            'obtenerCatEstMunAsentCp' => $this->getBaseURL() . '/catalogos/obtenerCatEstMunAsentCp/publicService',
            'consultaCatalogosAutos'  => $this->getBaseURL() . '/autos/consultaCatalogosAutos/publicService',
            'consultaClavesVehiculos' => $this->getBaseURL() . '/autos/consultaClavesVehiculos/publicService',
            'solCotV2'                => $this->getBaseURL() . '/autos/solCotV2/publicService',
            'pqtSelCot'               => $this->getBaseURL() . '/autos/pqtSelCot/publicService',
            'reCotV2'                 => $this->getBaseURL() . '/autos/reCotV2/publicService',
            'recuperaCotV2'           => $this->getBaseURL() . '/autos/recuperaCotV2/publicService',
            'datosGralesCot'          => $this->getBaseURL() . '/autos/datosGralesCot/publicService',
            'WSConsultaPoliza'        => $this->getBaseURL() . '/WSConsultaPoliza/service',
            'WSImpresionPolizaAutos'  => $this->getBaseURL() . '/WSImpresionPolizaAutos/service',
            'WSImpresionrecibosAutos' => $this->getBaseURL() . '/WSImpresionrecibosAutos/service',
        ];

        return $service[$this->resource];
    }

    /**
     * Methods Services Response
     *
     * @param string $methodRequest
     * @param string $methodResponse
     * @param array $fields
     * @param boolean $security
     * @return object
     */
    protected function serviceResponse(string $methodRequest, string $methodResponse, array $fields, bool $security = true): object
    {
        $response = $this->soapDoRequest(
            $methodRequest, 
            $this->soapXML($fields, $security)
        );

        return $response->{$methodResponse};
    }
}
