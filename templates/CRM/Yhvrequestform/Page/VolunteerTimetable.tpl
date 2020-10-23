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
            <th>{$day}</th>
        {/foreach}
    </tr>
    <tr>
        {foreach from=$gridElements key=period item=grid}
    <tr class="{cycle values="odd,even"}">
        <td>
            {$period}
        </td>
        {foreach from=$grid item=volunteerfield}
            <td>{$volunteerfield}</td>
        {/foreach}
    </tr>
    {/foreach}
    </tr>
</table>
<div class="action-link">
    <a accesskey="N" href="{$editUrl}" class="button crm-popup"><span><i class="crm-i fa-edit" aria-hidden="true"></i>Edit Availability</span></a>
</div>