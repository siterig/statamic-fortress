<?php

namespace Siterig\Fortress\Tests;

use Siterig\Fortress\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
