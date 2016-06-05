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
namespace Phire\Tags;

use Phire\Tags\Model;
use Phire\Tags\Table;

/**
 * Tags Updater class
 *
 * @category   Phire\Tags
 * @package    Phire\Tags
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.1.0
 */
class Updater extends \Phire\BaseUpdater
{

    public function update1()
    {
        $db = Table\Tags::db();
        $db->query('ALTER TABLE `' . DB_PREFIX . 'tags` ADD `test` VARCHAR(255) NULL AFTER `slug`;');
    }

}