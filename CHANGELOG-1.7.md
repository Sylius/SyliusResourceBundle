## CHANGELOG FOR `1.7.x`

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
