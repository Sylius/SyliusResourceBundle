<?php
/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ResourceBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Sylius\Bundle\ResourceBundle\EventListener\LoadMetadataListener
 *
 * @author Ivan Molchanov <ivan.molchanov@opensoftdev.ru>
 */
class LoadMetadataSubscriber implements EventSubscriber
{

    /**
     * @var array
     */
    protected $classes;

    /**
     * Constructor
     *
     * @param array $classes
     */
    public function __construct($classes)
    {
        $this->classes = $classes;
    }


    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'loadClassMetadata'
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        foreach ($this->classes as $class) {
            if ($class['model'] === $metadata->getName()) {
                $metadata->isMappedSuperclass = false;
            }
        }
        if (!$metadata->isMappedSuperclass) {
            foreach (class_parents($metadata->getName()) as $parent) {
                $parentMetadata = new ClassMetadata(
                    $parent,
                    $eventArgs->getEntityManager()->getConfiguration()->getNamingStrategy()
                );
                $eventArgs
                    ->getEntityManager()
                    ->getConfiguration()
                    ->getMetadataDriverImpl()
                    ->loadMetadataForClass(
                        $parent,
                        $parentMetadata
                    );
                if ($parentMetadata->isMappedSuperclass) {
                    foreach ($parentMetadata->getAssociationMappings() as $key => $value) {
                        if ($value['type'] === ClassMetadataInfo::ONE_TO_MANY || $value['type'] === ClassMetadataInfo::ONE_TO_ONE) {
                            $metadata->associationMappings[$key] = $value;
                        }
                    }
                }
            }
        } else {
            foreach ($metadata->getAssociationMappings() as $key => $value) {
                if ($value['type'] === ClassMetadataInfo::ONE_TO_MANY || $value['type'] === ClassMetadataInfo::ONE_TO_ONE) {
                    unset($metadata->associationMappings[$key]);
                }
            }
        }
    }
}
