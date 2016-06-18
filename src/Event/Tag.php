<?php
/**
 * Phire Tags Module
 *
 * @link       https://github.com/phirecms/phire-tags
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Phire\Tags\Event;

use Phire\Tags\Model;
use Phire\Tags\Table;
use Pop\Application;
use Pop\Filter\Slug;
use Phire\Controller\AbstractController;

/**
 * Tag Event class
 *
 * @category   Phire\Tags
 * @package    Phire\Tags
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class Tag
{

    /**
     * Bootstrap the module
     *
     * @param  Application $application
     * @return void
     */
    public static function bootstrap(Application $application)
    {
        $forms = $application->config()['forms'];

        if (isset($forms['Phire\Content\Form\Content'])) {
            $forms['Phire\Content\Form\Content'][0]['content_tags'] = [
                'type'       => 'text',
                'label'      => 'Tags',
                'attributes' => [
                    'size'  => 15,
                    'class' => 'tags-field'
                ]
            ];

            $application->mergeConfig(['forms' => $forms], true);
        }
    }

    /**
     * Set the tag template
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function setTemplate(AbstractController $controller, Application $application)
    {
        if ($application->isRegistered('phire-templates') && ($controller instanceof \Phire\Content\Controller\IndexController) &&
            ($controller->hasView()) && $controller->view()->isStream()) {
            if (null !== $controller->view()->tag_title) {
                $template = \Phire\Templates\Table\Templates::findBy(['name' => 'Tag ' . $controller->view()->tag_title]);
                if (!isset($template->id)) {
                    $template = \Phire\Templates\Table\Templates::findBy(['name' => 'Tag']);
                }
            } else {
                $template = \Phire\Templates\Table\Templates::findBy(['name' => 'Tag']);
            }

            if (isset($template->id)) {
                if (isset($template->id)) {
                    $device = \Phire\Templates\Event\Template::getDevice($controller->request()->getQuery('mobile'));
                    if ((null !== $device) && ($template->device != $device)) {
                        $childTemplate = \Phire\Templates\Table\Templates::findBy(['parent_id' => $template->id, 'device' => $device]);
                        if (isset($childTemplate->id)) {
                            $tmpl = $childTemplate->template;
                        } else {
                            $tmpl = $template->template;
                        }
                    } else {
                        $tmpl = $template->template;
                    }
                    $controller->view()->setTemplate(\Phire\Templates\Event\Template::parse($tmpl));
                }
            }
        } else if ($application->isRegistered('phire-themes') && ($controller instanceof \Phire\Content\Controller\IndexController) &&
            ($controller->hasView()) && $controller->view()->isFile()) {
            $theme = \Phire\Themes\Table\Themes::findBy(['active' => 1]);
            if (isset($theme->id)) {
                $template  = null;
                $themePath = $_SERVER['DOCUMENT_ROOT'] . BASE_PATH . CONTENT_PATH . '/themes/' . $theme->folder . '/';

                if (null !== $controller->view()->tag_slug) {
                    $tagSlug = 'tag-' . str_replace('/', '-', $controller->view()->tag_slug);
                    if (file_exists($themePath . $tagSlug . '.phtml') || file_exists($themePath . $tagSlug . '.php')) {
                        $template = file_exists($themePath . $tagSlug . '.phtml') ? $tagSlug . '.phtml' : $tagSlug . '.php';
                    } else if (file_exists($themePath . 'tag.phtml') || file_exists($themePath . 'tag.php')) {
                        $template = file_exists($themePath . 'tag.phtml') ? 'tag.phtml' : 'tag.php';
                    }
                } else if (file_exists($themePath . 'tag.phtml') || file_exists($themePath . 'tag.php')) {
                    $template = file_exists($themePath . 'tag.phtml') ? 'tag.phtml' : 'tag.php';
                }

                if (null !== $template) {
                    $device = \Phire\Themes\Event\Theme::getDevice($controller->request()->getQuery('mobile'));
                    if ((null !== $device) && (file_exists($themePath . $device . '/' . $template))) {
                        $template = $device . '/' . $template;
                    }
                    $controller->view()->setTemplate($themePath . $template);
                }
            }
        }
    }

    /**
     * Init tag model and tag cloud
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function init(AbstractController $controller, Application $application)
    {
        if ((!$_POST) && ($controller->hasView()) && ($controller instanceof \Phire\Content\Controller\IndexController)) {
            $sql = Table\TagItems::sql();
            $sql->select([
                0       => 'tag_id',
                1       => 'id',
                2       => 'title',
                3       => 'slug',
                'count' => 'COUNT(1)'
            ])->join(DB_PREFIX . 'tags', [DB_PREFIX . 'tags.id' => DB_PREFIX . 'tag_items.tag_id']);

            $sql->select()->groupBy('tag_id')->orderBy('count', 'DESC');

            $tags  = Table\TagItems::query($sql);
            $cloud = null;
            $max   = 0;
            if ($tags->hasRows()) {
                foreach ($tags->rows() as $i => $tag) {
                    if ($i == 0) {
                        $max = $tag->count;
                    }
                    $weight = (round(($tag->count / $max) * 100));
                    if ($weight < 10) {
                        $weight = 1;
                    } else {
                        $weight = $weight - ($weight % 10);
                    }
                    $cloud  .= '<a class="tag-link tag-weight-' . $weight . '" href="' . BASE_PATH . '/tag/' . $tag->slug . '">' . $tag->title . '</a>' . PHP_EOL;
                }
            }

            $tag = new Model\Tag();
            $tag->filters = $application->module('phire-tags')['filters'];

            $controller->view()->tag_cloud  = $cloud;
            $controller->view()->phire->tag = $tag;
        }
    }

    /**
     * Get all tag values for the form object
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function getAll(AbstractController $controller, Application $application)
    {
        if ((!$_POST) && ($controller->hasView()) && (null !== $controller->view()->form) && ($controller->view()->form !== false) &&
            ((int)$controller->view()->form->id != 0) && (null !== $controller->view()->form) &&
            ($controller->view()->form instanceof \Phire\Content\Form\Content)) {
            $contentId  = $controller->view()->form->id;
            $tags       = [];

            if (null !== $contentId) {
                $sql = Table\TagItems::sql();
                $sql->select()->join(
                    DB_PREFIX . 'tags', [DB_PREFIX . 'tags.id' => DB_PREFIX . 'tag_items.tag_id']
                );
                $sql->select()->where('content_id = :content_id');
                $c2t = Table\TagItems::execute($sql, ['content_id' => $contentId]);
                if ($c2t->hasRows()) {
                    foreach ($c2t->rows() as $c) {
                        $tags[] = $c->title;
                    }
                }
                if (count($tags) > 0) {
                    $controller->view()->form->content_tags = implode(',', $tags);
                }
            }
        }
    }

    /**
     * Save tag relationships
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function save(AbstractController $controller, Application $application)
    {
        $contentId = null;

        if (($_POST) && ($controller->hasView()) && (null !== $controller->view()->id) &&
            (null !== $controller->view()->form) && ($controller->view()->form !== false) && ($controller->view()->form instanceof \Pop\Form\Form)) {
            $tags      = $controller->view()->form->content_tags;
            $contentId = $controller->view()->id;

            if (null !== $contentId) {
                $c2t = new Table\TagItems();
                $c2t->delete(['content_id' => $contentId]);
            }

            if (!empty($tags)) {
                $tags = explode(',', $tags);
                foreach ($tags as $tag) {
                    $tag = trim($tag);
                    $t   = Table\Tags::findBy(['title' => $tag]);
                    if (!isset($t->id)) {
                        $t = new Table\Tags([
                            'title' => $tag,
                            'slug'  => Slug::filter($tag)
                        ]);
                        $t->save();
                    }
                    $c2t = new Table\TagItems([
                        'content_id' => $contentId,
                        'tag_id'     => $t->id
                    ]);
                    $c2t->save();
                }
            }
        }
    }

    /**
     * Delete tag relationships
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function delete(AbstractController $controller, Application $application)
    {
        if (($_POST) && isset($_POST['process_content']) && isset($_POST['content_process_action']) && ($_POST['content_process_action'] == -3)) {
            foreach ($_POST['process_content'] as $id) {
                $c2t = new Table\TagItems();
                $c2t->delete(['content_id' => (int)$id]);
            }
        }
    }

}
