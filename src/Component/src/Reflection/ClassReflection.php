<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Resource\Reflection;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use Symfony\Component\Finder\Finder;

final class ClassReflection
{
    /**
     * @return \Generator<class-string>
     */
    public static function getResourcesByPaths(array $paths): iterable
    {
        foreach ($paths as $resourceDirectory) {
            $resources = self::getResourcesByPath($resourceDirectory);

            foreach ($resources as $className) {
                yield $className;
            }
        }
    }

    public static function getResourcesByPath(string $path): iterable
    {
        $finder = new Finder();
        $finder->files()->in($path)->name('*.php');

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

        foreach ($finder as $file) {
            $code = file_get_contents($file->getRealPath());
            if ($code === false) {
                continue; // unreadable file
            }

            try {
                $ast = $parser->parse($code);
            } catch (Error) {
                continue; // invalid PHP
            }

            if ($ast === null) {
                continue;
            }

            $traverser = new NodeTraverser();
            $visitor = new class extends NodeVisitorAbstract {
                public string $namespace = '';
                public array $found = [];

                public function enterNode(Node $node): ?int
                {
                    if ($node instanceof Node\Stmt\Namespace_) {
                        $this->namespace = $node->name ? $node->name->toString() : '';
                    }

                    if ($node instanceof Node\Stmt\Class_) {
                        if ($node->name === null) {
                            // skip anonymous class
                            return null;
                        }

                        $name = $node->name->toString();
                        $fqcn = $this->namespace ? $this->namespace . '\\' . $name : $name;
                        $this->found[] = $fqcn;
                    }

                    return null;
                }
            };

            $traverser->addVisitor($visitor);
            $traverser->traverse($ast);

            foreach ($visitor->found as $fqcn) {
                yield $fqcn; // yield immediately to save memory
            }

            unset($ast, $visitor, $traverser); // free memory
        }
    }

    /**
     * @psalm-param class-string $className
     *
     * @return \ReflectionAttribute[]
     */
    public static function getClassAttributes(string $className, ?string $attributeName = null): array
    {
        return (new \ReflectionClass($className))->getAttributes($attributeName);
    }
}

if (!class_exists(\Sylius\Component\Resource\Reflection\ClassReflection::class, false)) {
    class_alias(ClassReflection::class, \Sylius\Component\Resource\Reflection\ClassReflection::class);
}
