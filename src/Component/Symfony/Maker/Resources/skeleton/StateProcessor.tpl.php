<?php declare(strict_types=1);
echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProcessorInterface;

final class <?php echo $class_name; ?> implements ProcessorInterface
{
    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        // Handle the state

        return null;
    }
}
