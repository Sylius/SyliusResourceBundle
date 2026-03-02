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

namespace Sylius\Bundle\ResourceBundle\Tests\Bundle\EventListener;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\DefaultNamingStrategy;
use Doctrine\Persistence\Mapping\ClassMetadata as ClassMetadataInterface;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\EventListener\ORMMappedSuperClassSubscriber;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\ChildEntity;
use Sylius\Bundle\ResourceBundle\Tests\Fixtures\ParentEntity;
use Sylius\Resource\Metadata\Registry;

final class ORMMappedSuperClassSubscriberTest extends TestCase
{
    public function testItUpdatesSourceEntityWhenCopyingParentAssociationMappings(): void
    {
        $subscriber = new ORMMappedSuperClassSubscriber(new Registry());

        $namingStrategy = new DefaultNamingStrategy();

        $childMetadata = new ClassMetadata(ChildEntity::class, $namingStrategy);
        $childMetadata->wakeupReflection(new RuntimeReflectionService());

        $configuration = new Configuration();
        $configuration->setNamingStrategy($namingStrategy);
        $configuration->setMetadataDriverImpl(new class () implements MappingDriver {
            public function loadMetadataForClass(string $className, ClassMetadataInterface $metadata): void
            {
                if ($className !== ParentEntity::class) {
                    return;
                }

                /** @var ClassMetadata $metadata */
                $metadata->isMappedSuperclass = true;
                $metadata->mapOneToOne([
                    'fieldName' => 'relatedEntity',
                    'targetEntity' => ParentEntity::class,
                    'joinColumns' => [['name' => 'related_entity_id', 'referencedColumnName' => 'id']],
                ]);
            }

            /** @return list<string> */
            public function getAllClassNames(): array
            {
                return [ParentEntity::class];
            }

            public function isTransient(string $className): bool
            {
                return false;
            }
        });

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getConfiguration')->willReturn($configuration);

        $event = new LoadClassMetadataEventArgs($childMetadata, $em);
        $subscriber->loadClassMetadata($event);

        self::assertArrayHasKey('relatedEntity', $childMetadata->associationMappings);

        $association = $childMetadata->associationMappings['relatedEntity'];
        $sourceEntity = \is_array($association) ? $association['sourceEntity'] : $association->sourceEntity;

        self::assertSame(ChildEntity::class, $sourceEntity);
    }
}
