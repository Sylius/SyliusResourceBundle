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

namespace App\Tests\Trait;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Form;

trait UiTestTrait
{
    protected function submitForm(string $buttonLabel, array $fields = []): void
    {
        $crawler = $this->getBrowser()->getCrawler();

        $button = $crawler->selectButton($buttonLabel);

        /** @var Form $form */
        $form = $button->form();

        foreach ($fields as $name => $value) {
            if ($value === null) {
                $value = '';
            }
            $form[$name] = $value;
        }

        $this->getBrowser()->submit($form);
    }

    protected function getBrowser(): KernelBrowser
    {
        if (!isset($this->client) || !$this->client instanceof KernelBrowser) {
            throw new \RuntimeException('The test class must have a "$client" property initialized via static::createClient()');
        }

        return $this->client;
    }
}
