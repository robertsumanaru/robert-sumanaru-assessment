<?php

require_once(ROOT . '/utils/DB.php');
require_once(ROOT . '/utils/CommentManager.php');
require_once(ROOT . '/class/News.php');
require_once(ROOT . '/utils/Helper.php');

/**
 * Singleton class responsible for managing news
 */
class NewsManager
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
            self::$instance = new NewsManager();
        }
        return self::$instance;
    }

    /**
     * List all news
     */
    public function listNews(): array
    {
        try {
            $db = DB::getInstance();
            $rows = $db->select('SELECT * FROM `news`');

            return array_map(
                fn($row) => new News(
                    id: $row['id'],
                    title: $row['title'],
                    body: $row['body'],
                    createdAt: $row['created_at']
                ),
                $rows
            );
        } catch (\Exception $e) {
            print_r('Error listing news');
            return [];
        }
    }

    /**
     * Add
     */
    public function addNews(string $title, string $body): int
    {
        try {
            $escapedTitle = Helper::escapedInput($title);
            $escapedBody = Helper::escapedInput($body);

            $db = DB::getInstance();
            $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES(:title, :body, :created_at)";
            $parameters = [
                ':title' => $escapedTitle,
                ':body' => $escapedBody,
                ':created_at' => date('Y-m-d')
            ];
            $db->execute($sql, $parameters);
            return $db->lastInsertId() ?? 0;
        } catch (\Exception $e) {
            print_r('Error adding news');
            return 0;
        }
    }

    /**
	* deletes a news, and also linked comments
	*/
	public function deleteNews($id): bool
	{
        try {
            if(!is_numeric($id)){
                print_r("Please insert an numeric value");
                return false;
            }
            $escapedId = Helper::escapedInput($id);

            $db = DB::getInstance();

            $checkIfNewsExists = self::checkIfNewsExists($escapedId);

            if(!$checkIfNewsExists) {
                print_r("News doesn't exist");
                return false;
            }

            $commentManager = CommentManager::getInstance();
            $idsToDelete = $commentManager->getCommentsByNewsId($escapedId);

            foreach ($idsToDelete as $idToDelete) {
                $commentManager->deleteComment($idToDelete->getId());
            }

            $sql = "DELETE FROM `news` WHERE `id` = :id";
            $parameters = array(':id' => $escapedId);
            return $db->execute($sql, $parameters);
        } catch (\Exception $e) {
            print_r('Error deleting news');
            return false;
        }
	}

    public static function checkIfNewsExists($newsId): bool
    {
        $db = DB::getInstance();

        $sql = "SELECT `id` from `news` WHERE `id` = $newsId";
        $checkIfNewsExists = $db->select($sql);
        if(!count($checkIfNewsExists)) {
            return false;
        }
        return true;
    }
}