<?php

declare(strict_types=1);

/*
 * This file is part of [package name].
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
 */

namespace MargretSchroeder\ContaoStammBundle\Tests;

use MargretSchroeder\ContaoStammBundle\ContaoStammBundle;
use PHPUnit\Framework\TestCase;

class ContaoStammBundleTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $bundle = new ContaoSkeletonBundle();

        $this->assertInstanceOf('MargretSchroeder\ContaoStammBundle\ContaoStammBundle', $bundle);
    }
}
