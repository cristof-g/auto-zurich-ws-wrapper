# Auto Zurich Web Service Wrapper
 Libreria complementaria para el consumo del web services de Zurich

 Metodos disponibles

## Catalogos
#### Entidades Catalogo
Obtener Estados
```php
getEstados();
```

Obtener Municipios
```php
getMunicipios($claveEstado);
```

Obtener Asentamientos
```php
getAsentamientos($claveEstado, $claveMunicipio)
```

Obtener Direccion por Códigos Postal
```php
getDireccionByCodigoPostal($codigoPostal)
```

Obtener Códigos Postales
```php
getCodigosPostales($claveEstado, $claveMunicio, $claveAsentamiento)
```

#### Vehiculos Catalogo
Obtener Marcas
```php
getMarcas($tipoVehiculo)
```

Obtener Submarcas
```php
getSubMarcas($tipoVehiculo, $claveMarca)
```

Obtener Modelos
```php
getModelos($tipoVehiculo, $claveMarca, $claveSubMarca)
```

Obtener Detalle del vehiculo
```php
getDetalle($claveZurich)
```