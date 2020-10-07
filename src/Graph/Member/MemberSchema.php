<?php
/**
 * @link https://github.com/rx-public
 * @author BNU <bnufactory@gmail.com>
 * @author RXPublic <rhymixpublic@gmail.com>
 * @license https://spdx.org/licenses/GPL-3.0-or-later.html GPL-3.0-or-later
 */

namespace RXPublic\QueryKit\Graph\Member;

use GraphQL\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\Type;
use RXPublic\QueryKit\QueryKitApp;
use RXPublic\QueryKit\Graph\Member\MemberNode;
use RXPublic\QueryKit\Graph\Ncenterlite\NcenterliteSchema;

class MemberSchema
{
    private static $memberType = null;

    public static function boot()
    {
        QueryKitApp::registRootResolver('me', function ($source, $args, $context, $info) {
            $member_srl = ($source['member_srl']) ?: \Rhymix\Framework\Session::getMemberSrl();
            $data = getModel('member')->getMemberInfo($member_srl);
            return new MemberNode($data);
        });
    }
}
