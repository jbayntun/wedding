<h2>Please <span>Log In</span></h2>

<p>{result}</p>

<form class="guest_form" action="/login/authenticate" method="post">
    <div>
        <label for="user_name">User Name</label>
        <input type="text" id="user_name" name="user_name">
    </div>
    <div>
        <label for="password">Password</label>
        <input type ="password" id="password" name="password">
    </div>
    <input type="submit" value="Login">
</form>