Didimo Laravel - Web API
========================

Laravel Package para enviar sms con la plataforma didimo.

[![Latest Stable Version](https://poser.pugx.org/ssheduardo/didimo-laravel/v/stable)](https://packagist.org/packages/ssheduardo/didimo-laravel)
[![Total Downloads](https://poser.pugx.org/ssheduardo/didimo-laravel/downloads)](https://packagist.org/packages/ssheduardo/didimo-laravel)
[![License](https://poser.pugx.org/ssheduardo/didimo-laravel/license)](https://packagist.org/packages/ssheduardo/didimo-laravel)


## Créditos

Eduardo Díaz, Madrid 2017

Twitter: @eduardo_dx


## Instalación

### Vía Composer

**Laravel 5.2, 5.3, 5.4**
``` bash
$ composer require "ssheduardo/didimo-laravel=~1.0"
```

Ahora debemos cargar nuestro Services Provider dentro del array **'providers'** (config/app.php)

```php
Ssheduardo\Didimo\SmsServiceProvider::class
```

Creamos un alias dentro del array **'aliases'** (config/app.php)

```php
'Sms'   => Ssheduardo\Didimo\Facades\Sms::class,
```

En nuestro archivo **.env** debemos agregar

```php
DIDIMO_USER=TU_USER
DIDIMO_PASSWORD=TU_PASSWORD
```

Finalmente publicamos nuestro archivo de configuración por si queremos cambiar la configuración y no hacer eso de las variables de entorno

```bash
php artisan vendor:publish --provider="Ssheduardo\Didimo\SmsServiceProvider"
```
>Esto nos creara un archivo llamado *didimo.php* dentro de config,

## Uso

### Enviar un sms

Imaginemos que tenemos esta ruta http://ubublog.com/sms que enlaza con **SmsController@index**

```php
Route::get('/sms', ['as' => 'sms', 'uses' => 'SmsController@index']);
```

Y el contenido del controlador **SmsController** sería este:
``` php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ssheduardo\Didimo\Facades\Sms;

class SmsController extends Controller
{

    public function index()
    {
        //Enviar sms desde producción
        Sms::setEnviroment('live');

        $response = Sms::createMessage('Test','[NUMERO_DESTINO]','Mensaje de prueba');
        if($response->Status == 200) {
            if($response->ResponseCode == 0 && $response->ResponseMessage == 'Operation Success') {
                echo "Enviado correctamente, id status: {$response->Id}";
            }
            else {
                echo 'Error, no se pudo enviar el sms';
            }
        }
        else {
            print_r($response);
        }
    }
}

```

> Tener en cuenta que para enviar sms de pruebas, tenéis que contactar por vuestro proveedor para que os de de alta. Bastará con cambiar live por test

```php
Sms::setEnviroment('test');
```

> Nota: Podemos pasar un tercer parámetro para programar el envío del sms, dicho valor tiene que tener el siguiente formato Y-m-d\TH:i:s.

```php
    $now = date('Y-m-d H:i:s');
    $newdate = date('Y-m-d\TH:i:s', strtotime('+1 hour', strtotime($now)));
    Sms::createMessage('Prueba','[NUMERO_DESTINO]','Mensaje con scheduler',$newdate);
```


### Consultar el estado de un mensaje

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ssheduardo\Didimo\Facades\Sms;

class SmsController extends Controller
{

    public function index()
    {
        //Consultar en producción
        Sms::setEnviroment('live');

        $id='c366018b-97ba-4a78-8183-0d975bd2620b';
        $response = Sms::getMessageStatus($id);
        if($response->Status == 200) {
            if($response->ResponseCode == 0 && $response->ResponseMessage == 'Operation Success') {
                echo "Estatus: ".$response->StatusDescription;
            }
            else {
                echo 'Error al obtener estatus';
            }
        }
        else{
            print_r($response);
        }
    }
}

```

### Consultar saldo disponible para enviar SMS

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ssheduardo\Didimo\Facades\Sms;

class SmsController extends Controller
{

    public function index()
    {
        //Consultar en producción
        Sms::setEnviroment('live');

        $response = Sms::getCredits();
        if($response->Status == 200) {
            if($response->ResponseCode == 0 && $response->ResponseMessage == 'Operation Success') {
                echo "Total saldo: ".$response->Credits;
            }
            else {
                echo 'Error al obtener saldo';
            }
        }
        else {
            print_r($response);
        }
    }
}

```
> Este package se apoya de mi clase principal https://github.com/ssheduardo/didimo.

## Documentación oficial
[Web API Didimo SMS - Manual de Integracion](https://goo.gl/j0yKRP)


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Licencia

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Donación

¿Te gustaría apoyarme?
¿Aprecias mi trabajo?
¿Lo usas en proyectos comerciales?

¡Siéntete libre de hacer una pequeña [donación](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ssh%2eeduardo%40gmail%2ecom&lc=ES&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted)! :wink:
