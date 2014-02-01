# 20steps/placetel-bundle (twentystepsPlacetelBundle)

## About

The 20steps Placetel Bundle provides a Service-oriented API for Symfony2 applications that need to interact with the Placetel monitoring service.

For further information about Placetel goto http://www.placetel.de.

## Features

- [x] Placetel accessible as a configurable Symfony2 service.
- [ ] Complete API for Placetel.
- [x] Configurable caching of responses to prevent surpassing rate limit.
- [ ] Provide some derived KPIs.
- [ ] Full documentation and some examples.
- [ ] Prepare for open sourcing of 20steps control.

## Installation

Require the bundle by adding the following entry to the respective section of your composer.json:
```
"20steps/placetel-bundle": "dev-master"
```

Get the bundle via packagist from GitHub by calling:
```
php composer.phar update 20steps/placetel-bundle
```

Register the bundle in your application by adding the following line to the registerBundles() method of your AppKernel.php:  
```
new twentysteps\Bundle\PlacetelBundle\twentystepsPlacetelBundle()
```

Register services provided by the bundle by adding the following line to the imports section of your config.yml:  
```
- { resource: "@twentystepsPlacetelBundle/Resources/config/services.yml" }
```

Define the following properties in your parameters.yml:  
* twentysteps_placetel.url - URL of the Placetel API - normally should point to "https://placetel.de/api/".
* twentysteps_placetel.api_key - API key of your account at Placetel.
* twentysteps_placetel.timeout - Timeout in seconds to apply on calls of the Placetel API - you should use 10.
* twentysteps_placetel.connect_timeout - Connect timeout in seconds to apply on calls to the Placetel API - you should use 5.
* twentysteps_placetel.cache_ttl - Cache TTL to apply on responses of the Placetel API - you should use 3600.

## Usage

* Get reference to the Placetel service either by adding @twentysteps_placetel.service as a dependency in your service or by  explicitely getting the service from the container during runtime e.g. by calling $this->get('twentysteps_placetel.service') in the action of your controller.
* Call any public function provided by Services/PlacetelService.php e.g. getServices() to get the monitoring services listed in Placetel.

## Version

This version is not yet complete or usable.

## Author

Helmut Hoffer von Ankershoffen (hhva@20steps.de).