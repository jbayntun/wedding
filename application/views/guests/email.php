<form action="/mailer/feedback" method="post">
	<div>
        <label for="name">Name:</label><br/>
        <input type="text" id="name" name="name">
    </div>
    <div>
        <label for="subject">Subject:</label><br/>
        <input type ="text" id="subject" name="subject">
    </div>
	<div>
        <label for="email">Your Email Address:</label><br/>
        <input type ="text" id="email" name="email" size="50">
    </div>
	<div>
        <label for="message">Message:</label></br>
        <textarea id="message" name="message" cols="50" rows="5"></textarea>
    </div>
	<input type="submit" value="Send Message"> 
</form>