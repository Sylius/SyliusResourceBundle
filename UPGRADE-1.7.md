# UPGRADE FROM `v1.6.*` TO `v1.7.0`

* **BC BREAK**: Edit your `fos_rest` config to enable the body listener as follows:
```yaml
fos_rest:
    # [...]
    body_listener:
        enabled: true 
```

* **BC BREAK**: `Sylius\Bundle\ResourceBundle\Controller\ViewHandler` constructor now requires `FOS\RestBundle\View\ConfigurableViewHandlerInterface` as first argument instead of 
    `FOS\RestBundle\View\ViewHandler`.
