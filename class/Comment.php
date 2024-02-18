<?php

/**
 * Class for comment, it will represent the Model of the comment table
 */
class Comment
{
	protected $id, $body, $createdAt, $newsId;

    public function __construct($id, $body, $createdAt, $newsId){
        $this->id = $id;
        $this->body = $body;
        $this->createdAt = $createdAt;
        $this->newsId = $newsId;
    }

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setBody($body)
	{
		$this->body = $body;
		return $this;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;
		return $this;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	public function getNewsId()
	{
		return $this->newsId;
	}

	public function setNewsId($newsId)
	{
		$this->newsId = $newsId;
		return $this;
	}
}