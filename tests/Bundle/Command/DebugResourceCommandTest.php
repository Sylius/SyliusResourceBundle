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

namespace Sylius\Bundle\ResourceBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Bundle\ResourceBundle\Command\DebugResourceCommand;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Symfony\Component\Console\Tester\CommandTester;

final class DebugResourceCommandTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy $registry;

    private ObjectProphecy $resourceCollectionMetadataFactory;

    private CommandTester $tester;

    public function setUp(): void
    {
        $this->registry = $this->prophesize(RegistryInterface::class);
        $this->resourceCollectionMetadataFactory = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);

        $command = new DebugResourceCommand($this->registry->reveal(), $this->resourceCollectionMetadataFactory->reveal());
        $this->tester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function it_lists_all_resources_if_no_argument_is_given(): void
    {
        $this->registry->getAll()->willReturn([$this->createMetadata('one'), $this->createMetadata('two')]);

        $this->tester->execute([]);
        $display = $this->tester->getDisplay();

        $this->assertEquals(
            <<<TXT
             ------------ 
              Alias       
             ------------ 
              sylius.one  
              sylius.two  
             ------------ 
            
            
            TXT
            ,
            $display,
        );
    }

    /**
     * @test
     */
    public function it_displays_the_metadata_for_given_resource_alias_without_operations(): void
    {
        $this->registry->get('metadata.one')->willReturn($this->createMetadata('one'));

        $resourceMetadataCollection = new ResourceMetadataCollection();

        $this->resourceCollectionMetadataFactory->create('App\One')->willReturn($resourceMetadataCollection);

        $this->tester->execute([
            'resource' => 'metadata.one',
        ]);

        $display = $this->tester->getDisplay();

        $this->assertStringContainsString('[INFO] This resource has no defined operations.', $display);
    }

    /**
     * @test
     */
    public function it_displays_the_metadata_for_given_resource_alias_with_operations(): void
    {
        $this->registry->get('metadata.one')->willReturn($this->createMetadata('one'));

        $resourceMetadata = (new ResourceMetadata())->withOperations(new Operations([
            'app_one_index' => new Index(name: 'app_one_index', provider: 'App\GetOneItemProvider'),
            'app_one_create' => new Create(name: 'app_one_create', processor: 'App\CreateOneProcessor'),
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resourceMetadata]);

        $this->resourceCollectionMetadataFactory->create('App\One')->willReturn($resourceMetadataCollection);

        $this->tester->execute([
            'resource' => 'metadata.one',
        ]);

        $display = $this->tester->getDisplay();

        $this->assertEquals(
            <<<TXT
            
            Configuration
            -------------
            
             ----------------------- ----------------------------- 
              Option                  Value                        
             ----------------------- ----------------------------- 
              name                    "one"                        
              applicationName         "sylius"                     
              driver                  "doctrine/foobar"            
              stateMachineComponent   null                         
              templatesNamespace      null                         
              classes                 [                            
                                        "model" => "App\One",      
                                        "foo" => "bar",            
                                        "bar" => "foo"             
                                      ]                            
              whatever                [                            
                                        "something" => [           
                                          "elephants" => "camels"  
                                        ]                          
                                      ]                            
             ----------------------- ----------------------------- 
            
            New Resource Metadata
            ---------------------
            
             ------------------------ ------- 
              Option                   Value  
             ------------------------ ------- 
              alias                    null   
              section                  null   
              formType                 null   
              templatesDir             null   
              routePrefix              null   
              name                     null   
              pluralName               null   
              applicationName          null   
              identifier               null   
              normalizationContext     null   
              denormalizationContext   null   
              validationContext        null   
              class                    null   
              driver                   null   
              vars                     null   
             ------------------------ ------- 
            
            New operations
            --------------
            
             ---------------- --------------------------------------------------------------- 
              Name             Details                                                        
             ---------------- --------------------------------------------------------------- 
              app_one_index    bin/console sylius:debug:resource metadata.one app_one_index   
              app_one_create   bin/console sylius:debug:resource metadata.one app_one_create  
             ---------------- --------------------------------------------------------------- 
            
            
            TXT
            ,
            $display,
        );
    }

    /**
     * @test
     */
    public function it_displays_the_metadata_for_given_resource_as_fully_qualified_class_name(): void
    {
        $this->registry->getByClass('App\Resource')->willReturn($this->createMetadata('one'));

        $resourceMetadata = (new ResourceMetadata(alias: 'sylius.one'))->withOperations(new Operations([
            'app_one_index' => new Index(name: 'app_one_index', provider: 'App\GetOneItemProvider'),
            'app_one_create' => new Create(name: 'app_one_create', processor: 'App\CreateOneProcessor'),
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resourceMetadata]);

        $this->resourceCollectionMetadataFactory->create('App\One')->willReturn($resourceMetadataCollection);

        $this->tester->execute([
            'resource' => 'App\Resource',
        ]);

        $display = $this->tester->getDisplay();

        $this->assertEquals(
            <<<TXT
            
            Configuration
            -------------
            
             ----------------------- ----------------------------- 
              Option                  Value                        
             ----------------------- ----------------------------- 
              name                    "one"                        
              applicationName         "sylius"                     
              driver                  "doctrine/foobar"            
              stateMachineComponent   null                         
              templatesNamespace      null                         
              classes                 [                            
                                        "model" => "App\One",      
                                        "foo" => "bar",            
                                        "bar" => "foo"             
                                      ]                            
              whatever                [                            
                                        "something" => [           
                                          "elephants" => "camels"  
                                        ]                          
                                      ]                            
             ----------------------- ----------------------------- 
            
            New Resource Metadata
            ---------------------
            
             ------------------------ -------------- 
              Option                   Value         
             ------------------------ -------------- 
              alias                    "sylius.one"  
              section                  null          
              formType                 null          
              templatesDir             null          
              routePrefix              null          
              name                     null          
              pluralName               null          
              applicationName          null          
              identifier               null          
              normalizationContext     null          
              denormalizationContext   null          
              validationContext        null          
              class                    null          
              driver                   null          
              vars                     null          
             ------------------------ -------------- 
            
            New operations
            --------------
            
             ---------------- --------------------------------------------------------------- 
              Name             Details                                                        
             ---------------- --------------------------------------------------------------- 
              app_one_index    bin/console sylius:debug:resource App\Resource app_one_index   
              app_one_create   bin/console sylius:debug:resource App\Resource app_one_create  
             ---------------- --------------------------------------------------------------- 
            
            
            TXT
            ,
            $display,
        );
    }

    /**
     * @test
     */
    public function it_displays_the_metadata_for_given_resource_operation(): void
    {
        $this->registry->get('metadata.one')->willReturn($this->createMetadata('one'));

        $resourceMetadata = (new ResourceMetadata(alias: 'sylius.one'))->withOperations(new Operations([
            'app_one_index' => new Index(name: 'app_one_index', provider: 'App\GetOneItemProvider'),
            'app_one_create' => new Create(
                template: 'register.html.twig',
                name: 'app_one_create',
                provider: 'App\ItemProvider',
                processor: 'App\CreateOneProcessor',
                responder: 'App\ItemResponder',
                factory: 'App\CreateOneFactory',
                factoryMethod: 'createWithCreator',
                factoryArguments: ['creator' => 'user'],
                eventShortName: 'register',
                vars: ['foo' => 'bar'],
            ),
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection([$resourceMetadata]);

        $this->resourceCollectionMetadataFactory->create('App\One')->willReturn($resourceMetadataCollection);

        $this->tester->execute([
            'resource' => 'metadata.one',
            'operation' => 'app_one_create',
        ]);

        $display = $this->tester->getDisplay();

        $this->assertEquals(
            <<<TXT
            
            Operation Metadata
            ------------------
            
             ------------------------ -------------------------- 
              Option                   Value                     
             ------------------------ -------------------------- 
              factory                  "App\CreateOneFactory"    
              factoryMethod            "createWithCreator"       
              factoryArguments         [                         
                                         "creator" => "user"     
                                       ]                         
              stateMachineComponent    null                      
              stateMachineTransition   null                      
              stateMachineGraph        null                      
              twigContextFactory       null                      
              methods                  [                         
                                         "GET",                  
                                         "POST"                  
                                       ]                         
              path                     null                      
              routeName                null                      
              routePrefix              null                      
              redirectToRoute          null                      
              redirectArguments        null                      
              vars                     [                         
                                         "foo" => "bar"          
                                       ]                         
              provider                 "App\ItemProvider"        
              processor                "App\CreateOneProcessor"  
              responder                "App\ItemResponder"       
              repository               null                      
              template                 "register.html.twig"      
              shortName                "create"                  
              name                     "app_one_create"          
              repositoryMethod         null                      
              repositoryArguments      null                      
              read                     null                      
              write                    null                      
              validate                 null                      
              deserialize              null                      
              serialize                null                      
              formType                 null                      
              formOptions              null                      
              normalizationContext     null                      
              denormalizationContext   null                      
              validationContext        null                      
              eventShortName           "register"                
             ------------------------ -------------------------- 
            
            
            TXT
            ,
            $display,
        );
    }

    private function createMetadata(string $suffix): MetadataInterface
    {
        return Metadata::fromAliasAndConfiguration(sprintf('sylius.%s', $suffix), [
            'driver' => 'doctrine/foobar',
            'classes' => [
                'model' => 'App\\' . ucfirst($suffix),
                'foo' => 'bar',
                'bar' => 'foo',
            ],
            'whatever' => [
                'something' => [
                    'elephants' => 'camels',
                ],
            ],
        ]);
    }
}
