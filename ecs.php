<?php

use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use SlevomatCodingStandard\Sniffs\Commenting\InlineDocCommentDeclarationSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $config): void {
    $config->import('vendor/sylius-labs/coding-standard/ecs.php');

    $config
        ->ruleWithConfiguration(HeaderCommentFixer::class, [
            'location' => 'after_open',
            'header' =>
'This file is part of the Sylius package.

(c) Paweł Jędrzejewski

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.',
        ]);

    $config->ruleWithConfiguration(NoSuperfluousPhpdocTagsFixer::class, ['allow_mixed' => true]);

    $config->skip([
        InlineDocCommentDeclarationSniff::class . '.MissingVariable',
        VisibilityRequiredFixer::class => ['*Spec.php'],
        'src/Bundle/Controller/ControllerTrait.php',
        '**/var/*',
    ]);
};
