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
    
    <div>
        <label for="notes">Notes:</label><br>
        <textarea id="notes" name="notes" value="notes">{notes}</textarea>
    </div>
    <input type="submit" value="Submit">    
</form>


