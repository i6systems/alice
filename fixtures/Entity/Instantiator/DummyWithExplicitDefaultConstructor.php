<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nelmio\Alice\Entity\Instantiator;

class DummyWithExplicitDefaultConstructor
{
<<<<<<< HEAD:tests/Nelmio/Alice/Instances/Populator/Fixtures/Direct/ProtectedDummy.php
    /** @var string */
    public $name;

    protected function setName($name)
=======
    public function __construct()
>>>>>>> master:fixtures/Entity/Instantiator/DummyWithExplicitDefaultConstructor.php
    {
        $this->constructor = true;
    }
}
