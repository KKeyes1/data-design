<?php
namespace Edu\Cnm\DataDesign;



require_once("autoload.php");
require_once(dirname(__DIR__, 4) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Cross Section of a "Medium" Profile
 *
 *This is a cross section of what is likely stored in a User's Profile on Medium. This entity is a top-level entity and holds the keys to the other entities I will be using: Article and Clap.
 *
 * @author Kenneth Keyes kkeyes1@cnm.edu updated for /~kkeyes1/data-design
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 4.0.0
 * @package Edu\Cnm\DataDesign
 **/

class Profile implements \JsonSerializable {
	use ValidateUuid;

	/**
	 * id for this profile: primary key
	 * @var Uuid $profileId
	 **/
	private $profileId;
	/**
	 * this is the Full Name associated with this account
	 * @var string $profileFullName
	 **/
	private $profileFullName;
	/**
	 * token handed out to verify that account is not malicious
	 * @var string $profileActivationToken
	 **/
	private $profileActivationToken;
	/**
	 * user defined caption (this would most likely be stored in a different database...but I included it in my preliminary design here)
	 * @var string $profileCaption
	 **/
	private $profileCaption;
	/**
	 * email associated with this profile; this is a unique index
	 * @var string $profileEmail
	 **/
	private $profileEmail;
	/**
	 * hash for profile password
	 * @var string $profileHash
	 **/
	private $profileHash;
	/**
	 * phone number stored for this profile without "-"
	 * @var string $profilePhone
	 **/
	private $profilePhone;
	/**
	 * salt stored for this profile
	 * @var string $profileSalt
	 **/
	private $profileSalt;

	/**
	 * constructor for this Profile
	 *
	 * @param string|Uuid $newProfileId id of this Profile or null if a new Profile
	 * @param string $newProfileActivationToken activation token to safe guard against malicious accounts
	 * @param string $newProfileFullName string containing newProfileFullName
	 * @param string $newProfileCaption string containing newProfileCaption can be null
	 * @param string $newProfileEmail string containing email
	 * @param string $newProfileHash string containing password hash
	 * @param string $newProfilePhone string containing phone number
	 * @param string $newProfileSalt string containing password salt
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if a data type violates a data hint
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newProfileId, ?string $newProfileActivationToken, string $newProfileFullName, string $newProfileCaption, string $newProfileEmail, string $newProfileHash, ?string $newProfilePhone, string $newProfileSalt) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileActivationToken($newProfileActivationToken);
			$this->setProfileFullName($newProfileFullName);
			$this->setProfileCaption($newProfileCaption);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileHash($newProfileHash);
			$this->setProfilePhone($newProfilePhone);
			$this->setProfileSalt($newProfileSalt);
		} catch(\InvalidArgumentException | \RangeException |\TypeError | \Exception $exception) {
			//determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * accessor method for getting profileId
	 *
	 * @return Uuid value for profileId (or null if new profile)
	 **/
	public function getProfileId(): Uuid {
		return ($this->profileId);
	}
	/**
	 * mutator function for profileId
	 *
	 * @param Uuid|string $newProfileId with the value of profileId
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if profile id is not positive
	 **/
	public function setProfileId($newProfileId): void {
		try {
			$uuid = self::validateUuid($newProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile id
		$this->profileId = $uuid;
	}
	/**
	 * accessor method for full name
	 *
	 * @return string value of full name
	 **/
	public function getProfileFullName(): string {
		return ($this->profileFullName);
	}
	/**
	 * mutator method for full name
	 *
	 * @param string $newProfileFullName new value of full name
	 * @throws \InvalidArgumentException if $newProfileFullName is not a string or insecure
	 * @throws \RangeException if $newProfileFullName is > 32 characters (may not work for all names, but I did set this field to 32 in my database so I am sticking with it)
	 * @throws \TypeError if $newProfileFullName is not a string
	 **/
	public function setProfileFullName(string $newProfileFullName): void {
		// verify the full name is secure
		$newProfileFullName = trim($newProfileFullName);
		$newProfileFullName = filter_var($newProfileFullName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileFullName) === true) {
			throw(new \InvalidArgumentException("name is empty or insecure"));
		}
		// verify the full name will fit in the database
		if(strlen($newProfileFullName) > 32) {
			throw(new \RangeException("name is too large"));
		}
		// store the full name
		$this->profileFullName = $newProfileFullName;
	}
	/**
	 * accessor method for account activation token
	 *
	 * @return string value of the activation token
	 **/
	public function getProfileActivationToken(): ?string {
		return ($this->profileActivationToken);
	}
	/**
	 * mutator method for account activation token
	 *
	 * @param string $newProfileActivationToken
	 * @throws \InvalidArgumentException  if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 **/
	public function setProfileActivationToken(?string $newProfileActivationToken): void {
		if($newProfileActivationToken === null) {
			$this->profileActivationToken = null;
			return;
		}
		$newProfileActivationToken = strtolower(trim($newProfileActivationToken));
		if(ctype_xdigit($newProfileActivationToken) === false) {
			throw(new\RangeException("user activation is not valid"));
		}
		//make sure user activation token is only 32 characters
		if(strlen($newProfileActivationToken) !== 32) {
			throw(new\RangeException("user activation token has to be 32"));
		}
		$this->profileActivationToken = $newProfileActivationToken;
	}
	/**
	 * accessor method for profile caption
	 *
	 * @return string value of profile caption
	 **/
	public function getProfileCaption(): string {
		return($this->profileCaption);
	}
	/**
	 * mutator method for profile caption
	 *
	 * @param string $newProfileCaption new value of profile caption
	 * @throws \InvalidArgumentException if $newProfileCaption is not a string or insecure
	 * @throws \RangeException if $newProfileCaption is > 140 characters
	 * @throws \TypeError if $newProfileCaption is not a string
	 **/
	public function setProfileCaption(string $newProfileCaption): void {
		// verify the profile caption is secure
		$newProfileCaption = trim($newProfileCaption);
		$newProfileCaption = filter_var($newProfileCaption, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileCaption) === true) {
			throw(new \InvalidArgumentException("caption is empty or insecure"));
		}

		// verify the caption will fit in the database
		if(strlen($newProfileCaption) > 140) {
			throw(new \RangeException("caption is too large"));
		}

		// store the caption
		$this->profileCaption = $newProfileCaption;
	}
	/**
	 * accessor method for email
	 *
	 * @return string value of email
	 **/
	public function getProfileEmail(): string {
		return $this->profileEmail;
	}
	/**
	 * mutator method for email
	 *
	 * @param string $newProfileEmail new value of email
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/
	public function setProfileEmail(string $newProfileEmail): void {
		// verify the email is secure
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_SANITIZE_EMAIL);
		if(empty($newProfileEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newProfileEmail) > 128) {
			throw(new \RangeException("profile email is too large"));
		}
		// store the email
		$this->profileEmail = $newProfileEmail;
	}
	/**
	 * accessor method for profileHash
	 *
	 * @return string value of hash
	 **/
	public function getProfileHash(): string {
		return $this->profileHash;
	}
	/**
	 * mutator method for profile hash password
	 *
	 * @param string $newProfileHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if profile hash is not a string
	 **/
	public function setProfileHash(string $newProfileHash): void {
		//enforce that the hash is properly formatted
		$newProfileHash = trim($newProfileHash);
		$newProfileHash = strtolower($newProfileHash);
		if(empty($newProfileHash) === true) {
			throw(new \InvalidArgumentException("profile password hash empty or insecure"));
		}
		//enforce that the hash is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileHash)) {
			throw(new \InvalidArgumentException("profile password hash is empty or insecure"));
		}
		//enforce that the hash is exactly 128 characters.
		if(strlen($newProfileHash) !== 128) {
			throw(new \RangeException("profile hash must be 128 characters"));
		}
		//store the hash
		$this->profileHash = $newProfileHash;
	}
	/**
	 * accessor method for phone
	 *
	 * @return string value of phone or null
	 **/
	public function getProfilePhone(): ?string {
		return ($this->profilePhone);
	}
	/**
	 * mutator method for phone
	 *
	 * @param string $newProfilePhone new value of phone
	 * @throws \InvalidArgumentException if $newPhone is not a string or insecure
	 * @throws \RangeException if $newPhone is > 32 characters
	 * @throws \TypeError if $newPhone is not a string
	 **/
	public function setProfilePhone(?string $newProfilePhone): void {
		//if $profilePhone is null return it right away
		if($newProfilePhone === null) {
			$this->profilePhone = null;
			return;
		}
		// verify the phone is secure
		$newProfilePhone = trim($newProfilePhone);
		$newProfilePhone = filter_var($newProfilePhone, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfilePhone) === true) {
			throw(new \InvalidArgumentException("profile phone is empty or insecure"));
		}
		// verify the phone will fit in the database
		if(strlen($newProfilePhone) > 32) {
			throw(new \RangeException("profile phone is too large"));
		}
		// store the phone
		$this->profilePhone = $newProfilePhone;
	}
	/**
	 *accessor method for profile salt
	 *
	 * @return string representation of the salt hexadecimal
	 */
	public function getProfileSalt(): string {
		return $this->profileSalt;
	}
	/**
	 * mutator method for profile salt
	 *
	 * @param string $newProfileSalt
	 * @throws \InvalidArgumentException if the salt is not secure
	 * @throws \RangeException if the salt is not 64 characters
	 * @throws \TypeError if the profile salt is not a string
	 */
	public function setProfileSalt(string $newProfileSalt): void {
		//enforce that the salt is properly formatted
		$newProfileSalt = trim($newProfileSalt);
		$newProfileSalt = strtolower($newProfileSalt);
		//enforce that the salt is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileSalt)) {
			throw(new \InvalidArgumentException("profile password hash is empty or insecure"));
		}
		//enforce that the salt is exactly 64 characters.
		if(strlen($newProfileSalt) !== 64) {
			throw(new \RangeException("profile salt must be 128 characters"));
		}
		//store the hash
		$this->profileSalt = $newProfileSalt;
	}
		/**
		 * inserts this Profile into mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError if $pdo is not a PDO connection object
		 **/
		public function insert(\PDO $pdo): void {
			// create query template
			$query = "INSERT INTO profile(profileId, profileActivationToken, profileFullName, profileCaption,  profileEmail, profileHash, profilePhone, profileSalt) VALUES (:profileId, :profileActivationToken, :profileFullName, :profileCaption, :profileEmail, :profileHash, :profilePhone, :profileSalt)";
			$statement = $pdo->prepare($query);
			//bind the member variables to the place holders in the template
			$parameters = ["profileId" => $this->profileId->getBytes(), "profileActivationToken" => $this->profileActivationToken, "profileFullName" => $this->profileFullName, "profileCaption" => $this->profileCaption, "profileEmail" => $this->profileEmail, "profileHash" => $this->profileHash,"profilePhone" => $this->profilePhone, "profileSalt" => $this->profileSalt];
			$statement->execute($parameters);
		}
		/**
		 * deletes this Profile from mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError if $pdo is not a PDO connection object
		 **/
		public function delete(\PDO $pdo): void {
			// create query template
			$query = "DELETE FROM profile WHERE profileId = :profileId";
			$statement = $pdo->prepare($query);
			//bind the member variables to the place holders in the template
			$parameters = ["profileId" => $this->profileId->getBytes()];
			$statement->execute($parameters);
		}
		/**
		 * updates this Profile from mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL related errors occur
		 **/
		public function update(\PDO $pdo): void {
			// create query template
			$query = "UPDATE profile SET profileId = :profileId, profileActivationToken = :profileActivationToken, profileFullName = :profileFullName, profileCaption = :profileCaption, profileEmail = :profileEmail, profileHash = :profileHash, profilePhone = :profilePhone, profileSalt = :profileSalt WHERE profileId = :profileId";
			$statement = $pdo->prepare($query);
			// bind the member variables to the place holders in the template
			$parameters = ["profileId" => $this->profileId->getBytes(), "profileActivationToken" => $this->profileActivationToken, "profileFullName" => $this->profileFullName, "profileCaption" => $this->profileCaption, "profileEmail" => $this->profileEmail, "profileHash" => $this->profileHash, "profilePhone" => $this->profilePhone, "profileSalt" => $this->profileSalt];
			$statement->execute($parameters);
		}
		/**
		 * gets the Profile by profile id
		 *
		 * @param \PDO $pdo $pdo PDO connection object
		 * @param string|Uuid $profileId profile Id to search for
		 * @return Profile|null Profile or null if not found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when a variable are not the correct data type
		 **/
		public static function getProfileByProfileId(\PDO $pdo, $profileId): ?Profile {
			// sanitize the profile id before searching
			try {
				$profileId = self::validateUuid($profileId);
			} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			// create query template
			$query = "SELECT profileId, profileActivationToken, profileFullName, profileCaption, profileEmail, profileHash, profilePhone, profileSalt FROM profile WHERE profileId = :profileId";
			$statement = $pdo->prepare($query);
			// bind the profile id to the place holder in the template
			$parameters = ["profileId" => $profileId->getBytes()];
			$statement->execute($parameters);
			// grab the Profile from mySQL
			try {
				$profile = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$row = $statement->fetch();
				if($row !== false) {
					$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileFullName"], $row["profileCaption"],$row["profileEmail"], $row["profileHash"], $row["profilePhone"], $row["profileSalt"]);
				}
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			return ($profile);
		}
		/**
		 * gets the Profile by email
		 *
		 * @param \PDO $pdo PDO connection object
		 * @param string $profileEmail email to search for
		 * @return Profile|null Profile or null if not found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 **/
		public static function getProfileByProfileEmail(\PDO $pdo, string $profileEmail): ?Profile {
			// sanitize the email before searching
			$profileEmail = trim($profileEmail);
			$profileEmail = filter_var($profileEmail, FILTER_VALIDATE_EMAIL);
			if(empty($profileEmail) === true) {
				throw(new \PDOException("not a valid email"));
			}
			// create query template
			$query = "SELECT profileId, profileActivationToken, profileFullName, profileCaption, profileEmail, profileHash, profilePhone, profileSalt FROM profile WHERE profileEmail = :profileEmail";
			$statement = $pdo->prepare($query);
			// bind the profile email to the place holder in the template
			$parameters = ["profileEmail" => $profileEmail];
			$statement->execute($parameters);
			// grab the Profile from mySQL
			try {
				$profile = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$row = $statement->fetch();
				if($row !== false) {
					$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileFullName"], $row["profileCaption"], $row["profileEmail"], $row["profileHash"], $row["profilePhone"], $row["profileSalt"]);
				}
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			return ($profile);
		}
		/**
		 * gets the Profile by full name
		 *
		 * @param \PDO $pdo PDO connection object
		 * @param string $profileFullName full name to search for
		 * @return \SPLFixedArray of all profiles found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 **/
		public static function getProfileByProfileFullName(\PDO $pdo, string $profileFullName): \SPLFixedArray {
			// sanitize the full name before searching
			$profileFullName = trim($profileFullName);
			$profileFullName = filter_var($profileFullName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			if(empty($profileFullName) === true) {
				throw(new \PDOException("not a valid name"));
			}
			// create query template
			$query = "SELECT  profileId, profileActivationToken, profileFullName, profileCaption, profileEmail, profileHash, profilePhone, profileSalt FROM profile WHERE profileAtHandle = :profileAtHandle";
			$statement = $pdo->prepare($query);
			// bind the name to the place holder in the template
			$parameters = ["profileFullName" => $profileFullName];
			$statement->execute($parameters);
			$profiles = new \SPLFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while (($row = $statement->fetch()) !== false) {
				try {
					$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileFullName"], $row["profileCaption"], $row["profileEmail"], $row["profileHash"], $row["profilePhone"], $row["profileSalt"]);
					$profiles[$profiles->key()] = $profile;
					$profiles->next();
				} catch(\Exception $exception) {
					// if the row couldn't be converted, rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
			return ($profiles);
		}
		/**
		 * get the profile by profile activation token
		 *
		 * @param string $profileActivationToken
		 * @param \PDO object $pdo
		 * @return Profile|null Profile or null if not found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 **/
		public
		static function getProfileByProfileActivationToken(\PDO $pdo, string $profileActivationToken): ?Profile {
			//make sure activation token is in the right format and that it is a string representation of a hexadecimal
			$profileActivationToken = trim($profileActivationToken);
			if(ctype_xdigit($profileActivationToken) === false) {
				throw(new \InvalidArgumentException("profile activation token is empty or in the wrong format"));
			}
			//create the query template
			$query = "SELECT  profileId, profileActivationToken, profileFullName, profileCaption, profileEmail, profileHash, profilePhone, profileSalt FROM profile WHERE profileActivationToken = :profileActivationToken";
			$statement = $pdo->prepare($query);
			// bind the profile activation token to the placeholder in the template
			$parameters = ["profileActivationToken" => $profileActivationToken];
			$statement->execute($parameters);
			// grab the Profile from mySQL
			try {
				$profile = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$row = $statement->fetch();
				if($row !== false) {
					$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileFullName"], $row["profileCaption"], $row["profileEmail"], $row["profileHash"], $row["profilePhone"], $row["profileSalt"]);
				}
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			return ($profile);
		}
		/**
		 * formats the state variables for JSON serialization
		 *
		 * @return array resulting state variables to serialize
		 **/
		public function jsonSerialize() {
			$fields = get_object_vars($this);
			$fields["profileId"] = $this->profileId->toString();
			unset($fields["profileActivationToken"]);
			unset($fields["profileHash"]);
			unset($fields["profileSalt"]);
			return ($fields);
		}
}
