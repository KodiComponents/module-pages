<?php

namespace KodiCMS\Pages\Http\Controllers;

use Carbon\Carbon;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Repository\PageRepository;
use KodiCMS\Users\Model\User;
use Meta;

class PageController extends BackendController
{
    /**
     * @var array
     */
    public $allowedActions = ['children'];

    /**
     * @param PageRepository $repository
     */
    public function getIndex(PageRepository $repository)
    {
        Meta::loadPackage('nestable', 'editable');

        $this->templateScripts['PAGE_STATUSES'] = array_map(function ($value, $key) {
            return ['id' => $key, 'text' => $value];
        }, Page::getStatusList(), array_keys(Page::getStatusList()));

        /** @var Page $page */
        $page = $repository->getRootPage();

        $this->setContent('pages.index', compact('page'));
    }

    /**
     * @param PageRepository $repository
     * @param int            $id
     */
    public function getEdit(PageRepository $repository, $id)
    {
        Meta::loadPackage('backbone', 'jquery-ui');
        $this->includeModuleMediaFile('BehaviorController');

        /** @var Page $page */
        $page = $repository->findOrFail($id);

        foreach ($page->getBreadcrumbsChain() as $page) {
            $this->breadcrumbs->add($page->title, route('backend.page.edit', $page->id));
        }

        $this->templateScripts['PAGE'] = $page;

        /** @var User $updator */
        $updator = $page->updatedBy;

        /** @var User $creator */
        $creator = $page->createdBy;

        $page->setAppends(['layout']);
        $this->setContent('pages.edit', compact('page', 'updator', 'creator'));
    }

    /**
     * @param PageRepository $repository
     * @param int            $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(PageRepository $repository, $id)
    {
        $repository->validateOnUpdate($id, $this->request);

        /** @var Page $page */
        $page = $repository->update($id, $this->request->all());

        return $this->smartRedirect([$page])
            ->with('success', trans('pages::core.messages.updated', [
                'title' => $page->title,
            ]));
    }

    /**
     * @param PageRepository $repository
     * @param int|null       $parentId
     */
    public function getCreate(PageRepository $repository, $parentId = null)
    {
        /** @var Page $page */
        $page = $repository->instance([
            'parent_id' => $parentId,
            'published_at' => new Carbon,
            'status' => config('pages.default_status'),
        ]);

        $this->setTitle(trans('pages::core.title.pages.create'));
        $this->includeModuleMediaFile('BehaviorController');

        $this->setContent('pages.create', compact('page'));
    }

    /**
     * @param PageRepository $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(PageRepository $repository)
    {
        $repository->validateOnCreate($this->request);

        /** @var Page $page */
        $page = $repository->create($this->request->except(['widgets']));

        return $this->smartRedirect([$page])->with('success', trans('pages::core.messages.created', [
            'title' => $page->title,
        ]));
    }

    /**
     * @param PageRepository $repository
     * @param int            $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete(PageRepository $repository, $id)
    {
        /** @var Page $page */
        $page = $repository->delete($id);

        return $this->smartRedirect()->with('success', trans('pages::core.messages.deleted', [
            'title' => $page->title,
        ]));
    }
}
