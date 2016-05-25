<?php

namespace KodiCMS\Pages\Exceptions;

use Illuminate\Http\Response;
use KodiCMS\CMS\Exceptions\Exception;
use KodiCMS\Pages\Http\Controllers\FrontendController;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Support\Helpers\Mime;
use Request;

class PageNotFoundException extends Exception
{

    /**
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if (config('app.debug')) {
            return;
        }

        $ext      = pathinfo(Request::getUri(), PATHINFO_EXTENSION);
        $mimeType = null;

        if (empty($ext) or ($ext and ! ($mimeType = Mime::byExt($ext)))) {
            $mimeType = 'text/html';
        }

        if ($mimeType and $mimeType != 'text/html') {
            $response = new Response();
            $this->sendResponse($response, $mimeType);
        } elseif (! is_null($page = FrontendPage::findByField('behavior', 'page.not.found'))) {

            /** @var FrontendController $controller */
            $controller = app()->make(FrontendController::class);

            $response = app()->call([$controller, 'run'], [$page->getUri()]);
            $this->sendResponse($response, $mimeType);
        }
    }

    /**
     * @param Response $response
     * @param string $mimeType
     */
    protected function sendResponse(Response $response, $mimeType)
    {
        if (empty($mimeType)) {
            $mimeType = 'text/html';
        }

        $response->header('Content-type', $mimeType);
        $response->setStatusCode(404);
        $response->send();
        
        exit();
    }
}
