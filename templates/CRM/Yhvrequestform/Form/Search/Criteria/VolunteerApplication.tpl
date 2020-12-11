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
{literal}
    <script type="text/javascript">
        CRM.$(function($) {
            $('.crm-volunteer_application-accordion').crmAccordionToggle();
        });
    </script>
{/literal}