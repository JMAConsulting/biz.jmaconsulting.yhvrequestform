{literal}
    <style type="text/css">
        table#volunteertimetable td {
            width: 100px;
            overflow: hidden;
            white-space: nowrap;
        }
    </style>
{/literal}
<table id="volunteertimetable">
    <tr>
        <td>
        </td>
        {foreach from=$yhvDays item=day}
            <td>{$day}</td>
        {/foreach}
    </tr>
    <tr>
        {foreach from=$gridElements key=period item=grid}
    <tr>
        <td>
            {$period}
        </td>
        {foreach from=$grid item=volunteerfield}
            <td>{$form.$volunteerfield.html}</td>
        {/foreach}
    </tr>
    {/foreach}
    </tr>
</table>