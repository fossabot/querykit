<?php
/**
 * @link https://github.com/rx-public
 * @author BNU <bnufactory@gmail.com>
 * @author RXPublic <rhymixpublic@gmail.com>
 * @license https://spdx.org/licenses/GPL-3.0-or-later.html GPL-3.0-or-later
 */

namespace RXPublic\QueryKit;

class QueryKitApp
{
    public static $instance;
    private $types = [];
    private $loggedMember;
    public $rootResolver = [];
    public $typeResolver = [];

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function defaultFieldResolver($source, $args, $context, $info)
    {
        $parentType = $info->parentType->name;
        $fieldName = $info->fieldName;

        if ($source === null) {
            $rootResolver = $context->getRootResolver($fieldName);
            return $rootResolver($source, $args, $context, $info);
        } else {
            $property = null;

            if (is_array($source) || $source instanceof \ArrayAccess) {
                if (isset($source[$fieldName])) {
                    $property = $source[$fieldName];
                }
            } elseif (is_object($source)) {
                if (isset($source->{$fieldName})) {
                    $property = $source->{$fieldName};
                } elseif ($source->{$fieldName}) {
                    $property = $source->{$fieldName};
                }
            }

            return $property instanceof \Closure ? $property($source, $args, $context, $info) : $property;
        }
    }

    public static function registTypeResolver($name, $resolver)
    {
        self::$instance->typeResolver[$name] = $resolver;
    }

    public static function registRootResolver($name, \Closure $resolver)
    {
        self::$instance->rootResolver[$name] = $resolver;
    }

    public static function resolvePagination(\BaseObject $source, $node)
    {
        $previousPage = $source->page_navigation->cur_page - 1;
        $nextPage = $source->page_navigation->cur_page + 1;
        if ($previousPage < 1) {
            $previousPage = null;
        }
        if ($nextPage > $source->page_navigation->total_page || $nextPage === $source->page_navigation->cur_page) {
            $nextPage = null;
        }

        $result = [
            'pagination' => [
                'totalCount' => $source->page_navigation->total_count,
                'totalPage' => $source->page_navigation->total_page,
                'currentPage' => $source->page_navigation->cur_page,
                'previousPage' => $previousPage,
                'nextPage' => $nextPage,
                'hasNextPage' => $nextPage !== null,
                'hasPreviousPage' => $previousPage !== null
            ],
            'edges' => [],
        ];

        foreach ($source->data as $edge) {
            $result['edges'][] = new $node($edge);
        }

        return $result;
    }

    public function getRootResolver($name)
    {
        return $this->rootResolver[$name];
    }
    public function getTypeResolver($name)
    {
        return $this->typeResolver[$name];
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new QueryKitApp();
        }

        return self::$instance;
    }
}
