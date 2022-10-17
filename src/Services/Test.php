<?php

/*
 * This file is part of the jolicode/elastically library.
 *
 * (c) JoliCode <coucou@jolicode.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services;



final class Test
{
    private string $configurationDirectory;


    public function __construct(string $configurationDirectory)
    {
        $this->configurationDirectory = $configurationDirectory;
        var_dump($this->configurationDirectory);
    }

   
}
