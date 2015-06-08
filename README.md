# Laravel Addressable

## Installation

First, pull in the package through Composer.

```js
"require": {
    "draperstudio/laravel-addressable": "~1.0"
}
```

And then include the service provider within `app/config/app.php`.

```php
'providers' => [
    'DraperStudio\Addressable\AddressableServiceProvider',
];
```

To get started, you'll need to publish the vendor assets and migrate the countries table:

```bash
php artisan vendor:publish && php artisan migrate
```

Now you can seed the countries into the database like this.

```bash
php artisan countries:seed
```
