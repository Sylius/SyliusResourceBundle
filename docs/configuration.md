# Configuring Your Resources

Now you need to configure your first resource. Let's assume you have a *Book* entity in your application and it has simple fields:

* id
* title
* author
* description

You can see a full exemplary configuration of a typical resource
[How to add a custom model?](https://docs.sylius.com/en/latest/cookbook/entities/custom-model.html)

## Implement the ResourceInterface in your model class.

```php
    namespace App\Entity;

    use Sylius\Component\Resource\Model\ResourceInterface;

    class Book implements ResourceInterface
    {
        // Most of the time you have the code below already in your class.
        protected $id;

        public function getId()
        {
            return $this->id;
        }
    }
```

## Configure the class as a resource.

In your ``config/packages/sylius_resource.yaml`` add:

```yaml
    sylius_resource:
        resources:
            app.book:
                classes:
                    model: App\Entity\Book
```
That's it! Your Book entity is now registered as Sylius Resource.

## You can also configure several doctrine drivers.

Remember that the ``doctrine/orm`` driver is used by default.

```yaml
    sylius_resource:
        drivers:
            - doctrine/orm
            - doctrine/phpcr-odm
        resources:
            app.book:
                classes:
                    model: App\Entity\Book
            app.article:
                driver: doctrine/phpcr-odm
                classes:
                    model: App\Document\ArticleDocument
```
Generate API routing.
---------------------


Learn more about using Sylius REST API in these articles:
[How to use Sylius API? - Cookbook](https://docs.sylius.com/en/latest/cookbook/api/api.html)

Add the following lines to ``config/routes.yaml``:

```yaml
    app_book:
        resource: |
            alias: app.book
        type: sylius.resource_api
```
After that a full JSON/XML CRUD API is ready to use.
Sounds crazy? Spin up the built-in server and give it a try:

```bash
    php bin/console server:run
```
You should see something like:

```bash
    Server running on http://127.0.0.1:8000

    Quit the server with CONTROL-C.
```
Now, in a separate Terminal window, call these commands:

```bash
   curl -i -X POST -H "Content-Type: application/json" -d '{"title": "Lord of The Rings", "author": "J. R. R. Tolkien", "description": "Amazing!"}' http://localhost:8000/books/
   curl -i -X GET -H "Accept: application/json" http://localhost:8000/books/
```
As you can guess, other CRUD actions are available through this API.

## Generate web routing.

What if you want to render HTML pages? That's easy! Update the routing configuration:

```yaml
    app_book:
        resource: |
            alias: app.book
        type: sylius.resource
```
This will generate routing for HTML views.

Run the ``debug:router`` command to see available routes:

```bash
    php bin/console debug:router

    ------------------------ --------------- -------- ------ -------------------------
    Name                     Method          Scheme   Host   Path
    ------------------------ --------------- -------- ------ -------------------------
    app_book_show            GET             ANY      ANY    /books/{id}
    app_book_index           GET             ANY      ANY    /books/
    app_book_create          GET|POST        ANY      ANY    /books/new
    app_book_update          GET|PUT|PATCH   ANY      ANY    /books/{id}/edit
    app_book_delete          DELETE          ANY      ANY    /books/{id}
```

Do you need **views** for your newly created entity? Read more about 
[Grids](https://docs.sylius.com/en/latest/components_and_bundles/bundles/SyliusGridBundle/index.html),
which are a separate bundle of Sylius, but may be very useful for views generation.

##
You can configure more options for the routing generation but you can also define each route manually to have it fully configurable.
Continue reading to learn more!

**[Go back to the documentation's index](index.md)**
