ALTER DATABASE medium CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS clap;
DROP TABLE IF EXISTS article;
DROP TABLE IF EXISTS profile;

CREATE TABLE profile (
	profileId BINARY(16) NOT NULL,
	profileActivationToken CHAR(32),
	profileFullName VARCHAR(32) NOT NULL,
	profileCaption VARCHAR(140) NOT NULL,
	profileEmail VARCHAR(128) NOT NULL,
	profileHash	CHAR(128) NOT NULL,
	profilePhone VARCHAR(32),
	profileSalt CHAR(64) NOT NULL,
	UNIQUE(profileEmail),
	PRIMARY KEY(profileId)
);

CREATE TABLE article (
	articleId BINARY(16) NOT NULL,
	articleAuthorProfileId BINARY(16) NOT NULL,
	articleContent VARCHAR(140) NOT NULL,
	articleDate DATETIME(6) NOT NULL,
	INDEX(articleAuthorProfileId),
	FOREIGN KEY(articleProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(articleId)
);

CREATE TABLE clap (
	clapArticleId BINARY(16) NOT NULL,
	clapProfileId BINARY(16) NOT NULL,
	clapDate DATETIME(6) NOT NULL,
	INDEX(clapArticleId),
	INDEX(clapProfileId),
	-- create the foreign key relations
	FOREIGN KEY(clapArticleId) REFERENCES article(articleId),
	FOREIGN KEY(clapProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(clapArticleId, clapProfileId)
);

SELECT profileID
FROM profile
WHERE profileFullName = "Kenneth Keyes";

SELECT articleDate, articleID, profileFullName
FROM article
INNER JOIN profile ON profile.profileID = article.articleAuthorProfileId
WHERE articleDate = "August 2017";

UPDATE profile
SET profileCaption = "Is this thing on?"
WHERE profileFullName = "Kenneth Keyes";

UPDATE article
SET articleContent = "h4ck 743 P14n37"
WHERE articleAuthorProfileID = "7b3c750d-d6d2-40c2-9434-6ffe05639d89";

DELETE FROM article
WHERE articleDate = "August 2017";

DELETE FROM profile
WHERE profileId = "81af6286-2307-4bdc-8954-a7864e25435f";

INSERT INTO profile(profileId, profileActivationToken, profileFullName, profileCaption, profileEmail, profileHash, profilePhone, profileSalt)
VALUES ("b81d3bbd-59d8-4042-b0b3-5905c9227b07", "bhUPq3i8w90Kdv4QtwiT2cVk3YoLq", "Kenneth Keyes", "Ride like lightning, Crash like thunder", "foo@bar.com", "8743b52063cd84097a65d1633f5c74f5", "5052386850", "E1F53135E559C253");

INSERT INTO article(articleId, articleAuthorProfileId, articleContent, articleDate)
VALUE ("938a63aa-ee36-44df-9906-4f2d2439aca4", "b81d3bbd-59d8-4042-b0b3-5905c9227b07", "h4ck 743 P14n37", GETDATE());