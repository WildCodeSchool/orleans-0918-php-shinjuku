<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/10/18
 * Time: 11:22
 */

namespace Model;


class ArticleManager extends AbstractManager
{
    const TABLE = 'Article';

    /**
     * ArticleManager constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }
}
