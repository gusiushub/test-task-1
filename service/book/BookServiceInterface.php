<?php 

namespace app\service\book;

use app\models\db\Book;
use app\service\book\dto\CreateDto;
use app\service\book\dto\UpdateDto;

interface BookServiceInterface
{
    /**
     * @param CreateDto $dto
     * @return Book
     */
    public function create(CreateDto $dto): Book;

    /**
     * @param UpdateDto $dto
     * @return Book
     */
    public function update(UpdateDto $dto): Book;

    /**
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool;
}