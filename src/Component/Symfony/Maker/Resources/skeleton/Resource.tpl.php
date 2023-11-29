<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Uid\AbstractUid;

#[Resource]
#[Index(
    // grid: <?= $class_name_without_suffix ?>Grid::class,
)]
#[Create(
    // processor: Create<?= $class_name_without_suffix ?>Processor::class,
)]
#[Update(
    // provider: <?= $class_name_without_suffix ?>ItemProvider::class,
    // processor: Update<?= $class_name_without_suffix ?>Processor::class,
)]
#[Delete(
    // provider: <?= $class_name_without_suffix ?>ItemProvider::class,
    // processor: Delete<?= $class_name_without_suffix ?>Processor::class,
)]
#[Show(
    // template: '<?= $show_template_dir ?>/show.html.twig',
    // provider: <?= $class_name_without_suffix ?>ItemProvider::class,
)]
final class <?= $class_name ?> implements ResourceInterface
{
<?php if (class_exists(Symfony\Component\Uid\AbstractUid::class)) : ?>
    public function __construct(
        public ?AbstractUid $id = null,
    ) {
    }
<?php endif; ?>

    public function getId(): ?string
    {
<?php if (class_exists(Symfony\Component\Uid\AbstractUid::class)) : ?>
        return $this->id?->__toString();
<?php else : ?>
        throw new \LogicException('TODO: Implement getId method');
<?php endif; ?>
    }
}
