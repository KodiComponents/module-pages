<?php

namespace KodiCMS\Pages\Http\Controllers\System;

use CMS;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use KodiCMS\CMS\Http\Controllers\System\Controller;
use KodiCMS\Pages\Exceptions\LayoutNotFoundException;
use KodiCMS\Pages\Helpers\Block;
use KodiCMS\Pages\Model\LayoutCollection;
use KodiCMS\Widgets\Collection\WidgetCollection;

abstract class FrontPageController extends Controller
{
    /**
     * @var WidgetCollection;
     */
    protected $widgetCollection;

    /**
     * Execute before an action executed
     * return void.
     */
    public function before()
    {
        $this->widgetCollection = $collection = new WidgetCollection;

        app()->singleton('layout.block', function ($app) use ($collection) {
            return new Block($collection);
        });
    }

    /**
     * @param string $layout
     *
     * @return View
     * @throws LayoutNotFoundException
     */
    protected function getLayoutFile($layout)
    {
        if (is_null($layout = (new LayoutCollection)->findFile($layout))) {
            throw new LayoutNotFoundException(trans('pages::core.messages.layout_not_set'));
        }

        return $layout->toView();
    }

    /**
     * @param View   $layout
     * @param string $mime
     *
     * @return \Illuminate\View\View|null
     * @throws LayoutNotFoundException
     */
    protected function render(View $layout = null, $mime = 'text\html')
    {
        if (is_null($layout)) {
            throw new LayoutNotFoundException(trans('pages::core.messages.layout_not_set'));
        }

        $html = $layout->render();

        $response = new Response();

        $response->header('Content-Type', $mime);

        if (config('cms.show_response_sign', true)) {
            $response->header('X-Powered-CMS', CMS::getFullName());
        }

        $response->setContent($html);

        // Set the ETag header
        $response->setEtag(md5($html));

        // mark the response as either public or private
        $response->setPublic();

        // Check that the Response is not modified for the given Request
        if ($response->isNotModified($this->request)) {
            // return the 304 Response immediately
            return $response;
        }

        return $response;
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $this->before();

        $response = call_user_func_array([$this, $method], $parameters);

        if ($method != 'run') {
            $this->after($response);
        }

        return $response;
    }
}
