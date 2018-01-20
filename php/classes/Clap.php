<?php
namespace Edu\Cnm\DataDesign;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

class Clap {
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

}
