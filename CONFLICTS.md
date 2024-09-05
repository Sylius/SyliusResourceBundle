# CONFLICTS

This document explains why certain conflicts were added to `composer.json` and
references related issues.

- `willdurand/hateoas-bundle: ^2.6`

This version allows Symfony 7 but does not support the "annotation_reader" service removal.
@see https://github.com/willdurand/BazingaHateoasBundle/issues/108
