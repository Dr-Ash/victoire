<?php
namespace Victoire\Bundle\PageBundle\Listener;

use Symfony\Component\EventDispatcher\Event;
use Victoire\Bundle\CoreBundle\Listener\MenuListenerInterface;
use Victoire\Bundle\CoreBundle\Menu\MenuBuilder;
use Victoire\Bundle\PageBundle\Entity\Template;
use Victoire\Bundle\PageBundle\Event\Menu\PageMenuContextualEvent;

/**
 */

/**
 * When dispatched, this listener add items to a KnpMenu
 */
class TemplateMenuListener implements MenuListenerInterface
{
    private $menuBuilder;

    /**
     * {@inheritDoc}
     */
    public function __construct(MenuBuilder $menuBuilder)
    {
        $this->menuBuilder = $menuBuilder;
    }

    /**
     * add a contextual menu item
     *
     * @param PageMenuContextualEvent $event
     *
     * @return Ambigous <\Knp\Menu\ItemInterface, NULL>
     */
    public function addContextual(PageMenuContextualEvent $event)
    {
        $page = $event->getPage();

        $mainItem = $this->getMainItem();

        //this contextual menu appears only for template
        if ($page instanceof Template) {

            $mainItem->addChild('menu.template.settings',
                array(
                    'route' => 'victoire_core_template_settings',
                    'routeParameters' => array('slug' => $page->getSlug())
                    )
            )->setLinkAttribute('data-toggle', 'vic-modal');
        }

        return $mainItem;
    }

    /**
     * add a global menu item
     *
     * @param Event $event
     *
     * @return Ambigous <\Knp\Menu\ItemInterface, NULL>
     */
    public function addGlobal(Event $event)
    {
        $mainItem = $this->getMainItem();
        $mainItem->addChild('menu.template.new', array(
            'route' => 'victoire_core_template_new'
            )
        )->setLinkAttribute('data-toggle', 'vic-modal');

        $mainItem->addChild('menu.template.index', array(
            'route' => 'victoire_core_template_index'
            )
        )->setLinkAttribute('data-toggle', 'vic-modal');

        return $mainItem;
    }

    private function getMainItem()
    {
        if ($menuTemplate = $this->menuBuilder->getTopNavbar()->getChild('menu.template')) {
            return $menuTemplate;
        } else {
            return $this->menuBuilder->createDropdownMenuItem(
                $this->menuBuilder->getTopNavbar(),
                "menu.template"
            );
        }
    }
}