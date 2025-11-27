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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelper;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FlashHelperTest extends TestCase
{
    private FlashHelper $flashHelper;

    private Session $session;

    /** @var TranslatorInterface&MockObject */
    private TranslatorInterface $translator;

    protected function setUp(): void
    {
        $this->session = new Session(new MockArraySessionStorage());
        $this->session->registerBag(new FlashBag());

        $requestStack = new RequestStack();
        $request = new Request();
        $request->setSession($this->session);
        $requestStack->push($request);

        $this->translator = $this->createMock(TranslatorInterface::class);

        $this->flashHelper = new FlashHelper($requestStack, $this->translator, 'en_US');
    }

    public function testAddsSuccessFlashWithTranslatedResourceNameForSingleResource(): void
    {
        $this->configureTranslator(['sylius.ui.product' => 'Product']);
        $requestConfiguration = $this->createRequestConfiguration('product', 'sylius.resource.create');

        $this->flashHelper->addSuccessFlash($requestConfiguration, 'create');

        $this->assertFlashMessage('success', 'sylius.resource.create', ['%resource%' => 'Product']);
    }

    public function testAddsSuccessFlashWithTranslatedPluralResourceNameForBulkAction(): void
    {
        $this->configureTranslator(['sylius.ui.products' => 'Products']);
        $requestConfiguration = $this->createRequestConfiguration('product', 'sylius.resource.bulk_delete', 'products');

        $this->flashHelper->addSuccessFlash($requestConfiguration, 'bulk_delete');

        $this->assertFlashMessage('success', 'sylius.resource.bulk_delete', ['%resources%' => 'Products']);
    }

    public function testConvertsCamelCaseToSnakeCaseForResourceNames(): void
    {
        $this->configureTranslator(['sylius.ui.product_variant' => 'Product Variant']);
        $requestConfiguration = $this->createRequestConfiguration('productVariant', 'sylius.resource.update');

        $this->flashHelper->addSuccessFlash($requestConfiguration, 'update');

        $this->assertFlashMessage('success', 'sylius.resource.update', ['%resource%' => 'Product Variant']);
    }

    public function testConvertsCamelCaseToSnakeCaseForPluralResourceNames(): void
    {
        $this->configureTranslator(['sylius.ui.product_variants' => 'Product Variants']);
        $requestConfiguration = $this->createRequestConfiguration('productVariant', 'sylius.resource.bulk_update', 'productVariants');

        $this->flashHelper->addSuccessFlash($requestConfiguration, 'bulk_update');

        $this->assertFlashMessage('success', 'sylius.resource.bulk_update', ['%resources%' => 'Product Variants']);
    }

    public function testAddsErrorFlashWithTranslatedResourceName(): void
    {
        $this->configureTranslator(['sylius.ui.customer' => 'Customer']);
        $requestConfiguration = $this->createRequestConfiguration('customer', 'sylius.resource.delete');

        $this->flashHelper->addErrorFlash($requestConfiguration, 'delete');

        $this->assertFlashMessage('error', 'sylius.resource.delete', ['%resource%' => 'Customer']);
    }

    public function testDoesNotAddFlashWhenMessageIsEmpty(): void
    {
        $this->configureTranslator(['sylius.ui.product' => 'Product']);
        $requestConfiguration = $this->createRequestConfiguration('product', '');

        $this->flashHelper->addSuccessFlash($requestConfiguration, 'create');

        $this->assertFlashCount('success', 0);
    }

    public function testTranslatesResourceNameToGerman(): void
    {
        $this->configureTranslator(['sylius.ui.product' => 'Produkt']);
        $requestConfiguration = $this->createRequestConfiguration('product', 'sylius.resource.create');

        $this->flashHelper->addSuccessFlash($requestConfiguration, 'create');

        $this->assertFlashMessage('success', 'sylius.resource.create', ['%resource%' => 'Produkt']);
    }

    public function testTranslatesPluralResourceNameToGerman(): void
    {
        $this->configureTranslator(['sylius.ui.products' => 'Produkte']);
        $requestConfiguration = $this->createRequestConfiguration('product', 'sylius.resource.bulk_delete', 'products');

        $this->flashHelper->addSuccessFlash($requestConfiguration, 'bulk_delete');

        $this->assertFlashMessage('success', 'sylius.resource.bulk_delete', ['%resources%' => 'Produkte']);
    }

    public function testTranslatesResourceNameToGermanForUpdate(): void
    {
        $this->configureTranslator(['sylius.ui.customer' => 'Kunde']);
        $requestConfiguration = $this->createRequestConfiguration('customer', 'sylius.resource.update');

        $this->flashHelper->addSuccessFlash($requestConfiguration, 'update');

        $this->assertFlashMessage('success', 'sylius.resource.update', ['%resource%' => 'Kunde']);
    }

    private function createRequestConfiguration(string $resourceName, string $flashMessage, ?string $pluralName = null): RequestConfiguration
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $metadata->method('getName')->willReturn($resourceName);
        $metadata->method('getApplicationName')->willReturn('app');

        if ($pluralName !== null) {
            $metadata->method('getPluralName')->willReturn($pluralName);
        }

        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $requestConfiguration->method('getMetadata')->willReturn($metadata);
        $requestConfiguration->method('getFlashMessage')->willReturn($flashMessage);

        return $requestConfiguration;
    }

    /**
     * @param array<string, string> $translations
     */
    private function configureTranslator(array $translations): void
    {
        $this->translator
            ->method('trans')
            ->willReturnCallback(function (string $id, array $parameters, string $domain) use ($translations) {
                if ($domain === 'messages' && isset($translations[$id])) {
                    return $translations[$id];
                }

                return $id;
            });
    }

    /**
     * @param array<string, string> $expectedParameters
     */
    private function assertFlashMessage(string $type, string $expectedMessage, array $expectedParameters): void
    {
        $flashes = $this->session->getFlashBag()->get($type);
        $this->assertCount(1, $flashes);
        $this->assertSame($expectedMessage, $flashes[0]['message']);
        $this->assertSame($expectedParameters, $flashes[0]['parameters']);
    }

    private function assertFlashCount(string $type, int $expectedCount): void
    {
        $flashes = $this->session->getFlashBag()->get($type);
        $this->assertCount($expectedCount, $flashes);
    }
}
