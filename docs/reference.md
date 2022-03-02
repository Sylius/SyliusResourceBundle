## Configuration Reference

```yaml
sylius_resource:
    mapping:
        paths: ['%kernel.project_dir%/src/Entity'] # Used for Routes with PHP attributes
    translation:
        enabled: true
        available_locales: ['en']
        default_locale: 'en'
        locale_provider: 'sylius.translation_locale_provider.immutable'
    settings:
        paginate: ~
        limit: ~
        allowed_paginate: [10, 20, 30]
        default_page_size: 10
        sortable: false
        sorting: ~
        filterable: false
        criteria: ~
        state_machine_component: winzou # or symfony
    resources:
        app.book:
            driver: doctrine/orm
            classes:
                model: # Required!
                interface: ~
                controller: Sylius\Bundle\ResourceBundle\Controller\ResourceController
                repository: ~
                factory: Sylius\Component\Resource\Factory\Factory
                form: Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType
                    validation_groups: [sylius]
            options:
                object_manager: default
            templates:
                form: Book/_form.html.twig
            translation:
                classes:
                    model: ~
                    interface: ~
                    controller: Sylius\Bundle\ResourceBundle\Controller\ResourceController
                    repository: ~
                    factory: Sylius\Component\Resource\Factory\Factory
                    form: Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType
                        validation_groups: [sylius]
                templates:
                    form: Book/Translation/_form.html.twig
                options: ~
```

## Routing Generator Configuration Reference

```yaml
app_book:
    resource: |
        alias: app.book
        path: library
        identifier: code
        criteria:
            code: $code
        section: admin
        templates: :Book
        form: App/Form/Type/SimpleBookType
        redirect: create
        except: ['show']
        only: ['create', 'index']
        serialization_version: 1
    type: sylius.resource
```
**[Go back to the documentation's index](index.md)**
