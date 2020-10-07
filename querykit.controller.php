<?php
/**
 * @link https://github.com/rx-public
 * @author BNU <bnufactory@gmail.com>
 * @author RXPublic <rhymixpublic@gmail.com>
 * @license https://spdx.org/licenses/GPL-3.0-or-later.html GPL-3.0-or-later
 */

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Utils\BuildSchema;
use RXPublic\QueryKit\QueryKitApp;
use RXPublic\QueryKit\Graph\Member\MemberSchema;
use RXPublic\QueryKit\Graph\Ncenterlite\NcenterliteSchema;
use RXPublic\QueryKit\Graph\Document\DocumentSchema;

class querykitController extends querykit
{
    public $appContext;
    private $schema;

    public function __construct()
    {
        // FIXME 초기화 시점 이동

        // TODO cache
        $contents = file_get_contents(__DIR__ . '/schema.graphql');
        $this->appContext = new QueryKitApp();

        MemberSchema::boot();
        NcenterliteSchema::boot();
        DocumentSchema::boot();

        $typeConfigDecorator = function ($typeConfig, $typeDefinitionNode, $all) {
            $resolver = $this->appContext->getRootResolver($typeConfig['name']);

            if ($resolver && $resolver instanceof \Closure) {
                $typeConfig['resolveType'] = $resolver;
            }

            return $typeConfig;
        };

        $schemaContent = file_get_contents(__DIR__ . '/schema.graphql');
        $schemaDocument = \GraphQL\Language\Parser::parse($schemaContent);
        $schemaBuilder = new \GraphQL\Utils\BuildSchema($schemaDocument, $typeConfigDecorator);
        $this->schema = $schemaBuilder->buildSchema();
    }

    public function init()
    {
    }

    public function tirggerBeforeDisplay(&$output)
    {
        // JSON 응답에서 data, errors 외 제거
        if (Context::getResponseMethod() === 'JSON' && Context::get('act') === 'procQuerykit') {
            $temp = json_decode($output);
            $temp = (isset($temp->data)) ? [ 'data' => $temp->data] : [ 'errors' => $temp->errors] ;
            $output = json_encode($temp);
        }
    }

    public function tirggerBeforeLayout()
    {
        Context::loadFile($this->module_path . 'assets/rxp.js');

        $site_module_info = Context::get('site_module_info');
        $logged_info = Context::get('logged_info');

        $commonConfig = [
            'csrf_token' => (Context::get('is_logged') || context::get('act')) ? \Rhymix\Framework\Session::getGenericToken() : null,
            'default_url' => \Rhymix\Framework\URL::encodeIdna(Context::getDefaultUrl()),
            'current_url' => \Rhymix\Framework\URL::encodeIdna(Context::get('current_url')),
            'request_uri' => \Rhymix\Framework\URL::encodeIdna(Context::get('request_uri')),
            'locale' => [
                'default' => config('locale.default_lang'),
                'current' => Context::getLangType(),
                'enabled' => Context::loadLangSelected(),
            ],
            'user' => null,
            'timezone' => config('locale.default_timezone'),
            'current_mid' => Context::get('mid') ?: null,
            'http_port' => Context::get('_http_port') ?: null,
            'https_port' => Context::get('_https_port') ?: null,
            'enforce_ssl' => $site_module_info->security === 'always' ? 'true' : 'false',
            'cookies_ssl' => config('session.use_ssl_cookies') ? 'true' : 'false'
        ];


        if ($logged_info) {
            $commonConfig['user'] = [
                'member_srl' => $logged_info->member_srl,
                'is_member' => $logged_info->isMember(),
                'is_admin' => $logged_info->isAdmin(),
                'is_module_admin' => $logged_info->isModuleAdmin(),
                'is_valid' => $logged_info->isValid()
            ];
        }

        $script = '<script>rxp.boot(' . json_encode($commonConfig, JSON_UNESCAPED_SLASHES) . ')</script>';
        Context::addHtmlHeader($script);
    }

    public function procQuerykitGrqphql()
    {
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);
        $query = $input['query'];
        $variableValues = isset($input['variables']) ? $input['variables'] : null;
        $validationRules = null;

        try {
            $rootValue = null;

            $fieldResolver = function () {
                return call_user_func_array([QueryKitApp::class, 'defaultFieldResolver'], func_get_args());
            };

            $result = GraphQL::executeQuery(
                $this->schema,
                $query,
                $rootValue,
                $this->appContext,
                $variableValues,
                null,
                $fieldResolver,
                $validationRules
            );
            $output = $result->toArray();
            $this->add('data', $output['data']);
        } catch (\Exception $e) {
            $this->add('errors', [
                [
                    'message' => $e->getMessage()
                ]
            ]);
        }
    }
}
