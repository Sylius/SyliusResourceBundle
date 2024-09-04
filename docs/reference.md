## Configuration Reference

```yaml
sylius_resource:
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
            templates: 'book' # directory of templates, this would be placed in `templates/book/`. Default value is empty. Keep in mind, that in Sylius we usually use UpperCamelCase notation in contrary to Symfony recommendations
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
