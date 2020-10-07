<?php
/**
 * @link https://github.com/rx-public
 * @author BNU <bnufactory@gmail.com>
 * @author RXPublic <rhymixpublic@gmail.com>
 * @license https://spdx.org/licenses/GPL-3.0-or-later.html GPL-3.0-or-later
 */

namespace RXPublic\QueryKit\Graph\Ncenterlite;

use GraphQL\Utils\Utils;
use RXPublic\QueryKit\Graph\Document\DocumentSchema;

class NotificationNode
{
    public $data;
    private $originTypes = [
        'D' => 'DOCUMENT', // Document.
        'C' => 'COMMENT', // Comment.
        'E' => 'MESSAGE', // Message.
        'X' => 'CUSTOM_STRING', // Custom string.
        'Y' => 'CUSTOM_LANG', // Custom language.
        'Z' => 'CUSTOM_LANG_WITH_VARIABLES', //Custom language with string interpolation

        # 공통
        'T' => 'TEST', // Test.
        'U' => 'CUSTOM', // Other.

        // 'I' => 'ADMIN_NEW_MEMBER', // Insert member
        // 'G' => 'COMMENT_ALL',
        // 'M' => 'MENTION',
        // 'P' => 'DOCUMENTS',
        // 'V' => 'VOTED',
        // 'R' => 'SCRAPPED',
    ];
    private $eventTypes = [
        // 'D' => 'DOCUMENT',
        'C' => 'COMMENT', // Comment on your document. '<strong>%1$s</strong>님이 회원님의 %2$s에 <strong>"%3$s"</strong>라고 댓글을 남겼습니다.';
        'M' => 'MENTION', // Mentioned. '<strong>%s</strong>님이 <strong>"%s"</strong> 게시판의 <strong>"%s"</strong> %s에서 회원님을 언급하였습니다.';
        'E' => 'MESSAGE', // Message arrived. '<strong>%s</strong>님이 <strong>"%s"</strong>라고 메시지를 보내셨습니다.';
        'P' => 'NEW_DOCUMENT_ON_BOARD', // New document on a board. '<strong>%1$s</strong>님이 <strong>"%2$s"</strong> 게시판에 <strong>"%3$s"</strong>라고 글을 남겼습니다.';
        'S' => 'NEW_DOCUMENT', // New document.
                                // '<strong>%1$s</strong>님이 <strong>"%2$s"</strong> 게시판에 <strong>"%3$s"</strong>라고 글을 남겼습니다.';
                                // '<strong>%1$s</strong>님이 <strong>"%2$s"</strong>라고 글을 남겼습니다.';
        'V' => 'VOTED', // Voted. '<strong>%s</strong>님이 회원님의 <strong>"%s"</strong> %s을 추천하였습니다.';
        'R' => 'SCRAPPED', // Scrapped. '<strong>%s</strong>님이 회원님의 <strong>"%s"</strong> %s을 스크랩하였습니다.';

        'G' => 'COMMENT_ALL', // ncenterlite_commented '<strong>%1$s</strong>님이 회원님의 %2$s에 <strong>"%3$s"</strong>라고 댓글을 남겼습니다.';

        # 공통
        'T' => 'TEST', // Test notification. '<strong>%s</strong>님! 테스트 알림입니다.';
        'U' => 'CUSTOM',

        # 관리자 알림
        'B' => 'ADMIN_NEW_DOCUMENT', // Admin notification.
        'A' => 'ADMIN_NEW_COMMENT', // Comment on a board. '<strong>%1$s</strong>님이 <strong>"%2$s"</strong>게시판에 <strong>"%3$s"</strong>라고 댓글을 남겼습니다.';
        // 'I' => 'ADMIN_NEW_MEMBER', // REMOVED
    ];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __isset($name)
    {
        return isset($this->data->{$name});
    }

    public function resolveType()
    {
        $type = [
            'ADMIN_NEW_DOCUMENT' => 'NewDocumetNotification',
            // 'MESSAGE' => 'NewMessageNotification',
        ];

        if ($type[$this->eventType]) {
            return $type[$this->eventType];
        }

        return 'CommonNotification';
    }

    public function __get($property)
    {
        switch ($property) {
            case 'document':
                return DocumentSchema::documentFetch([ 'documentSrl' => $this->originSrl ]);
                break;
            // identifier
            case 'notify':
                return $this->data->notify;
                break;
            // 수신 회원 번호
            case 'memberSrl':
                return $this->data->member_srl;
                break;
            // ? 뭘까..
            case 'originSrl':
                return $this->data->srl;
                break;

            // 알림을 발생한 대상
            case 'targetSrl':
                return $this->data->target_srl;
                break;
            // ??
            case 'targetPSrl':
                return $this->data->target_p_srl;
                break;

            // 알림 타입
            case 'originType':
                return $this->originTypes[$this->data->type] ?? 'CUSTOM';
                break;
            // 알림의 대상 타입
            case 'eventType':
                return $this->eventTypes[$this->data->target_type] ?? 'CUSTOM';
                break;
            // ?
            case 'notifyType':
                return $this->data->notify_type;
                break;

            // 발신 회원
            case 'targetMemberSrl':
                return $this->data->target_member_srl;
                break;
            case 'targetNickName':
                return $this->data->target_nick_name;
                break;
            case 'targetUserId':
                return $this->data->target_user_id;
                break;
            case 'targetEmailAddress':
                return $this->data->target_email_address;
                break;

            // 발생 정보
            case 'targetBrowser':
                return $this->data->target_browser;
                break;
            case 'targetSummary':
                return $this->data->target_summary;
                break;
            case 'targetBody':
                return $this->data->target_body;
                break;
            case 'targetUrl':
                return $this->data->target_url;
                break;

            // 수신 여부: Boolean
            case 'readed':
                return $this->data->readed === 'Y';
                break;
            // 발생일시
            case 'regdate':
                return $this->data->regdate;
                break;
            // ?
            case 'text':
                return $this->data->text;
                break;
            // ?
            case 'url':
                return $this->data->url;
                break;

            case 'thumbnailUrl':
                return 'http://...';
                break;

            default:
                return null;
        }
    }
}
