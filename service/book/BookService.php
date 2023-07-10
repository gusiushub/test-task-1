<?php

namespace app\service\book;

use Yii;
use DateTime;
use Exception;
use yii\helpers\Json;
use app\models\db\Book;
use app\models\db\BookAuthor;
use app\models\db\Followers;
use app\service\book\dto\UpdateDto;
use app\service\book\dto\CreateDto;
use app\service\smspilot\SmsServiceInterface;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class BookService implements \app\service\book\BookServiceInterface
{
    /**
     * @param CreateDto $dto
     * @return Book
     */
    public function create(CreateDto $dto): Book
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $book = new Book();
            $book->setScenario(Book::SCENARIO_CREATE);
            $book->setAttributes([
                'title' => $dto->title, 
                'description' => $dto->description, 
                'release_year' => $dto->releaseYear, 
                'isbn' => $dto->isbn, 
                'cover_art' => $dto->coverArt, 
                'author_id' => $dto->authorId,
                'created_at' => (new DateTime())->format('Y-m-d H:i:s')
            ]);
            
            if (!$book->validate()) {
                throw new BadRequestHttpException(Json::encode($book->errors));
            }

            $book->save();

            $bookAuthor = new BookAuthor();
            $bookAuthor->setScenario(BookAuthor::SCENARIO_CREATE);
            $bookAuthor->setAttributes([
                'book_id' => $book->book_id,
                'author_id' => $dto->authorId
            ]);
            if (!$bookAuthor->validate()) {
                throw new BadRequestHttpException(Json::encode($bookAuthor->errors));
            }
            $bookAuthor->save();

            $smsService = Yii::createObject(
                SmsServiceInterface::class, 
                [
                    'apiKey' => 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ',
                    'host' => 'smspilot.ru',
                ]
            );

            //@todo подобный функционал обычно реализуется через очередь, допустим, Rabbitmq
            $followers = Followers::findAll(['author_id' => $dto->authorId]);
            foreach($followers as $follower) {
                $smsService->setMsg('Вышла новая книга')->setMsisdn($follower->msisdn)->sendSms();
            }

            $transaction->commit();
        } catch (Exception $exception) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }

            throw $exception;
        }

        return $book;
    }

    /**
     * @param UpdateDto $dto
     * @return Book
     */
    public function update(UpdateDto $dto): Book
    {
        if (!$book = Book::findOne(['book_id' => $dto->bookId])) {
            throw new NotFoundHttpException("Book with id {$dto->bookId} not found");
        }

        $book->setScenario(Book::SCENARIO_CREATE);
        $book->setAttributes([
            'title' => $dto->title, 
            'description' => $dto->description, 
            'release_year' => $dto->releaseYear, 
            'isbn' => $dto->isbn, 
            'cover_art' => $dto->coverArt, 
            'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
        ]);
        
        if (!$book->validate()) {
            throw new BadRequestHttpException(Json::encode($book->errors));
        }

        return $book;
    }

    /**
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        if (!$book = Book::findOne(['book_id' => $id])) {
            throw new NotFoundHttpException("Book with id {$id} not found");
        }

        return $book->delete();
    }
}