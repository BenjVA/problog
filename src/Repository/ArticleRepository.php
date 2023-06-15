<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Article;
use App\Model\DatabaseConnection;

class ArticleRepository
{
    public DatabaseConnection $connection;

    public function getRecentArticles(): array
    {
        $statement = $this->connection->getConnection()->query(
            "SELECT *,
            DATE_FORMAT(createdAt, '%d-%m-%Y à %Hh%imin%ss') AS createdAt,
            DATE_FORMAT(updatedAt, '%d-%m-%Y à %Hh%imin%ss') AS updatedAt
            FROM article
            ORDER BY article.updatedAt
            DESC
            LIMIT 0, 3"
        );
        $recentArticles = [];

        while (($row = $statement->fetch())) {
            $recentArticle = new Article();
            $recentArticle->title = $row['title'];
            $recentArticle->createdAt = $row['createdAt'];
            $recentArticle->updatedAt = $row['updatedAt'];
            $recentArticle->short = $row['short'];
            $recentArticle->id = $row['id'];

            $recentArticles[] = $recentArticle;
        }

        return $recentArticles;
    }

    public function getArticles(): array
    {
        $statement = $this->connection->getConnection()->query(
            "SELECT *,
            DATE_FORMAT(createdAt, '%d-%m-%Y à %Hh%imin%ss') AS createdAt,
            DATE_FORMAT(updatedAt, '%d-%m-%Y à %Hh%imin%ss') AS updatedAt
            FROM article
            ORDER BY article.updatedAt
            DESC"
        );
        $articles = [];

        while (($row = $statement->fetch())) {
            $article = new Article();
            $article->title = $row['title'];
            $article->createdAt = $row['createdAt'];
            $article->updatedAt = $row['updatedAt'];
            $article->short = $row['short'];
            $article->id = $row['id'];

            $articles[] = $article;
        }

        return $articles;
    }

    public function getArticle(string $id): Article
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT article.id, user.pseudo, title, content,
             DATE_FORMAT(createdAt, '%d-%m-%Y à %Hh%imin%ss') AS createdAt,
             DATE_FORMAT(updatedAt, '%d-%m-%Y à %Hh%imin%ss') AS updatedAt
             FROM article
             INNER JOIN user ON article.idUser = user.id
             WHERE article.id = ?"
        );
        $statement->execute([$id]);


        $row = $statement->fetch();
        $article = new Article();
        $article->title = $row['title'];
        $article->createdAt = $row['createdAt'];
        $article->updatedAt = $row['updatedAt'];
        $article->content = $row['content'];
        $article->id = $row['id'];
        $article->pseudo = $row['pseudo'];

        return $article;
    }
}