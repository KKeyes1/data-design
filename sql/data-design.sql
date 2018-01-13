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