<?php

namespace Phire\Tags\Form;

use Pop\Form\Form;
use Pop\Validator;
use Phire\Tags\Table;

class Tag extends Form
{

    /**
     * Constructor
     *
     * Instantiate the form object
     *
     * @param  array  $fields
     * @param  string $action
     * @param  string $method
     * @return Tag
     */
    public function __construct(array $fields, $action = null, $method = 'post')
    {
        parent::__construct($fields, $action, $method);
        $this->setAttribute('id', 'tag-form');
        $this->setIndent('    ');
    }

    /**
     * Set the field values
     *
     * @param  array $values
     * @return Category
     */
    public function setFieldValues(array $values = null)
    {
        parent::setFieldValues($values);

        if (($_POST) && (null !== $this->title)) {
            // Check for dupe name
            $tag = Table\Tags::findBy(['title' => $this->title]);
            if (isset($tag->id) && ($this->id != $tag->id)) {
                $this->getElement('title')
                     ->addValidator(new Validator\NotEqual($this->title, 'That tag already exists.'));
            }
        }

        return $this;
    }

}