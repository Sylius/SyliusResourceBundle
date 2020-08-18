## CHANGELOG FOR `1.6.x`

### v1.6.4 (2020-08-18)

Security release:

- [CVE-2020-15143: Remote Code Execution in ParametersParser while using request parameters inside expression language](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-p4pj-9g59-4ppv)
- [CVE-2020-15146: Remote Code Execution in OptionsParser while using request parameters inside expression language](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-h6m7-j4h3-9rf5)

### v1.6.3 (2020-01-27)

Security release:

- [CVE-2020-5220: Ability to define unintended serialisation groups via HTTP header which might lead to data exposure](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-8vp7-j5cj-vvm2)

### v1.6.2 (2020-01-13)

- [#145](https://github.com/Sylius/SyliusResourceBundle/issues/145) Autowire Doctrine\Persistence\ObjectManager ([@pamil](https://github.com/pamil))

### v1.6.1 (2019-10-10)

- [#112](https://github.com/Sylius/SyliusResourceBundle/issues/112) Support for Symfony 3.4 / 4.3+ ([@pamil](https://github.com/pamil))

### v1.6.0 (2019-10-07)

- [#104](https://github.com/Sylius/SyliusResourceBundle/issues/104) [RFC] Add success flashes before post event ([@Zales0123](https://github.com/Zales0123))
- [#110](https://github.com/Sylius/SyliusResourceBundle/issues/110) Add Psalm and fix all the errors ([@pamil](https://github.com/pamil))

### v1.6.0-RC.3 (2019-06-18)

- [#92](https://github.com/Sylius/SyliusResourceBundle/issues/92) Removing '2' from 'Symfony2' ([@loevgaard](https://github.com/loevgaard))
- [#95](https://github.com/Sylius/SyliusResourceBundle/issues/95) Autowire factories and repositories by class and name ([@pamil](https://github.com/pamil))
- [#97](https://github.com/Sylius/SyliusResourceBundle/issues/97) Autodiscover resource's model interfaces and deprecate explicit configuration ([@pamil](https://github.com/pamil))

### v1.6.0-RC.2 (2019-06-07)

- [#91](https://github.com/Sylius/SyliusResourceBundle/issues/91) Support for Gedmo/DoctrineExtensions ([@pamil](https://github.com/pamil))

### v1.6.0-RC.1 (2019-06-07)

- [#88](https://github.com/Sylius/SyliusResourceBundle/issues/88) Ensure forward compatibility with ResolveTargetEntityListener ([@teohhanhui](https://github.com/teohhanhui))
- [#89](https://github.com/Sylius/SyliusResourceBundle/issues/89) Add support for embeddables ([@pamil](https://github.com/pamil))
- [#90](https://github.com/Sylius/SyliusResourceBundle/issues/90) Drop Symfony 4.1, add support for Symfony 4.3 ([@pamil](https://github.com/pamil))
