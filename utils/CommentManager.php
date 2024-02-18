<?php

require_once(ROOT . '/utils/DB.php');
require_once(ROOT . '/class/Comment.php');
require_once(ROOT . '/utils/Helper.php');
require_once('NewsManager.php');

/**
 * Singleton class responsible for managing comments.
 */
class CommentManager
{
    private static $instance = null;

    private function __construct()
    {
    }

    /*
     * The object is created from within the class itself only if the class has no instance.
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CommentManager();
        }
        return self::$instance;
    }

    /*
     * Get all comments from the database
     */
    public function listComments(): array
    {
        try {
            $db = DB::getInstance();
            $rows = $db->select('SELECT * FROM `comment`');

            return array_map(
                fn($row) => new Comment(
                    id: $row['id'],
                    body: $row['body'],
                    createdAt: $row['created_at'],
                    newsId: $row['news_id']
                ),
                $rows
            );
        } catch (\Exception $e) {
            print_r('Error listing comments');
            return [];
        }
    }

    /*
     * Add comment to news
     */
    public function addCommentForNews($body, $newsId): int
    {
        try {
            $escapedBody = Helper::escapedInput($body);
            $escapedNewsId = Helper::escapedInput($newsId);

            $checkIfNewsExists = NewsManager::checkIfNewsExists($escapedNewsId);
            if(!$checkIfNewsExists){
                print_r("News doesn't exist");
                return 0;
            }

            $db = DB::getInstance();
            $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES(:body, :created_at, :news_id)";
            $parameters = [
                ':body' => $escapedBody,
                ':created_at' => date('Y-m-d'),
                ':news_id' => $escapedNewsId
            ];
            $db->execute($sql, $parameters);
            return $db->lastInsertId() ?? 0;
        } catch (\Exception $e) {
            print_r('Error adding comment');
            return 0;
        }
    }

    /*
     * Delete a comment by id
     */
    public function deleteComment($id): bool
    {
        try {
            if (!is_numeric($id)) {
                print_r("Please insert an numeric value");
                return false;
            }
            $escapedId = Helper::escapedInput($id);
            $db = DB::getInstance();

            $sql = "SELECT `id` from `comment` WHERE `id` = $escapedId";
            $checkIfCommentExists = $db->select($sql);
            if (!count($checkIfCommentExists)) {
                print_r("Comment doesn't exist");
                return false;
            }

            $sql = "DELETE FROM `comment` WHERE `id` = :id";
            $parameters = [':id' => $escapedId];
            return $db->execute($sql, $parameters);
        } catch (\Exception $e) {
            print_r('Error deleting comment');
            return false;
        }
    }

    /*
     * Get all comments from a news id
     */
    public function getCommentsByNewsId($newsId): array
    {
        try {
            $db = DB::getInstance();
            $sql = "SELECT * FROM `comment` WHERE `news_id` = $newsId";
            $rows = $db->select($sql);

            return array_map(
                fn($row) => new Comment(
                    id: $row['id'],
                    body: $row['body'],
                    createdAt: $row['created_at'],
                    newsId: $row['news_id']
                ),
                $rows
            );
        } catch (\Exception $e) {
            print_r('Error fetching comments by news id');
            return [];
        }
    }
}