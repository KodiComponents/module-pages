<?php

namespace KodiCMS\Pages\Listeners;

use Block;
use KodiCMS\Pages\Events\FrontPageFound;
use KodiCMS\Pages\PagePart;
use KodiCMS\Pages\Model\LayoutBlock;
use KodiCMS\Pages\Widget\PagePart as PagePartWidget;

class PlacePagePartsToBlocksEventHandler
{

    /**
     * @param FrontPageFound $event
     */
    public function handle(FrontPageFound $event)
    {
        $page = $event->getPage();

        $layoutBlocks = (new LayoutBlock)->getBlocksGroupedByLayouts($page->getLayout());

        foreach ($layoutBlocks as $name => $blocks) {
            foreach ($blocks as $block) {
                if (! ($part = PagePart::exists($page, $block))) {
                    continue;
                }

                $partWidget = new PagePartWidget(app('widget.manager'), $part['name']);
                $partWidget->setContent($part['content_html']);
                Block::addWidget($partWidget, $block);
            }
        }
    }
}
