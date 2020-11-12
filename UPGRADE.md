## UPGRADE FOR `1.7.x`

### FROM `1.6.x` TO `1.7.x`

We switched from dot notation as service id for the repositories to class names to resolve [#217](https://github.com/Sylius/SyliusResourceBundle/issues/217).
This will cause an issue when referring to a repository via the dot notation in a compiler pass or extension via  
`getDefinition`. You can still refer the service via dot notation by using `findDefinition` instead.


## UPGRADE FOR `1.3.x`

### FROM `1.3.x` TO `1.3.13`

If you're using an "Accept" HTTP header to set the serialization groups, you need to define allowed groups
either by passing them as default in `serialization_groups` setting or marking them as allowed in 
`allowed_serialization_groups` setting, both settings are set in the route definition (under `_sylius` key).
