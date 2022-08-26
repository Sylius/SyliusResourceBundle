<?php

declare(strict_types=1);

namespace App\Entity\Operation;

use App\Entity\Book;
use Sylius\Component\Resource\Metadata\Create;

#[Create(vars: ['foo' => 'bar'], resource: 'app.book')]
final class CreateBookWithVars extends Book
{
}
