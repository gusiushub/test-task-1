<?php 

namespace app\service\author;

use app\models\db\Author;
use app\service\author\dto\CreateDto;
use app\service\author\dto\UpdateDto;

interface AuthorServiceInterface
{
    /**
     * @param CreateDto $dto
     * @return Author
     */
    public function create(CreateDto $dto): Author;

    /**
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool;

    /**
     * @param UpdateDto $dto
     * @return Author
     */
    public function update(UpdateDto $dto): Author;
}