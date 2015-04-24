<h2>Manage <span>Guests</span></h2>

<p><a href="/guest/add_group">Add New Group</a></p>

<p>
    <span class="bold">Total Yes:</span> {yes}</br>
    <span class="bold">Total No:</span> {no}</br>
    <span class="bold">Total Unknown:</span> {maybe}</br>
    <span class="bold">Total Invited:</span> {invited}
</p>

<ul id="groups">
    {groups}
    <li><a href="/guest/admin_show_group/{id}">Group <span>{name}</span></a> </br>
        <span class="bold">Size:</span>{size}
        <span class="bold">Yes:</span>{yes}
        <span class="bold">No:</span> {no}
        <span class="bold">Unknown:</span> {maybe}
    </li>
    {/groups}
</ul>