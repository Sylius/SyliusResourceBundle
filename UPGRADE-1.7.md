# UPGRADE FROM `v1.6.*` TO `v1.7.0`

* **BC BREAK**: `Sylius\Bundle\ResourceBundle\Controller\ViewHandler` constructor now requires `FOS\RestBundle\View\ConfigurableViewHandlerInterface` as first argument instead of 
    `FOS\RestBundle\View\ViewHandler`.
