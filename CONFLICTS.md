# CONFLICTS

This document explains why certain conflicts were added to `composer.json` and
references related issues.

- `"phpstan/phpdoc-parser": "^1.6.0"`:

  Usage of ^1.6 versions leads to following error: https://issuehint.com/issue/slevomat/coding-standard/1379. To remove it, Sylius-Labs/ECS 
  requires bump of Slevomat lib - "slevomat/coding-standard"
