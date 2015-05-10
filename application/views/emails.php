<h2>Send <span>Invitations</span></h2>

<form class="guest_form" action="/mailer/send_invitations" method="post">
    <div>
        <label for="his_email">His Email</label>
        <input type="text" id="his_email" name="his_email">
    </div>
    <div>
        <label for="his_pass">His Password</label>
        <input type="text" id="his_pass" name="his_pass" autocomplete="off">
    </div>
    <div>
        <label for="his_server">His SMTP Server</label>
        <input type ="text" id="his_server" name="his_server" value="smtp-mail.outlook.com">
    </div>
    
    <br/><br/>
    
    <div>
        <label for="her_email">Her Email</label>
        <input type="text" id="her_email" name="her_email">
    </div>
    <div>
        <label for="her_pass">Her Password</label>
        <input type="text" id="her_pass" name="her_pass" autocomplete="off">
    </div>
    <div>
        <label for="her_server">Her SMTP Server</label>
        <input type ="text" id="her_server" name="her_server" value="smtp-mail.outlook.com">
    </div>
    
     <br/><br/>
    
    <table>
    {groups}
        <tr><th colspan="2">{name}</th>
            <th>His</th>
            <th>Hers</th>
            <th>None</th>
        </tr>
            {guests}
            <tr><td>{first_name} {last_name}</td>
                <td>{email}</td>
                <td><input type="radio" name="{id}" value="his" {disabled}></td>
                <td><input type="radio" name="{id}" value="hers" {disabled}></td>
                <td><input type="radio" name="{id}" value="none" {disabled} checked ></td>
            </tr>
            {/guests}   
    {/groups}
    </table>
    
    <input type="submit" value="Send Emails">
</form>