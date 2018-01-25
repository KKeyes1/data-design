<?php
namespace Edu\Cnm\DataDesign;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "../vendor/autoload.php");

use Ramsey\Uuid\Uuid;

class Clap implements \JsonSerializable {
	use ValidateDate;
	use ValidateUuid;
	/**
	 * id of the article that this clap is for; this is a foreign key
	 * @var Uuid $clapArticleId
	 **/
	private $clapArticleId;
	/**
	 * id of the Profile that sent this clap; this is a foreign key
	 * @var Uuid $clapProfilId
	 **/
	private $clapProfileId;
	/**
	 * date and time this clap was sent, in a PHP DateTime object
	 * @var \DateTime $clapDate
	 **/
	private $clapDate;

	/**
	 * constructor for this article
	 *
	 * @param string|Uuid $newClapArticleId id of this article or null if a new article
	 * @param string|Uuid $newClapProfileId of the Profile that sent this article
	 * @param \DateTime|string|null $newClapDate date and time article was sent or null if set to current date and time/**
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newClapArticleId, $newClapProfileId, $newClapDate, $newArticleDate = null) {
		try {
			$this->setClapArticleId($newClapArticleId);
			$this->setClapProfileId($newClapProfileId);
			$this->setClapDate($newClapDate);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for clap article id
	 *
	 * @return Uuid value of clap article id
	 **/
	public function getClapArticleId() : Uuid{
		return($this->clapArticleId);
	}
	/**
	 * mutator method for clap article id
	 *
	 * @param string | Uuid $newClapArticleId new value of clap article id
	 * @throws \RangeException if $newClapArticleId is not positive
	 * @throws \TypeError if $newClapArticleId is not an integer
	 **/
	public function setClapArticleId($newClapArticleId) : void {
		try {
			$uuid = self::validateUuid($newClapArticleId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile id
		$this->clapArticleId= $uuid;
	}
	/**
	 * accessor method for clap profile id
	 *
	 * @return Uuid value of clap profile id
	 **/
	public function getClapProfileId() : Uuid{
		return($this->clapProfileId);
	}
	/**
	 * mutator method for clap profile id
	 *
	 * @param string | Uuid $newClapProfileId new value of clap profile id
	 * @throws \RangeException if $newClapProfileId is not positive
	 * @throws \TypeError if $newClapProfileId is not an integer
	 **/
	public function setClapProfileId($newClapProfileId) : void {
		try {
			$uuid = self::validateUuid($newClapProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile id
		$this->clapProfileId = $uuid;
	}
	/**
	 * accessor method for clap date
	 *
	 * @return \DateTime value of clap date
	 **/
	public function getClapDate() : \DateTime {
		return($this->clapDate);
	}
	/**
	 * mutator method for clap date
	 *
	 * @param \DateTime|string|null $newClapDate clap date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newClapDate is not a valid object or string
	 * @throws \RangeException if $newClapDate is a date that does not exist
	 **/
	public function setClapDate($newClapDate = null) : void {
		// base case: if the date is null, use the current date and time
		if($newClapDate === null) {
			$this->clapDate = new \DateTime();
			return;
		}
		// store the like date using the ValidateDate trait
		try {
			$newClapDate = self::validateDateTime($newClapDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->clapDate = $newClapDate;
	}
	/**
	 * inserts this clap into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public function insert(\PDO $pdo) : void {
		// create query template
		$query = "INSERT INTO clap(clapArticleId, clapProfileId, likeDate) VALUES(:clapArticleId, :clapProfileId, :likeDate)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$formattedDate = $this->clapArticleId->format("Y-m-d H:i:s.u");
		$parameters = ["clapArticleId" => $this->clapArticleId->getBytes(), "clapProfileId" => $this->clapProfileId->getBytes(), "clapDate" => $formattedDate];
		$statement->execute($parameters);
	}
	/**
	 * deletes this clap from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public function delete(\PDO $pdo) : void {
		// create query template
		$query = "DELETE FROM clap WHERE clapArticleId = :clapArticleId AND clapProfileId = :clapTweetId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the placeholders in the template
		$parameters = ["clapArticleId" => $this->clapArticleId->getBytes(), "clapProfileId" => $this->clapProfileId->getBytes()];
		$statement->execute($parameters);
	}
	/**
	 * gets the clap by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string|Uuid $clapProfileId profile id to search for
	 * @return \SplFixedArray SplFixedArray of Likes found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public static function getClapByClapProfileId(\PDO $pdo, $clapProfileId) : \SPLFixedArray {
		try {
			$clapProfileId = self::validateUuid($clapProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT clapArticleId, clapProfileId, clapDate FROM clap WHERE clapProfileId = :clapProfileId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["clapProfileId" => $clapProfileId->getBytes()];
		$statement->execute($parameters);
		// build an array of claps
		$claps = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$clap = new Clap($row["clapArticleId"], $row["clapProfileId"], $row["clapDate"]);
				$claps[$claps->key()] = $clap;
				$claps->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($claps);
	}
	/**
	 * gets the clap by article id and profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string|Uuid $clapArticleId tweet id to search for
	 * @param string|Uuid $clapProfileId profile id to search for
	 * @return Like|null Like found or null if not found
	 */
	public static function getClapByClapArticleIdAndClapProfileId(\PDO $pdo, $clapArticleId, $clapProfileId) : ?Clap {
		//
		try {
			$clapArticleId = self::validateUuid($clapArticleId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		try {
			$clapProfileId = self::validateUuid($clapProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT clapArticleId, clapProfileId, clapDate FROM clap WHERE clapArticleId = :clapArticleId AND clapProfileId = :clapProfileId";
		$statement = $pdo->prepare($query);
		// bind the article id and profile id to the place holder in the template
		$parameters = ["clapArticleId" => $clapArticleId->getBytes(), "clapProfileId" => $clapProfileId->getBytes()];
		$statement->execute($parameters);
		// grab the clap from mySQL
		try {
			$clap = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$clap = new Clap($row["clapArticleId"], $row["clapProfileId"], $row["clapDate"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($clap);
	}
	/**
	 * gets the clap by article id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string|Uuid $clapArticleId article id to search for
	 * @return \SplFixedArray array of Likes found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public static function getClapByClapArticleId(\PDO $pdo, $clapArticleId) : \SplFixedArray {
		try {
			$clapArticleId = self::validateUuid($clapArticleId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT clapArticleId, clapProfileId, clapDate FROM clap WHERE clapArticleId = :clapArticleId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["clapArticleId" => $clapArticleId->getBytes()];
		$statement->execute($parameters);
		// build the array of likes
		$claps = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$clap = new Clap($row["clapArticleId"], $row["clapProfileId"], $row["clapDate"]);
				$claps[$claps->key()] = $clap;
				$claps->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($claps);
	}
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);
		$fields["clapArticleId"] = $this->clapArticleId->toString();
		$fields["clapProfileId"] = $this->clapProfileId->toString();
		$fields["clapDate"] = round(floatval($this->clapDate->format("U.u"))*1000);
		return($fields);
	}

}
