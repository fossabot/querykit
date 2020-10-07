<?php
/**
 * @link https://github.com/rx-public
 * @author BNU <bnufactory@gmail.com>
 * @author RXPublic <rhymixpublic@gmail.com>
 * @license https://spdx.org/licenses/GPL-3.0-or-later.html GPL-3.0-or-later
 */

require_once __DIR__ . '/vendor/autoload.php';

class querykit extends ModuleObject
{
    public function __construct()
    {
        // require_once __DIR__ . '/vendor/autoload.php';
    }

    public function moduleInstall()
    {
    }

    public function checkUpdate()
    {
        if (!getModel('module')->getTrigger('layout', 'querykit', 'controller', 'tirggerBeforeLayout', 'before')) {
            return true;
        }
        if (!getModel('module')->getTrigger('display', 'querykit', 'controller', 'tirggerBeforeDisplay', 'before')) {
            return true;
        }

        return false;
    }

    public function moduleUpdate()
    {
        if (!getModel('module')->getTrigger('layout', 'querykit', 'controller', 'tirggerBeforeLayout', 'before')) {
            getController('module')->insertTrigger('layout', 'querykit', 'controller', 'tirggerBeforeLayout', 'before');
        }

        if (!getModel('module')->getTrigger('display', 'querykit', 'controller', 'tirggerBeforeDisplay', 'before')) {
            getController('module')->insertTrigger('display', 'querykit', 'controller', 'tirggerBeforeDisplay', 'before');
        }
    }

    public function recompileCache()
    {
    }
}
