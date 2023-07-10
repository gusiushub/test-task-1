<?php

namespace app\controllers;

use app\models\db\Author as DbAuthor;
use app\models\db\Permission;
use app\models\db\search\Author as SearchAuthor;
use app\service\author\dto\CreateDto;
use app\models\response\Author;
use app\models\response\BaseResponse;
use app\service\author\AuthorServiceInterface;
use app\service\author\dto\UpdateDto;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class AuthorController extends ApiController
{
    private $authorService;

    public function __construct($id, $module, AuthorServiceInterface $authorService, $config)
    {
        $this->authorService = $authorService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['list', 'get'],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['?'],
                    'actions' => ['get', 'list'],
                ],
                [
                    'allow' => true,
                    'roles' => [Permission::CREATE_BOOK],
                    'actions' => ['create'],
                ],
                [
                    'allow' => true,
                    'roles' => [Permission::VIEW_BOOK],
                    'actions' => ['get', 'list'],
                ],
                [
                    'allow' => true,
                    'roles' => [Permission::EDIT_BOOK],
                    'actions' => ['update'],
                ],
                [
                    'allow' => true,
                    'roles' => [Permission::DELETE_BOOK],
                    'actions' => ['delete'],
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    protected function verbs(): array
    {
        return [
            'create' => ['POST'],
            'list' => ['GET'],
            'get' => ['GET'],
            'delete' => ['DELETE'],
            'update' => ['PUT'],
        ];
    }

    /**
     * @param integer $id
     * @return ResponseBook
     */
    public function actionGet(int $id): Author
    {
        if(!$book = DbAuthor::findOne(['author_id' => $id])) {
            throw new NotFoundHttpException("Author with {$id} not found");
        }

        return Author::fromModel($book);
    }
    
     /**
     * @return array
     */
    public function actionList(): array
    {
        $company = new SearchAuthor();
        $dataProvider = $company->search();
        $dataProvider->prepare();

        return [
            'page' => $dataProvider->pagination->page + 1,
            'perPage' => $dataProvider->pagination->pageSize,
            'totalCount' => $dataProvider->getTotalCount(),
            'lastPage' => $dataProvider->pagination->pageCount - 1 <= $dataProvider->pagination->page,
            'books' => array_map(function($author) {
                return Author::fromModel($author);
            }, $dataProvider->getModels())
        ];
    }

    /**
     * @return Author
     */
    public function actionCreate(): Author
    {
        $bodyParams = \Yii::$app->request->post();
        $dto = new CreateDto(
            ArrayHelper::getValue($bodyParams, 'title'),
        );
        $model = $this->authorService->create($dto);
        return Author::fromModel($model);
    }

    /**
     * @param integer $id
     * @return Author
     */
    public function actionUpdate(int $id): Author
    {
        $bodyParams = \Yii::$app->request->post();
        $dto = new UpdateDto(
            $id,
            ArrayHelper::getValue($bodyParams, 'title'),
        );
        $model = $this->authorService->update($dto);
        return Author::fromModel($model);
    }

    /**
     * @param integer $id
     * @return BaseResponse
     */
    public function actionDelete(int $id): BaseResponse
    {
        $this->authorService->delete($id);
        return new BaseResponse();
    }


}