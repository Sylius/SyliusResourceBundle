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

use Doctrine\Persistence\ObjectManager;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\View\View;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Exception\DeleteHandlingException;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\ResourceActions;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;

final class ResourceControllerTest extends TestCase
{
    /** @var MetadataInterface|MockObject */
    private MockObject $metadataMock;

    /** @var RequestConfigurationFactoryInterface|MockObject */
    private MockObject $requestConfigurationFactoryMock;

    /** @var ViewHandlerInterface|MockObject */
    private MockObject $viewHandlerMock;

    /** @var RepositoryInterface|MockObject */
    private MockObject $repositoryMock;

    /** @var FactoryInterface|MockObject */
    private MockObject $factoryMock;

    /** @var NewResourceFactoryInterface|MockObject */
    private MockObject $newResourceFactoryMock;

    /** @var ObjectManager|MockObject */
    private MockObject $managerMock;

    /** @var SingleResourceProviderInterface|MockObject */
    private MockObject $singleResourceProviderMock;

    /** @var ResourcesCollectionProviderInterface|MockObject */
    private MockObject $resourcesCollectionProviderMock;

    /** @var ResourceFormFactoryInterface|MockObject */
    private MockObject $resourceFormFactoryMock;

    /** @var RedirectHandlerInterface|MockObject */
    private MockObject $redirectHandlerMock;

    /** @var FlashHelperInterface|MockObject */
    private MockObject $flashHelperMock;

    /** @var AuthorizationCheckerInterface|MockObject */
    private MockObject $authorizationCheckerMock;

    /** @var EventDispatcherInterface|MockObject */
    private MockObject $eventDispatcherMock;

    /** @var StateMachineInterface|MockObject */
    private MockObject $stateMachineMock;

    /** @var ResourceUpdateHandlerInterface|MockObject */
    private MockObject $resourceUpdateHandlerMock;

    /** @var ResourceDeleteHandlerInterface|MockObject */
    private MockObject $resourceDeleteHandlerMock;

    /** @var ContainerInterface|MockObject */
    private MockObject $containerMock;

    private ResourceController $resourceController;

    protected function setUp(): void
    {
        $this->metadataMock = $this->createMock(MetadataInterface::class);
        $this->requestConfigurationFactoryMock = $this->createMock(RequestConfigurationFactoryInterface::class);
        $this->viewHandlerMock = $this->createMock(ViewHandlerInterface::class);
        $this->repositoryMock = $this->createMock(RepositoryInterface::class);
        $this->factoryMock = $this->createMock(FactoryInterface::class);
        $this->newResourceFactoryMock = $this->createMock(NewResourceFactoryInterface::class);
        $this->managerMock = $this->createMock(ObjectManager::class);
        $this->singleResourceProviderMock = $this->createMock(SingleResourceProviderInterface::class);
        $this->resourcesCollectionProviderMock = $this->createMock(ResourcesCollectionProviderInterface::class);
        $this->resourceFormFactoryMock = $this->createMock(ResourceFormFactoryInterface::class);
        $this->redirectHandlerMock = $this->createMock(RedirectHandlerInterface::class);
        $this->flashHelperMock = $this->createMock(FlashHelperInterface::class);
        $this->authorizationCheckerMock = $this->createMock(AuthorizationCheckerInterface::class);
        $this->eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $this->stateMachineMock = $this->createMock(StateMachineInterface::class);
        $this->resourceUpdateHandlerMock = $this->createMock(ResourceUpdateHandlerInterface::class);
        $this->resourceDeleteHandlerMock = $this->createMock(ResourceDeleteHandlerInterface::class);
        $this->containerMock = $this->createMock(ContainerInterface::class);
        $this->resourceController = new ResourceController($this->metadataMock, $this->requestConfigurationFactoryMock, $this->viewHandlerMock, $this->repositoryMock, $this->factoryMock, $this->newResourceFactoryMock, $this->managerMock, $this->singleResourceProviderMock, $this->resourcesCollectionProviderMock, $this->resourceFormFactoryMock, $this->redirectHandlerMock, $this->flashHelperMock, $this->authorizationCheckerMock, $this->eventDispatcherMock, $this->stateMachineMock, $this->resourceUpdateHandlerMock, $this->resourceDeleteHandlerMock);
        $this->resourceController->setContainer($this->containerMock);
    }

    public function testThrowsA403ExceptionIfUserIsUnauthorizedToViewASingleResource(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);

        $request = new Request();
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::SHOW)->willReturn('sylius.product.show');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.show')->willReturn(false);

        $this->expectException(AccessDeniedException::class);

        $this->resourceController->showAction($request);
    }

    public function testThrowsA404ExceptionIfResourceIsNotFoundBasedOnConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        $request = new Request();

        $this->metadataMock->expects($this->once())->method('getHumanizedName')->willReturn('product');
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::SHOW)->willReturn('sylius.product.show');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.show')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('The "product" has not been found');

        $this->resourceController->showAction($request);
    }

    public function testReturnsAResponseForHtmlViewOfASingleResource(): void
    {
        $configurationMock = $this->createMock(RequestConfiguration::class);
        $resourceMock = $this->createMock(ResourceInterface::class);
        $twigMock = $this->createMock(Environment::class);
        $request = new Request();

        $this->metadataMock->expects($this->once())->method('getName')->willReturn('product');

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())
            ->method('getPermission')
            ->with(ResourceActions::SHOW)
            ->willReturn('sylius.product.show');

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.show')
            ->willReturn(true);

        $this->singleResourceProviderMock
            ->expects($this->once())
            ->method('get')
            ->with($configurationMock, $this->repositoryMock)
            ->willReturn($resourceMock);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())
            ->method('getTemplate')
            ->with(ResourceActions::SHOW . '.html')
            ->willReturn('@SyliusShop/Product/show.html.twig');

        $this->containerMock->method('has')
            ->willReturnMap([
                ['templating', false],
                ['twig', true],
            ]);
        $this->containerMock->method('get')->with('twig')->willReturn($twigMock);

        $expectedContext = [
            'configuration' => $configurationMock,
            'metadata' => $this->metadataMock,
            'resource' => $resourceMock,
            'product' => $resourceMock,
        ];

        $twigMock->expects($this->once())
            ->method('render')
            ->with('@SyliusShop/Product/show.html.twig', $expectedContext)
            ->willReturn('rendered');

        $response = $this->resourceController->showAction($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('rendered', $response->getContent());
    }

    public function testReturnsEventResponseIfExistsDuringShow(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::SHOW)->willReturn('sylius.product.show');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.show')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatch')->with(ResourceActions::SHOW, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->expects($this->once())->method('getResponse')->willReturn($responseMock);
        $configurationMock->expects($this->never())->method('isHtmlRequest');
        $this->viewHandlerMock->expects($this->never())->method('handle');

        $this->assertSame($responseMock, $this->resourceController->showAction($request));
    }

    public function testReturnsAResponseForNonHtmlViewOfSingleResource(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        $configurationMock = $this->createMock(RequestConfiguration::class);
        $resourceMock = $this->createMock(ResourceInterface::class);
        $responseMock = $this->createMock(Response::class);

        $request = new Request();

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::SHOW)->willReturn('sylius.product.show');
        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.show')->willReturn(true);

        $this->singleResourceProviderMock
            ->expects($this->once())
            ->method('get')
            ->with($configurationMock, $this->repositoryMock)
            ->willReturn($resourceMock);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);
        $this->eventDispatcherMock->expects($this->once())->method('dispatch')->with(ResourceActions::SHOW, $configurationMock, $resourceMock);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->showAction($request));
    }

    public function testThrowsA403ExceptionIfUserIsUnauthorizedToViewAnIndexOfResources(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);

        $request = new Request();
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::INDEX)->willReturn('sylius.product.index');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.index')->willReturn(false);

        $this->expectException(AccessDeniedException::class);

        $this->resourceController->indexAction($request);
    }

    public function testReturnsAResponseForHtmlViewOfPaginatedResources(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resource1Mock */
        $resource1Mock = $this->createMock(ResourceInterface::class);
        /** @var ResourceInterface|MockObject $resource2Mock */
        $resource2Mock = $this->createMock(ResourceInterface::class);
        /** @var Environment|MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);

        $this->metadataMock->expects($this->once())->method('getPluralName')->willReturn('products');

        $request = new Request();
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::INDEX)->willReturn('sylius.product.index');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('getTemplate')->with(ResourceActions::INDEX . '.html')->willReturn('@SyliusShop/Product/index.html.twig');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.index')->willReturn(true);
        $this->resourcesCollectionProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn([$resource1Mock, $resource2Mock]);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchMultiple')->with(ResourceActions::INDEX, $configurationMock, [$resource1Mock, $resource2Mock]);
        $this->containerMock->method('has')
            ->willReturnMap([
                ['templating', false],
                ['twig', true],
            ]);
        $this->containerMock->expects($this->once())->method('get')->with('twig')->willReturn($twigMock);

        $expectedContext = [
            'configuration' => $configurationMock,
            'metadata' => $this->metadataMock,
            'resources' => [$resource1Mock, $resource2Mock],
            'products' => [$resource1Mock, $resource2Mock],
        ];

        $twigMock->expects($this->once())->method('render')->willReturnMap([['@SyliusShop/Product/index.html.twig', $expectedContext, 'view'], ['@SyliusShop/Product/index.html.twig', $expectedContext]]);

        $this->resourceController->indexAction($request);
    }

    public function testReturnsEventResponseIfExistsDuringIndex(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceInterface|MockObject $resource1Mock */
        $resource1Mock = $this->createMock(ResourceInterface::class);
        /** @var ResourceInterface|MockObject $resource2Mock */
        $resource2Mock = $this->createMock(ResourceInterface::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::INDEX)->willReturn('sylius.product.index');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.index')->willReturn(true);
        $this->resourcesCollectionProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn([$resource1Mock, $resource2Mock]);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchMultiple')->with(ResourceActions::INDEX, $configurationMock, [$resource1Mock, $resource2Mock])->willReturn($eventMock);

        $eventMock->expects($this->once())->method('getResponse')->willReturn($responseMock);
        $configurationMock->expects($this->never())->method('isHtmlRequest');

        $this->viewHandlerMock->expects($this->never())->method('handle');

        $this->assertSame($responseMock, $this->resourceController->indexAction($request));
    }

    public function testThrowsA403ExceptionIfUserIsUnauthorizedToCreateANewResource(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);

        $request = new Request();
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.create')->willReturn(false);

        $this->expectException(AccessDeniedException::class);

        $this->resourceController->createAction($request);
    }

    public function testReturnsAHtmlResponseForCreatingNewResourceForm(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var FormView|MockObject $formViewMock */
        $formViewMock = $this->createMock(FormView::class);
        /** @var Environment|MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);

        $request = new Request();
        $request->setMethod('GET');
        $this->metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('getTemplate')->with(ResourceActions::CREATE . '.html')->willReturn('@SyliusShop/Product/create.html.twig');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.create')->willReturn(true);
        $this->newResourceFactoryMock->expects($this->once())->method('create')->with($configurationMock, $this->factoryMock)->willReturn($newResourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $newResourceMock)->willReturn($formMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchInitializeEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($eventMock);

        $eventMock->expects($this->once())->method('getResponse')->willReturn(null);
        $formMock->expects($this->once())->method('createView')->willReturn($formViewMock);
        $formMock->method('handleRequest')->willReturnMap([[$request, $formMock], [$request]]);

        $this->containerMock->method('has')
            ->willReturnMap([
                ['templating', false],
                ['twig', true],
            ]);
        $this->containerMock->expects($this->once())->method('get')->with('twig')->willReturn($twigMock);

        $expectedContext = [
            'configuration' => $configurationMock,
            'metadata' => $this->metadataMock,
            'resource' => $newResourceMock,
            'product' => $newResourceMock,
            'form' => $formViewMock,
        ];

        $twigMock->expects($this->once())->method('render')->with('@SyliusShop/Product/create.html.twig', $expectedContext)->willReturn('view');
        $twigMock->method('render')->willReturnMap([['@SyliusShop/Product/create.html.twig', $expectedContext, 'view'], ['@SyliusShop/Product/create.html.twig', $expectedContext]]);

        $this->resourceController->createAction($request);
    }

    public function testReturnsAHtmlResponseForInvalidFormDuringResourceCreation(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var FormView|MockObject $formViewMock */
        $formViewMock = $this->createMock(FormView::class);
        /** @var Environment|MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);

        $request = new Request();
        $request->setMethod('POST');
        $this->metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('getTemplate')->with(ResourceActions::CREATE . '.html')->willReturn('@SyliusShop/Product/create.html.twig');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.create')->willReturn(true);
        $this->newResourceFactoryMock->expects($this->once())->method('create')->with($configurationMock, $this->factoryMock)->willReturn($newResourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $newResourceMock)->willReturn($formMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchInitializeEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($eventMock);

        $eventMock->expects($this->once())->method('getResponse')->willReturn(null);
        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->method('isSubmitted')->willReturn(true);
        $formMock->method('isValid')->willReturn(false);
        $formMock->expects($this->once())->method('createView')->willReturn($formViewMock);
        $this->containerMock->method('has')
            ->willReturnMap([
                ['templating', false],
                ['twig', true],
            ]);
        $this->containerMock->expects($this->once())->method('get')->with('twig')->willReturn($twigMock);

        $expectedContext = [
            'configuration' => $configurationMock,
            'metadata' => $this->metadataMock,
            'resource' => $newResourceMock,
            'product' => $newResourceMock,
            'form' => $formViewMock,
        ];

        $twigMock->method('render')->willReturnMap([['@SyliusShop/Product/create.html.twig', $expectedContext, 'view'], ['@SyliusShop/Product/create.html.twig', $expectedContext]]);

        $this->resourceController->createAction($request);
    }

    public function testReturnsAHtmlResponseForNotSubmittedFormDuringResourceCreation(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var FormView|MockObject $formViewMock */
        $formViewMock = $this->createMock(FormView::class);
        /** @var Environment|MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);

        $request = new Request();
        $request->setMethod('POST');
        $this->metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.create')->willReturn(true);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('getTemplate')->with(ResourceActions::CREATE . '.html')->willReturn('@SyliusShop/Product/create.html.twig');

        $this->newResourceFactoryMock->expects($this->once())->method('create')->with($configurationMock, $this->factoryMock)->willReturn($newResourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $newResourceMock)->willReturn($formMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchInitializeEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($eventMock);

        $eventMock->expects($this->once())->method('getResponse')->willReturn(null);
        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->method('isSubmitted')->willReturn(false);
        $formMock->expects($this->once())->method('createView')->willReturn($formViewMock);

        $this->containerMock->method('has')
            ->willReturnMap([
                ['templating', false],
                ['twig', true],
            ]);
        $this->containerMock->expects($this->once())->method('get')->with('twig')->willReturn($twigMock);

        $expectedContext = [
            'configuration' => $configurationMock,
            'metadata' => $this->metadataMock,
            'resource' => $newResourceMock,
            'product' => $newResourceMock,
            'form' => $formViewMock,
        ];

        $twigMock->method('render')->willReturnMap([['@SyliusShop/Product/create.html.twig', $expectedContext, 'view'], ['@SyliusShop/Product/create.html.twig', $expectedContext]]);

        $this->resourceController->createAction($request);
    }

    public function testReturnsANonHtmlResponseForInvalidFormDuringResourceCreation(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('POST');

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.create')
            ->willReturn(true);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);

        $this->newResourceFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($configurationMock, $this->factoryMock)
            ->willReturn($newResourceMock);

        $this->resourceFormFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($configurationMock, $newResourceMock)
            ->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request);
        $formMock->method('isSubmitted')->willReturn(true);
        $formMock->method('isValid')->willReturn(false);

        $expectedView = View::create($formMock, 400);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->anything(),
                $this->callback(function ($view) use ($expectedView) {
                    return $view instanceof View &&
                        $view->getData() === $expectedView->getData() &&
                        $view->getStatusCode() === $expectedView->getStatusCode();
                }),
            )
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->createAction($request));
    }

    public function testReturnsANonHtmlResponseForNotSubmittedFormDuringResourceCreation(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('POST');

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.create')
            ->willReturn(true);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);

        $this->newResourceFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($configurationMock, $this->factoryMock)
            ->willReturn($newResourceMock);

        $this->resourceFormFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($configurationMock, $newResourceMock)
            ->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request);
        $formMock->method('isSubmitted')->willReturn(false);

        $expectedView = View::create($formMock, 400);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->anything(),
                $this->callback(function ($view) use ($expectedView) {
                    return $view instanceof View &&
                        $view->getData() === $expectedView->getData() &&
                        $view->getStatusCode() === $expectedView->getStatusCode();
                }),
            )
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->createAction($request));
    }

    public function testDoesNotCreateTheResourceAndRedirectsToIndexForHtmlRequestsStoppedViaEvents(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('POST');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.create')->willReturn(true);
        $this->newResourceFactoryMock->expects($this->once())->method('create')->with($configurationMock, $this->factoryMock)->willReturn($newResourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $newResourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($newResourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(true);

        $this->flashHelperMock->expects($this->once())->method('addFlashFromEvent')->with($configurationMock, $eventMock);

        $eventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->repositoryMock->expects($this->never())->method('add')->with($newResourceMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock);
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash');
        $this->redirectHandlerMock->expects($this->once())->method('redirectToIndex')->with($configurationMock, $newResourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->createAction($request));
    }

    public function testDoesNotCreateTheResourceAndReturnResponseForHtmlRequestsStoppedViaEvents(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('POST');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.create')->willReturn(true);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);

        $this->newResourceFactoryMock->expects($this->once())->method('create')->with($configurationMock, $this->factoryMock)->willReturn($newResourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $newResourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($newResourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($eventMock);

        $eventMock->method('isStopped')->willReturn(true);
        $eventMock->expects($this->once())->method('getResponse')->willReturn($responseMock);

        $this->flashHelperMock->expects($this->once())->method('addFlashFromEvent')->with($configurationMock, $eventMock);
        $this->repositoryMock->expects($this->never())->method('add')->with($newResourceMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock);
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash');

        $this->assertSame($responseMock, $this->resourceController->createAction($request));
    }

    public function testRedirectsToNewlyCreatedResource(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('POST');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $configurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);
        $configurationMock->method('isHtmlRequest')->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.create')->willReturn(true);
        $this->newResourceFactoryMock->expects($this->once())->method('create')->with($configurationMock, $this->factoryMock)->willReturn($newResourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $newResourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($newResourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(false);

        $this->stateMachineMock->expects($this->once())->method('apply')->with($configurationMock, $newResourceMock);
        $this->repositoryMock->expects($this->once())->method('add')->with($newResourceMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($postEventMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::CREATE, $newResourceMock);
        $this->redirectHandlerMock->expects($this->once())->method('redirectToResource')->with($configurationMock, $newResourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->createAction($request));
    }

    public function testUsesResponseFromPostCreateEventIfDefined(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('POST');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $configurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);
        $configurationMock->method('isHtmlRequest')->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.create')->willReturn(true);
        $this->newResourceFactoryMock->expects($this->once())->method('create')->with($configurationMock, $this->factoryMock)->willReturn($newResourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $newResourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($newResourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(false);

        $this->stateMachineMock->expects($this->once())->method('apply')->with($configurationMock, $newResourceMock);
        $this->repositoryMock->expects($this->once())->method('add')->with($newResourceMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($postEventMock);
        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::CREATE, $newResourceMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->createAction($request));
    }

    public function testReturnsANonHtmlResponseForCorrectlyCreatedResources(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('POST');

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $configurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.create')
            ->willReturn(true);

        $configurationMock->method('isHtmlRequest')->willReturn(false);

        $this->newResourceFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($configurationMock, $this->factoryMock)
            ->willReturn($newResourceMock);

        $this->resourceFormFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($configurationMock, $newResourceMock)
            ->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($newResourceMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPreEvent')
            ->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)
            ->willReturn($eventMock);

        $eventMock->method('isStopped')->willReturn(false);

        $this->stateMachineMock
            ->expects($this->once())
            ->method('apply')
            ->with($configurationMock, $newResourceMock);

        $this->repositoryMock
            ->expects($this->once())
            ->method('add')
            ->with($newResourceMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPostEvent')
            ->with(ResourceActions::CREATE, $configurationMock, $newResourceMock);

        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash');

        $expectedView = View::create($newResourceMock, 201);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->anything(),
                $this->callback(function ($view) use ($expectedView) {
                    return $view instanceof View &&
                        $view->getData() === $expectedView->getData() &&
                        $view->getStatusCode() === $expectedView->getStatusCode();
                }),
            )
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->createAction($request));
    }

    public function testDoesNotCreateTheResourceAndThrowsHttpExceptionForNonHtmlRequestsStoppedViaEvent(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);

        $request = new Request();
        $request->setMethod('POST');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.create')->willReturn(true);
        $this->newResourceFactoryMock->expects($this->once())->method('create')->with($configurationMock, $this->factoryMock)->willReturn($newResourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $newResourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($newResourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($eventMock);
        $eventMock->expects($this->once())->method('isStopped')->willReturn(true);
        $eventMock->expects($this->once())->method('getMessage')->willReturn('You cannot add a new product right now.');
        $eventMock->expects($this->once())->method('getErrorCode')->willReturn(500);

        $this->repositoryMock->expects($this->never())->method('add')->with($newResourceMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock);
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash');

        $this->expectException(HttpException::class);

        $this->resourceController->createAction($request);
    }

    public function testThrowsA403ExceptionIfUserIsUnauthorizedToEditASingleResource(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);

        $request = new Request();

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(false);

        $this->expectException(AccessDeniedException::class);

        $this->resourceController->updateAction($request);
    }

    public function testThrowsA404ExceptionIfResourceToUpdateIsNotFoundBasedOnConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);

        $request = new Request();

        $this->metadataMock->expects($this->once())->method('getHumanizedName')->willReturn('product');
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('The "product" has not been found');

        $this->resourceController->updateAction($request);
    }

    public function testReturnsAHtmlResponseForUpdatingResourceForm(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var FormView|MockObject $formViewMock */
        $formViewMock = $this->createMock(FormView::class);
        /** @var Environment|MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);

        $request = $this->createMock(Request::class);
        $request->setMethod('GET');

        $this->metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('getTemplate')->with(ResourceActions::UPDATE . '.html')->willReturn('@SyliusShop/Product/update.html.twig');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $resourceMock)->willReturn($formMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchInitializeEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);

        $eventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('createView')->willReturn($formViewMock);

        $this->containerMock->method('has')
            ->willReturnMap([
                ['templating', false],
                ['twig', true],
            ]);
        $this->containerMock->expects($this->once())->method('get')->with('twig')->willReturn($twigMock);

        $expectedContext = [
            'configuration' => $configurationMock,
            'metadata' => $this->metadataMock,
            'resource' => $resourceMock,
            'product' => $resourceMock,
            'form' => $formViewMock,
        ];

        $twigMock->method('render')->willReturnMap([['@SyliusShop/Product/update.html.twig', $expectedContext, 'view'], ['@SyliusShop/Product/update.html.twig', $expectedContext]]);

        $this->resourceController->updateAction($request);
    }

    public function testReturnsAHtmlResponseForInvalidFormDuringResourceUpdate(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var FormView|MockObject $formViewMock */
        $formViewMock = $this->createMock(FormView::class);
        /** @var Environment|MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);

        $request = $this->createMock(Request::class);
        $request->setMethod('PUT');

        $this->metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('getTemplate')->with(ResourceActions::UPDATE . '.html')->willReturn('@SyliusShop/Product/update.html.twig');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $resourceMock)->willReturn($formMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchInitializeEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);

        $eventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->method('isSubmitted')->willReturn(true);
        $formMock->method('isValid')->willReturn(false);
        $formMock->expects($this->once())->method('createView')->willReturn($formViewMock);

        $this->containerMock->method('has')
            ->willReturnMap([
                ['templating', false],
                ['twig', true],
            ]);
        $this->containerMock->expects($this->once())->method('get')->with('twig')->willReturn($twigMock);

        $expectedContext = [
            'configuration' => $configurationMock,
            'metadata' => $this->metadataMock,
            'resource' => $resourceMock,
            'product' => $resourceMock,
            'form' => $formViewMock,
        ];

        $twigMock->method('render')->willReturnMap([['@SyliusShop/Product/update.html.twig', $expectedContext, 'view'], ['@SyliusShop/Product/update.html.twig', $expectedContext]]);

        $this->resourceController->updateAction($request);
    }

    public function testReturnsAHtmlResponseForNotSubmittedFormDuringResourceUpdate(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var FormView|MockObject $formViewMock */
        $formViewMock = $this->createMock(FormView::class);
        /** @var Environment|MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var Request|MockObject $request */
        $request = new Request();
        $request->setMethod('PUT');

        $this->metadataMock->expects($this->once())->method('getName')->willReturn('product');
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('getTemplate')->with(ResourceActions::UPDATE . '.html')->willReturn('@SyliusShop/Product/update.html.twig');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $resourceMock)->willReturn($formMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchInitializeEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);

        $eventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->method('isSubmitted')->willReturn(false);
        $formMock->expects($this->once())->method('createView')->willReturn($formViewMock);

        $this->containerMock->method('has')
            ->willReturnMap([
                ['templating', false],
                ['twig', true],
            ]);
        $this->containerMock->expects($this->once())->method('get')->with('twig')->willReturn($twigMock);

        $expectedContext = [
            'configuration' => $configurationMock,
            'metadata' => $this->metadataMock,
            'resource' => $resourceMock,
            'product' => $resourceMock,
            'form' => $formViewMock,
        ];

        $twigMock->method('render')->willReturnMap([['@SyliusShop/Product/update.html.twig', $expectedContext, 'view'], ['@SyliusShop/Product/update.html.twig', $expectedContext]]);

        $this->resourceController->updateAction($request);
    }

    public function testReturnsANonHtmlResponseForInvalidFormDuringResourceUpdate(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);

        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('PATCH');

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.update')
            ->willReturn(true);

        $this->singleResourceProviderMock
            ->expects($this->once())
            ->method('get')
            ->with($configurationMock, $this->repositoryMock)
            ->willReturn($resourceMock);

        $this->resourceFormFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($configurationMock, $resourceMock)
            ->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request);
        $formMock->method('isSubmitted')->willReturn(true);
        $formMock->method('isValid')->willReturn(false);

        $expectedView = View::create($formMock, 400);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->anything(),
                $this->callback(function ($view) use ($expectedView) {
                    return $view instanceof View &&
                        $view->getData() === $expectedView->getData() &&
                        $view->getStatusCode() === $expectedView->getStatusCode();
                }),
            )
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->updateAction($request));
    }

    public function testReturnsANonHtmlResponseForNotSubmittedFormDuringResourceUpdate(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $requestMock)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.update')
            ->willReturn(true);

        $this->singleResourceProviderMock
            ->expects($this->once())
            ->method('get')
            ->with($configurationMock, $this->repositoryMock)
            ->willReturn($resourceMock);

        $this->resourceFormFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($configurationMock, $resourceMock)
            ->willReturn($formMock);

        $requestMock->method('isMethod')->with('PATCH')->willReturn(true);
        $requestMock->method('getMethod')->willReturn('PATCH');

        $formMock->expects($this->once())->method('handleRequest')->with($requestMock);
        $formMock->method('isSubmitted')->willReturn(false);

        $expectedView = View::create($formMock, 400);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->anything(),
                $this->callback(function ($view) use ($expectedView) {
                    return $view instanceof View &&
                        $view->getData() === $expectedView->getData() &&
                        $view->getStatusCode() === $expectedView->getStatusCode();
                }),
            )
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->updateAction($requestMock));
    }

    public function testDoesNotUpdateTheResourceAndRedirectsToResourceForHtmlRequestIfStoppedViaEvent(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('PUT');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $resourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($resourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(true);
        $eventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->flashHelperMock->expects($this->once())->method('addFlashFromEvent')->with($configurationMock, $eventMock);
        $this->managerMock->expects($this->never())->method('flush');
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent');
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash');
        $this->redirectHandlerMock->expects($this->once())->method('redirectToResource')->with($configurationMock, $resourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->updateAction($request));
    }

    public function testRedirectsToUpdatedResource(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var ResourceControllerEvent|MockObject $preEventMock */
        $preEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('PUT');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->method('isHtmlRequest')->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $resourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($resourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($preEventMock);
        $preEventMock->method('isStopped')->willReturn(false);

        $this->resourceUpdateHandlerMock->expects($this->once())->method('handle')->with($resourceMock, $configurationMock, $this->managerMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($postEventMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::UPDATE, $resourceMock);
        $this->redirectHandlerMock->expects($this->once())->method('redirectToResource')->with($configurationMock, $resourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->updateAction($request));
    }

    public function testUsesResponseFromPostUpdateEventIfDefined(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var ResourceControllerEvent|MockObject $preEventMock */
        $preEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('PUT');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->method('isHtmlRequest')->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $resourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($resourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($preEventMock);
        $preEventMock->method('isStopped')->willReturn(false);

        $this->resourceUpdateHandlerMock->expects($this->once())->method('handle')->with($resourceMock, $configurationMock, $this->managerMock);
        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::UPDATE, $resourceMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($postEventMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn($redirectResponseMock);

        $this->redirectHandlerMock->expects($this->never())->method('redirectToResource')->with($configurationMock, $resourceMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->updateAction($request));
    }

    public function testUsesResponseFromInitializeCreateEventIfDefined(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $initializeEventMock */
        $initializeEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('GET');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.create')->willReturn(true);
        $this->newResourceFactoryMock->expects($this->once())->method('create')->with($configurationMock, $this->factoryMock)->willReturn($newResourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $newResourceMock)->willReturn($formMock);

        $formMock->expects($this->never())->method('createView');
        $formMock->method('handleRequest')->willReturnMap([[$request, $formMock], [$request]]);

        $initializeEventMock->expects($this->once())->method('getResponse')->willReturn($responseMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchInitializeEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock)->willReturn($initializeEventMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPreEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent')->with(ResourceActions::CREATE, $configurationMock, $newResourceMock);
        $this->redirectHandlerMock->expects($this->never())->method('redirectToResource')->with($configurationMock, $newResourceMock);

        $this->containerMock->method('has')
            ->willReturnMap([
                ['templating', false],
                ['twig', true],
            ]);

        $this->resourceController->createAction($request);
    }

    public function testUsesResponseFromInitializeUpdateEventIfDefined(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var ResourceControllerEvent|MockObject $initializeEventMock */
        $initializeEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $resourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);

        $this->eventDispatcherMock->expects($this->never())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock);
        $this->resourceUpdateHandlerMock->expects($this->never())->method('handle')->with($resourceMock, $configurationMock, $this->managerMock);
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash')->with($configurationMock, ResourceActions::UPDATE, $resourceMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock);
        $this->redirectHandlerMock->expects($this->never())->method('redirectToResource')->with($configurationMock, $resourceMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchInitializeEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($initializeEventMock);

        $initializeEventMock->expects($this->once())->method('getResponse')->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->updateAction($request));
    }

    public function testReturnsANonHtmlResponseForCorrectlyUpdatedResource(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        /** @var ParameterBagInterface|MockObject $parameterBagMock */
        $parameterBagMock = $this->createMock(ParameterBagInterface::class);
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();
        $request->setMethod('PUT');

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->method('isHtmlRequest')->willReturn(false);
        $configurationMock->expects($this->once())->method('getParameters')->willReturn($parameterBagMock);

        $parameterBagMock->expects($this->once())->method('get')->with('return_content', false)->willReturn(false);

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.update')
            ->willReturn(true);

        $this->singleResourceProviderMock
            ->expects($this->once())
            ->method('get')
            ->with($configurationMock, $this->repositoryMock)
            ->willReturn($resourceMock);

        $this->resourceFormFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($configurationMock, $resourceMock)
            ->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($resourceMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPreEvent')
            ->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)
            ->willReturn($eventMock);

        $eventMock->method('isStopped')->willReturn(false);

        $this->resourceUpdateHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with($resourceMock, $configurationMock, $this->managerMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPostEvent')
            ->with(ResourceActions::UPDATE, $configurationMock, $resourceMock);

        $expectedView = View::create(null, 204);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->anything(),
                $this->callback(function ($view) use ($expectedView) {
                    return $view instanceof View &&
                        $view->getData() === $expectedView->getData() &&
                        $view->getStatusCode() === $expectedView->getStatusCode();
                }),
            )
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->updateAction($request));
    }

    public function testDoesNotUpdateTheResourceThrowsAHttpExceptionForNonHtmlRequestsStoppedViaEvent(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);

        $request = new Request();
        $request->setMethod('PUT');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $resourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($resourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);

        $eventMock->method('isStopped')->willReturn(true);
        $eventMock->expects($this->once())->method('getMessage')->willReturn('Cannot update this channel.');
        $eventMock->expects($this->once())->method('getErrorCode')->willReturn(500);

        $this->managerMock->expects($this->never())->method('flush');
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent');

        $this->expectException(HttpException::class);

        $this->resourceController->updateAction($request);
    }

    public function testAppliesStateMachineTransitionToUpdatedResourceIfConfigured(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var Form|MockObject $formMock */
        $formMock = $this->createMock(Form::class);
        /** @var ResourceControllerEvent|MockObject $preEventMock */
        $preEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);
        /** @var Request|MockObject $request */
        $request = new Request();
        $request->setMethod('PUT');

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->method('isHtmlRequest')->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->resourceFormFactoryMock->expects($this->once())->method('create')->with($configurationMock, $resourceMock)->willReturn($formMock);

        $formMock->expects($this->once())->method('handleRequest')->with($request)->willReturn($formMock);
        $formMock->expects($this->once())->method('isSubmitted')->willReturn(true);
        $formMock->expects($this->once())->method('isValid')->willReturn(true);
        $formMock->expects($this->once())->method('getData')->willReturn($resourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($preEventMock);
        $preEventMock->method('isStopped')->willReturn(false);

        $this->resourceUpdateHandlerMock->expects($this->once())->method('handle')->with($resourceMock, $configurationMock, $this->managerMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($postEventMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::UPDATE, $resourceMock);
        $this->redirectHandlerMock->expects($this->once())->method('redirectToResource')->with($configurationMock, $resourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->updateAction($request));
    }

    public function testThrowsA403ExceptionIfUserIsUnauthorizedToDeleteMultipleResources(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);

        $request = new Request();

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::BULK_DELETE)->willReturn('sylius.product.bulk_delete');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.bulk_delete')->willReturn(false);

        $this->expectException(AccessDeniedException::class);

        $this->resourceController->bulkDeleteAction($request);
    }

    public function testDeletesMultipleResourcesAndRedirectsToIndexForHtmlRequest(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $firstResourceMock */
        $firstResourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceInterface|MockObject $secondResourceMock */
        $secondResourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $firstPreEventMock */
        $firstPreEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $secondPreEventMock */
        $secondPreEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $firstPostEventMock */
        $firstPostEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $secondPostEventMock */
        $secondPostEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::BULK_DELETE)->willReturn('sylius.product.bulk_delete');

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);
        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('bulk_delete', 'xyz'))->willReturn(true);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchMultiple')
            ->with(ResourceActions::BULK_DELETE, $configurationMock, [$firstResourceMock, $secondResourceMock]);

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.bulk_delete')
            ->willReturn(true);

        $this->resourcesCollectionProviderMock
            ->expects($this->once())
            ->method('get')
            ->with($configurationMock, $this->repositoryMock)
            ->willReturn([$firstResourceMock, $secondResourceMock]);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->eventDispatcherMock
            ->expects($this->exactly(2))
            ->method('dispatchPreEvent')
            ->willReturnCallback(function ($action, $configuration, $resource) use ($firstResourceMock, $secondResourceMock, $firstPreEventMock, $secondPreEventMock) {
                if ($resource === $firstResourceMock) {
                    return $firstPreEventMock;
                }
                if ($resource === $secondResourceMock) {
                    return $secondPreEventMock;
                }

                return null;
            });

        $firstPreEventMock->method('isStopped')->willReturn(false);
        $secondPreEventMock->method('isStopped')->willReturn(false);

        $this->resourceDeleteHandlerMock
            ->expects($this->exactly(2))
            ->method('handle')
            ->willReturnCallback(function ($resource, $repository) use ($firstResourceMock, $secondResourceMock) {
                static $call = 0;
                ++$call;

                if ($call === 1) {
                    $this->assertSame($firstResourceMock, $resource);
                } elseif ($call === 2) {
                    $this->assertSame($secondResourceMock, $resource);
                }

                $this->assertSame($this->repositoryMock, $repository);
            });

        $this->eventDispatcherMock
            ->expects($this->exactly(2))
            ->method('dispatchPostEvent')
            ->willReturnCallback(function ($action, $configuration, $resource) use ($firstResourceMock, $secondResourceMock, $firstPostEventMock, $secondPostEventMock) {
                if ($resource === $firstResourceMock) {
                    return $firstPostEventMock;
                }
                if ($resource === $secondResourceMock) {
                    return $secondPostEventMock;
                }

                return null;
            });

        $secondPostEventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->flashHelperMock
            ->expects($this->once())
            ->method('addSuccessFlash')
            ->with($configurationMock, ResourceActions::BULK_DELETE);

        $this->redirectHandlerMock
            ->expects($this->once())
            ->method('redirectToIndex')
            ->with($configurationMock)
            ->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->bulkDeleteAction($request));
    }

    public function testThrowsA403ExceptionIfUserIsUnauthorizedToDeleteASingleResource(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);

        $request = new Request();

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(false);

        $this->expectException(AccessDeniedException::class);

        $this->resourceController->deleteAction($request);
    }

    public function testThrowsA404ExceptionIfResourceForDeletionIsNotFoundBasedOnConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);

        $requestMock = new Request();

        $this->metadataMock->expects($this->once())->method('getHumanizedName')->willReturn('product');
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $requestMock)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('The "product" has not been found');

        $this->resourceController->deleteAction($requestMock);
    }

    public function testDeletesAResourceAndRedirectsToIndexByForHtmlRequest(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');
        $configurationMock->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($eventMock);

        $eventMock->method('isStopped')->willReturn(false);

        $this->resourceDeleteHandlerMock->expects($this->once())->method('handle')->with($resourceMock, $this->repositoryMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($postEventMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::DELETE, $resourceMock);
        $this->redirectHandlerMock->expects($this->once())->method('redirectToIndex')->with($configurationMock, $resourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->deleteAction($request));
    }

    public function testUsesResponseFromPostDeleteEventIfDefined(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);

        $configurationMock->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(false);

        $this->resourceDeleteHandlerMock->expects($this->once())->method('handle')->with($resourceMock, $this->repositoryMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($postEventMock);
        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::DELETE, $resourceMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->deleteAction($request));
    }

    public function testDoesNotDeleteAResourceAndRedirectsToIndexForHtmlRequestsStoppedViaEvent(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(true);
        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(true);
        $eventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->resourceDeleteHandlerMock->expects($this->never())->method('handle')->with($resourceMock, $this->repositoryMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock);
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash')->with($configurationMock, ResourceActions::DELETE, $resourceMock);
        $this->flashHelperMock->expects($this->once())->method('addFlashFromEvent')->with($configurationMock, $eventMock);
        $this->redirectHandlerMock->expects($this->once())->method('redirectToIndex')->with($configurationMock, $resourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->deleteAction($request));
    }

    public function testDoesNotDeleteAResourceAndUsesResponseFromEventIfDefined(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(true);
        $eventMock->expects($this->once())->method('getResponse')->willReturn($redirectResponseMock);

        $this->flashHelperMock->expects($this->once())->method('addFlashFromEvent')->with($configurationMock, $eventMock);
        $this->resourceDeleteHandlerMock->expects($this->never())->method('handle')->with($resourceMock, $this->repositoryMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock);
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash')->with($configurationMock, ResourceActions::DELETE, $resourceMock);
        $this->redirectHandlerMock->expects($this->never())->method('redirectToIndex')->with($configurationMock, $resourceMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->deleteAction($request));
    }

    public function testDoesNotCorrectlyDeleteAResourceAndReturns500ForNotHtmlResponse(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock
            ->expects($this->once())
            ->method('isTokenValid')
            ->with(new CsrfToken('1', 'xyz'))
            ->willReturn(true);

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.delete')
            ->willReturn(true);

        $this->singleResourceProviderMock
            ->expects($this->once())
            ->method('get')
            ->with($configurationMock, $this->repositoryMock)
            ->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPreEvent')
            ->with(ResourceActions::DELETE, $configurationMock, $resourceMock)
            ->willReturn($eventMock);

        $this->resourceDeleteHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with($resourceMock, $this->repositoryMock)
            ->willThrowException(new DeleteHandlingException());

        $this->eventDispatcherMock
            ->expects($this->never())
            ->method('dispatchPostEvent');

        $expectedView = View::create(null, 500);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->anything(),
                $this->callback(function ($view) use ($expectedView) {
                    return $view instanceof View &&
                        $view->getData() === $expectedView->getData() &&
                        $view->getStatusCode() === $expectedView->getStatusCode();
                }),
            )
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->deleteAction($request));
    }

    public function testDeletesAResourceAndReturns204ForNonHtmlRequests(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock
            ->expects($this->once())
            ->method('isTokenValid')
            ->with(new CsrfToken('1', 'xyz'))
            ->willReturn(true);

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.delete')
            ->willReturn(true);

        $this->singleResourceProviderMock
            ->expects($this->once())
            ->method('get')
            ->with($configurationMock, $this->repositoryMock)
            ->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);

        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPreEvent')
            ->with(ResourceActions::DELETE, $configurationMock, $resourceMock)
            ->willReturn($eventMock);

        $this->resourceDeleteHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with($resourceMock, $this->repositoryMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPostEvent')
            ->with(ResourceActions::DELETE, $configurationMock, $resourceMock);

        $expectedView = View::create(null, 204);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->anything(),
                $this->callback(function ($view) use ($expectedView) {
                    return $view instanceof View &&
                        $view->getData() === $expectedView->getData() &&
                        $view->getStatusCode() === $expectedView->getStatusCode();
                }),
            )
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->deleteAction($request));
    }

    public function testDoesNotDeleteAResourceAndThrowsHttpExceptionForNonHtmlRequestsStoppedViaEvent(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(true);
        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);

        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($eventMock);

        $eventMock->expects($this->once())->method('isStopped')->willReturn(true);
        $eventMock->expects($this->once())->method('getMessage')->willReturn('Cannot delete this product.');
        $eventMock->expects($this->once())->method('getErrorCode')->willReturn(500);

        $this->resourceDeleteHandlerMock->expects($this->never())->method('handle')->with($resourceMock, $this->repositoryMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent');
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash');
        $this->flashHelperMock->expects($this->never())->method('addFlashFromEvent');

        $this->expectException(HttpException::class);

        $this->resourceController->deleteAction($request);
    }

    public function testThrowsA403ExceptionIfCsrfTokenIsInvalidDuringDeleteAction(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(false);
        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);
        $eventMock->expects($this->never())->method('isStopped');

        $this->resourceDeleteHandlerMock->expects($this->never())->method('handle')->with($resourceMock, $this->repositoryMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent');
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash');
        $this->flashHelperMock->expects($this->never())->method('addFlashFromEvent');

        $this->expectException(HttpException::class);

        $this->resourceController->deleteAction($request);
    }

    public function testThrowsA403ExceptionIfUserIsUnauthorizedToApplyStateMachineTransitionOnResource(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);

        $request = new Request();

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(false);

        $this->expectException(AccessDeniedException::class);

        $this->resourceController->applyStateMachineTransitionAction($request);
    }

    public function testThrowsA404ExceptionIfResourceIsNotFoundWhenTryingToApplyStateMachineTransition(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);

        $request = new Request();

        $this->metadataMock->expects($this->once())->method('getHumanizedName')->willReturn('product');
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('The "product" has not been found');

        $this->resourceController->applyStateMachineTransitionAction($request);
    }

    public function testDoesNotApplyStateMachineTransitionOnResourceIfNotApplicableAndReturns400BadRequest(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ObjectManager|MockObject $objectManagerMock */
        $objectManagerMock = $this->createMock(ObjectManager::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $resourceMock->expects($this->once())->method('getId')->willReturn('1');

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);

        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(false);

        $this->stateMachineMock->expects($this->once())->method('can')->with($configurationMock, $resourceMock)->willReturn(false);
        $this->stateMachineMock->expects($this->never())->method('apply')->with($configurationMock, $resourceMock);

        $objectManagerMock->expects($this->never())->method('flush');
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent');

        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash');
        $this->flashHelperMock->expects($this->never())->method('addFlashFromEvent');

        $this->expectException(BadRequestHttpException::class);
        $this->resourceController->applyStateMachineTransitionAction($request);
    }

    public function testAppliesStateMachineTransitionToResourceAndRedirectsForHtmlRequest(): void
    {
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(true);
        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn('1');
        $configurationMock->method('isHtmlRequest')->willReturn(true);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);

        $eventMock->method('isStopped')->willReturn(false);

        $this->stateMachineMock->expects($this->once())->method('can')->with($configurationMock, $resourceMock)->willReturn(true);
        $this->resourceUpdateHandlerMock->expects($this->once())->method('handle')->with($resourceMock, $configurationMock, $this->managerMock);
        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::UPDATE, $resourceMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($postEventMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->redirectHandlerMock->expects($this->once())->method('redirectToResource')->with($configurationMock, $resourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->applyStateMachineTransitionAction($request));
    }

    public function testUsesResponseFromPostApplyStateMachineTransitionEventIfDefined(): void
    {
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn('1');
        $configurationMock->method('isHtmlRequest')->willReturn(true);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(false);

        $this->stateMachineMock->expects($this->once())->method('can')->with($configurationMock, $resourceMock)->willReturn(true);
        $this->resourceUpdateHandlerMock->expects($this->once())->method('handle')->with($resourceMock, $configurationMock, $this->managerMock);
        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::UPDATE, $resourceMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($postEventMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->applyStateMachineTransitionAction($request));
    }

    public function testDoesNotApplyStateMachineTransitionOnResourceAndRedirectsForHtmlRequestsStoppedViaEvent(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn('1');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(true);

        $this->managerMock->expects($this->never())->method('flush');
        $this->stateMachineMock->expects($this->never())->method('apply')->with($resourceMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock);
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash')->with($configurationMock, ResourceActions::UPDATE, $resourceMock);

        $eventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->flashHelperMock->expects($this->once())->method('addFlashFromEvent')->with($configurationMock, $eventMock);
        $this->redirectHandlerMock->expects($this->once())->method('redirectToResource')->with($configurationMock, $resourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->applyStateMachineTransitionAction($request));
    }

    public function testDoesNotApplyStateMachineTransitionOnResourceAndReturnEventResponseForHtmlRequestsStoppedViaEvent(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'xyz']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'xyz'))->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn('1');
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(true);

        $this->managerMock->expects($this->never())->method('flush');
        $this->stateMachineMock->expects($this->never())->method('apply')->with($resourceMock);
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock);
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash')->with($configurationMock, ResourceActions::UPDATE, $resourceMock);
        $this->flashHelperMock->expects($this->once())->method('addFlashFromEvent')->with($configurationMock, $eventMock);

        $eventMock->expects($this->once())->method('getResponse')->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->applyStateMachineTransitionAction($request));
    }

    public function testAppliesStateMachineTransitionOnResourceAndReturns200ForNonHtmlRequests(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        /** @var ParameterBagInterface|MockObject $parameterBagMock */
        $parameterBagMock = $this->createMock(ParameterBagInterface::class);
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('getParameters')->willReturn($parameterBagMock);
        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(false);
        $parameterBagMock->expects($this->once())->method('get')->with('return_content', true)->willReturn(true);

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.update')
            ->willReturn(true);

        $this->singleResourceProviderMock
            ->expects($this->once())
            ->method('get')
            ->with($configurationMock, $this->repositoryMock)
            ->willReturn($resourceMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPreEvent')
            ->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)
            ->willReturn($eventMock);

        $eventMock->method('isStopped')->willReturn(false);

        $this->stateMachineMock
            ->expects($this->once())
            ->method('can')
            ->with($configurationMock, $resourceMock)
            ->willReturn(true);

        $this->resourceUpdateHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with($resourceMock, $configurationMock, $this->managerMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPostEvent')
            ->with(ResourceActions::UPDATE, $configurationMock, $resourceMock);

        $expectedView = View::create($resourceMock, 200);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->anything(),
                $this->callback(function ($view) use ($expectedView) {
                    return $view instanceof View &&
                        $view->getData() === $expectedView->getData() &&
                        $view->getStatusCode() === $expectedView->getStatusCode();
                }),
            )
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->applyStateMachineTransitionAction($request));
    }

    public function testAppliesStateMachineTransitionOnResourceAndReturns204ForNonHtmlRequestsIfAdditionalOptionAdded(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();

        /** @var ParameterBagInterface|MockObject $parameterBagMock */
        $parameterBagMock = $this->createMock(ParameterBagInterface::class);
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);

        $request = new Request();

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $request)
            ->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('getParameters')->willReturn($parameterBagMock);
        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(false);
        $parameterBagMock->expects($this->once())->method('get')->with('return_content', true)->willReturn(false);

        $this->authorizationCheckerMock
            ->expects($this->once())
            ->method('isGranted')
            ->with($configurationMock, 'sylius.product.update')
            ->willReturn(true);

        $this->singleResourceProviderMock
            ->expects($this->once())
            ->method('get')
            ->with($configurationMock, $this->repositoryMock)
            ->willReturn($resourceMock);

        $configurationMock->method('isHtmlRequest')->willReturn(false);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPreEvent')
            ->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)
            ->willReturn($eventMock);

        $eventMock->method('isStopped')->willReturn(false);

        $this->stateMachineMock
            ->expects($this->once())
            ->method('can')
            ->with($configurationMock, $resourceMock)
            ->willReturn(true);

        $this->resourceUpdateHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with($resourceMock, $configurationMock, $this->managerMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPostEvent')
            ->with(ResourceActions::UPDATE, $configurationMock, $resourceMock);

        $expectedView = View::create(null, 204);

        $this->viewHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with(
                $this->anything(),
                $this->callback(function ($view) use ($expectedView) {
                    return $view instanceof View &&
                        $view->getData() === $expectedView->getData() &&
                        $view->getStatusCode() === $expectedView->getStatusCode();
                }),
            )
            ->willReturn($responseMock);

        $this->assertSame($responseMock, $this->resourceController->applyStateMachineTransitionAction($request));
    }

    public function testDoesNotApplyStateMachineTransitionResourceAndThrowsHttpExceptionForNonHtmlRequestsStoppedViaEvent(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ObjectManager|MockObject $objectManagerMock */
        $objectManagerMock = $this->createMock(ObjectManager::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);

        $request = new Request();

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::UPDATE)->willReturn('sylius.product.update');
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(false);
        $configurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.update')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::UPDATE, $configurationMock, $resourceMock)->willReturn($eventMock);

        $eventMock->expects($this->once())->method('isStopped')->willReturn(true);
        $eventMock->expects($this->once())->method('getMessage')->willReturn('Cannot approve this product.');
        $eventMock->expects($this->once())->method('getErrorCode')->willReturn(500);

        $this->stateMachineMock->expects($this->never())->method('apply')->with($configurationMock, $resourceMock);

        $objectManagerMock->expects($this->never())->method('flush');

        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent');
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash');
        $this->flashHelperMock->expects($this->never())->method('addFlashFromEvent');

        $this->expectException(HttpException::class);

        $this->resourceController->applyStateMachineTransitionAction($request);
    }

    public function testValidatesCsrfWithDefaultParameterName(): void
    {
        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_csrf_token' => 'valid-token-here']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');
        $configurationMock->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'valid-token-here'))->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(false);

        $this->resourceDeleteHandlerMock->expects($this->once())->method('handle')->with($resourceMock, $this->repositoryMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($postEventMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::DELETE, $resourceMock);
        $this->redirectHandlerMock->expects($this->once())->method('redirectToIndex')->with($configurationMock, $resourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $this->resourceController->deleteAction($request));
    }

    public function testValidatesCsrfWithCustomParameterName(): void
    {
        $controller = $this->buildControllerWithCsrfParameter('_my_csrf_field');

        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var ResourceControllerEvent|MockObject $postEventMock */
        $postEventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var Response|MockObject $redirectResponseMock */
        $redirectResponseMock = $this->createMock(Response::class);

        $request = new Request(request: ['_my_csrf_field' => 'valid-token-here']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');
        $configurationMock->method('isHtmlRequest')->willReturn(true);
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', 'valid-token-here'))->willReturn(true);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);

        $this->eventDispatcherMock->expects($this->once())->method('dispatchPreEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($eventMock);
        $eventMock->method('isStopped')->willReturn(false);

        $this->resourceDeleteHandlerMock->expects($this->once())->method('handle')->with($resourceMock, $this->repositoryMock);
        $this->eventDispatcherMock->expects($this->once())->method('dispatchPostEvent')->with(ResourceActions::DELETE, $configurationMock, $resourceMock)->willReturn($postEventMock);

        $postEventMock->expects($this->once())->method('getResponse')->willReturn(null);

        $this->flashHelperMock->expects($this->once())->method('addSuccessFlash')->with($configurationMock, ResourceActions::DELETE, $resourceMock);
        $this->redirectHandlerMock->expects($this->once())->method('redirectToIndex')->with($configurationMock, $resourceMock)->willReturn($redirectResponseMock);

        $this->assertSame($redirectResponseMock, $controller->deleteAction($request));
    }

    public function testRejectsWhenCsrfTokenIsInWrongField(): void
    {
        $controller = $this->buildControllerWithCsrfParameter('_my_csrf_field');

        /** @var RequestConfiguration|MockObject $configurationMock */
        $configurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var CsrfTokenManagerInterface|MockObject $csrfTokenManagerMock */
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);

        $request = new Request(request: ['_csrf_token' => 'valid-token-here']);

        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($this->metadataMock, $request)->willReturn($configurationMock);

        $configurationMock->expects($this->once())->method('hasPermission')->willReturn(true);
        $configurationMock->expects($this->once())->method('getPermission')->with(ResourceActions::DELETE)->willReturn('sylius.product.delete');
        $configurationMock->expects($this->once())->method('isCsrfProtectionEnabled')->willReturn(true);

        $this->containerMock->expects($this->once())->method('has')->with('security.csrf.token_manager')->willReturn(true);
        $this->containerMock->expects($this->once())->method('get')->with('security.csrf.token_manager')->willReturn($csrfTokenManagerMock);

        $csrfTokenManagerMock->expects($this->once())->method('isTokenValid')->with(new CsrfToken('1', ''))->willReturn(false);

        $this->authorizationCheckerMock->expects($this->once())->method('isGranted')->with($configurationMock, 'sylius.product.delete')->willReturn(true);
        $this->singleResourceProviderMock->expects($this->once())->method('get')->with($configurationMock, $this->repositoryMock)->willReturn($resourceMock);

        $resourceMock->expects($this->once())->method('getId')->willReturn(1);

        $this->resourceDeleteHandlerMock->expects($this->never())->method('handle');
        $this->eventDispatcherMock->expects($this->never())->method('dispatchPostEvent');
        $this->flashHelperMock->expects($this->never())->method('addSuccessFlash');

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Invalid csrf token.');

        try {
            $controller->deleteAction($request);
        } catch (HttpException $exception) {
            $this->assertSame(Response::HTTP_FORBIDDEN, $exception->getStatusCode());

            throw $exception;
        }
    }

    private function buildControllerWithCsrfParameter(string $csrfParameter): ResourceController
    {
        $controller = new ResourceController(
            $this->metadataMock,
            $this->requestConfigurationFactoryMock,
            $this->viewHandlerMock,
            $this->repositoryMock,
            $this->factoryMock,
            $this->newResourceFactoryMock,
            $this->managerMock,
            $this->singleResourceProviderMock,
            $this->resourcesCollectionProviderMock,
            $this->resourceFormFactoryMock,
            $this->redirectHandlerMock,
            $this->flashHelperMock,
            $this->authorizationCheckerMock,
            $this->eventDispatcherMock,
            $this->stateMachineMock,
            $this->resourceUpdateHandlerMock,
            $this->resourceDeleteHandlerMock,
            $csrfParameter,
        );
        $controller->setContainer($this->containerMock);

        return $controller;
    }

    private function markAsSkippedIfFosRestBundleIsNotAvailable(): void
    {
        if (!class_exists(FOSRestBundle::class)) {
            $this->markTestSkipped('FriendsOfSymfony Rest Bundle is not installed.');
        }
    }
}
