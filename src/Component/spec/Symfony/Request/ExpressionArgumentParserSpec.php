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

namespace spec\Sylius\Component\Resource\Symfony\Request;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Symfony\Request\ExpressionArgumentParser;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class ExpressionArgumentParserSpec extends ObjectBehavior
{
    public function let(
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
    ): void {
        $this->beConstructedWith($requestStack, new ExpressionLanguage(), $tokenStorage);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ExpressionArgumentParser::class);
    }

    function it_parses_an_attributes_from_request(
        RequestStack $requestStack,
        Request $request,
    ): void {
        $requestStack->getCurrentRequest()->willReturn($request);
        $request->attributes = new ParameterBag(['id' => '42']);

        $this->parseExpression("request.attributes.get('id')")->shouldReturn('42');
    }

    function it_parses_a_route_parameter(
        RequestStack $requestStack,
        Request $request,
    ): void {
        $requestStack->getCurrentRequest()->willReturn($request);
        $request->attributes = new ParameterBag(['_route_params' => ['id' => '42']]);

        $this->parseExpression("params['id']")->shouldReturn('42');
    }

    function it_parses_a_user_attribute(
        RequestStack $requestStack,
        Request $request,
        TokenStorageInterface $tokenStorage,
        TokenInterface $token,
        CompanyUserInterface $companyUser,
        CompanyInterface $company,
    ): void {
        $tokenStorage->getToken()->willReturn($token);

        $token->getUser()->willReturn($companyUser);

        $companyUser->getCompany()->willReturn($company);

        $company->getId()->willReturn(42);

        $this->parseExpression('user.getCompany().getId()')->shouldReturn(42);
    }
}

interface CompanyUserInterface extends UserInterface
{
    public function getCompany(): CompanyInterface;
}

interface CompanyInterface extends UserInterface
{
    public function getId(): int;
}
