<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Tests\Symfony\Maker;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class MakeResourceTest extends MakerTestCase
{
    private const BOOK_RESOURCE_PATH = 'Sylius/Resource/BookResource.php';

    /** @test */
    public function it_can_create_resources(): void
    {
        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:resource'));

        $this->assertFileDoesNotExist(self::tempFile(self::BOOK_RESOURCE_PATH));

        $tester->execute(['class' => 'BookResource', '--namespace' => 'Tests\Tmp\Sylius\Resource']);

        $this->assertFileExists(self::tempFile(self::BOOK_RESOURCE_PATH));
        $this->assertSame(self::getBookResourceExpectedContent(), \file_get_contents(self::tempFile(self::BOOK_RESOURCE_PATH)));
    }

    private static function getBookResourceExpectedContent(): string
    {
        return <<<EOF
            <?php
            
            namespace App\Tests\Tmp\Sylius\Resource;
            
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
                // grid: BookGrid::class,
            )]
            #[Create(
                // processor: CreateBookProcessor::class,
            )]
            #[Update(
                // provider: BookItemProvider::class,
                // processor: UpdateBookProcessor::class,
            )]
            #[Delete(
                // provider: BookItemProvider::class,
                // processor: DeleteBookProcessor::class,
            )]
            #[Show(
                // template: 'book/show.html.twig',
                // provider: BookItemProvider::class,
            )]
            final class BookResource implements ResourceInterface
            {
                public function __construct(
                    public ?AbstractUid \$id = null,
                ) {
                }
            
                public function getId(): ?string
                {
                    return \$this->id?->__toString();
                }
            }
            
            EOF
        ;
    }
}
