## CHANGELOG

### v1.10.3 (2023-12-01)

#### Details

- [#589](https://github.com/Sylius/SyliusResourceBundle/issues/589) Fix the build on 1.10 branch ([@loic425](https://github.com/loic425))
- [#493](https://github.com/Sylius/SyliusResourceBundle/issues/493) Add support for Doctrine persistence version 3.0 (@[@loic425](https://github.com/loic425))
- [#701](https://github.com/Sylius/SyliusResourceBundle/issues/701) [HotFix] Flip id with alias on resource loader ([@loic425](https://github.com/loic425))
- [#700](https://github.com/Sylius/SyliusResourceBundle/issues/700) Fix missing pagerfanta ORM adapter error ([@loic425](https://github.com/loic425))
- [#694](https://github.com/Sylius/SyliusResourceBundle/issues/694) Quick fix for state machine workflow usage with Symfony 6.2 ([@loic425](https://github.com/loic425))
- [#538](https://github.com/Sylius/SyliusResourceBundle/issues/538) fix(flashbag): fixed addFlash in ControllerTrait for Symfony 6 ([@UlrichHP](https://github.com/UlrichHP))
- [#765](https://github.com/Sylius/SyliusResourceBundle/issues/765) Missed typehint for `TranslatableFactoryInterface` ([@Rafikooo](https://github.com/Rafikooo))
- [#809](https://github.com/Sylius/SyliusResourceBundle/issues/809) Add support for Symfony 6.4 ([@loic425](https://github.com/loic425))

### v1.10.2 (2023-01-04)

#### Details

- [#510](https://github.com/Sylius/SyliusResourceBundle/issues/510) Fix declaring repository on resource when it is a service entity repository ([@loic425](https://github.com/loic425))
- [#530](https://github.com/Sylius/SyliusResourceBundle/issues/530) [DOCS] Change configuration to correct one ([@arti0090](https://github.com/arti0090))
- [#549](https://github.com/Sylius/SyliusResourceBundle/issues/549) Hotfix for Attributes routing system ([@loic425](https://github.com/loic425))
- [#550](https://github.com/Sylius/SyliusResourceBundle/issues/550) Fix build on 1.10 ([@loic425](https://github.com/loic425))
- [#551](https://github.com/Sylius/SyliusResourceBundle/issues/551) Flip back service ids and FQCN ([@loic425](https://github.com/loic425))

### v1.10.1 (2022-12-05)

#### Details

- [#498](https://github.com/Sylius/SyliusResourceBundle/issues/498) Add services' aliases to improve autowiring experience ([@loic425](https://github.com/loic425))
- [#502](https://github.com/Sylius/SyliusResourceBundle/issues/502) Fix the build ([@loic425](https://github.com/loic425))
- [#503](https://github.com/Sylius/SyliusResourceBundle/issues/503) Init gitattributes file to preserve the planet ([@loic425](https://github.com/loic425))
- [#507](https://github.com/Sylius/SyliusResourceBundle/issues/507) Fix Symfony 6 Exception ([@mpysiak](https://github.com/mpysiak), [@lchrusciel](https://github.com/lchrusciel))

### v1.10.0 (2022-10-31)

#### Details

- [#489](https://github.com/Sylius/SyliusResourceBundle/issues/489) Fix can apply a transition on workflow with a graph ([@loic425](https://github.com/loic425))
- [#492](https://github.com/Sylius/SyliusResourceBundle/issues/492) [Maintenance] Out-of-date phpstan/phpdoc-parser conflict removed, docker static analysis memory limit increased ([@Rafikooo](https://github.com/Rafikooo))
- [#495](https://github.com/Sylius/SyliusResourceBundle/issues/495) Inform about potential BC break after response code change ([@Zales0123](https://github.com/Zales0123))

### v1.10.0-BETA.1 (2022-10-18)

#### Details

- [#341](https://github.com/Sylius/SyliusResourceBundle/issues/341) Dropping usage of Request->get ([@loic425](https://github.com/loic425), [@Zales0123](https://github.com/Zales0123))
- [#450](https://github.com/Sylius/SyliusResourceBundle/issues/450) Adjust when some runtime deprecation notices are triggered and use Symfony's trigger_deprecation() helper ([@mbabker](https://github.com/mbabker))
- [#467](https://github.com/Sylius/SyliusResourceBundle/issues/467) [README] Add development section and update links ([@lchrusciel](https://github.com/lchrusciel))
- [#478](https://github.com/Sylius/SyliusResourceBundle/issues/478) Add tests with grids ([@loic425](https://github.com/loic425))
- [#487](https://github.com/Sylius/SyliusResourceBundle/issues/487) Make CsrfTokenManager public ([@Zales0123](https://github.com/Zales0123))
- [#488](https://github.com/Sylius/SyliusResourceBundle/issues/488) Return 422 status code when the form fails ([@belmeopmenieuwesim](https://github.com/belmeopmenieuwesim), [@Zales0123](https://github.com/Zales0123))

### v1.10.0-ALPHA.2 (2022-09-09)

#### Details

- [#446](https://github.com/Sylius/SyliusResourceBundle/issues/446) [Maintenance] Add flex support to global composer ([@lchrusciel](https://github.com/lchrusciel))
- [#454](https://github.com/Sylius/SyliusResourceBundle/issues/454) Add generic typehint to RepositoryInterface and FactoryInterface ([@MrSrsen](https://github.com/MrSrsen))
- [#458](https://github.com/Sylius/SyliusResourceBundle/issues/458) Basic configuration of Gitbook ([@Zales0123](https://github.com/Zales0123))
- [#459](https://github.com/Sylius/SyliusResourceBundle/issues/459) Fix coding standards to fix the build ([@Zales0123](https://github.com/Zales0123))
- [#460](https://github.com/Sylius/SyliusResourceBundle/issues/460) [Maintenance] Allow flex plugin during plugin installation ([@lchrusciel](https://github.com/lchrusciel))
- [#461](https://github.com/Sylius/SyliusResourceBundle/issues/461) [docker]Dockerized Resource Bundle ([@Ferror](https://github.com/Ferror), [@lchrusciel](https://github.com/lchrusciel))
- [#462](https://github.com/Sylius/SyliusResourceBundle/issues/462) Configure global symfony/flex plugin ([@Zales0123](https://github.com/Zales0123))
- [#465](https://github.com/Sylius/SyliusResourceBundle/issues/465) [Maintenance] Downgrade rector to fix build ([@lchrusciel](https://github.com/lchrusciel))
- [#466](https://github.com/Sylius/SyliusResourceBundle/issues/466) Update rector/rector requirement from ^0.12.20 to ^0.13.5 ([@dependabot](https://github.com/dependabot)[[@bot](https://github.com/bot)])
- [#470](https://github.com/Sylius/SyliusResourceBundle/issues/470) [CI] Fix the build for 1.10 ([@loic425](https://github.com/loic425))
- [#471](https://github.com/Sylius/SyliusResourceBundle/issues/471) [Symfony 6] Fix submitting a form ([@loic425](https://github.com/loic425))
- [#474](https://github.com/Sylius/SyliusResourceBundle/issues/474) Resource alias, always return 2 array items ([@Prometee](https://github.com/Prometee), [@lchrusciel](https://github.com/lchrusciel))

### v1.10.0-ALPHA.1 (2022-05-16)

#### TL;DR

Drop Symfony 4 support, add Symfony 6 support 🚀

#### Details

- [#379](https://github.com/Sylius/SyliusResourceBundle/issues/379) Fix setDeprecated deprecation ([@dannyvw](https://github.com/dannyvw))
- [#380](https://github.com/Sylius/SyliusResourceBundle/issues/380) Fix session deprecation ([@dannyvw](https://github.com/dannyvw))
- [#399](https://github.com/Sylius/SyliusResourceBundle/issues/399) [Symfony 6] Fix Kernel on test app ([@loic425](https://github.com/loic425))
- [#401](https://github.com/Sylius/SyliusResourceBundle/issues/401) [Symfony 6] Use storage factory id option on test app ([@loic425](https://github.com/loic425))
- [#402](https://github.com/Sylius/SyliusResourceBundle/issues/402) [Symfony 6] Fix http foundation request handler typehints ([@loic425](https://github.com/loic425))
- [#408](https://github.com/Sylius/SyliusResourceBundle/issues/408) [1.10] Correct branch alias to 1.10-dev ([@Zales0123](https://github.com/Zales0123))
- [#411](https://github.com/Sylius/SyliusResourceBundle/issues/411) [Symfony 6] Bump Psalm version from 4.7 to 4.22 ([@loic425](https://github.com/loic425))
- [#412](https://github.com/Sylius/SyliusResourceBundle/issues/412) [Symfony 6] Fix bootstrap on test app ([@loic425](https://github.com/loic425))
- [#413](https://github.com/Sylius/SyliusResourceBundle/issues/413) [Symfony 6] Fix setting deprecations on pagerfanta bridge pass ([@loic425](https://github.com/loic425))
- [#418](https://github.com/Sylius/SyliusResourceBundle/issues/418) [Symfony 6] Fix getting container on PHPUnit tests ([@loic425](https://github.com/loic425))
- [#419](https://github.com/Sylius/SyliusResourceBundle/issues/419) [Symfony 6] Fix routes on test app ([@loic425](https://github.com/loic425))
- [#426](https://github.com/Sylius/SyliusResourceBundle/issues/426) Add missing options on sylius route attribute ([@loic425](https://github.com/loic425))
- [#428](https://github.com/Sylius/SyliusResourceBundle/issues/428) Add support for Symfony 6  ([@loic425](https://github.com/loic425), [@lchrusciel](https://github.com/lchrusciel))
- [#430](https://github.com/Sylius/SyliusResourceBundle/issues/430) Drop Symfony 4 support ([@loic425](https://github.com/loic425))
- [#431](https://github.com/Sylius/SyliusResourceBundle/issues/431) Simplify Kernel on test app ([@loic425](https://github.com/loic425))
- [#433](https://github.com/Sylius/SyliusResourceBundle/issues/433) Remove session pass ([@loic425](https://github.com/loic425))
- [#434](https://github.com/Sylius/SyliusResourceBundle/issues/434) [Maintenance] Bump EasyCodingStandard dependency ([@lchrusciel](https://github.com/lchrusciel))
- [#435](https://github.com/Sylius/SyliusResourceBundle/issues/435) [Maintenance] Removal of Sf4.4 BC layer leftovers ([@lchrusciel](https://github.com/lchrusciel))
- [#437](https://github.com/Sylius/SyliusResourceBundle/issues/437) Remove is master request usage ([@loic425](https://github.com/loic425))
- [#439](https://github.com/Sylius/SyliusResourceBundle/issues/439) Fix Phpspec for symfony 6 ([@loic425](https://github.com/loic425))
- [#440](https://github.com/Sylius/SyliusResourceBundle/issues/440) Fix some other getting container deprecations ([@loic425](https://github.com/loic425))
- [#444](https://github.com/Sylius/SyliusResourceBundle/issues/444) [Symfony 6] Third solution to fix resource controllers ([@loic425](https://github.com/loic425))
- [#445](https://github.com/Sylius/SyliusResourceBundle/issues/445) [Maintenance] Drop Sf4 ACL on parameters class due to lack of its support ([@lchrusciel](https://github.com/lchrusciel))

### v1.9.1 (2022-05-16)

#### Details

- [#407](https://github.com/Sylius/SyliusResourceBundle/issues/407) [1.8] Correct branch alias to 1.8-dev ([@Zales0123](https://github.com/Zales0123))
- [#409](https://github.com/Sylius/SyliusResourceBundle/issues/409) Document supported branches ([@Zales0123](https://github.com/Zales0123))
- [#416](https://github.com/Sylius/SyliusResourceBundle/issues/416) Revert "Bump Pagerfanta from 2.x to 3.x" ([@lchrusciel](https://github.com/lchrusciel))
- [#417](https://github.com/Sylius/SyliusResourceBundle/issues/417) Revert "Revert "Bump Pagerfanta from 2.x to 3.x"" ([@lchrusciel](https://github.com/lchrusciel))
- [#424](https://github.com/Sylius/SyliusResourceBundle/issues/424) Run lint container instead of smoke test for twig ([@loic425](https://github.com/loic425))
- [#425](https://github.com/Sylius/SyliusResourceBundle/issues/425) Run lint container for state machine instead of smoke tests ([@loic425](https://github.com/loic425))
- [#432](https://github.com/Sylius/SyliusResourceBundle/issues/432) [Maintenance] Correct branch alias to 1.9-dev ([@lchrusciel](https://github.com/lchrusciel))
- [#446](https://github.com/Sylius/SyliusResourceBundle/issues/446) [Maintenance] Simplify GitHub action workflow ([@lchrusciel](https://github.com/lchrusciel))

### v1.9.0 (2022-04-07)

#### TL;DR

Stable 1.9.0 release 🎉🎉🎉

- PHP bumped to ^8.0
- PHP 7.4 syntaxt
- Sylius Resource routes generated with PHP attributes
- Support for Symfony Workflow
- Simpler usage of new service entity repository
- Multiple bug fixes and improvements

#### Details

- [#375](https://github.com/Sylius/SyliusResourceBundle/issues/375) Testing build with PHP 8.1 ([@loic425](https://github.com/loic425))
- [#378](https://github.com/Sylius/SyliusResourceBundle/issues/378) Fix phpdoc for getTranslations ([@dannyvw](https://github.com/dannyvw))
- [#381](https://github.com/Sylius/SyliusResourceBundle/issues/381) Bump Pagerfanta from 2.x to 3.x ([@mbabker](https://github.com/mbabker))
- [#403](https://github.com/Sylius/SyliusResourceBundle/issues/403) Add form attribute on SyliusCrudRoute ([@loic425](https://github.com/loic425))
- [#405](https://github.com/Sylius/SyliusResourceBundle/issues/405) Fix the build ([@loic425](https://github.com/loic425))
- [#406](https://github.com/Sylius/SyliusResourceBundle/issues/406) Allow to run GitHub actions manually + fix build on 1.8 ([@Zales0123](https://github.com/Zales0123))

### v1.9.0-RC.1 (2022-02-28)

#### Details

- [#338](https://github.com/Sylius/SyliusResourceBundle/issues/338) Fix some Symfony Deprecations ([@dannyvw](https://github.com/dannyvw))
- [#373](https://github.com/Sylius/SyliusResourceBundle/issues/373) Fix the build after #338 ([@Zales0123](https://github.com/Zales0123))

### v1.9.0-BETA.1 (2022-02-10)

#### Details

- [#365](https://github.com/Sylius/SyliusResourceBundle/issues/365) Fix route loaders ([@loic425](https://github.com/loic425))

### v1.9.0-ALPHA.1 (2022-01-25)

#### TL;DR

- Bump required PHP version to ^8.0 and use PHP 7.4 syntax
- Generate Sylius Resource routes with PHP attributes
- Add support for Symfony Workflow

#### Details

- [#287](https://github.com/Sylius/SyliusResourceBundle/issues/287) Manage event response in show and index action to be able to redirect ([@maximehuran](https://github.com/maximehuran), [@Zales0123](https://github.com/Zales0123))
- [#298](https://github.com/Sylius/SyliusResourceBundle/issues/298) Allow use with Pagerfanta 3.0 ([@mbabker](https://github.com/mbabker))
- [#310](https://github.com/Sylius/SyliusResourceBundle/issues/310) Upgrade to GitHub-native Dependabot ([@dependabot-preview](https://github.com/dependabot-preview)[[@bot](https://github.com/bot)])
- [#328](https://github.com/Sylius/SyliusResourceBundle/issues/328) Use PHP 7.4 syntax ([@Zales0123](https://github.com/Zales0123))
- [#330](https://github.com/Sylius/SyliusResourceBundle/issues/330) Add a simple way to use the new service entity repository ([@loic425](https://github.com/loic425))
- [#332](https://github.com/Sylius/SyliusResourceBundle/issues/332) Change all MasterRequest calls to MainRequest ([@Roshyo](https://github.com/Roshyo), [@Zales0123](https://github.com/Zales0123))
- [#333](https://github.com/Sylius/SyliusResourceBundle/issues/333) Don't use deprecated Twig `spaceless` tag ([@stloyd](https://github.com/stloyd))
- [#334](https://github.com/Sylius/SyliusResourceBundle/issues/334) Sylius route with attributes ([@loic425](https://github.com/loic425))
- [#334](https://github.com/Sylius/SyliusResourceBundle/issues/334) Symfony workflow ([@loic425](https://github.com/loic425))
- [#340](https://github.com/Sylius/SyliusResourceBundle/issues/340) Allow jms/serializer-bundle 4 ([@dannyvw](https://github.com/dannyvw))
- [#343](https://github.com/Sylius/SyliusResourceBundle/issues/343) Change ECS config to php and run it ([@Zales0123](https://github.com/Zales0123))
- [#344](https://github.com/Sylius/SyliusResourceBundle/issues/344) Remove travis build status from README ([@GSadee](https://github.com/GSadee))
- [#345](https://github.com/Sylius/SyliusResourceBundle/issues/345) Update phpstan/phpstan requirement from 0.12.94 to 0.12.99 ([@dependabot](https://github.com/dependabot)[[@bot](https://github.com/bot)])
- [#347](https://github.com/Sylius/SyliusResourceBundle/issues/347) Update phpstan/phpstan-webmozart-assert requirement from 0.12.12 to 0.12.16 ([@dependabot](https://github.com/dependabot)[[@bot](https://github.com/bot)])
- [#348](https://github.com/Sylius/SyliusResourceBundle/issues/348) Update phpstan/phpstan-phpunit requirement from 0.12.18 to 0.12.22 ([@dependabot](https://github.com/dependabot)[[@bot](https://github.com/bot)])
- [#349](https://github.com/Sylius/SyliusResourceBundle/issues/349) Update winzou/state-machine-bundle requirement from ^0.5 to ^0.6 ([@dependabot](https://github.com/dependabot)[[@bot](https://github.com/bot)])
- [#353](https://github.com/Sylius/SyliusResourceBundle/issues/353) Require symfony/routing and symfony/http-foundation 4.4 and 5.4 ([@Zales0123](https://github.com/Zales0123))
- [#354](https://github.com/Sylius/SyliusResourceBundle/issues/354) Reactivate checking coding standard ([@loic425](https://github.com/loic425))
- [#356](https://github.com/Sylius/SyliusResourceBundle/issues/356) Add documentation for Routes with attributes ([@loic425](https://github.com/loic425))
- [#358](https://github.com/Sylius/SyliusResourceBundle/issues/358) Fix docs for crud routes with attributes ([@loic425](https://github.com/loic425))
- [#359](https://github.com/Sylius/SyliusResourceBundle/issues/359) Fix type of serialization groups ([@loic425](https://github.com/loic425))

### v1.8.4 (2022-04-11)

#### Details

- [#416](https://github.com/Sylius/SyliusResourceBundle/issues/416) Revert "Bump Pagerfanta from 2.x to 3.x" ([@lchrusciel](https://github.com/lchrusciel))

### v1.8.3 (2022-04-07)

#### Details

- [#375](https://github.com/Sylius/SyliusResourceBundle/issues/375) Testing build with PHP 8.1 ([@loic425](https://github.com/loic425))
- [#381](https://github.com/Sylius/SyliusResourceBundle/issues/381) Bump Pagerfanta from 2.x to 3.x ([@mbabker](https://github.com/mbabker))
- [#406](https://github.com/Sylius/SyliusResourceBundle/issues/406) Allow to run GitHub actions manually + fix build on 1.8 ([@Zales0123](https://github.com/Zales0123))

### v1.8.2 (2021-04-08)

#### Details

- [#304](https://github.com/Sylius/SyliusResourceBundle/issues/304) Fix doctrine extensions version on component ([@loic425](https://github.com/loic425))
- [#303](https://github.com/Sylius/SyliusResourceBundle/issues/303) fix namespace of `ConfigurationTest` ([@bendavies](https://github.com/bendavies))
- [#302](https://github.com/Sylius/SyliusResourceBundle/issues/302) Fix namespace of `WinzouStateMachinePassTest` ([@bendavies](https://github.com/bendavies))
- [#301](https://github.com/Sylius/SyliusResourceBundle/issues/301) Update phpstan/phpstan requirement from 0.12.82 to 0.12.83 ([@dependabot-preview](https://github.com/dependabot-preview)[[@bot](https://github.com/bot)])
- [#300](https://github.com/Sylius/SyliusResourceBundle/issues/300) Update vimeo/psalm requirement from 4.6.4 to 4.7.0 ([@dependabot-preview](https://github.com/dependabot-preview)[[@bot](https://github.com/bot)])

### v1.8.1 (2021-03-19)

#### Details

- [#297](https://github.com/Sylius/SyliusResourceBundle/issues/297) Skip registering controllers as a services if there is no custom class defined ([@pamil](https://github.com/pamil))

### v1.8.0 (2021-03-19)

#### TL;DR

- Added support for PHP 8
- Removed StofDoctrineExtensionsBundle from dependencies
- Remvoed support for winzou/state-machine-bundle <0.5

#### Details

- [#210](https://github.com/Sylius/SyliusResourceBundle/issues/210) Add compatibility with PHP 8 ([@pamil](https://github.com/pamil))
- [#247](https://github.com/Sylius/SyliusResourceBundle/issues/247) Fix wrong licence on test app's kernel ([@loic425](https://github.com/loic425))
- [#255](https://github.com/Sylius/SyliusResourceBundle/issues/255) Add autowire for resource Controllers ([@AdamKasp](https://github.com/AdamKasp), [@lchrusciel](https://github.com/lchrusciel))
- [#259](https://github.com/Sylius/SyliusResourceBundle/issues/259) [Minor] Add symfony.lock to git ignore ([@lchrusciel](https://github.com/lchrusciel))
- [#264](https://github.com/Sylius/SyliusResourceBundle/issues/264) Fix the build ([@pamil](https://github.com/pamil))
- [#283](https://github.com/Sylius/SyliusResourceBundle/issues/283) Remove StofDoctrineExtensionsBundle and replace it with GedmoDoctrineExtensions ([@pamil](https://github.com/pamil))
- [#285](https://github.com/Sylius/SyliusResourceBundle/issues/285) Drop winzou/state-machine-bundle <0.5 ([@pamil](https://github.com/pamil))

### v1.7.1 (2020-12-09)

#### Details

- [#243](https://github.com/Sylius/SyliusResourceBundle/issues/243) Add back winzou/state-machine to required packages of the component ([@pamil](https://github.com/pamil))

### v1.7.0 (2020-12-09)

These are complete release notes summing up all BETA and RC releases.

#### TL;DR

- Bumped up requirements from PHP 7.2 to PHP 7.3
- Dropped support for Symfony ^3.4, added support for Symfony ^5.1
- Added support for `doctrine/doctrine-bundle` in version `^2.0`
- Added support for `winzou/state-machine-bundle` in versions `^0.4.3` and `^0.5`
- Bumped up `friendsofsymfony/rest-bundle` requirements from `^2.1` to `^3.0`
- Bumped up `jms/serializer-bundle` requirements from `^2.0` to `^3.5`
- Bumped up `willdurand/hateoas-bundle` requirements from `^1.2` to `^2.0`
- Removed the usage of deprecated `doctrine/inflector` API, added support for version `^2.0`
- Replaced `white-october/pagerfanta-bundle:^1.0` with `babdev/pagerfanta-bundle:^2.5`
- Deduplicated repositories retrieved from the service container and from the object manager 

#### Details

- [#114](https://github.com/Sylius/SyliusResourceBundle/issues/114) Updating composer dependencies ([@mamazu](https://github.com/mamazu))
- [#117](https://github.com/Sylius/SyliusResourceBundle/issues/117) Fix extended types deprecation ([@dannyvw](https://github.com/dannyvw))
- [#119](https://github.com/Sylius/SyliusResourceBundle/issues/119) Update composer dependencies ([@pamil](https://github.com/pamil), [@dannyvw](https://github.com/dannyvw))
- [#124](https://github.com/Sylius/SyliusResourceBundle/issues/124) Replace deprecated doctrine object manager ([@loic425](https://github.com/loic425))
- [#125](https://github.com/Sylius/SyliusResourceBundle/issues/125) Replace deprecated doctrine object repository ([@loic425](https://github.com/loic425))
- [#126](https://github.com/Sylius/SyliusResourceBundle/issues/126) Replace dbal types ([@loic425](https://github.com/loic425))
- [#127](https://github.com/Sylius/SyliusResourceBundle/issues/127) Fix phpspec tests on DefaultFormBuilder ([@loic425](https://github.com/loic425))
- [#128](https://github.com/Sylius/SyliusResourceBundle/issues/128) Lock static analysis tools versions & fix the build ([@pamil](https://github.com/pamil))
- [#129](https://github.com/Sylius/SyliusResourceBundle/issues/129) Add support for PHP 7.4 and Symfony 4.4 ([@pamil](https://github.com/pamil), [@dannyvw](https://github.com/dannyvw))
- [#130](https://github.com/Sylius/SyliusResourceBundle/issues/130) Remove deprecated templating configuration ([@dannyvw](https://github.com/dannyvw))
- [#131](https://github.com/Sylius/SyliusResourceBundle/issues/131) Allow twig 3.x ([@dannyvw](https://github.com/dannyvw))
- [#132](https://github.com/Sylius/SyliusResourceBundle/issues/132) Fix controller deprecation ([@dannyvw](https://github.com/dannyvw))
- [#133](https://github.com/Sylius/SyliusResourceBundle/issues/133) Fix testing multiple Symfony versions, add build for 5.0 & remove support for <4.4 ([@pamil](https://github.com/pamil))
- [#135](https://github.com/Sylius/SyliusResourceBundle/issues/135) Allow for DoctrineBundle ^2.0 ([@pamil](https://github.com/pamil))
- [#136](https://github.com/Sylius/SyliusResourceBundle/issues/136) Remove unneccessary dependency on winzou/state-machine in the component ([@pamil](https://github.com/pamil))
- [#138](https://github.com/Sylius/SyliusResourceBundle/issues/138) Remove legacy di configuration ([@dannyvw](https://github.com/dannyvw))
- [#139](https://github.com/Sylius/SyliusResourceBundle/issues/139) Fix event dispatcher deprecation ([@dannyvw](https://github.com/dannyvw))
- [#141](https://github.com/Sylius/SyliusResourceBundle/issues/141) Upgrade to PHPStan 0.12 ([@GSadee](https://github.com/GSadee))
- [#142](https://github.com/Sylius/SyliusResourceBundle/issues/142) Github repository configuration from Sylius/Sylius ([@CoderMaggie](https://github.com/CoderMaggie))
- [#143](https://github.com/Sylius/SyliusResourceBundle/issues/143) [Maintenance] Updated branch alias ([@lchrusciel](https://github.com/lchrusciel))
- [#144](https://github.com/Sylius/SyliusResourceBundle/issues/144) [Maintenance] Update github templates ([@lchrusciel](https://github.com/lchrusciel))
- [#151](https://github.com/Sylius/SyliusResourceBundle/issues/151) [Maintenance] Bump ApiTestCase to v5.0 ([@lchrusciel](https://github.com/lchrusciel))
- [#159](https://github.com/Sylius/SyliusResourceBundle/issues/159) Remove duplicated docblocks ([@GSadee](https://github.com/GSadee))
- [#160](https://github.com/Sylius/SyliusResourceBundle/issues/160) ResourceBundle documentation extracted to its repository ([@SirDomin](https://github.com/SirDomin))
- [#161](https://github.com/Sylius/SyliusResourceBundle/issues/161) [HOTFIX] Conflict with amphp/amp 2.4.3 ([@lchrusciel](https://github.com/lchrusciel))
- [#162](https://github.com/Sylius/SyliusResourceBundle/issues/162) [Documentation] Fix index menu ([@SirDomin](https://github.com/SirDomin))
- [#163](https://github.com/Sylius/SyliusResourceBundle/issues/163) [HOTFIX] Conflict with the newest amphp/amp ([@lchrusciel](https://github.com/lchrusciel))
- [#165](https://github.com/Sylius/SyliusResourceBundle/issues/165) Fix the build ([@pamil](https://github.com/pamil))
- [#167](https://github.com/Sylius/SyliusResourceBundle/issues/167) Upgrade rest bundle ([@loic425](https://github.com/loic425))
- [#168](https://github.com/Sylius/SyliusResourceBundle/issues/168) [Docs] Serialization groups of the elements in a paginated collection ([@vvasiloi](https://github.com/vvasiloi))
- [#172](https://github.com/Sylius/SyliusResourceBundle/issues/172) Check if form is submitted on resource creation/edition ([@loic425](https://github.com/loic425))
- [#173](https://github.com/Sylius/SyliusResourceBundle/issues/173) Remove rest dependencies ([@loic425](https://github.com/loic425), [@pamil](https://github.com/pamil))
- [#175](https://github.com/Sylius/SyliusResourceBundle/issues/175) Upgrade PagerfantaBundle to new version with B/C layer ([@mbabker](https://github.com/mbabker))
- [#177](https://github.com/Sylius/SyliusResourceBundle/issues/177) Pagerfanta updates ([@mbabker](https://github.com/mbabker))
- [#178](https://github.com/Sylius/SyliusResourceBundle/issues/178) When using winzouStateMachineBundle 0.4, the old named services are aliases, so need to be marked public as well ([@mbabker](https://github.com/mbabker), [@pamil](https://github.com/pamil))
- [#181](https://github.com/Sylius/SyliusResourceBundle/issues/181) Fix build ([@loic425](https://github.com/loic425))
- [#182](https://github.com/Sylius/SyliusResourceBundle/issues/182) Testing several state machine versions ([@loic425](https://github.com/loic425))
- [#187](https://github.com/Sylius/SyliusResourceBundle/issues/187) Symfony 5 support ([@loic425](https://github.com/loic425))
- [#189](https://github.com/Sylius/SyliusResourceBundle/issues/189) Require webmozart/assert as it's used by the bundle code ([@pamil](https://github.com/pamil))
- [#190](https://github.com/Sylius/SyliusResourceBundle/issues/190) Fix errors reported by static analysis tools ([@pamil](https://github.com/pamil))
- [#191](https://github.com/Sylius/SyliusResourceBundle/issues/191) Normalise composer.json (with ergebnis/composer-normalize) ([@pamil](https://github.com/pamil))
- [#192](https://github.com/Sylius/SyliusResourceBundle/issues/192) Remove conflict with amphp/amp ([@pamil](https://github.com/pamil))
- [#193](https://github.com/Sylius/SyliusResourceBundle/issues/193) Bump up requirements to PHP ^7.3 ([@pamil](https://github.com/pamil))
- [#194](https://github.com/Sylius/SyliusResourceBundle/issues/194) Fix deprecations and errors while running PHPUnit ([@pamil](https://github.com/pamil))
- [#195](https://github.com/Sylius/SyliusResourceBundle/issues/195) Upgrade to Psalm v3.17.1 ([@pamil](https://github.com/pamil))
- [#196](https://github.com/Sylius/SyliusResourceBundle/issues/196) Remove unnecessary dev dependency on "polishsymfonycommunity/symfony-mocker-container" ([@pamil](https://github.com/pamil))
- [#197](https://github.com/Sylius/SyliusResourceBundle/issues/197) Make RegisterFormBuilderPass accept multiple tags on a single service ([@pamil](https://github.com/pamil))
- [#198](https://github.com/Sylius/SyliusResourceBundle/issues/198) Bump up minimal requirements to Symfony ^5.1 ([@pamil](https://github.com/pamil))
- [#199](https://github.com/Sylius/SyliusResourceBundle/issues/199) Update the year in LICENSE file ([@ValentineJester](https://github.com/ValentineJester))
- [#200](https://github.com/Sylius/SyliusResourceBundle/issues/200) Use HTTPS instead of HTTP in links in composer.json ([@ValentineJester](https://github.com/ValentineJester))
- [#201](https://github.com/Sylius/SyliusResourceBundle/issues/201) Remove outdated persistence backends from the documentation ([@ValentineJester](https://github.com/ValentineJester))
- [#202](https://github.com/Sylius/SyliusResourceBundle/issues/202) Remove winzou state machine dependency ([@loic425](https://github.com/loic425))
- [#204](https://github.com/Sylius/SyliusResourceBundle/issues/204) Replace AbstractController with ControllerTrait & ContainerAwareInterface ([@pamil](https://github.com/pamil))
- [#206](https://github.com/Sylius/SyliusResourceBundle/issues/206) Remove twig bundle dependency ([@loic425](https://github.com/loic425))
- [#207](https://github.com/Sylius/SyliusResourceBundle/issues/207) Bump doctrine/persistance version ([@dotdevru](https://github.com/dotdevru))
- [#209](https://github.com/Sylius/SyliusResourceBundle/issues/209) [Travis] Use symfony/flex ^1.10 instead of dev-master ([@pamil](https://github.com/pamil))
- [#211](https://github.com/Sylius/SyliusResourceBundle/issues/211) Upgrade to Psalm 4 ([@pamil](https://github.com/pamil))
- [#212](https://github.com/Sylius/SyliusResourceBundle/issues/212) Normalise composer.json ([@pamil](https://github.com/pamil))
- [#213](https://github.com/Sylius/SyliusResourceBundle/issues/213) Update component's composer.json and normalise it ([@pamil](https://github.com/pamil))
- [#214](https://github.com/Sylius/SyliusResourceBundle/issues/214) Do not use deprecated Doctrine Inflector API ([@pamil](https://github.com/pamil))
- [#215](https://github.com/Sylius/SyliusResourceBundle/issues/215) Fix InMemoryRepository::applyOrder implementation ([@pamil](https://github.com/pamil))
- [#216](https://github.com/Sylius/SyliusResourceBundle/issues/216) [CI] Better job naming ([@pamil](https://github.com/pamil))
- [#220](https://github.com/Sylius/SyliusResourceBundle/issues/220) Duplicate initialisation of repositories ([@Fantus](https://github.com/Fantus))
- [#224](https://github.com/Sylius/SyliusResourceBundle/issues/224) Require previously required dependencies ([@pamil](https://github.com/pamil))
- [#225](https://github.com/Sylius/SyliusResourceBundle/issues/225) Bump up dev dependencies ([@pamil](https://github.com/pamil))
- [#226](https://github.com/Sylius/SyliusResourceBundle/issues/226) Remove Gedmo/DoctrineExtensions from dependencies ([@pamil](https://github.com/pamil))
- [#227](https://github.com/Sylius/SyliusResourceBundle/issues/227) Apply misc static analysis fixes ([@pamil](https://github.com/pamil))
- [#228](https://github.com/Sylius/SyliusResourceBundle/issues/228) Add an ability to define your own Inflector for Metadata class ([@pamil](https://github.com/pamil))
- [#229](https://github.com/Sylius/SyliusResourceBundle/issues/229) Fix tests namespace in the bundle ([@pamil](https://github.com/pamil))
- [#230](https://github.com/Sylius/SyliusResourceBundle/issues/230) Do not rely on services in DoctrineORMDriver ([@pamil](https://github.com/pamil))
- [#231](https://github.com/Sylius/SyliusResourceBundle/issues/231) Remove unnecessary BC layer for symfony/dependency-injection <=4.2 ([@pamil](https://github.com/pamil))
- [#232](https://github.com/Sylius/SyliusResourceBundle/issues/232) Create ResourceBundle's EntityRepository only if custom repository is not set ([@pamil](https://github.com/pamil))
- [#234](https://github.com/Sylius/SyliusResourceBundle/issues/234) Refactor test app ([@loic425](https://github.com/loic425))
- [#238](https://github.com/Sylius/SyliusResourceBundle/issues/238) Always set sylius.doctrine.orm.container_repository_factory.entities parameter ([@pamil](https://github.com/pamil))
- [#239](https://github.com/Sylius/SyliusResourceBundle/issues/239) Fix the static analysis build ([@pamil](https://github.com/pamil))

### v1.7.0-RC.4 (2020-12-07)

#### Details

- [#234](https://github.com/Sylius/SyliusResourceBundle/issues/234) Refactor test app ([@loic425](https://github.com/loic425))
- [#238](https://github.com/Sylius/SyliusResourceBundle/issues/238) Always set sylius.doctrine.orm.container_repository_factory.entities parameter ([@pamil](https://github.com/pamil))
- [#239](https://github.com/Sylius/SyliusResourceBundle/issues/239) Fix the static analysis build ([@pamil](https://github.com/pamil))

### v1.7.0-RC.3 (2020-11-25)

#### Details

- [#232](https://github.com/Sylius/SyliusResourceBundle/issues/232) Create ResourceBundle's EntityRepository only if custom repository is not set ([@pamil](https://github.com/pamil))

### v1.7.0-RC.2 (2020-11-25)

#### Details

- [#230](https://github.com/Sylius/SyliusResourceBundle/issues/230) Do not rely on services in DoctrineORMDriver ([@pamil](https://github.com/pamil))
- [#231](https://github.com/Sylius/SyliusResourceBundle/issues/231) Remove unnecessary BC layer for symfony/dependency-injection <=4.2 ([@pamil](https://github.com/pamil))

### v1.7.0-RC.1 (2020-11-24)

#### TL;DR

- Added an ability to customise the inflector used by Metadata class
- All the packages made optional in previous 1.7.0-BETA releases were made required once again

#### Details

- [#224](https://github.com/Sylius/SyliusResourceBundle/issues/224) Require previously required dependencies ([@pamil](https://github.com/pamil))
- [#225](https://github.com/Sylius/SyliusResourceBundle/issues/225) Bump up dev dependencies ([@pamil](https://github.com/pamil))
- [#226](https://github.com/Sylius/SyliusResourceBundle/issues/226) Remove Gedmo/DoctrineExtensions from dependencies ([@pamil](https://github.com/pamil))
- [#227](https://github.com/Sylius/SyliusResourceBundle/issues/227) Apply misc static analysis fixes ([@pamil](https://github.com/pamil))
- [#228](https://github.com/Sylius/SyliusResourceBundle/issues/228) Add an ability to define your own Inflector for Metadata class ([@pamil](https://github.com/pamil))
- [#229](https://github.com/Sylius/SyliusResourceBundle/issues/229) Fix tests namespace in the bundle ([@pamil](https://github.com/pamil))

### v1.7.0-BETA.5 (2020-11-23)

#### TL;DR

- Modernized the usage of Doctrine Inflector not to use deprecated API
- Made sure there is only one instance of repository service for each resource

#### Details

- [#214](https://github.com/Sylius/SyliusResourceBundle/issues/214) Do not use deprecated Doctrine Inflector API ([@pamil](https://github.com/pamil))
- [#215](https://github.com/Sylius/SyliusResourceBundle/issues/215) Fix InMemoryRepository::applyOrder implementation ([@pamil](https://github.com/pamil))
- [#216](https://github.com/Sylius/SyliusResourceBundle/issues/216) [CI] Better job naming ([@pamil](https://github.com/pamil))
- [#220](https://github.com/Sylius/SyliusResourceBundle/issues/220) Duplicate initialisation of repositories ([@Justus Krapp](https://github.com/Fantus))
- [#221](https://github.com/Sylius/SyliusResourceBundle/issues/221) Update vimeo/psalm requirement from 4.1.1 to 4.2.1 ([@dependabot-preview](https://github.com/dependabot-preview))
- [#223](https://github.com/Sylius/SyliusResourceBundle/issues/223) Update phpstan/phpstan requirement from 0.12.49 to 0.12.57 ([@dependabot-preview](https://github.com/dependabot-preview))

### v1.7.0-BETA.4 (2020-11-05)

#### TL;DR

- Made `winzou/state-machine-bundle` optional
- Made `symfony/twig-bundle` optional

#### Details

- [#202](https://github.com/Sylius/SyliusResourceBundle/issues/202) Remove winzou state machine dependency ([@loic425](https://github.com/loic425))
- [#206](https://github.com/Sylius/SyliusResourceBundle/issues/206) Remove twig bundle dependency ([@loic425](https://github.com/loic425))
- [#207](https://github.com/Sylius/SyliusResourceBundle/issues/207) Bump doctrine/persistance version ([@dotdevru](https://github.com/dotdevru))
- [#209](https://github.com/Sylius/SyliusResourceBundle/issues/209) [Travis] Use symfony/flex ^1.10 instead of dev-master ([@pamil](https://github.com/pamil))
- [#211](https://github.com/Sylius/SyliusResourceBundle/issues/211) Upgrade to Psalm 4 ([@pamil](https://github.com/pamil))
- [#212](https://github.com/Sylius/SyliusResourceBundle/issues/212) Normalise composer.json ([@pamil](https://github.com/pamil))
- [#213](https://github.com/Sylius/SyliusResourceBundle/issues/213) Update component's composer.json and normalise it ([@pamil](https://github.com/pamil))

### v1.7.0-BETA.3 (2020-10-15)

#### Details

- [#198](https://github.com/Sylius/SyliusResourceBundle/issues/198) Bump up minimal requirements to Symfony ^5.1 ([@pamil](https://github.com/pamil))
- [#199](https://github.com/Sylius/SyliusResourceBundle/issues/199) Update the year in LICENSE file ([@ValentineJester](https://github.com/ValentineJester))
- [#200](https://github.com/Sylius/SyliusResourceBundle/issues/200) Use HTTPS instead of HTTP in links in composer.json ([@ValentineJester](https://github.com/ValentineJester))
- [#201](https://github.com/Sylius/SyliusResourceBundle/issues/201) Remove outdated persistence backends from the documentation ([@ValentineJester](https://github.com/ValentineJester))
- [#204](https://github.com/Sylius/SyliusResourceBundle/issues/204) Replace AbstractController with ControllerTrait & ContainerAwareInterface ([@pamil](https://github.com/pamil))

### v1.7.0-BETA.2 (2020-10-14)

#### TL;DR

- Made FOSRestBundle optional
- Ensured WinzouStateMachineBundle services and aliases are public

#### Details

- [#173](https://github.com/Sylius/SyliusResourceBundle/issues/173) Remove rest dependencies ([@loic425](https://github.com/loic425), [@pamil](https://github.com/pamil))
- [#177](https://github.com/Sylius/SyliusResourceBundle/issues/177) Pagerfanta updates ([@mbabker](https://github.com/mbabker))
- [#178](https://github.com/Sylius/SyliusResourceBundle/issues/178) When using winzouStateMachineBundle 0.4, the old named services are aliases, so need to be marked public as well ([@mbabker](https://github.com/mbabker), [@pamil](https://github.com/pamil))
- [#192](https://github.com/Sylius/SyliusResourceBundle/issues/192) Remove conflict with amphp/amp ([@pamil](https://github.com/pamil))
- [#195](https://github.com/Sylius/SyliusResourceBundle/issues/195) Upgrade to Psalm v3.17.1 ([@pamil](https://github.com/pamil))
- [#196](https://github.com/Sylius/SyliusResourceBundle/issues/196) Remove unnecessary dev dependency on "polishsymfonycommunity/symfony-mocker-container" ([@pamil](https://github.com/pamil))
- [#197](https://github.com/Sylius/SyliusResourceBundle/issues/197) Make RegisterFormBuilderPass accept multiple tags on a single service ([@pamil](https://github.com/pamil))

### v1.7.0-BETA.1 (2020-10-13)

#### TL;DR

- Added support for Symfony 5
- Added support for Twig 3
- Added support for `winzou/state-machine-bundle` 0.4 and 0.5

#### Details

- [#114](https://github.com/Sylius/SyliusResourceBundle/issues/114) Updating composer dependencies ([@mamazu](https://github.com/mamazu))
- [#117](https://github.com/Sylius/SyliusResourceBundle/issues/117) Fix extended types deprecation ([@dannyvw](https://github.com/dannyvw))
- [#119](https://github.com/Sylius/SyliusResourceBundle/issues/119) Update composer dependencies ([@dannyvw](https://github.com/dannyvw))
- [#124](https://github.com/Sylius/SyliusResourceBundle/issues/124) Replace deprecated doctrine object manager ([@loic425](https://github.com/loic425))
- [#125](https://github.com/Sylius/SyliusResourceBundle/issues/125) Replace deprecated doctrine object repository ([@loic425](https://github.com/loic425))
- [#126](https://github.com/Sylius/SyliusResourceBundle/issues/126) Replace dbal types ([@loic425](https://github.com/loic425))
- [#127](https://github.com/Sylius/SyliusResourceBundle/issues/127) Fix phpspec tests on DefaultFormBuilder ([@loic425](https://github.com/loic425))
- [#128](https://github.com/Sylius/SyliusResourceBundle/issues/128) Lock static analysis tools versions & fix the build ([@pamil](https://github.com/pamil))
- [#129](https://github.com/Sylius/SyliusResourceBundle/issues/129) Add support for PHP 7.4 and Symfony 4.4 ([@dannyvw](https://github.com/dannyvw), [@pamil](https://github.com/pamil))
- [#130](https://github.com/Sylius/SyliusResourceBundle/issues/130) Remove deprecated templating configuration ([@dannyvw](https://github.com/dannyvw))
- [#131](https://github.com/Sylius/SyliusResourceBundle/issues/131) Allow twig 3.x ([@dannyvw](https://github.com/dannyvw))
- [#132](https://github.com/Sylius/SyliusResourceBundle/issues/132) Fix controller deprecation ([@dannyvw](https://github.com/dannyvw))
- [#133](https://github.com/Sylius/SyliusResourceBundle/issues/133) Fix testing multiple Symfony versions, add build for 5.0 & remove support for <4.4 ([@pamil](https://github.com/pamil))
- [#135](https://github.com/Sylius/SyliusResourceBundle/issues/135) Allow for DoctrineBundle ^2.0 ([@pamil](https://github.com/pamil))
- [#136](https://github.com/Sylius/SyliusResourceBundle/issues/136) Remove unneccessary dependency on winzou/state-machine in the component ([@pamil](https://github.com/pamil))
- [#138](https://github.com/Sylius/SyliusResourceBundle/issues/138) Remove legacy di configuration ([@dannyvw](https://github.com/dannyvw))
- [#139](https://github.com/Sylius/SyliusResourceBundle/issues/139) Fix event dispatcher deprecation ([@dannyvw](https://github.com/dannyvw))
- [#141](https://github.com/Sylius/SyliusResourceBundle/issues/141) Upgrade to PHPStan 0.12 ([@GSadee](https://github.com/GSadee))
- [#142](https://github.com/Sylius/SyliusResourceBundle/issues/142) Github repository configuration from Sylius/Sylius ([@CoderMaggie](https://github.com/CoderMaggie))
- [#143](https://github.com/Sylius/SyliusResourceBundle/issues/143) [Maintenance] Updated branch alias ([@lchrusciel](https://github.com/lchrusciel))
- [#144](https://github.com/Sylius/SyliusResourceBundle/issues/144) [Maintenance] Update github templates ([@lchrusciel](https://github.com/lchrusciel))
- [#151](https://github.com/Sylius/SyliusResourceBundle/issues/151) [Maintenance] Bump ApiTestCase to v5.0 ([@lchrusciel](https://github.com/lchrusciel))
- [#159](https://github.com/Sylius/SyliusResourceBundle/issues/159) Remove duplicated docblocks ([@GSadee](https://github.com/GSadee))
- [#160](https://github.com/Sylius/SyliusResourceBundle/issues/160) ResourceBundle documentation extracted to its repository ()
- [#161](https://github.com/Sylius/SyliusResourceBundle/issues/161) [HOTFIX] Conflict with amphp/amp 2.4.3 ([@lchrusciel](https://github.com/lchrusciel))
- [#162](https://github.com/Sylius/SyliusResourceBundle/issues/162) [Documentation] Fix index menu ([@SirDomin](https://github.com/SirDomin))
- [#163](https://github.com/Sylius/SyliusResourceBundle/issues/163) [HOTFIX] Conflict with the newest amphp/amp ([@lchrusciel](https://github.com/lchrusciel))
- [#165](https://github.com/Sylius/SyliusResourceBundle/issues/165) Fix the build ([@pamil](https://github.com/pamil))
- [#167](https://github.com/Sylius/SyliusResourceBundle/issues/167) Upgrade rest bundle ([@loic425](https://github.com/loic425))
- [#168](https://github.com/Sylius/SyliusResourceBundle/issues/168) [Docs] Serialization groups of the elements in a paginated collection ([@vvasiloi](https://github.com/vvasiloi))
- [#172](https://github.com/Sylius/SyliusResourceBundle/issues/172) Check if form is submitted on resource creation/edition ([@loic425](https://github.com/loic425))
- [#175](https://github.com/Sylius/SyliusResourceBundle/issues/175) Upgrade PagerfantaBundle to new version with B/C layer ([@mbabker](https://github.com/mbabker))
- [#181](https://github.com/Sylius/SyliusResourceBundle/issues/181) Fix build ([@loic425](https://github.com/loic425))
- [#182](https://github.com/Sylius/SyliusResourceBundle/issues/182) Testing several state machine versions ([@loic425](https://github.com/loic425))
- [#187](https://github.com/Sylius/SyliusResourceBundle/issues/187) Symfony 5 support ([@loic425](https://github.com/loic425))
- [#189](https://github.com/Sylius/SyliusResourceBundle/issues/189) Require webmozart/assert as it's used by the bundle code ([@pamil](https://github.com/pamil))
- [#190](https://github.com/Sylius/SyliusResourceBundle/issues/190) Fix errors reported by static analysis tools ([@pamil](https://github.com/pamil))
- [#191](https://github.com/Sylius/SyliusResourceBundle/issues/191) Normalise composer.json (with ergebnis/composer-normalize) ([@pamil](https://github.com/pamil))
- [#193](https://github.com/Sylius/SyliusResourceBundle/issues/193) Bump up requirements to PHP ^7.3 ([@pamil](https://github.com/pamil))
- [#194](https://github.com/Sylius/SyliusResourceBundle/issues/194) Fix deprecations and errors while running PHPUnit ([@pamil](https://github.com/pamil))

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

## CHANGELOG FOR `1.5.x`

### v1.5.2 (2020-08-18)

Security release:

- [CVE-2020-15143: Remote Code Execution in ParametersParser while using request parameters inside expression language](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-p4pj-9g59-4ppv)
- [CVE-2020-15146: Remote Code Execution in OptionsParser while using request parameters inside expression language](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-h6m7-j4h3-9rf5)

### v1.5.1 (2020-01-27)

Security release:

- [CVE-2020-5220: Ability to define unintended serialisation groups via HTTP header which might lead to data exposure](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-8vp7-j5cj-vvm2)

### v1.5.0 (2019-05-07)

#### TL;DR

Released ResourceBundle as a standalone package, containing a subtree split of Resource component.

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

## CHANGELOG FOR `1.3.x`

### v1.3.14 (2020-08-18)

Security release:

- [CVE-2020-15143: Remote Code Execution in ParametersParser while using request parameters inside expression language](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-p4pj-9g59-4ppv)
- [CVE-2020-15146: Remote Code Execution in OptionsParser while using request parameters inside expression language](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-h6m7-j4h3-9rf5)

### v1.3.13 (2020-01-27)

Security release:

- [CVE-2020-5220: Ability to define unintended serialisation groups via HTTP header which might lead to data exposure](https://github.com/Sylius/SyliusResourceBundle/security/advisories/GHSA-8vp7-j5cj-vvm2)

### v1.3.12 (2019-05-07)

#### TL;DR

Released ResourceBundle as a standalone package, containing a subtree split of Resource component.

## CHANGELOG FOR `1.2.x`

### v1.2.17 (2019-05-07)

#### TL;DR

Released ResourceBundle as a standalone package, containing a subtree split of Resource component.

## CHANGELOG FOR `1.1.x`

### v1.1.18 (2019-05-07)

#### TL;DR

Released ResourceBundle as a standalone package, containing a subtree split of Resource component.
