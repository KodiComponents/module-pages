<?php

namespace KodiCMS\Pages\Contracts\Behavior;

use KodiCMS\Pages\Contracts\FrontendPageInterface;

interface BehaviorInterface
{
    /**
     * @return FrontendPageInterface
     */
    public function getPage();

    /**
     * @return array
     */
    public function routeList();

    /**
     * @return BehaviorRouterInterface
     */
    public function getRouter();

    /**
     * @param string $uri
     *
     * @return string
     */
    public function executeRoute($uri);

    /**
     * @return BehaviorSettingsInterface
     */
    public function getSettings();

    /**
     * @return string
     */
    public function getSettingsTemplate();
}
