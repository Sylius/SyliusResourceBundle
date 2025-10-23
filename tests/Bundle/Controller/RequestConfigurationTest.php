<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\Parameters;
use Sylius\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class RequestConfigurationTest extends TestCase
{
    /**
     * @var MetadataInterface|MockObject
     */
    private MockObject $metadataMock;
    /**
     * @var Request|MockObject
     */
    private MockObject $requestMock;
    /**
     * @var Parameters|MockObject
     */
    private MockObject $parametersMock;
    private RequestConfiguration $requestConfiguration;
    protected function setUp(): void
    {
        $this->metadataMock = $this->createMock(MetadataInterface::class);
        $this->requestMock = $this->createMock(Request::class);
        $this->parametersMock = $this->createMock(Parameters::class);
        $this->requestConfiguration = new RequestConfiguration($this->metadataMock, $this->requestMock, $this->parametersMock);
    }

    function testHasRequest(): void
    {
        $this->assertSame($this->requestMock, $this->requestConfiguration->getRequest());
    }

    function testHasMetadata(): void
    {
        $this->assertSame($this->metadataMock, $this->requestConfiguration->getMetadata());
    }

    function testHasParameters(): void
    {
        $this->assertSame($this->parametersMock, $this->requestConfiguration->getParameters());
    }

    function testChecksIfItsAHtmlRequest(): void
    {
        $this->requestMock->expects($this->once())->method('getRequestFormat')->willReturn('html');
        $this->assertTrue($this->requestConfiguration->isHtmlRequest());
    }

    function testChecksIfItsNotAHtmlRequest(): void
    {
        $this->requestMock->expects($this->once())->method('getRequestFormat')->willReturn('json');
        $this->assertFalse($this->requestConfiguration->isHtmlRequest());
    }

    function testReturnsDefaultTemplateNames(): void
    {
        $this->metadataMock->expects($this->exactly(5))->method('getTemplatesNamespace')->willReturn('@SyliusAdmin/Product');
        $this->assertSame('@SyliusAdmin/Product/index.html.twig', $this->requestConfiguration->getDefaultTemplate('index.html'));
        $this->assertSame('@SyliusAdmin/Product/show.html.twig', $this->requestConfiguration->getDefaultTemplate('show.html'));
        $this->assertSame('@SyliusAdmin/Product/create.html.twig', $this->requestConfiguration->getDefaultTemplate('create.html'));
        $this->assertSame('@SyliusAdmin/Product/update.html.twig', $this->requestConfiguration->getDefaultTemplate('update.html'));
        $this->assertSame('@SyliusAdmin/Product/custom.html.twig', $this->requestConfiguration->getDefaultTemplate('custom.html'));
    }

    function testReturnsDefaultTemplateNamesForADirectoryBasedTemplates(): void
    {
        $this->metadataMock->expects($this->exactly(5))->method('getTemplatesNamespace')->willReturn('book/Backend');
        $this->assertSame('book/Backend/index.html.twig', $this->requestConfiguration->getDefaultTemplate('index.html'));
        $this->assertSame('book/Backend/show.html.twig', $this->requestConfiguration->getDefaultTemplate('show.html'));
        $this->assertSame('book/Backend/create.html.twig', $this->requestConfiguration->getDefaultTemplate('create.html'));
        $this->assertSame('book/Backend/update.html.twig', $this->requestConfiguration->getDefaultTemplate('update.html'));
        $this->assertSame('book/Backend/custom.html.twig', $this->requestConfiguration->getDefaultTemplate('custom.html'));
    }

    function testTakesTheCustomTemplateIfSpecified(): void
    {
        $this->metadataMock->expects($this->once())->method('getTemplatesNamespace')->willReturn('@SyliusAdmin/Product');
        $this->parametersMock->expects($this->once())->method('get')->with('template', '@SyliusAdmin/Product/foo.html.twig')->willReturn('Product/show.html.twig');
        $this->assertSame('Product/show.html.twig', $this->requestConfiguration->getTemplate('foo.html'));
    }

    function testFormTypeAndOptionsArrayWithTypeOnly(): void
    {
        $this->parametersMock->method('get')->willReturn(['type' => 'sylius_custom_resource']);

        $this->assertSame('sylius_custom_resource', $this->requestConfiguration->getFormType());
        $this->assertSame([], $this->requestConfiguration->getFormOptions());
    }

    function testFormTypeAndOptionsString(): void
    {
        $this->parametersMock->method('get')->willReturn('sylius_custom_resource');

        $this->assertSame('sylius_custom_resource', $this->requestConfiguration->getFormType());
        $this->assertSame([], $this->requestConfiguration->getFormOptions());
    }

    function testFormTypeAndOptionsArrayWithTypeAndOptions(): void
    {
        $this->parametersMock->method('get')->willReturn([
            'type' => 'sylius_custom_resource',
            'options' => ['key' => 'value'],
        ]);

        $this->assertSame('sylius_custom_resource', $this->requestConfiguration->getFormType());
        $this->assertSame(['key' => 'value'], $this->requestConfiguration->getFormOptions());
    }

    function testFormTypeAndOptionsFallbackToMetadataWhenEmpty(): void
    {
        $this->parametersMock->method('get')->willReturn([]);
        $this->metadataMock->method('getClass')->with('form')->willReturn('\Fully\Qualified\ClassName');

        $this->assertSame('\Fully\Qualified\ClassName', $this->requestConfiguration->getFormType());
        $this->assertSame([], $this->requestConfiguration->getFormOptions());
    }

    function testFormTypeAndOptionsFallbackToMetadataWithOptionsOnly(): void
    {
        $this->parametersMock->method('get')->willReturn(['options' => ['key' => 'value']]);
        $this->metadataMock->method('getClass')->with('form')->willReturn('\Fully\Qualified\ClassName');

        $this->assertSame('\Fully\Qualified\ClassName', $this->requestConfiguration->getFormType());
        $this->assertSame(['key' => 'value'], $this->requestConfiguration->getFormOptions());
    }

    function testGeneratesFormTypeWithArrayConfiguration(): void
    {
        $this->parametersMock->expects($this->exactly(2))->method('get')->with('form')->willReturn(['type' => 'sylius_product', 'options' => ['validation_groups' => ['sylius']]]);
        $this->assertSame('sylius_product', $this->requestConfiguration->getFormType());
        $this->assertSame(['validation_groups' => ['sylius']], $this->requestConfiguration->getFormOptions());
    }

    function testGeneratesRouteNamesWithoutSection(): void
    {
        $this->metadataMock->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->method('getName')->willReturn('product');
        $this->parametersMock->method('get')->with('section')->willReturn(null);
        $this->assertSame('sylius_product_index', $this->requestConfiguration->getRouteName('index'));
        $this->assertSame('sylius_product_show', $this->requestConfiguration->getRouteName('show'));
        $this->assertSame('sylius_product_custom', $this->requestConfiguration->getRouteName('custom'));
    }

    function testGeneratesRouteNamesWithSection(): void
    {
        $this->metadataMock->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->method('getName')->willReturn('product');
        $this->parametersMock->method('get')->with('section')->willReturn('admin');
        $this->assertSame('sylius_admin_product_index', $this->requestConfiguration->getRouteName('index'));
        $this->assertSame('sylius_admin_product_show', $this->requestConfiguration->getRouteName('show'));
        $this->assertSame('sylius_admin_product_custom', $this->requestConfiguration->getRouteName('custom'));
    }

    function testGeneratesRedirectReferer(): void
    {
        /** @var HeaderBag|MockObject $bagMock */
        $bagMock = $this->createMock(HeaderBag::class);
        $this->requestMock->headers = $bagMock;
        $bagMock->expects($this->once())->method('get')->with('referer')->willReturn('http://myurl.com');
        $this->parametersMock->expects($this->once())->method('get')->with('redirect')->willReturn(['referer' => 'http://myurl.com']);
        $this->assertSame('http://myurl.com', $this->requestConfiguration->getRedirectReferer());
    }

    function testGeneratesRedirectRouteWithoutRedirect(): void
    {
        $this->metadataMock->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->method('getName')->willReturn('product');
        $this->parametersMock
            ->method('get')
            ->willReturnMap([
                ['section', null, null],
                ['redirect', null, null],
            ]);
        $this->assertSame('sylius_product_index', $this->requestConfiguration->getRedirectRoute('index'));
    }

    function testGeneratesRedirectRouteWithRedirectAsArray(): void
    {
        $this->metadataMock->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->method('getName')->willReturn('product');
        $this->parametersMock
            ->method('get')
            ->willReturnMap([
                ['section', null, null],
                ['redirect', null, ['route' => 'myRoute']],
            ]);
        $this->assertSame('myRoute', $this->requestConfiguration->getRedirectRoute('show'));
    }

    function testGeneratesRedirectRouteWithRedirectAsString(): void
    {
        $this->metadataMock->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->method('getName')->willReturn('product');
        $this->parametersMock
            ->method('get')
            ->willReturnMap([
                ['section', null, null],
                ['redirect', null, 'myRoute'],
            ]);
        $this->assertSame('myRoute', $this->requestConfiguration->getRedirectRoute('custom'));
    }

    function testRedirectRouteUsesSection(): void
    {
        $this->metadataMock->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->method('getName')->willReturn('product');
        $this->parametersMock->method('get')->willReturnMap([
            ['section', null, 'admin'],
            ['redirect', null, null],
        ]);

        $this->assertSame('sylius_admin_product_index', $this->requestConfiguration->getRedirectRoute('index'));
    }

    function testRedirectRouteOverriddenWithArray(): void
    {
        $this->metadataMock->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->method('getName')->willReturn('product');
        $this->parametersMock->method('get')->with('redirect')->willReturn(['route' => 'myRoute']);
        $this->assertSame('myRoute', $this->requestConfiguration->getRedirectRoute('show'));
    }

    function testRedirectRouteOverriddenWithString(): void
    {
        $this->metadataMock->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->method('getName')->willReturn('product');
        $this->parametersMock->method('get')->with('redirect')->willReturn('myRoute');
        $this->assertSame('myRoute', $this->requestConfiguration->getRedirectRoute('custom'));
    }

    public function testReturnsEmptyRedirectParametersWhenRedirectIsNull(): void
    {
        $this->parametersMock->method('get')->willReturnMap([
            ['vars', [], []],
            ['redirect', null, null],
        ]);

        $this->assertSame([], $this->requestConfiguration->getVars());
        $this->assertSame([], $this->requestConfiguration->getRedirectParameters());
    }

    public function testReturnsEmptyRedirectParametersWhenRedirectIsString(): void
    {
        $this->parametersMock->method('get')->willReturn(['redirect' => 'string']);

        $this->assertSame([], $this->requestConfiguration->getRedirectParameters());
    }

    public function testReturnsEmptyRedirectParametersWhenRedirectHasEmptyArray(): void
    {
        $this->parametersMock->method('get')->willReturn(['redirect' => ['parameters' => []]]);

        $this->assertSame([], $this->requestConfiguration->getRedirectParameters());
    }

    public function testReturnsRedirectParametersWhenPresent(): void
    {
        $this->parametersMock->method('get')->willReturn(['redirect' => ['parameters' => ['myParameter']]]);

        $this->assertSame(['myParameter'], $this->requestConfiguration->getRedirectParameters());
    }

    public function testMergesExtraVarsWithRedirectParameters(): void
    {
        $extraVars = ['redirect' => ['parameters' => ['myExtraParameter']]];
        $this->parametersMock->method('get')->willReturnMap([
            ['vars', [], $extraVars],
            ['redirect', null, ['parameters' => ['myParameter']]],
        ]);

        $this->assertSame($extraVars, $this->requestConfiguration->getVars());
        $this->assertSame(['myParameter', 'myExtraParameter'], $this->requestConfiguration->getRedirectParameters('resource'));
    }

    function testIsLimitedReturnsTrueWhenLimitIsSet(): void
    {
        $this->parametersMock->method('get')->willReturn(10);

        $this->assertTrue($this->requestConfiguration->isLimited());
    }

    function testIsLimitedReturnsFalseWhenLimitIsNotSet(): void
    {
        $this->parametersMock->method('get')->willReturn(null);

        $this->assertFalse($this->requestConfiguration->isLimited());
    }

    function testGetLimitReturnsValue(): void
    {
        $this->parametersMock->method('get')->willReturnMap([
            ['limit', false, true],
            ['limit', 10, 10],
        ]);

        $this->assertSame(10, $this->requestConfiguration->getLimit());
    }

    function testGetLimitReturnsNullWhenNotSet(): void
    {
        $this->parametersMock->method('get')->willReturnMap([
            ['limit', false, false],
            ['limit', 10, null],
        ]);

        $this->assertNull($this->requestConfiguration->getLimit());
    }

    function testIsPaginatedReturnsTrueWhenLimitIsPositive(): void
    {
        $this->parametersMock->method('get')->willReturn(10);

        $this->assertTrue($this->requestConfiguration->isPaginated());
    }

    function testIsPaginatedReturnsTrueWhenLimitIsZero(): void
    {
        $this->parametersMock->method('get')->willReturn(0);

        $this->assertTrue($this->requestConfiguration->isPaginated());
    }

    function testIsPaginatedReturnsFalseWhenLimitIsNull(): void
    {
        $this->parametersMock->method('get')->willReturn(null);

        $this->assertFalse($this->requestConfiguration->isPaginated());
    }

    function testIsPaginatedReturnsFalseWhenLimitIsFalse(): void
    {
        $this->parametersMock->method('get')->willReturn(false);

        $this->assertFalse($this->requestConfiguration->isPaginated());
    }

    function testGetPaginationMaxPerPageReturnsCustomValue(): void
    {
        $this->parametersMock->method('get')->with('paginate', 10)->willReturn(20);

        $this->assertSame(20, $this->requestConfiguration->getPaginationMaxPerPage());
    }

    function testGetPaginationMaxPerPageReturnsDefaultValue(): void
    {
        $this->parametersMock->method('get')->with('paginate', 10)->willReturn(10);

        $this->assertSame(10, $this->requestConfiguration->getPaginationMaxPerPage());
    }

    function testIsFilterableReturnsTrue(): void
    {
        $this->parametersMock->method('get')->willReturn(true);

        $this->assertTrue($this->requestConfiguration->isFilterable());
    }

    function testIsFilterableReturnsFalse(): void
    {
        $this->parametersMock->method('get')->willReturn(null);

        $this->assertFalse($this->requestConfiguration->isFilterable());
    }

    function testHasNoFilterableParameter(): void
    {
        $defaultCriteria = ['property' => 'myValue'];
        $this->parametersMock
            ->method('get')
            ->willReturnMap([
                ['criteria', [], []],
                ['filterable', false, false],
            ]);
        $this->assertIsIterable($this->requestConfiguration->getCriteria($defaultCriteria));
        $this->assertCount(1, $this->requestConfiguration->getCriteria($defaultCriteria));
    }

    function testHasCriteriaParameter(): void
    {
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $queryBag = new InputBag();
        $requestBag = new InputBag();
        $criteria = ['property' => 'myNewValue'];
        $this->requestMock->attributes = $attributesBagMock;
        $this->requestMock->query = $queryBag;
        $this->requestMock->request = $requestBag;

        $this->parametersMock
            ->method('get')
            ->willReturnMap([
                ['criteria', [], []],
                ['filterable', false, true],
            ]);

        $attributesBagMock->expects($this->once())->method('get')->with('criteria', $this->requestMock)->willReturn($this->requestMock);
        $queryBag->set('criteria', $criteria);
        $requestBag->set('criteria', []);
        $this->assertSame($criteria, $this->requestConfiguration->getCriteria());
    }

    function testHasCriteriaParameterInRequest(): void
    {
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $queryBag = new InputBag();
        $requestBag = new InputBag();
        $criteria = ['property' => 'myNewValue'];
        $this->requestMock->attributes = $attributesBagMock;
        $this->requestMock->query = $queryBag;
        $this->requestMock->request = $requestBag;

        $this->parametersMock
            ->method('get')
            ->willReturnMap([
                ['criteria', [], []],
                ['filterable', false, true],
            ]);

        $attributesBagMock->expects($this->once())->method('get')->with('criteria', $this->requestMock)->willReturn($this->requestMock);
        $requestBag->set('criteria', $criteria);
        $this->assertSame($criteria, $this->requestConfiguration->getCriteria());
    }

    function testCriteriaIsOverriddenByQueryParameters(): void
    {
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $queryBag = new InputBag();
        $requestBag = new InputBag();

        $criteria = ['property' => 'myValue'];
        $overriddenCriteria = ['other_property' => 'myNewValue'];
        $expected = ['property' => 'myValue', 'other_property' => 'myNewValue'];

        $this->requestMock->attributes = $attributesBagMock;
        $this->requestMock->query = $queryBag;
        $this->requestMock->request = $requestBag;

        $this->parametersMock->method('get')->willReturnMap([
            ['filterable', false, true],
            ['criteria', [], $criteria],
        ]);
        $attributesBagMock->method('get')->with('criteria', $this->requestMock)->willReturn($this->requestMock);

        $queryBag->set('criteria', $overriddenCriteria);
        $requestBag->set('criteria', []);

        $this->assertSame($expected, $this->requestConfiguration->getCriteria());
    }

    function testCriteriaMergesWithDefaultCriteriaWhenOverridden(): void
    {
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $queryBag = new InputBag();
        $requestBag = new InputBag();

        $criteria = ['property' => 'myValue'];
        $overriddenCriteria = ['other_property' => 'myNewValue'];
        $defaultCriteria = ['slug' => 'foo'];
        $expected = ['property' => 'myValue', 'slug' => 'foo', 'other_property' => 'myNewValue'];

        $this->requestMock->attributes = $attributesBagMock;
        $this->requestMock->query = $queryBag;
        $this->requestMock->request = $requestBag;

        $this->parametersMock->method('get')->willReturnMap([
            ['filterable', false, true],
            ['criteria', [], $criteria],
        ]);
        $attributesBagMock->method('get')->with('criteria', $this->requestMock)->willReturn($this->requestMock);

        $queryBag->set('criteria', $overriddenCriteria);
        $requestBag->set('criteria', []);

        $this->assertSame($expected, $this->requestConfiguration->getCriteria($defaultCriteria));
    }

    function testQueryCriteriaTakesPrecedenceOverDefaultAndRouteCriteria(): void
    {
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $queryBag = new InputBag();
        $requestBag = new InputBag();

        $criteria = ['filter' => 'route'];
        $expected = ['filter' => 'request'];

        $this->requestMock->attributes = $attributesBagMock;
        $this->requestMock->query = $queryBag;
        $this->requestMock->request = $requestBag;

        $this->parametersMock->method('get')->willReturnMap([
            ['filterable', false, true],
            ['criteria', [], $criteria],
        ]);
        $attributesBagMock->method('get')->with('criteria', $this->requestMock)->willReturn($this->requestMock);

        $queryBag->set('criteria', ['filter' => 'request']);
        $requestBag->set('criteria', []);

        $this->assertSame($expected, $this->requestConfiguration->getCriteria(['filter' => 'default']));
    }

    function testResourceIsSortableWhenParameterIsTrue(): void
    {
        $this->parametersMock
            ->method('get')
            ->with('sortable', false)
            ->willReturn(true);

        $this->assertTrue($this->requestConfiguration->isSortable());
    }

    function testResourceIsNotSortableWhenParameterIsNull(): void
    {
        $this->parametersMock
            ->method('get')
            ->with('sortable', false)
            ->willReturn(null);

        $this->assertFalse($this->requestConfiguration->isSortable());
    }

    function testHasSortingParameter(): void
    {
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $queryBag = new InputBag();
        $requestBag = new InputBag();
        $sorting = ['property' => 'asc'];
        $this->requestMock->attributes = $attributesBagMock;
        $this->requestMock->query = $queryBag;
        $this->requestMock->request = $requestBag;

        $this->parametersMock->method('get')->willReturnMap([
            ['sortable', false, true],
            ['sorting', [], $sorting],
        ]);

        $attributesBagMock->expects($this->once())->method('get')->with('sorting', $this->requestMock)->willReturn($this->requestMock);
        $queryBag->set('sorting', $sorting);
        $requestBag->set('sorting', []);
        $this->assertSame($sorting, $this->requestConfiguration->getSorting());
    }

    function testHasNoSortableParameter(): void
    {
        $defaultSorting = ['property' => 'desc'];

        $this->parametersMock->method('get')->willReturnMap([
            ['sortable', false, false],
            ['sorting', [], []],
        ]);

        $this->assertIsIterable($this->requestConfiguration->getSorting($defaultSorting));
        $this->assertCount(1, $this->requestConfiguration->getSorting($defaultSorting));
    }

    function testSortingIsOverriddenByQueryParameters(): void
    {
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $queryBag = new InputBag();
        $requestBag = new InputBag();

        $sorting = ['property' => 'desc'];
        $overriddenSorting = ['other_property' => 'asc'];
        $expected = ['other_property' => 'asc', 'property' => 'desc'];

        $this->requestMock->attributes = $attributesBagMock;
        $this->requestMock->query = $queryBag;
        $this->requestMock->request = $requestBag;

        $this->parametersMock->method('get')->willReturnMap([
            ['sortable', false, true],
            ['sorting', [], $sorting],
        ]);
        $attributesBagMock->method('get')->with('sorting', $this->requestMock)->willReturn($this->requestMock);

        $queryBag->set('sorting', $overriddenSorting);
        $requestBag->set('sorting', []);

        $this->assertSame($expected, $this->requestConfiguration->getSorting());
    }

    function testSortingMergesWithDefaultSortingWhenOverridden(): void
    {
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $queryBag = new InputBag();
        $requestBag = new InputBag();

        $sorting = ['property' => 'desc'];
        $overriddenSorting = ['other_property' => 'asc'];
        $defaultSorting = ['slug' => 'foo'];
        $expected = ['other_property' => 'asc', 'property' => 'desc', 'slug' => 'foo'];

        $this->requestMock->attributes = $attributesBagMock;
        $this->requestMock->query = $queryBag;
        $this->requestMock->request = $requestBag;

        $this->parametersMock->method('get')->willReturnMap([
            ['sortable', false, true],
            ['sorting', [], $sorting],
        ]);
        $attributesBagMock->method('get')->with('sorting', $this->requestMock)->willReturn($this->requestMock);

        $queryBag->set('sorting', $overriddenSorting);
        $requestBag->set('sorting', []);

        $this->assertSame($expected, $this->requestConfiguration->getSorting($defaultSorting));
    }

    function testQuerySortingTakesPrecedenceOverRouteAndDefaultSorting(): void
    {
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $queryBag = new InputBag();
        $requestBag = new InputBag();

        $this->requestMock->attributes = $attributesBagMock;
        $this->requestMock->query = $queryBag;
        $this->requestMock->request = $requestBag;

        $this->parametersMock->method('get')->willReturnMap([
            ['sortable', false, true],
            ['sorting', [], ['sort' => 'route']],
        ]);
        $attributesBagMock->method('get')->with('sorting', $this->requestMock)->willReturn($this->requestMock);

        $queryBag->set('sorting', ['sort' => 'request']);
        $requestBag->set('sorting', []);

        $this->assertSame(['sort' => 'request'], $this->requestConfiguration->getSorting(['sort' => 'default']));
    }

    function testGetRepositoryMethodReturnsNullIfRepositoryParamDoesNotExist(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('repository')
            ->willReturn(false);

        $this->assertNull($this->requestConfiguration->getRepositoryMethod());
    }

    function testGetRepositoryMethodReturnsConfiguredMethod(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('repository')
            ->willReturn(true);

        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('repository')
            ->willReturn(['method' => 'findAllEnabled']);

        $this->assertSame('findAllEnabled', $this->requestConfiguration->getRepositoryMethod());
    }

    function testReturnsEmptyArrayWhenRepositoryConfigurationIsMissing(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('repository')
            ->willReturn(false);

        $this->assertSame([], $this->requestConfiguration->getRepositoryArguments());
    }

    function testReturnsRepositoryArgumentsWhenScalarValueProvided(): void
    {
        $repositoryConfiguration = ['arguments' => 'value'];

        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('repository')
            ->willReturn(true);

        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('repository')
            ->willReturn($repositoryConfiguration);

        $this->assertSame(['value'], $this->requestConfiguration->getRepositoryArguments());
    }

    function testReturnsRepositoryArgumentsWhenArrayProvided(): void
    {
        $repositoryConfiguration = ['arguments' => ['foo, bar']];

        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('repository')
            ->willReturn(true);

        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('repository')
            ->willReturn($repositoryConfiguration);

        $this->assertSame(['foo, bar'], $this->requestConfiguration->getRepositoryArguments());
    }

    function testReturnsNullWhenFactoryConfigurationIsMissing(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('factory')
            ->willReturn(false);

        $this->assertNull($this->requestConfiguration->getFactoryMethod());
    }

    function testReturnsFactoryMethodWhenConfigured(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('factory')
            ->willReturn(true);

        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('factory')
            ->willReturn(['method' => 'createForPromotion']);

        $this->assertSame('createForPromotion', $this->requestConfiguration->getFactoryMethod());
    }

    function testReturnsEmptyArrayWhenFactoryConfigurationIsMissing(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('factory')
            ->willReturn(false);

        $this->assertSame([], $this->requestConfiguration->getFactoryArguments());
    }

    function testReturnsFactoryArgumentsAsArrayWhenConfiguredWithScalar(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('factory')
            ->willReturn(true);

        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('factory')
            ->willReturn(['arguments' => 'value']);

        $this->assertSame(['value'], $this->requestConfiguration->getFactoryArguments());
    }

    function testReturnsFactoryArgumentsAsArrayWhenConfiguredWithArray(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('factory')
            ->willReturn(true);

        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('factory')
            ->willReturn(['arguments' => ['foo, bar']]);

        $this->assertSame(['foo, bar'], $this->requestConfiguration->getFactoryArguments());
    }

    function testReturnsDefaultFlashMessageForMessageAction(): void
    {
        $this->metadataMock->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->method('getName')->willReturn('product');

        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('flash', 'sylius.product.message')
            ->willReturn('sylius.product.message');

        $this->assertSame('sylius.product.message', $this->requestConfiguration->getFlashMessage('message'));
    }

    function testReturnsCustomFlashMessageForFlashAction(): void
    {
        $this->metadataMock->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->method('getName')->willReturn('product');

        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('flash', 'sylius.product.flash')
            ->willReturn('sylius.product.myMessage');

        $this->assertSame('sylius.product.myMessage', $this->requestConfiguration->getFlashMessage('flash'));
    }

    function testReturnsDefaultSortablePosition(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('sortable_position', 'position')
            ->willReturn('position');

        $this->assertSame('position', $this->requestConfiguration->getSortablePosition());
    }

    function testReturnsCustomSortablePosition(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('sortable_position', 'position')
            ->willReturn('myPosition');

        $this->assertSame('myPosition', $this->requestConfiguration->getSortablePosition());
    }

    function testShouldNotHavePermissionWhenPermissionIsFalse(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('permission', false)
            ->willReturn(false);

        $this->assertFalse($this->requestConfiguration->hasPermission());
    }

    function testShouldHavePermissionWhenPermissionIsCustomString(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('permission', false)
            ->willReturn('custom_permission');

        $this->assertTrue($this->requestConfiguration->hasPermission());
    }

    function testGeneratesPermissionName(): void
    {
        $this->metadataMock->expects($this->once())->method('getApplicationName')->willReturn('sylius');
        $this->metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->parametersMock->expects($this->once())->method('get')->with('permission')->willReturn(true);
        $this->assertSame('sylius.product.index', $this->requestConfiguration->getPermission('index'));
    }

    function testTakesPermissionNameFromParametersIfProvided(): void
    {
        $this->parametersMock->expects($this->once())->method('get')->with('permission')->willReturn('app.sales_order.view_pricing');
        $this->assertSame('app.sales_order.view_pricing', $this->requestConfiguration->getPermission('index'));
    }

    function testThrowsAnExceptionWhenPermissionIsSetAsFalseInParametersButStillTryingToGetIt(): void
    {
        $this->parametersMock->expects($this->once())->method('get')->with('permission')->willReturn(null);
        $this->expectException(\LogicException::class);
        $this->requestConfiguration->getPermission('index');
    }

    function testHasEventName(): void
    {
        $this->parametersMock->expects($this->once())->method('get')->with('event')->willReturn('foo');
        $this->assertSame('foo', $this->requestConfiguration->getEvent());
    }

    function testReturnsNullWhenSectionIsNotSet(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('section')
            ->willReturn(null);

        $this->assertNull($this->requestConfiguration->getSection());
    }

    function testReturnsSectionWhenSet(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('section')
            ->willReturn('admin');

        $this->assertSame('admin', $this->requestConfiguration->getSection());
    }

    function testHasVars(): void
    {
        $this->parametersMock->expects($this->once())->method('get')->with('vars', [])->willReturn(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $this->requestConfiguration->getVars());
    }

    function testShouldNotHaveGridWhenGridNotDefined(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('grid')
            ->willReturn(false);

        $this->assertFalse($this->requestConfiguration->hasGrid());
    }

    function testShouldHaveGridWhenGridDefined(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('grid')
            ->willReturn(true);

        $this->assertTrue($this->requestConfiguration->hasGrid());
    }

    function testGetGridReturnsConfiguredValue(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('grid')
            ->willReturn(true);

        $this->parametersMock
            ->expects($this->once())
            ->method('get')
            ->with('grid')
            ->willReturn('sylius_admin_tax_category');

        $this->assertSame('sylius_admin_tax_category', $this->requestConfiguration->getGrid());
    }

    function testThrowsAnExceptionWhenTryingToRetrieveUndefinedGrid(): void
    {
        $this->parametersMock->expects($this->once())->method('has')->with('grid')->willReturn(false);
        $this->expectException(\LogicException::class);
        $this->requestConfiguration->getGrid();
    }

    function testReturnsFalseWhenNoStateMachineConfigured(): void
    {
        $this->parametersMock
            ->expects($this->once())
            ->method('has')
            ->with('state_machine')
            ->willReturn(false);

        $this->assertFalse($this->requestConfiguration->hasStateMachine());
    }

    function testReturnsStateMachineDetailsWhenConfigured(): void
    {
        $stateMachineConfig = [
            'graph' => 'sylius_product_review_state',
            'transition' => 'approve',
        ];

        $this->parametersMock
            ->method('has')
            ->with('state_machine')
            ->willReturn(true);

        $this->parametersMock
            ->method('get')
            ->with('state_machine')
            ->willReturn($stateMachineConfig);

        $this->assertTrue($this->requestConfiguration->hasStateMachine());
        $this->assertSame('sylius_product_review_state', $this->requestConfiguration->getStateMachineGraph());
        $this->assertSame('approve', $this->requestConfiguration->getStateMachineTransition());
    }
}
