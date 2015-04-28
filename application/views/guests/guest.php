<h2>Guest <span>Response</span></h2>
<p>To ensure smooth planning, please RSVP no later than August 9th. </p>
<form action="/guest/respond" method="post">
    <table>
        <tr>
            <th>{group_name}</th>
            {responses}
            <th>{description}</th>
            {/responses}
        </tr>
        {guests}
        <tr>
            <td>{first_name} {last_name}</td>
            {responses}
            <td><input type="radio" name="{name}" value="{value}"{checked}></td>
            {/responses}
        </tr>
        {/guests}
    </table>
    <input type="submit" value="Submit"> 
	</form>
	
	<p>Need to get in Touch with Sarah and Jeff?  <a href="/guest/email">Click here to send us an email.</a></p>
    
      



