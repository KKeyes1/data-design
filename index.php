<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Data Design of Medium.com</title>
		<link rel="stylesheet" href="">
	</head>
	<body>
		<h1>Data Design Project</h1>
		<h1>Phase 0 Assignment</h1>
		<h2>Requirements</h2>
		<p>Create a NEW project in GitHub and PhpStorm named data-design. Be sure your deployment is 100% correct and functional.
			Create an index.php file in the root of your project, and document your Persona, User Story, Use Case/Interaction Flow, and Conceptual Model here. Use standards-compliant HTML.</p>
		<h2>Persona</h2>
		<h3>Kenneth Keyes</h3>
		<p>Kenneth is a 26 year old male who uses a 17" 2011 macbook pro. He has a diverse background, both professionally and personally, and wants to publish relevant articles about his hobbies to share with his family and friends.</p>
		<h3>User Story</h3>
		<p>As a user, I want to publish simple, modern, multi-media articles about my hobbies</p>
		<h3>Use Case</h3>
		<p>Ken wants to publish an "About Me" as their first article</p>
		<h3>Preconditions</h3>
		<p>Ken has subscribed and is already logged into her Medium account</p>
		<h3>Postconditions</h3>
		<p>Ken's article "About Me" is live</p>
		<h3>Interaction Flow</h3>
		<ol>
			<li>Ken clicks their profile image in the top right of the screen</li>
			<li>Medium provides a drop-down menu</li>
			<li>Ken clicks "New story"</li>
			<li>Medium navigates to http://medium.com/new-story</li>
			<li>Ken writes them "About Me" and clicks "Publish"</li>
			<li>Medium presents a pop-up asking them if she is "Ready to publish?", to enter tags, connect to their social media... etc.</li>
			<li>Ken clicks "Publish"</li>
			<li>Medium redirects them to https://medium.com/@theirUsername/About-Me-articleId</li>
		</ol>

		<h2>Conceptual Model</h2>
		<h3>profile</h3>
		<ul>
			<li>profileId (primary key)</li>
			<li>profileActivationToken (for account verification)</li>
			<li>profileFullName</li>
			<li>profileCaption</li>
			<li>profileEmail</li>
			<li>profileHash (for verification)</li>
			<li>profileSalt (for verification</li>
		</ul>
		<h3>article</h3>
		<ul>
			<li>articleId (primary id)</li>
			<li>articleAuthorProfileId (foreign key)</li>
			<li>articleTitle</li>
			<li>articlePostDate</li>
			<li>articleContent</li>
		</ul>
		<h3>clap (weak entity)</h3>
		<ul>
			<li>clapId (primary key)</li>
			<li>clapProfileId (foreign key)</li>
			<li>clapArticleId (foreign key)</li>
		</ul>
		<h3>Relationships</h3>
		<p>ONE profile can have MANY articles</p>
		<p>ONE profiles can have MANY claps</p>
		<p>ONE article can have MANY claps</p>
		<p>MANY profiles can have MANY claps for MANY articles</p>
		<hr>
		<h2>Entity Relationship Diagram</h2>
		<img src="/images/erd.svg" alt="An ERD for Medium.com" />
	</body>
</html>