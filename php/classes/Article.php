<?php
namespace Edu\Cnm\DataDesign;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Cross Section of a "Medium" article
 *
 *This is a cross section of what is likely stored when a user posts an article on Medium. This entity is a top-level entity and holds the keys to the other entities I will be using: Clap.
 *
 * @author Kenneth Keyes kkeyes1@cnm.edu updated  for /~kkeyes1/data-design
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 4.0.0
 * @package Edu\Cnm\DataDesign
 **/

class Article {
	use ValidateDate;
	use ValidateUuid;
	/**
	 * id for this article: primary key
	 * @var Uuid $articleID
	 **/
	private $articleId;
	/**
	 * this is the profile Id associated with this article: foreign key
	 * @var Uuid $articleAuthorProfileId
	 **/
	private $articleAuthorProfileId;
	/**
	 * text content of the article (for this exercise this attribute has been limited to 140 characters)
	 * @var string $articleContent
	 **/
	private $articleContent;
	/**
	 * date and time the article was published in a PHP date time object
	 * @var \DateTime $articleDate
	 **/
	private $articleDate;

	/**
	 * constructor for this article
	 *
	 * @param string|Uuid $newArticleId id of this article or null if a new article
	 * @param string|Uuid $newArticleAuthorProfileId id of the Profile that sent this article
	 * @param string $newArticleContent string containing actual article data
	 * @param \DateTime|string|null $newArticleDate date and time article was sent or null if set to current date and time
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newArticleId, $newArticleAuthorProfileId, string $newArticleContent, $newArticleDate = null) {
		try {
			$this->setArticleId($newArticleId);
			$this->setArticleAuthorProfileId($newArticleAuthorProfileId);
			$this->setArticleContent($newArticleContent);
			$this->setArticleDate($newArticleDate);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for article id
	 *
	 * @return Uuid value of article id
	 **/
	public function getArticleId(): Uuid {
		return ($this->articleId);
	}

	/**
	 * mutator method for article id
	 *
	 * @param Uuid/string $newArticleId new value of article id
	 * @throws \RangeException if $newArticleId is not positive
	 * @throws \TypeError if $newArticleId is not a uuid or string
	 **/
	public function setArticleId($newArticleId): void {
		try {
			$uuid = self::validateUuid($newArticleId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the tweet id
		$this->articleId = $uuid;
	}

	/**
	 * accessor method for article author's profile id
	 *
	 * @return Uuid value of article author's profile id
	 **/
	public function getArticleAuthorProfileId(): Uuid {
		return ($this->articleAuthorProfileId);
	}

	/**
	 * mutator method for article author's profile id
	 *
	 * @param string | Uuid $newArticleAuthorProfileId new value of article author's profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newTweetProfileId is not an integer
	 **/
	public function setArticleAuthorProfileId($newArticleAuthorProfileId): void {
		try {
			$uuid = self::validateUuid($newArticleAuthorProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile id
		$this->articleAuthorProfileId = $uuid;
	}

	/**
	 * accessor method for article content
	 *
	 * @return string value of aricle content
	 **/
	public function getArticleContent(): string {
		return ($this->articleContent);
	}

	/**
	 * mutator method for article content
	 *
	 * @param string $newArticleContent new value of article content
	 * @throws \InvalidArgumentException if $enwArticleContent is not a string or insecure
	 * @throws \RangeException if $newArticleContent is > 140 characters (unrealistic, but that is how I build the database)
	 * @throws \TypeError if $newArticleContent is not a string
	 **/
	public function setArticleContent(string $newArticleContent): void {
		// verify the tweet content is secure
		$newArticleContent = trim($newArticleContent);
		$newArticleContent = filter_var($newArticleContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newArticleContent) === true) {
			throw(new \InvalidArgumentException("article content is empty or insecure"));
		}
		// verify the article content will fit in the database
		if(strlen($newArticleContent) > 140) {
			throw(new \RangeException("article content too large"));
		}
		// store the article content
		$this->articleContent = $newArticleContent;
	}

	/**
	 * accessor method for article date
	 *
	 * @return \DateTime value of article date
	 **/
	public function getArticleDate(): \DateTime {
		return ($this->articleDate);
	}

	/**
	 * mutator method for article date
	 *
	 * @param \DateTime|string|null $newArticleDate article date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newArticleDate is not a valid object or string
	 * @throws \RangeException if $newArticleDate is a date that does not exist
	 **/
	public function setArticleDate($newArticleDate = null): void {
		// base case: if the date is null, use the current date and time
		if($newArticleDate === null) {
			$this->articleDate = new \DateTime();
			return;
		}
		// store the like date using the ValidateDate trait
		try {
			$newArticleDate = self::validateDateTime($newArticleDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->articleDate = $newArticleDate;
	}
}