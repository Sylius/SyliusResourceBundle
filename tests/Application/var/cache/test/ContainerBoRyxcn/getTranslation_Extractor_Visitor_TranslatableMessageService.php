<?php

namespace ContainerBoRyxcn;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getTranslation_Extractor_Visitor_TranslatableMessageService extends App_KernelTestDebugContainer
{
    /**
     * Gets the private 'translation.extractor.visitor.translatable_message' shared service.
     *
     * @return \Symfony\Component\Translation\Extractor\Visitor\TranslatableMessageVisitor
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/vendor/symfony/translation/Extractor/Visitor/AbstractVisitor.php';
        include_once \dirname(__DIR__, 6).'/vendor/nikic/php-parser/lib/PhpParser/NodeVisitor.php';
        include_once \dirname(__DIR__, 6).'/vendor/symfony/translation/Extractor/Visitor/TranslatableMessageVisitor.php';

        return $container->privates['translation.extractor.visitor.translatable_message'] = new \Symfony\Component\Translation\Extractor\Visitor\TranslatableMessageVisitor();
    }
}