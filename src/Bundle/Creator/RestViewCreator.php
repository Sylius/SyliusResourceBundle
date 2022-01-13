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

namespace Sylius\Bundle\ResourceBundle\Creator;

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

final class RestViewCreator implements RestViewCreatorInterface
{
    private ?ViewHandlerInterface $viewHandler;

    public function __construct(?ViewHandlerInterface $viewHandler = null)
    {
        $this->viewHandler = $viewHandler;
    }

    public function createRestView(RequestConfiguration $configuration, mixed $data, int $statusCode = null): Response
    {
        if (null === $this->viewHandler) {
            throw new \LogicException('You can not use the "non-html" request if FriendsOfSymfony Rest Bundle is not available. Try running "composer require friendsofsymfony/rest-bundle".');
        }

        return $this->viewHandler->handle($configuration, View::create($data, $statusCode));
    }
}
