<?php

namespace Phire\Tags\Table;

use Pop\Db\Record;

class ContentToTags extends Record
{

    /**
     * Table prefix
     * @var string
     */
    protected $prefix = DB_PREFIX;

    /**
     * Primary keys
     * @var array
     */
    protected $primaryKeys = ['content_id', 'tag_id'];

}