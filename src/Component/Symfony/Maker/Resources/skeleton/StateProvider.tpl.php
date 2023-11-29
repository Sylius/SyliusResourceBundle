<?php declare(strict_types=1);
echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProviderInterface;

final class <?php echo $class_name; ?> implements ProviderInterface
{
    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        // Retrieve the state from somewhere

        return null;
    }
}
