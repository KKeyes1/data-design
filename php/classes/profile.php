<?php
namespace Edu\Cnm\DataDesign;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Cross Section of a "Medium" Profile
 *
 *This is a cross section of what is likely stored in a User's Profile on Medium. This entity is a top-level entity and holds the keys to the other entities I will be using: Article and Clap.
 *
 * @author Kenneth Keyes kkeyes1@cnm.edu
 * @package Edu\Cnm\DataDesign
 **/

class profile {
	use validateUuid;

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




}
