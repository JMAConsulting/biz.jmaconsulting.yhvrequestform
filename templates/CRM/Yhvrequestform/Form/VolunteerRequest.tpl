{* HEADER *}

{* FIELD EXAMPLE: OPTION 1 (AUTOMATIC LAYOUT) *}

<table class="compressed">
    <tr>
        <td>
            <div class="crm-section">
                {if $locationPreHelp}
                    <div class="content pre-description"><i>{$locationPreHelp}</i></div>
                {/if}
                <div class="label">{$form.location.label}</div>
                <div class="content">{$form.location.html}</div>
                {if $locationPostHelp}
                    <div class="content post-description"><i>{$locationPostHelp}</i></div>
                {/if}
                <div class="clear"></div>
            </div>
            <div class="crm-section">
                {if $divisionPreHelp}
                    <div class="content pre-description"><i>{$divisionPreHelp}</i></div>
                {/if}
                <div class="label">{$form.division.label}</div>
                <div class="content">{$form.division.html}</div>
                {if $divisionPostHelp}
                    <div class="content post-description"><i>{$divisionPostHelp}</i></div>
                {/if}
                <div class="clear"></div>
            </div>
            <div class="crm-section">
                {if $programPreHelp}
                    <div class="content pre-description"><i>{$programPreHelp}</i></div>
                {/if}
                <div class="label">{$form.program.label}</div>
                <div class="content">{$form.program.html}</div>
                {if $programPostHelp}
                    <div class="content post-description"><i>{$programPostHelp}</i></div>
                {/if}
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
                <div class="label">{$form.liaison_staff.label}</div>
                <div class="content">{$form.liaison_staff.html}</div>
                <div class="content post-description"><i>Please enter your Email address here.</i></div>
                <div class="clear"></div>
            </div>
            <div class="crm-section">
                {if $jobPreHelp}
                    <div class="content pre-description"><i>{$jobPreHelp}</i></div>
                {/if}
                <div class="label">{$form.job.label}</div>
                <div class="content">{$form.job.html}</div>
                {if $jobPostHelp}
                    <div class="content post-description"><i>{$jobPostHelp}</i></div>
                {/if}
                <div class="clear"></div>
            </div>
            <div class="crm-section">
                {if $jobdescPreHelp}
                    <div class="content pre-description"><i>{$jobdescPreHelp}</i></div>
                {/if}
                <div class="label">{$form.job_description.label}</div>
                <div class="content">{$form.job_description.html}</div>
                {if $jobdescPostHelp}
                    <div class="content post-description"><i>{$jobdescPostHelp}</i></div>
                {/if}
                <div class="clear"></div>
            </div>
        </td>
        <td>
        </td>
    </tr>
</table>
<legend>{ts}Knowledge / Skills / Qualifications Required{/ts}</legend>
<table class="compressed">
    <tr>
        <td>
            <div class="crm-section">
                {if $languagesPreHelp}
                    <div class="content pre-description"><i>{$languagesPreHelp}</i></div>
                {/if}
                <div class="label">{$form.languages.label}</div>
                <div class="content">{$form.languages.html}</div>
                {if $languagesPostHelp}
                    <div class="content post-description"><i>{$languagesPostHelp}</i></div>
                {/if}
                <div class="clear"></div>
            </div>

            <div class="crm-section">
                {if $computerPreHelp}
                    <div class="content pre-description"><i>{$computerPreHelp}</i></div>
                {/if}
                <div class="label">{$form.computer_skills.label}</div>
                <div class="content">{$form.computer_skills.html}</div>
                {if $computerPostHelp}
                    <div class="content post-description"><i>{$computerPostHelp}</i></div>
                {/if}
                <div class="clear"></div>
            </div>

            <div class="crm-section">
                {if $tbPreHelp}
                    <div class="content pre-description"><i>{$tbPreHelp}</i></div>
                {/if}
                <div class="label">{$form.tb_screening.label}</div>
                <div class="content">{$form.tb_screening.html}</div>
                {if $tbPostHelp}
                    <div class="content post-description"><i>{$tbPostHelp}</i></div>
                {/if}
                <div class="clear"></div>
            </div>

            <div class="crm-section">
                {if $policePreHelp}
                    <div class="content pre-description"><i>{$policePreHelp}</i></div>
                {/if}
                <div class="label">{$form.police_check.label}</div>
                <div class="content">{$form.police_check.html}</div>
                {if $policePostHelp}
                    <div class="content post-description"><i>{$policePostHelp}</i></div>
                {/if}
                <div class="clear"></div>
            </div>

            <div class="crm-section">
                {if $vehiclePreHelp}
                    <div class="content pre-description"><i>{$vehiclePreHelp}</i></div>
                {/if}
                <div class="label">{$form.vehicle.label}</div>
                <div class="content">{$form.vehicle.html}</div>
                {if $vehiclePostHelp}
                    <div class="content post-description"><i>{$vehiclePostHelp}</i></div>
                {/if}
                <div class="clear"></div>
            </div>

            <div class="crm-section">
                {if $otherPreHelp}
                    <div class="content pre-description"><i>{$otherPreHelp}</i></div>
                {/if}
                <div class="label">{$form.other_skills.label}</div>
                <div class="content">{$form.other_skills.html}</div>
                {if $otherPostHelp}
                    <div class="content post-description"><i>{$otherPostHelp}</i></div>
                {/if}
                <div class="clear"></div>
            </div>
        </td>
    </tr>
</table>
<legend>{ts}Type of Volunteer Request and Duration{/ts}</legend>

<div class="crm-section">
    {if $requestPreHelp}
        <div class="content pre-description"><i>{$requestPreHelp}</i></div>
    {/if}
    <div class="label">{$form.type_of_request.label}</div>
    <div class="content">{$form.type_of_request.html}</div>
    {if $requestPostHelp}
        <div class="content post-description"><i>{$requestPostHelp}</i></div>
    {/if}
    <div class="clear"></div>
</div>

<div class="crm-section">
    {if $durationPreHelp}
        <div class="content"><i>{$durationPreHelp}</i></div>
    {/if}
    <div class="label">{$form.duration.label}</div>
    <div class="content">{$form.duration.html}</div>
    {if $durationPostHelp}
        <div class="content post-description"><i>{$durationPostHelp}</i></div>
    {/if}
    <div class="clear"></div>
</div>

<div class="crm-section">
    {if $startPreHelp}
        <div class="content pre-description"><i>{$startPreHelp}</i></div>
    {/if}
    <div class="label">{$form.start_date.label}</div>
    <div class="content">{$form.start_date.html}</div>
    {if $startPostHelp}
        <div class="content post-description"><i>{$startPostHelp}</i></div>
    {/if}
    <div class="clear"></div>
</div>

<div class="crm-section">
    {if $endPreHelp}
        <div class="content pre-description"><i>{$endPreHelp}</i></div>
    {/if}
    <div class="label">{$form.end_date.label}</div>
    <div class="content">{$form.end_date.html}</div>
    {if $endPostHelp}
        <div class="content post-description"><i>{$endPostHelp}</i></div>
    {/if}
    <div class="clear"></div>
</div>
<legend>{ts}Please enter the number of volunteer(s) needed for the time slot{/ts}</legend>

{include file="CRM/Yhvrequestform/Form/VolunteerTimetable.tpl"}
<div class="crm-section">
    {if $remarkPreHelp}
        <div class="content pre-description"><i>{$remarkPreHelp}</i></div>
    {/if}
    <div class="label">{$form.other_remarks.label}</div>
    <div class="content">{$form.other_remarks.html}</div>
    {if $remarkPostHelp}
        <div class="content post-description"><i>{$remarkPostHelp}</i></div>
    {/if}
    <div class="clear"></div>
</div>
<div class="clear"></div>
<br/>
<div class="help">
    <strong>
        The volunteer team will strive to find the volunteer(s) as requested. Mandatory procedures such as TB test/police check and seasonal fluctuation of volunteer availability may delay the process.
    </strong>
</div>
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
