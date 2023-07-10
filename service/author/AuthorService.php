<?php

namespace app\service\author;

use DateTime;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use app\service\author\dto\CreateDto;
use app\models\db\Author;
use app\service\author\dto\UpdateDto;

class AuthorService implements AuthorServiceInterface
{
    /**
     * @param CreateDto $dto
     * @return Author
     */
    public function create(CreateDto $dto): Author
    {
        $book = new Author();
        $book->setScenario(Author::SCENARIO_CREATE);
        $book->setAttributes([
            'title' => $dto->title, 
            'created_at' => (new DateTime())->format('Y-m-d H:i:s')
        ]);
        
        if (!$book->validate()) {
            throw new BadRequestHttpException(Json::encode($book->errors));
        }

        $book->save();

        return $book;
    }

     /**
     * @param UpdateDto $dto
     * @return Author
     */
    public function update(UpdateDto $dto): Author
    {
        if (!$author = Author::findOne(['author_id' => $dto->authorId])) {
            throw new NotFoundHttpException("Author with id {$dto->authorId} not found");
        }

        $author->setScenario(Author::SCENARIO_UPDATE);
        $author->setAttributes([
            'title' => $dto->title,
            'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
        ]);
        
        if (!$author->validate()) {
            throw new BadRequestHttpException(Json::encode($author->errors));
        }

        return $author;
    }

    /**
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        if (!$author = Author::findOne(['author_id' => $id])) {
            throw new NotFoundHttpException("Author with id {$id} not found");
        }

        return $author->delete();
    }
}