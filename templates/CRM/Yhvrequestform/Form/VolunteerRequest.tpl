{* HEADER *}

{* FIELD EXAMPLE: OPTION 1 (AUTOMATIC LAYOUT) *}
<legend>{ts}Department{/ts}</legend>

<table class="compressed">
    <tr>
        <td>
            <div class="crm-section">
                <div class="label">{$form.location.label}</div>
                <div class="content">{$form.location.html}</div>
                <div class="clear"></div>
            </div>
            <div class="crm-section">
                <div class="label">{$form.division.label}</div>
                <div class="content">{$form.division.html}</div>
                <div class="clear"></div>
            </div>
            <div class="crm-section">
                <div class="label">{$form.program.label}</div>
                <div class="content">{$form.program.html}</div>
                <div class="clear"></div>
            </div>
        </td>
        <td>
            <div class="label">{$form.request_date.label}</div>
            <div class="content">{$form.request_date.html}</div>
            <div class="clear"></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="crm-section">
                <div class="label">Liason Staff</div>
                <div class="content">{$liasonStaff}</div>
                <div class="clear"></div>
            </div>
            <div class="crm-section">
                <div class="label">{$form.job.label}</div>
                <div class="content">{$form.job.html}</div>
                <div class="clear"></div>
            </div>
        </td>
        <td>
        </td>
    </tr>
    <tr>
        <td>
            <div class="crm-section">
                <div class="label">{ts}Knowledge / Skills / Qualifications Required{/ts}</div>
                <div class="content">
                    <div class="label">{$form.languages.label}</div>
                    <div class="content">{$form.languages.html}</div>
                    <div class="clear"></div>

                    <div class="label">{$form.computer_skills.label}</div>
                    <div class="content">{$form.computer_skills.html}</div>
                    <div class="clear"></div>

                    <div class="label">{$form.tb_screening.label}</div>
                    <div class="content">{$form.tb_screening.html}</div>
                    <div class="clear"></div>

                    <div class="label">{$form.police_check.label}</div>
                    <div class="content">{$form.police_check.html}</div>
                    <div class="clear"></div>

                    <div class="label">{$form.vehicle.label}</div>
                    <div class="content">{$form.vehicle.html}</div>
                    <div class="clear"></div>

                    <div class="label">{$form.other_skills.label}</div>
                    <div class="content">{$form.other_skills.html}</div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
</table>
<legend>{ts}Type of Volunteer Request and Duration{/ts}</legend>

<div class="crm-section">
    <div class="label">{$form.type_of_request.label}</div>
    <div class="content">{$form.type_of_request.html}</div>
    <div class="clear"></div>
</div>

<div class="crm-section">
    <div class="label">{$form.duration.label}</div>
    <div class="content">{$form.duration.html}</div>
    <div class="clear"></div>
</div>

<div class="crm-section">
    <div class="label">{$form.start_date.label}</div>
    <div class="content">{$form.start_date.html}</div>
    <div class="clear"></div>
</div>

<div class="crm-section">
    <div class="label">{$form.end_date.label}</div>
    <div class="content">{$form.end_date.html}</div>
    <div class="clear"></div>
</div>
<legend>{ts}Day and # of Volunteer(s) Needed{/ts}</legend>

{include file="CRM/Yhvrequestform/Form/VolunteerTimetable.tpl"}
<div class="crm-section">
    <div class="label">{$form.other_remarks.label}</div>
    <div class="content">{$form.other_remarks.html}</div>
    <div class="clear"></div>
</div>
<div class="help">
    <strong>
        The volunteer Team will strive to find the volunteer(s) as requested. Mandatory procedures such as TB test/police check and seasonal fluctuation of volunteer availability may delay the process.
    </strong>
</div>
{* FIELD EXAMPLE: OPTION 2 (MANUAL LAYOUT)

  <div>
    <span>{$form.favorite_color.label}</span>
    <span>{$form.favorite_color.html}</span>
  </div>

{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
