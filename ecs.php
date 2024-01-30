<?php

use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use SlevomatCodingStandard\Sniffs\Commenting\InlineDocCommentDeclarationSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->import('vendor/sylius-labs/coding-standard/ecs.php');

    $ecsConfig->ruleWithConfiguration(HeaderCommentFixer::class, [
            'location' => 'after_open',
            'header' =>
'This file is part of the Sylius package.

(c) Sylius Sp. z o.o.

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.',
        ])
    ;
    $ecsConfig->ruleWithConfiguration(NoSuperfluousPhpdocTagsFixer::class, ['allow_mixed' => true]);

    $ecsConfig->skip([
        InlineDocCommentDeclarationSniff::class . '.MissingVariable',
        VisibilityRequiredFixer::class => ['*Spec.php'],
        MethodArgumentSpaceFixer::class => ['*/BoardGameBlog/*', '*/Subscription/*'],
        'src/Component/vendor/*',
        '**/var/*',
    ]);
};
