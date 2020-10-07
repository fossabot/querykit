<?php
/**
 * @link https://github.com/rx-public
 * @author BNU <bnufactory@gmail.com>
 * @author RXPublic <rhymixpublic@gmail.com>
 * @license https://spdx.org/licenses/GPL-3.0-or-later.html GPL-3.0-or-later
 */

class querykitAdminView extends Querykit
{
    public function init()
    {
    }

    public function dispQuerykitAdminGraphi()
    {
        // 알림 노티바 제거
        Context::set('ncenterlite_is_popup', true);

        $this->setLayoutPath('./common/tpl');
        $this->setLayoutFile('default_layout');

        $this->setTemplatePath($this->module_path . 'tpl');
        $this->setTemplateFile('graphi');
    }
}
