<?php

namespace KodiCMS\Pages\Contracts;

interface BehaviorRouterInterface
{
    /**
     * @return array
     */
    public function getRoutes();

    /**
     * @return string
     */
    public function getMatchedRoute();

    /**
     * @return null|string
     */
    public function getUri();

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return string|null
     */
    public function getParameter($name, $default = null);

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @return string
     */
    public function getDefaultMethod();

    /**
     * @param $uri
     *
     * @return string
     */
    public function findRouteByUri($uri);
    
    
}
