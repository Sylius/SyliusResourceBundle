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

namespace Sylius\Bundle\ResourceBundle\Tests\Form\Extension\HttpFoundation;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\Form\Util\ServerParams;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

final class HttpFoundationRequestHandlerTest extends TestCase
{
    private HttpFoundationRequestHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new HttpFoundationRequestHandler();
    }

    public function testImplementsRequestHandlerInterface(): void
    {
        self::assertInstanceOf(RequestHandlerInterface::class, $this->handler);
    }

    public function testThrowsExceptionWhenRequestIsNotHttpFoundationRequest(): void
    {
        $form = $this->createMock(FormInterface::class);

        self::expectException(UnexpectedTypeException::class);

        $this->handler->handleRequest($form, new \stdClass());
    }

    public function testSubmitsGetRequestWithEmptyFormName(): void
    {
        $form = $this->createFormMock('');
        $request = Request::create('/', 'GET', ['foo' => 'bar']);

        $form
            ->expects(self::once())
            ->method('submit')
            ->with(['foo' => 'bar'], true);

        $this->handler->handleRequest($form, $request);
    }

    public function testDoesNotSubmitGetRequestWhenFormNameNotInQuery(): void
    {
        $form = $this->createFormMock('user');
        $request = Request::create('/', 'GET', ['foo' => 'bar']);

        $form
            ->expects(self::never())
            ->method('submit');

        $this->handler->handleRequest($form, $request);
    }

    public function testSubmitsGetRequestWithFormNameInQuery(): void
    {
        $form = $this->createFormMock('user');
        $request = Request::create('/', 'GET', ['user' => ['name' => 'John']]);

        $form
            ->expects(self::once())
            ->method('submit')
            ->with(['name' => 'John'], true);

        $this->handler->handleRequest($form, $request);
    }

    public function testSubmitsPostRequestWithEmptyFormName(): void
    {
        $form = $this->createFormMock('');
        $request = Request::create('/', 'POST', ['foo' => 'bar']);

        $form
            ->expects(self::once())
            ->method('submit')
            ->with(['foo' => 'bar'], true);

        $this->handler->handleRequest($form, $request);
    }

    public function testDoesNotSubmitPostRequestWhenFormNameNotInRequest(): void
    {
        $form = $this->createFormMock('user');
        $request = Request::create('/', 'POST', ['foo' => 'bar']);

        $form
            ->expects(self::never())
            ->method('submit');

        $this->handler->handleRequest($form, $request);
    }

    public function testSubmitsPostRequestWithFormNameInRequest(): void
    {
        $form = $this->createFormMock('user');
        $request = Request::create('/', 'POST', ['user' => ['name' => 'John']]);

        $form
            ->expects(self::once())
            ->method('submit')
            ->with(['name' => 'John'], true);

        $this->handler->handleRequest($form, $request);
    }

    public function testSubmitsPatchRequestWithoutClearingMissingFields(): void
    {
        $form = $this->createFormMock('user');
        $request = Request::create('/', 'PATCH', ['user' => ['name' => 'John']]);

        $form
            ->expects(self::once())
            ->method('submit')
            ->with(['name' => 'John'], false);

        $this->handler->handleRequest($form, $request);
    }

    public function testMergesFilesWithPostData(): void
    {
        $form = $this->createFormMock('user');
        $file = new UploadedFile(__FILE__, 'test.php', null, null, true);

        $request = Request::create('/', 'POST', ['user' => ['name' => 'John']], [], ['user' => ['avatar' => $file]]);

        $form
            ->expects(self::once())
            ->method('submit')
            ->with(['name' => 'John', 'avatar' => $file], true);

        $this->handler->handleRequest($form, $request);
    }

    public function testHandlesPostMaxSizeExceeded(): void
    {
        $serverParams = $this->createMock(ServerParams::class);
        $serverParams
            ->expects(self::once())
            ->method('hasPostMaxSizeBeenExceeded')
            ->willReturn(true);

        $serverParams
            ->expects(self::once())
            ->method('getNormalizedIniPostMaxSize')
            ->willReturn('8M');

        $handler = new HttpFoundationRequestHandler($serverParams);

        $config = $this->createMock(FormConfigInterface::class);
        $config
            ->expects(self::once())
            ->method('getOption')
            ->with('upload_max_size_message')
            ->willReturn(fn (): string => 'Upload size exceeded');

        $form = $this->createMock(FormInterface::class);
        $form->method('getName')->willReturn('user');
        $form->method('getConfig')->willReturn($config);

        $form
            ->expects(self::once())
            ->method('submit')
            ->with(null, false);

        $form
            ->expects(self::once())
            ->method('addError')
            ->with(self::callback(function ($error): bool {
                return $error->getMessage() === 'Upload size exceeded';
            }));

        $request = Request::create('/', 'POST', ['user' => ['name' => 'John']]);

        $handler->handleRequest($form, $request);
    }

    public function testIsFileUploadReturnsTrueForFileInstance(): void
    {
        $file = $this->createMock(File::class);

        self::assertTrue($this->handler->isFileUpload($file));
    }

    public function testIsFileUploadReturnsFalseForNonFileInstance(): void
    {
        self::assertFalse($this->handler->isFileUpload('string'));
        self::assertFalse($this->handler->isFileUpload(123));
        self::assertFalse($this->handler->isFileUpload([]));
        self::assertFalse($this->handler->isFileUpload(null));
    }

    public function testHandlesHeadRequest(): void
    {
        $form = $this->createFormMock('user');
        $request = Request::create('/', 'HEAD', ['user' => ['name' => 'John']]);

        $form
            ->expects(self::once())
            ->method('submit')
            ->with(['name' => 'John'], true);

        $this->handler->handleRequest($form, $request);
    }

    public function testHandlesTraceRequest(): void
    {
        $form = $this->createFormMock('user');
        $request = Request::create('/', 'TRACE', ['user' => ['name' => 'John']]);

        $form
            ->expects(self::once())
            ->method('submit')
            ->with(['name' => 'John'], true);

        $this->handler->handleRequest($form, $request);
    }

    /** @return FormInterface&MockObject */
    private function createFormMock(string $name): FormInterface
    {
        $config = $this->createMock(FormConfigInterface::class);
        $config->method('getCompound')->willReturn(true);

        $form = $this->createMock(FormInterface::class);
        $form->method('getName')->willReturn($name);
        $form->method('getConfig')->willReturn($config);

        return $form;
    }
}
