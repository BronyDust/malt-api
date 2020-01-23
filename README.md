<p align="center">
  <img src="logo.png" width="200" /><span>v 0.1a</span>
  <h1 align="center">Malt Project. API.</h1>
</p>

<p align="center"><strong>Malt</strong> is a lite CMS for driving advertisement banners easy for The SOŁ projects.</p>

<h2>Authentication and Authorization</h2>

Malt API uses JWT token system to authorize interactions. Some interactions (e.g. editing, adding new one and deletting) are requires a Authorization Header with token to be done. Send string 'username.password' encoding with Sha256 as POST text/plain to <strong>/auth.php</strong> and take JWT as a response. 

Token lifetime is limited (default is 500 seconds). Token will be claimed as expired after this time. Token has a restriction to be created (not more than once every second as a default). So keep this in mind when sends a requests.

<h2>Response</h2>

Malt API sends a json as a response. Key "action" is a success indicator. There are some cases of posible actions:
<ul>
  <li>"auth" - Authentication is done successfully. Response contains a token.</li>
  <li>"noauth" - Authorization is falled. Access is denied.</li>
  <li>"task-done" - Task is done successfully.</li>
  <li>"task-failed" - Task isn't done. Error.</li>
  <li>"not-found" - Requested object isn't found.</li>
</ul>

<h2>CRUD</h2>

API supports CORS.

DON'T FORGET ABOUT AUTHORIZATION HEADER! To create new banner send a POST request to <strong>/new.php</strong> with FILE called "image" and field called "type" (any other rows are optional). New banner takes a name that is actually md5 from image file and timestamp before it.

DON'T FORGET ABOUT AUTHORIZATION HEADER! To edit banner send a POST request to <strong>/edit.php</strong> with required "name" field and all other fields to change in banner's options.

DON'T FORGET ABOUT AUTHORIZATION HEADER! To delete banner send a POST request to <strong>/delete.php</strong> with required "name" field.

<h3>Getting a banner</h3>

You can request to get banners without authorization header. GET API endpoint is <strong>/get.php</strong>. The most important parameter is "mode". Modes here:
<ul>
  <li>list - json array with all banner names with abc sorting.</li>
  <li>get - take subset from all of banners by "type" and response number of elements in this subset OR element with specified id.</li>
  <li>one - take an element with name.</li>
  <li>pic - take blob of banner raw image by banner's name.</li>
</ul>

<h4>mode=get</h4>
For now there is only 3 types of banners. Set "type" parameter to "feed", "post" or "both". This case give to you a json with key "action" = "task-done" (if everything is fine) and "count" eq the number of elements with specified type. Set an "id" parameter to take an element that is under this number in subset.
