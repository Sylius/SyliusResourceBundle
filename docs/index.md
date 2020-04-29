# SyliusResourceBundle

There are plenty of things you need to handle for every single Resource in your web application.

Several "Admin Generators" are available for Symfony, but we needed something really simple, that will allow us to have reusable controllers
but preserve the performance and standard Symfony workflow. We did not want to generate any code or write "Admin" class definitions in PHP.
The big goal was to have exactly the same workflow as with writing controllers manually but without actually creating them!

Another idea was not to limit ourselves to a single persistence backend.
``Resource`` component provides us with generic purpose persistence services and you can use this bundle with multiple persistence backends.
So far we support:

* Doctrine ORM
* Doctrine MongoDB ODM
* Doctrine PHPCR ODM
* InMemory
* ElasticSearch (via an [extension](https://github.com/Sylius/SyliusElasticSearchPlugin))

## Resource system for Symfony applications.

* [installation](installation.md)
* [configuration](configuration.md)
* [services](services.md)
* [routing](routing.md)
* [forms](forms.md)
* [show_resource](show_resource.md)
* [index_resources](index_resources.md)
* [create_resource](create_resource.md)
* [update_resource](update_resource.md)
* [delete_resource](delete_resource.md)
* [reference](reference.md)

## Learn more

* [Resource Layer in the Sylius platform](https://docs.sylius.com/en/latest/book/architecture/resource_layer.html) - concept documentation
