<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getJmsSerializer_ExpressionEvaluatorService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'jms_serializer.expression_evaluator' shared service.
     *
     * @return \JMS\Serializer\Expression\ExpressionEvaluator
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Expression/CompilableExpressionEvaluatorInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Expression/ExpressionEvaluatorInterface.php';
        include_once \dirname(__DIR__, 6).'/vendor/jms/serializer/src/Expression/ExpressionEvaluator.php';

        return $container->privates['jms_serializer.expression_evaluator'] = new \JMS\Serializer\Expression\ExpressionEvaluator(($container->privates['jms_serializer.expression_language'] ?? $container->load('getJmsSerializer_ExpressionLanguageService')), ['container' => $container]);
    }
}
