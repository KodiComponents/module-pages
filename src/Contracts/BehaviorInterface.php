<?php

namespace KodiCMS\Pages\Contracts;

use KodiCMS\Pages\Model\FrontendPage;

interface BehaviorInterface
{
    /**
     * @return FrontendPage
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
