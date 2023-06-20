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

namespace App\Tests\Controller;

use ApiTestCase\XmlApiTestCase as BaseXmlApiTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class XmlApiTestCase extends BaseXmlApiTestCase
{
    protected static array $headersWithContentType = [
        'CONTENT_TYPE' => 'application/xml',
        'HTTP_ACCEPT' => 'application/xml',
    ];

    protected function assertXmlHeader(Response $response): void
    {
        parent::assertHeader($response, 'xml');
    }
}
