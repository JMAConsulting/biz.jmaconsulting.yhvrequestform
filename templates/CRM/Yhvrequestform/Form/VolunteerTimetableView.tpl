<table id="volunteer-timetable">
    <tr class="volunteer-timetable-view">
        <td class="label">
            <label for="volunteer-timetable">{ts}Day and # of Volunteer(s) Needed{/ts}</label>
        </td>
        <td class="view-value">
            <div class="crm-accordion-wrapper collapsed">
                {include file="CRM/Yhvrequestform/Form/VolunteerTimetable.tpl"}
            </div>
        </td>
    </tr>
</table>

{literal}
    <script type="text/javascript">
        CRM.$(function($) {
            $('#volunteer-timetable tr.volunteer-timetable-view').insertAfter('tr.crm-activity-form-block-priority_id');
        });
    </script>
{/literal}
