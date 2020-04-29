# Services

When you register an entity as a resource, several services are registered for you.
For the ``app.book`` resource, the following services are available:

* ``app.controller.book`` - instance of ``ResourceController``;
* ``app.factory.book`` - instance of [FactoryInterface](https://docs.sylius.com/en/latest/components_and_bundles/components/Resource/factory.html#component-resource-factory-factory-interface);
* ``app.repository.book`` - instance of [RepositoryInterface](https://docs.sylius.com/en/latest/components_and_bundles/components/Resource/repository.html#component-resource-repository-repository-interface);
* ``app.manager.book`` - alias to an appropriate Doctrine's ``ObjectManager``.

**[Go back to the documentation's index](index.md)**
