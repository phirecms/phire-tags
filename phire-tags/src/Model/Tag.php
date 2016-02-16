<?php

namespace Phire\Tags\Model;

use Phire\Tags\Table;
use Phire\Model\AbstractModel;
use Pop\Filter\Slug;

class Tag extends AbstractModel
{

    /**
     * Get all tags
     *
     * @param  int    $limit
     * @param  int    $page
     * @param  string $sort
     * @return array
     */
    public function getAll($limit = null, $page = null, $sort = null)
    {
        $order = $this->getSortOrder($sort, $page);

        $sql = Table\ContentToTags::sql();
        $sql->select([
            0       => 'tag_id',
            1       => 'id',
            2       => 'title',
            3       => 'slug',
            'count' => 'COUNT(1)'
        ])->join(DB_PREFIX . 'tags', [DB_PREFIX . 'tags.id' => DB_PREFIX . 'content_to_tags.tag_id']);

        $sql->select()->groupBy('tag_id');

        $orderAry = explode(' ', $order);
        $sql->select()->orderBy($orderAry[0], $orderAry[1]);

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            $sql->select()->offset($page);
            $sql->select()->limit($limit);

            return Table\ContentToTags::query($sql)->rows();
        } else {
            return Table\ContentToTags::query($sql)->rows();
        }
    }

    /**
     * Get tag by ID
     *
     * @param  int $id
     * @return void
     */
    public function getById($id)
    {
        $tag = Table\Tags::findById($id);
        if (isset($tag->id)) {
            $this->data = array_merge($this->data, $tag->getColumns());
        }
    }

    /**
     * Get tag from slug
     *
     * @param  string  $slug
     * @param  \Pop\Module\Manager $modules
     * @return void
     */
    public function getBySlug($slug, \Pop\Module\Manager $modules)
    {
        $tag = Table\Tags::findBy(['slug' => $slug]);
        if (isset($tag->id)) {
            $this->getTag($tag, $modules);
        }
    }

    /**
     * Get tag content
     *
     * @param  mixed   $id
     * @param  boolean $fields
     * @return array
     */
    public function getTagContent($id, $fields = false)
    {
        if (!is_numeric($id)) {
            $tag = Table\Tags::findBy(['title' => $id]);
            if (isset($tag->id)) {
                $id = $tag->id;
            }
        }

        $items   = [];

        $c2t = Table\ContentToTags::findBy(['tag_id' => $id]);
        if ($c2t->hasRows()) {
            foreach ($c2t->rows() as $c) {
                if ($fields) {
                    $filters = ['strip_tags' => null];
                    if ($this->summary_length > 0) {
                        $filters['substr'] = [0, $this->summary_length];
                    };
                    $item = \Phire\Fields\Model\FieldValue::getModelObject(
                        'Phire\Content\Model\Content', [$c->content_id], 'getById', $filters
                    );
                } else {
                    $class = 'Phire\Content\Model\Content';
                    $model = new $class();
                    call_user_func_array([
                        $model, 'getById'], [$c->content_id]
                    );
                    $item = $model;
                }

                if (($item->status == 1) && (count($item->roles) == 0)) {
                    $items[$item->id] = new \ArrayObject($item->toArray(), \ArrayObject::ARRAY_AS_PROPS);
                }
            }
        }

        return $items;
    }

    /**
     * Save new tag
     *
     * @param  array $fields
     * @return void
     */
    public function save(array $fields)
    {
        $tag = new Table\Tags([
            'title' => $fields['title'],
            'slug'  => Slug::filter($fields['title'])
        ]);
        $tag->save();

        $this->data = array_merge($this->data, $tag->getColumns());
    }

    /**
     * Update an existing tag
     *
     * @param  array $fields
     * @return void
     */
    public function update(array $fields)
    {
        $tag = Table\Tags::findById((int)$fields['id']);
        if (isset($tag->id)) {
            $tag->title = $fields['title'];
            $tag->slug  = Slug::filter($fields['title']);
            $tag->save();

            $this->data = array_merge($this->data, $tag->getColumns());
        }
    }

    /**
     * Remove a tag
     *
     * @param  array $fields
     * @return void
     */
    public function remove(array $fields)
    {
        if (isset($fields['rm_tags'])) {
            foreach ($fields['rm_tags'] as $id) {
                $tag = Table\Tags::findById((int)$id);
                if (isset($tag->id)) {
                    $tag->delete();
                }
            }
        }
    }

    /**
     * Determine if list of tags has pages
     *
     * @param  int $limit
     * @return boolean
     */
    public function hasPages($limit)
    {
        return (Table\Tags::findAll()->count() > $limit);
    }

    /**
     * Get count of tags
     *
     * @return int
     */
    public function getCount()
    {
        return Table\Tags::findAll()->count();
    }

    /**
     * Get tag content
     *
     * @param  Table\Tags          $tag
     * @param  \Pop\Module\Manager $modules
     * @return void
     */
    protected function getTag(Table\Tags $tag, \Pop\Module\Manager $modules)
    {
        if ($modules->isRegistered('phire-fields')) {
            $t    = \Phire\Fields\Model\FieldValue::getModelObject('Phire\Tags\Model\Tag', [$tag->id]);
            $data = $t->toArray();
        } else {
            $data = $tag->getColumns();
        }

        $items = [];
        $c2t   = Table\ContentToTags::findBy(['tag_id' => $tag->id], ['order' => 'content_id DESC']);
        if ($c2t->hasRows()) {
            foreach ($c2t->rows() as $c) {
                if ($modules->isRegistered('phire-fields')) {
                    $filters = ['strip_tags' => null];
                    if ($this->summary_length > 0) {
                        $filters['substr'] = [0, $this->summary_length];
                    };
                    $item = \Phire\Fields\Model\FieldValue::getModelObject(
                        'Phire\Content\Model\Content', [$c->content_id], 'getById', $filters
                    );
                } else {
                    $class = 'Phire\Content\Model\Content';
                    $model = new $class();
                    call_user_func_array([
                        $model, 'getById'], [$c->content_id]
                    );
                    $item = $model;
                }

                if (($item->status == 1) && (count($item->roles) == 0)) {
                    $items[$item->id] = new \ArrayObject($item->toArray(), \ArrayObject::ARRAY_AS_PROPS);
                }
            }
        }

        $data['items'] = $items;
        $this->data = array_merge($this->data, $data);
    }

}
