<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use <?= $repository_namespace; ?>\<?= $repository_class_name; ?>;<?= "\n" ?>
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ORM\Entity(repositoryClass: <?= $repository_class_name; ?>::class)]
#[Resource]
class <?= $class_name ?> implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
