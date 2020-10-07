<?php
/**
 * @link https://github.com/rx-public
 * @author BNU <bnufactory@gmail.com>
 * @author RXPublic <rhymixpublic@gmail.com>
 * @license https://spdx.org/licenses/GPL-3.0-or-later.html GPL-3.0-or-later
 */

namespace RXPublic\QueryKit\Graph\Document;

use GraphQL\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use RXPublic\QueryKit\Graph\Document\DocumentNode;
use RXPublic\QueryKit\QueryKitApp;

class DocumentSchema
{
    public static function boot()
    {
        QueryKitApp::registRootResolver('document', function ($source, $args) {
            $document_srl = $source->documentSrl;
            $data = self::NotificationFetch(array_merge($args, [
                'documentSrl' => $document_srl,
            ]));
            return $data;
        });
    }

    public function documentFetch($args = [])
    {
        $output = getModel('document')->getDocument($args['documentSrl']);

        return new DocumentNode($output);
    }
}
