<?php

namespace KodiCMS\Pages\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use KodiCMS\CMS\Contracts\SettingsInterface;

interface BehaviorSettingsInterface extends Arrayable, SettingsInterface
{
    /**
     * @return string|null
     */
    public function render();
}
