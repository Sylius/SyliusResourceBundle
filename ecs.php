<?php

use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTagTypeFixer;
use SlevomatCodingStandard\Sniffs\Commenting\InlineDocCommentDeclarationSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $config): void
{
    $config->import('vendor/sylius-labs/coding-standard/ecs.php');

    $config->ruleWithConfiguration(HeaderCommentFixer::class, [
        'location' => 'after_open',
        'header' =>
            'This file is part of the Sylius package.

(c) Paweł Jędrzejewski

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.',
    ]);

    $config->skip([
        PhpdocTagTypeFixer::class,
        InlineDocCommentDeclarationSniff::class . '.MissingVariable',
        VisibilityRequiredFixer::class => ['*Spec.php'],
        '**/var/*',
        'src/Bundle/test/src/Kernel.php',
    ]);
};
