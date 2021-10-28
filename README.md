# Auto Zurich Web Service Wrapper
 Libreria complementaria para el consumo del web services de Zurich

 Metodos disponibles

## Catalogos
#### Entidades Catalogo
```php
$entidades = new WsZurich\Wrapper\EntidadesCatalogo();
```

Obtener Estados
```php
$entidades->getEstados();
```

Obtener Municipios
```php
$entidades->getMunicipios($claveEstado);
```

Obtener Asentamientos
```php
$entidades->getAsentamientos($claveEstado, $claveMunicipio)
```

Obtener Direccion por Códigos Postal
```php
$entidades->getDireccionByCodigoPostal($codigoPostal)
```

Obtener Códigos Postales
```php
$entidades->getCodigosPostales($claveEstado, $claveMunicio, $claveAsentamiento)
```

#### Vehiculos Catalogo
```php
$vehiculos = new WsZurich\Wrapper\VehiculosCatalogo();
```

Obtener Marcas
```php
$vehiculos->getMarcas($tipoVehiculo)
```

Obtener Submarcas
```php
$vehiculos->getSubMarcas($tipoVehiculo, $claveMarca)
```

Obtener Modelos
```php
$vehiculos->getModelos($tipoVehiculo, $claveMarca, $claveSubMarca)
```

Obtener Detalle del vehiculo
```php
$vehiculos->getDetalle($claveZurich)
```