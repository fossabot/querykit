<?php
/**
 * @link https://github.com/rx-public
 * @author BNU <bnufactory@gmail.com>
 * @author RXPublic <rhymixpublic@gmail.com>
 * @license https://spdx.org/licenses/GPL-3.0-or-later.html GPL-3.0-or-later
 */

namespace RXPublic\QueryKit\Graph\Document;

use GraphQL\Utils\Utils;

class DocumentNode
{
    private $data;

    public function __construct($document)
    {
        $this->data = $document;
    }

    public function __get($property)
    {
        switch ($property) {
            // identifier
            case 'documentSrl':
                return $this->data->document_srl;
                break;
            // 수신 회원 번호
            case 'title':
                return $this->data->getTitleText();
                break;
            case 'contentSummary':
                return $this->data->getSummary();
                break;
            case 'thumbnailUrl':
                return function ($source, $args) {
                    return $this->data->getThumbnail($args['width'], $args['height']);
                };
                break;
            default:
                return null;
        }
    }
}
