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

namespace Sylius\Bundle\ResourceBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\Parameters;

final class ParametersTest extends TestCase
{
    private Parameters $parameters;

    protected function setUp(): void
    {
        $this->parameters = new Parameters();
    }

    public function testHasMutableParameters(): void
    {
        $this->parameters->replace();
        $this->assertSame([], $this->parameters->all());
    }

    public function testHasParameters(): void
    {
        $this->parameters->replace([
            'criteria' => 'criteria',
            'paginate' => 'paginate',
        ]);

        $this->assertSame([
            'criteria' => 'criteria',
            'paginate' => 'paginate',
        ], $this->parameters->all());
    }

    public function testGetsASingleParameterAndSupportsDefaultValue(): void
    {
        $this->parameters->replace([
            'criteria' => 'criteria',
            'paginate' => 'paginate',
        ]);

        $this->assertSame('criteria', $this->parameters->get('criteria'));
        $this->assertNull($this->parameters->get('sorting'));
        $this->assertSame('default', $this->parameters->get('sorting', 'default'));
    }
}
