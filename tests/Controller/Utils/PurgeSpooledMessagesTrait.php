<?php

declare(strict_types=1);

namespace Tests\Sylius\ShopApiPlugin\Controller\Utils;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

trait PurgeSpooledMessagesTrait
{
    abstract protected function getContainer(): ContainerInterface;

    /**
     * @before
     */
    public function purgeSpooledMessages(): void
    {
        $emailChecker = $this->getContainer()->get('sylius.behat.email_checker');

        /** @var Filesystem $filesystem */
        $filesystem = $this->getContainer()->get('filesystem');

        $filesystem->remove($emailChecker->getSpoolDirectory());
    }
}
