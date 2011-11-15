<?php
namespace BCC\AutoMapperBundle\Tests\Fixtures;

/**
 * @author Brian Feaver <brian.feaver@gmail.com>
 */
class PrivateDestinationPost
{
	private $id;
	private $title;
    private $description;
    private $author;

	public function getId()
	{
		return $this->id;
	}

	public function setAuthor($author)
	{
		$this->author = $author;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}
}
