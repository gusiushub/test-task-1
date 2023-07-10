<?php

namespace app\controllers;

use app\models\db\Book;
use app\models\db\Permission;
use yii\web\NotFoundHttpException;
use app\service\book\dto\CreateDto;
use app\service\book\dto\UpdateDto;
use yii\filters\auth\HttpBearerAuth;
use app\models\response\BaseResponse;
use app\service\book\BookServiceInterface;
use app\models\db\search\Book as SearchBook;
use app\models\response\Book as ResponseBook;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class BookController extends ApiController
{
    private $bookService;

    public function __construct($id, $module, BookServiceInterface $bookService, $config)
    {
        $this->bookService = $bookService;
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
                    'actions' => ['list', 'get'],
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
    public function actionGet(int $id): ResponseBook
    {
        if(!$book = Book::findOne(['book_id' => $id])) {
            throw new NotFoundHttpException("Book with {$id} not found");
        }

        return ResponseBook::fromModel($book);
    }
    
    /**
     * @return array
     */
    public function actionList(): array
    {
        $company = new SearchBook();
        $dataProvider = $company->search();
        $dataProvider->prepare();

        return [
            'page' => $dataProvider->pagination->page + 1,
            'perPage' => $dataProvider->pagination->pageSize,
            'totalCount' => $dataProvider->getTotalCount(),
            'lastPage' => $dataProvider->pagination->pageCount - 1 <= $dataProvider->pagination->page,
            'books' => array_map(function($book) {
                return ResponseBook::fromModel($book);
            }, $dataProvider->getModels())
        ];
    }

    /**
     * @return ResponseBook
     */
    public function actionCreate(): ResponseBook
    {
        $bodyParams = \Yii::$app->request->post();
        $dto = new CreateDto(
            ArrayHelper::getValue($bodyParams, 'title'),
            ArrayHelper::getValue($bodyParams, 'description'),
            ArrayHelper::getValue($bodyParams, 'releaseYear'),
            ArrayHelper::getValue($bodyParams, 'isbn'),
            ArrayHelper::getValue($bodyParams, 'coverArt'),
            ArrayHelper::getValue($bodyParams, 'authorId'),
        );
        $model = $this->bookService->create($dto);
        return ResponseBook::fromModel($model);
    }

    /**
     * @param integer $id
     * @return ResponseBook
     */
    public function actionUpdate(int $id): ResponseBook
    {
        $bodyParams = \Yii::$app->request->post();
        $dto = new UpdateDto(
            $id,
            ArrayHelper::getValue($bodyParams, 'title'),
            ArrayHelper::getValue($bodyParams, 'description'),
            ArrayHelper::getValue($bodyParams, 'releaseYear'),
            ArrayHelper::getValue($bodyParams, 'isbn'),
            ArrayHelper::getValue($bodyParams, 'coverArt')
        );
        $model = $this->bookService->update($dto);
        return ResponseBook::fromModel($model);
    }

    /**
     * @param integer $id
     * @return BaseResponse
     */
    public function actionDelete(int $id): BaseResponse
    {
        $this->bookService->delete($id);
        return new BaseResponse();
    }
}