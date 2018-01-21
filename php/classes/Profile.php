<?php
namespace Edu\Cnm\DataDesign;

require_once("autoload.php");
require_once(dirname(__DIR__) . "autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Cross Section of a "Medium" Profile
 *
 *This is a cross section of what is likely stored in a User's Profile on Medium. This entity is a top-level entity and holds the keys to the other entities I will be using: Article and Clap.
 *
 * @author Kenneth Keyes kkeyes1@cnm.edu updated  for /~kkeyes1/data-design
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 4.0.0
 * @package Edu\Cnm\DataDesign
 **/

class Profile {
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
	 * caption: this would most likely be stored in a different database...but I included it in my preliminary design here...
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
	 * @param Uuid | string $newProfileId with the value of profileId
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError id profile id is not positive
	 **/
	public function setProfileId( newProfileId): void {
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
	public function setProfileFullName(string $newProfileFullName) : void {
		// verify the full name is secure
		$newProfileFullName = trim($newProfileFullName);
		$newProfileFullName = filter_var($newProfileFullName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileFullName) === true) {
			throw(new \InvalidArgumentException("profile full name is empty or insecure"));
		}
		// verify the full name will fit in the database
		if(strlen($newProfileFullName) > 32) {
			throw(new \RangeException("profile full name is too large"));
		}
		// store the full name
		$this->profileFullName = $newProfileFullName;
	}
	/**
	 * accessor method for account activation token
	 *
	 * @return string value of the activation token
	 **/
	public function getProfileActivationToken() : ?string {
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
	public function getProfileCaption() :string {
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
	public function setProfileCaption(string $newProfileCaption) : void {
		// verify the profile caption is secure
		$newProfileCaption = trim($newProfileCaption);
		$newProfileCaption = filter_var($newProfileCaption, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileCaption) === true) {
			throw(new \InvalidArgumentException("tweet content is empty or insecure"));
		}

		// verify the tweet content will fit in the database
		if(strlen($newProfileCaption) > 140) {
			throw(new \RangeException("tweet content too large"));
		}

		// store the tweet content
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
		$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
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

}
