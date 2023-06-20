<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use <?= $entity_namespace ?>\<?= $entity_class_name ?>;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @extends ServiceEntityRepository<<?= $entity_class_name ?>>
 *
 * @method <?= $entity_class_name ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?= $entity_class_name ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?= $entity_class_name ?>[]    findAll()
 * @method <?= $entity_class_name ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?= $class_name ?> extends ServiceEntityRepository implements RepositoryInterface
{
    use ResourceRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, <?= $entity_class_name ?>::class);
    }
}
