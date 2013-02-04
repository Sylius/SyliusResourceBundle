<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ResourceBundle\Doctrine\ORM;

use PHPSpec2\ObjectBehavior;

/**
 * Doctrine ORM driver entity repository spec.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class EntityRepository extends ObjectBehavior
{
    /**
     * @param Doctrine\ORM\EntityManager         $entityManager
     * @param Doctrine\ORM\Mapping\ClassMetadata $class
     * @param Doctrine\ORM\QueryBuilder          $queryBuilder
     */
    function let($entityManager, $class, $queryBuilder)
    {
        $class->name = 'spec\Sylius\Bundle\ResourceBundle\Fixture\Entity\Foo';

        $entityManager
            ->createQueryBuilder()
            ->willReturn($queryBuilder)
        ;

        $queryBuilder
            ->select(ANY_ARGUMENT)
            ->willReturn($queryBuilder)
        ;
        $queryBuilder
            ->from(ANY_ARGUMENTS)
            ->willReturn($queryBuilder)
        ;

        $this->beConstructedWith($entityManager, $class);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository');
        $this->shouldHaveType('Doctrine\ORM\EntityRepository');
    }

    function it_should_create_new()
    {
        $this->createNew()->shouldHaveType('spec\Sylius\Bundle\ResourceBundle\Fixture\Entity\Foo');
    }

    function it_should_return_null_if_not_found()
    {
        $this->find(1)->shouldReturn(null);
    }

    /**
     * @param Doctrine\ORM\QueryBuilder $queryBuilder
     */
    function it_should_apply_criteria_when_finding_one_by_criteria($queryBuilder)
    {
        $criteria = array(
            'foo' => 'bar',
            'bar' => 'baz',
        );

        $this->it_should_apply_criteria($criteria, $queryBuilder);

        $this->findOneBy($criteria)->shouldReturn(null);
    }

    /**
     * @param Doctrine\ORM\QueryBuilder $queryBuilder
     */
    function it_should_apply_criteria_when_finding_by_criteria($queryBuilder)
    {
        $criteria = array(
            'foo' => 'bar',
            'bar' => 'baz',
        );

        $this->it_should_apply_criteria($criteria, $queryBuilder);

        $this->findBy($criteria)->shouldReturn(null);
    }

    function it_should_return_null_if_all_not_found()
    {
        $this->findAll()->shouldReturn(null);
    }

    function it_should_create_paginator()
    {
        $this
            ->createPaginator()
            ->shouldHaveType('Pagerfanta\Pagerfanta')
        ;
    }

    private function it_should_apply_criteria(array $criteria, $queryBuilder)
    {
        foreach ($criteria as $property => $value) {
            $queryBuilder
                ->andWhere('o.'.$property.' = :'.$property)
                ->shouldBeCalled()
                ->willReturn($queryBuilder)
            ;

            $queryBuilder
                ->setParameter($property, $value)
                ->shouldBeCalled()
                ->willReturn($queryBuilder)
            ;
        }
    }
}
