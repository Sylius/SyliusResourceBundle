<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ResourceBundle\Form;

use Doctrine\ORM\EntityManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo as ClassMetadataInfoORM;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo as ClassMetadataInfoODM;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Generates a form class based on a Doctrine entity.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Hugo Hamon <hugo.hamon@sensio.com>
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class DefaultFormFactory
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param $resource
     * @param EntityManager|DocumentManager $manager
     * @return \Symfony\Component\Form\Form
     * @throws \Exception
     */
    public function create($resource, $manager)
    {
        if (!$manager instanceof EntityManager && !$manager instanceof DocumentManager) {
            throw new \Exception("Must be an instance of Doctrine\\ORM\\EntityManager or Doctrine\\ODM\\MongoDB\\DocumentManager");
        }

        $metadata = $manager->getClassMetadata(get_class($resource));

        if (count($metadata->identifier) > 1) {
            throw new \RuntimeException('The default form factory does not support entity classes with multiple primary keys.');
        }

        $builder = $this->formFactory->createNamedBuilder('', 'form', $resource, array('csrf_protection' => false));

        foreach ($this->getFieldsFromMetadata($metadata) as $field => $type) {
            $options = array();

            if (in_array($type, array('date', 'datetime'))) {
                $options = array('widget' => 'single_text');
            }
            if ('relation' === $type) {
                $options = array('property' => 'id');
            }

            $builder->add($field, null, $options);
        }

        return $builder->getForm();
    }

    /**
     * Returns an array of fields. Fields can be both column fields and
     * association fields.
     *
     * @param ClassMetadataInfoORM|ClassMetadataInfoODM $metadata
     *
     * @return array $fields
     */
    private function getFieldsFromMetadata($metadata)
    {
        if (!$metadata instanceof ClassMetadataInfoORM && !$metadata instanceof ClassMetadataInfoODM) {
            throw new \Exception("Must be an instance of Doctrine\\ORM\\Mapping\\ClassMetadataInfo or Doctrine\\ODM\\MongoDB\\Mapping\\ClassMetadataInfo");
        }

        $fieldsMapping = array();

        if ($metadata instanceof ClassMetadataInfoORM) {
            $fields = (array)$metadata->fieldNames;
            if (!$metadata->isIdentifierNatural()) {
                $fields = array_diff($fields, $metadata->identifier);
            }
        } elseif ($metadata instanceof ClassMetadataInfoODM) {
            $fields = array_keys($metadata->fieldMappings);
            if (!$metadata->getIdentifier()) {
                $fields = array_diff($fields, $metadata->identifier);
            }
        }

        foreach ($fields as $field) {
            $fieldsMapping[$field] = $metadata->getTypeOfField($field);
        }

        foreach ($metadata->associationMappings as $fieldName => $relation) {
            if ($relation['type'] !== ClassMetadataInfoORM::ONE_TO_MANY || $relation['type'] !== ClassMetadataInfoODM::REFERENCE_MANY) {
                $fieldsMapping[$fieldName] = 'relation';
            }
        }

        return $fieldsMapping;
    }
}
