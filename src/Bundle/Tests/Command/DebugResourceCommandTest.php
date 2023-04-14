<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
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
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
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

        $resourceMetadata = (new Resource())->withOperations(new Operations([
            new Index(name: 'app_one_index', provider: 'App\GetOneItemProvider'),
            new Create(name: 'app_one_create', processor: 'App\CreateOneProcessor'),
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
            
             ------------------------------ ----------------- 
              name                           one              
              application                    sylius           
              driver                         doctrine/foobar  
              classes.model                  App\One          
              classes.foo                    bar              
              classes.bar                    foo              
              whatever.something.elephants   camels           
             ------------------------------ ----------------- 
            
            New Resource Metadata
            ---------------------
            
             ------------------------ ------- 
              Option                   Value  
             ------------------------ ------- 
              alias                           
              section                         
              formType                        
              templatesDir                    
              routePrefix                     
              name                            
              pluralName                      
              applicationName                 
              identifier                      
              normalizationContext            
              denormalizationContext          
              validationContext               
              class                           
             ------------------------ ------- 

            New operations
            --------------
            
             ---------------- 
              Name            
             ---------------- 
              app_one_index   
              app_one_create  
             ---------------- 
            
            
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
