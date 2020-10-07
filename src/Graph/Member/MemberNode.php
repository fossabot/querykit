<?php
/**
 * @link https://github.com/rx-public
 * @author BNU <bnufactory@gmail.com>
 * @author RXPublic <rhymixpublic@gmail.com>
 * @license https://spdx.org/licenses/GPL-3.0-or-later.html GPL-3.0-or-later
 */

namespace RXPublic\QueryKit\Graph\Member;

use RXPublic\QueryKit\Graph\Ncenterlite\NcenterliteSchema;

class MemberNode
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __isset($name)
    {
        return isset($this->data->{$name});
    }

    public function __get($property)
    {
        switch ($property) {
            case 'memberSrl':
                return (int)$this->data->member_srl;
                break;
            case 'userId':
                return $this->data->user_id;
                break;
            case 'userName':
                return $this->data->user_name;
                break;
            case 'nickName':
                return $this->data->nick_name;
                break;
            case 'emailAddress':
                return $this->data->email_address;
                break;

            case 'phoneNumber':
                return $this->data->phone_number ?: null;
                break;
            case 'phoneCountry':
                return $this->data->phone_country ?: null;
                break;
            case 'phoneType':
                return $this->data->phone_type ?: null;
                break;

            case 'homepage':
                return $this->data->homepage ?: null;
                break;
            case 'blog':
                return $this->data->blog ?: null;
                break;
            case 'birthday':
                return $this->data->birthday ?: null;
                break;

            case 'allowMailing':
                return $this->data->allow_mailing === 'Y';
                break;
            case 'allowMessage':
                return $this->data->allow_message;
                break;
            case 'denied':
                return $this->data->denied === 'Y';
                break;
            case 'limitDate':
                return $this->data->limit_date;
                break;
            case 'regdate':
                return $this->data->regdate;
                break;
            case 'ipaddress':
                return $this->data->ipaddress;
                break;
            case 'lastLogin':
                return $this->data->last_login;
                break;
            case 'lastLoginIpaddress':
                return $this->data->last_login_ipaddress;
                break;
            case 'changePasswordDate':
                return $this->data->change_password_date;
                break;
            case 'isAdmin':
                return $this->data->is_admin === 'Y';
                break;
            case 'description':
                return $this->data->description ?: null;
                break;
            case 'profileImage':
                return $this->data->profile_image ?: null;
                break;
            case 'imageName':
                return $this->data->image_name ?: null;
                break;
            case 'imageMark':
                return $this->data->image_mark ?: null;
                break;
            case 'signature':
                return $this->data->signature ?: null;
                break;
            case 'isSiteAdmin':
                return $this->data->is_site_admin;
                break;

            case 'notification':
                return function ($source, $args, $context, $info) {
                    $data = null;
                    if ($source->memberSrl) {
                        $data = NcenterliteSchema::NotificationFetch(array_merge($args, [
                            'member_srl' => $source->memberSrl,
                        ]));
                    }

                    return $data;
                };
                break;

            default:
                return null;
        }
    }
}
