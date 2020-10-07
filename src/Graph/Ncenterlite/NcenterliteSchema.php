<?php
/**
 * @link https://github.com/rx-public
 * @author BNU <bnufactory@gmail.com>
 * @author RXPublic <rhymixpublic@gmail.com>
 * @license https://spdx.org/licenses/GPL-3.0-or-later.html GPL-3.0-or-later
 */

namespace RXPublic\QueryKit\Graph\Ncenterlite;

use GraphQL\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use RXPublic\QueryKit\QueryKitApp;

class NcenterliteSchema
{
    public static function boot()
    {
        QueryKitApp::registRootResolver('notification', function ($source, $args) {
            $member_srl = ($source->memberSrl) ?: \Rhymix\Framework\Session::getMemberSrl();
            $data = self::NotificationFetch(array_merge($args, [
                'member_srl' => $member_srl,
            ]));
            return $data;
        });

        QueryKitApp::registRootResolver('Notification', function ($source) {
            return $source->resolveType();
        });
    }

    public function NotificationFetch($args = [])
    {
        $output = self::_getMyNotifyList($args);

        return QueryKitApp::resolvePagination($output, NotificationNode::class);
    }

    private static function _getMyNotifyList($args = [])
    {
        $args['readed'] = 'N';
        $args['page'] = ($args['page']) ?: 1;
        $args['list_count'] = ($args['limit']) ?: 10;

        $output = executeQueryArray('ncenterlite.getNotifyList', $args);
        if (!$output->data) {
            $output->data = array();
        }

        return $output;
    }
}
