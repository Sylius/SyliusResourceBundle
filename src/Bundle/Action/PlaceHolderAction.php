<?php

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Action;

final class PlaceHolderAction
{
    public function __invoke(mixed $data): mixed
    {
        return $data;
    }
}
