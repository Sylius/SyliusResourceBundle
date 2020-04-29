# Installation

We assume you're familiar with [Composer](http://packagist.org), a dependency manager for PHP.
Use the following command to add the bundle to your ``composer.json`` and download the package.

If you have [Composer installed globally](http://getcomposer.org/doc/00-intro.md#globally).

```bash
    composer require sylius/resource-bundle
```
Otherwise you have to download .phar file.

```bash
    curl -sS https://getcomposer.org/installer | php
    php composer.phar require sylius/resource-bundle
```
## Adding Required Bundles to The Kernel

You need to enable the bundle and its dependencies in the kernel:

```php
    return [
        new FOS\RestBundle\FOSRestBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle($this),
        new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
        new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
        new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
        new winzou\Bundle\StateMachineBundle\winzouStateMachineBundle(),
    ];
```
That's it! Now you can configure your first resource.

**[Go back to the documentation's index](index.md)**
