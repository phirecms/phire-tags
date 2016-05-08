<?php

namespace Phire\Tags\Controller;

use Phire\Tags\Model;
use Phire\Tags\Table;
use Phire\Controller\AbstractController;
use Pop\Paginator\Paginator;

class IndexController extends AbstractController
{

    /**
     * Current template reference
     * @var mixed
     */
    protected $template = null;

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        $slug = substr($this->request->getRequestUri(), 5);

        if ($slug != '') {
            $tag = new Model\Tag();
            $tag->getBySlug($slug, $this->application->modules());

            if (isset($tag->id)) {
                if (count($tag->items) > $this->config->pagination) {
                    $page  = $this->request->getQuery('page');
                    $limit = $this->config->pagination;
                    $pages = new Paginator(count($tag->items), $limit);
                    $pages->useInput(true);
                    $offset = ((null !== $page) && ((int)$page > 1)) ?
                        ($page * $limit) - $limit : 0;
                    $tag->items = array_slice($tag->items, $offset, $limit, true);
                } else {
                    $pages = null;
                }

                $this->prepareView('tags-public/tag.phtml');
                $this->template        = 'tag.phtml';
                $this->view->title     = 'Tag : ' . $tag->title;
                $this->view->tag_id    = $tag->id;
                $this->view->tag_title = $tag->title;
                $this->view->tag_slug  = $tag->slug;

                $this->view->pages     = $pages;
                $this->view->merge($tag->toArray());
                $this->send();
            } else {
                $this->error();
            }
        } else {
            $this->redirect(((BASE_PATH == '') ? '/' : BASE_PATH));
        }
    }

    /**
     * Error action method
     *
     * @return void
     */
    public function error()
    {
        $this->prepareView('tags-public/error.phtml');
        $this->view->title = '404 Error';
        $this->template    = -1;
        $this->send(404);
    }

    /**
     * Get current template
     *
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set current template
     *
     * @param  string $template
     * @return IndexController
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Prepare view
     *
     * @param  string $category
     * @return void
     */
    protected function prepareView($category)
    {
        $this->viewPath = __DIR__ . '/../../view';
        parent::prepareView($category);
    }

}
