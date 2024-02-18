<?php

define('ROOT', __DIR__);
require_once(ROOT . '/utils/NewsManager.php');
require_once(ROOT . '/utils/CommentManager.php');


//$commentManager = CommentManager::getInstance();
//$newsManager = NewsManager::getInstance();
//print_r($commentManager->listComments());
//$lastCommentId = $commentManager->addCommentForNews('This is also a comment', 40);
//print_r($lastCommentId);
//$deleteComment = $commentManager->deleteComment(45);
//print_r($deleteComment);


//print_r($newsManager->listNews());
//$lastInsertedNews = $newsManager->addNews("This is a brand new news", 'This is my new body');
//$lastCommentId = $commentManager->addCommentForNews('This is my new comment', $lastInsertedNews);
//print_r($lastInsertedNews);
//print_r($lastCommentId);
//$deletedNews = $newsManager->deleteNews(18);
//print_r($deletedNews);


$commentManager = CommentManager::getInstance();
foreach (NewsManager::getInstance()->listNews() as $news) {
	echo("############ NEWS " . $news->getTitle() . " ############\n");
	echo($news->getBody() . "\n");
    foreach ($commentManager->getCommentsByNewsId($news->getId()) as $comment) {
        echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
    }
}