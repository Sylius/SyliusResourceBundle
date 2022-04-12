<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use <?= $from->getName(); ?> as <?= $fromShortName; ?>;
use <?= $to->getName(); ?> as <?= $toShortName; ?>;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\DataTransformer\DataTransformerInterface;
use Webmozart\Assert\Assert;

final class <?= $class_name ?> implements DataTransformerInterface
{
    public function transform(object $data, string $to, RequestConfiguration $configuration): <?= $toShortName; ?>

    {
        Assert::isInstanceOf($data, <?= $fromShortName; ?>::class);

        // TODO implement your logic
        return new <?= $toShortName; ?>();
    }

    public function supportsTransformation(object $data, string $to, RequestConfiguration $configuration): bool
    {
        return $data instanceof <?= $fromShortName; ?> && is_a($to, <?= $toShortName; ?>::class, true);
    }
}
