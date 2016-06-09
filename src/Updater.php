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

/**
 * Tag Updater class
 *
 * @category   Phire\Tags
 * @package    Phire\Tags
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.1
 */
class Updater extends \Phire\BaseUpdater
{

    public function update1()
    {
        $config = new \Phire\Table\Config([
            'setting' => 'foo',
            'value'   => 'bar'
        ]);
        $config->save();
    }


    public function update2()
    {
        $config = new \Phire\Table\Config([
            'setting' => 'baz',
            'value'   => '123'
        ]);
        $config->save();
    }

}
