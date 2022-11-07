<?php
namespace App;

class ArticleDB extends Database\DbConnect
{
    private \PDOStatement $statementCreateOne;
    private \PDOStatement $statementUpdateOne;
    private \PDOStatement $statementDeleteOne;
    private \PDOStatement $statementReadOne;
    private \PDOStatement $statementReadAll;
    private \PDOStatement $statementGetUserArticle;
    private \PDOStatement $statementReadPageArticles;
    private \PDOStatement $statementReadPageArticlesPerCategory;
    private \PDOStatement $statementGetUserArticleLength;
    private \PDO $pdo;

    function __construct()
    {
        $this->pdo = $this->pdoConnect();
        $this->statementCreateOne = $this->pdo->prepare('
            INSERT INTO article (
                title,
                category,
                content,
                image,
                author
            ) VALUES (
                :title,
                :category,
                :content,
                :image,
                :author
            )
        ');
        $this->statementUpdateOne = $this->pdo->prepare('
            UPDATE article
            SET
                title=:title,
                category=:category,
                content=:content,
                image=:image,
                author=:author
            WHERE id=:id
        ');
        $this->statementReadOne = $this->pdo->prepare('SELECT article.*, user.firstname, user.lastname FROM article LEFT JOIN user ON article.author = user.id WHERE article.id=:id');
        $this->statementReadAll = $this->pdo->prepare('SELECT COUNT(id) FROM article');
        $this->statementGetUserArticleLength = $this->pdo->prepare('SELECT COUNT(id) FROM article WHERE author=:id');
        $this->statementReadAllPerCategory = $this->pdo->prepare('SELECT COUNT(id) FROM article WHERE category=:category');
        $this->statementDeleteOne = $this->pdo->prepare('DELETE FROM article WHERE id=:id');
        $this->statementGetUserArticle = $this->pdo->prepare('SELECT * FROM article WHERE author=:id LIMIT 15 OFFSET :offset');
        $this->statementReadPageArticles = $this->pdo->prepare('SELECT article.*, firstname, lastname FROM article LEFT JOIN user ON article.author = user.id limit 9 OFFSET :index');
        $this->statementReadPageArticlesPerCategory = $this->pdo->prepare('SELECT article.*, firstname, lastname FROM article LEFT JOIN user ON article.author = user.id WHERE category=:category limit 9 OFFSET :index');
    }


    public function fetchAll(): int
    {

        $this->statementReadAll->execute();
        return $this->statementReadAll->fetch(\PDO::FETCH_NUM)[0];
    }
    
    public function fetchAllPerCatgory(string $category): int
    {
        $this->statementReadAllPerCategory->bindValue(':category', $category);
        $this->statementReadAllPerCategory->execute();
        return $this->statementReadAllPerCategory->fetch(\PDO::FETCH_NUM)[0];
    }

    public function fetchOne(string $id): array | bool
    {
        $this->statementReadOne->bindValue(':id', $id);
        $this->statementReadOne->execute();
        return $this->statementReadOne->fetch();
    }

    public function deleteOne(string $id): string
    {
        $this->statementDeleteOne->bindValue(':id', $id);
        $this->statementDeleteOne->execute();
        return $id;
    }

    public function createOne($article): array
    {
        $this->statementCreateOne->bindValue(':title', $article['title']);
        $this->statementCreateOne->bindValue(':content', $article['content']);
        $this->statementCreateOne->bindValue(':category', $article['category']);
        $this->statementCreateOne->bindValue(':image', $article['image']);
        $this->statementCreateOne->bindValue(':author', $article['author']);
        $this->statementCreateOne->execute();
        return $this->fetchOne($this->pdo->lastInsertId());
    }

    public function updateOne($article): array
    {
        $this->statementUpdateOne->bindValue(':title', $article['title']);
        $this->statementUpdateOne->bindValue(':content', $article['content']);
        $this->statementUpdateOne->bindValue(':category', $article['category']);
        $this->statementUpdateOne->bindValue(':image', $article['image']);
        $this->statementUpdateOne->bindValue(':id', $article['id']);
        $this->statementUpdateOne->bindValue(':author', $article['author']);
        $this->statementUpdateOne->execute();
        return $article;
    }
    
    public function fetchUserArticle(string $userId, int $offset): array
    {
        $this->statementGetUserArticle->bindValue(':id', $userId);
        $this->statementGetUserArticle->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $this->statementGetUserArticle->execute();
        return $this->statementGetUserArticle->fetchAll();
    }

    public function fetchUserArticleLength(string $userId): int
    {
        $this->statementGetUserArticleLength->bindValue(':id', $userId);
        $this->statementGetUserArticleLength->execute();
        return $this->statementGetUserArticleLength->fetch(\PDO::FETCH_NUM)[0];
    }

    public function fetchPageArticles(int $index): array
    {
        $this->statementReadPageArticles->bindValue(':index', $index, \PDO::PARAM_INT);
        $this->statementReadPageArticles->execute();
        return $this->statementReadPageArticles->fetchAll();
    }

    public function fetchPageArticlesPerCategory(int $index, string $category): array
    {
        $this->statementReadPageArticlesPerCategory->bindValue(':index', $index, \PDO::PARAM_INT);
        $this->statementReadPageArticlesPerCategory->bindValue(':category', $category);
        $this->statementReadPageArticlesPerCategory->execute();
        return $this->statementReadPageArticlesPerCategory->fetchAll();
    }
}

