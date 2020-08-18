## CHANGELOG FOR `1.4.x`

### v1.4.7 (2020-08-18)

Security release:

- [CVE-2020-15143: Remote Code Execution in ParametersParser while using request parameters inside expression language](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-p4pj-9g59-4ppv)
- [CVE-2020-15146: Remote Code Execution in OptionsParser while using request parameters inside expression language](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-h6m7-j4h3-9rf5)

### v1.4.6 (2020-01-27)

Security release:

- [CVE-2020-5220: Ability to define unintended serialisation groups via HTTP header which might lead to data exposure](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-8vp7-j5cj-vvm2)

### v1.4.5 (2019-10-07)

- [#88](https://github.com/Sylius/SyliusResourceBundle/issues/88) Ensure forward compatibility with ResolveTargetEntityListener ([@teohhanhui](https://github.com/teohhanhui))
- [#104](https://github.com/Sylius/SyliusResourceBundle/issues/104) [RFC] Add success flashes before post event ([@Zales0123](https://github.com/Zales0123))

### v1.4.4 (2019-05-07)

#### TL;DR

Released ResourceBundle as a standalone package, containing a subtree split of Resource component.
