# User Guide: Creating Evaluations and Issuing TANs

Version: 1.0  
Date: 2026-08-13  
Audience: internal staff members who prepare evaluations, start them, and issue TANs to participants.

## 1. Core Idea Of The Platform

The platform has two separate areas:

- Internal staff area: staff members log in and manage locations, courses, teachers, modules, questionnaires, evaluations, and TANs.
- Public TAN area: participants do not log in. They open the public evaluation page, enter their TAN, and complete the questionnaire anonymously.

Participants do not have user accounts. A TAN only controls access to one evaluation and is consumed after submission.

## 2. Roles And Permissions

Staff users need the correct permissions. Creating and managing evaluations currently uses the `campaigns.manage` permission.

Typical roles:

- Super Administration: can use all administration areas.
- Administration: can use the administration areas.
- Questionnaire Editor: creates modules and questionnaires.
- Report Viewer: later views results and reports.

If a page is missing or shows a "403" error, the user probably does not have the required permission.

## 3. Important Navigation

After login, the main navigation is shown at the top or on the left side of the administration area.

Important menu items for evaluations:

- Locations: manage states and locations such as Thuringia, Erfurt, Saxony, Leipzig.
- Teachers: manage teacher records.
- Courses: create courses and assign teachers.
- Modules: create reusable blocks of questions.
- Question Editor: edit and publish module versions, sections, and questions.
- Questionnaires: create questionnaire templates.
- Questionnaire Builder: combine published modules into a questionnaire version.
- Evaluations: create real evaluation runs and generate TANs.

## 4. Complete Workflow

An evaluation works reliably when the preparation is done in the correct order:

1. Check or create the location.
2. Create the teachers.
3. Create the course and assign teachers.
4. Create modules.
5. In the Question Editor, create a module version, add sections and questions, then publish the module version.
6. Create a questionnaire template.
7. In the Questionnaire Builder, create a questionnaire version, add published modules, set repeat behavior, then publish the version.
8. Under Evaluations, create the real evaluation.
9. Generate TANs for the evaluation.
10. Give the TANs to participants.
11. Participants complete the evaluation anonymously.
12. After the evaluation ends, responses and later reports can be reviewed.

## 5. Prepare Location, Course, And Teachers

### Location

Path: Administration -> Locations

A location usually belongs to a state. Example:

- Thuringia
  - Erfurt
  - Suhl
- Saxony
  - Chemnitz
  - Doebeln
  - Dresden
  - Leipzig
  - Riesa
- Berlin
  - Prenzlauer Promenade
  - Trachenbergring

Many records are later filtered by location. Therefore, a course should always be assigned to the correct location.

### Teachers

Path: Administration -> Teachers

Teachers can be created with or without a staff login account. For evaluations, a teacher record with name, e-mail address, and location is enough. A login is not required.

### Course

Path: Administration -> Courses

The course stores:

- Course name, for example "German B2".
- Course code, for example "DE-B2-2026-09".
- Location.
- Start and end date, if known.
- Assigned teachers.

Important: If the teacher module should appear once per teacher, the teachers must be assigned to the course.

## 6. Prepare Modules And Questions

### Create A Module

Path: Administration -> Modules

A module is a reusable block of questions. Examples:

- Organisation
- Teacher / Lessons
- Rooms / Technology
- Course Content
- Final Comment

The module itself is only the container. The real questions are stored in module versions.

### Edit A Module Version

Path: Administration -> Question Editor

In the Question Editor, create a draft version. Inside this version, add sections and questions.

Supported question types:

- Scale: numeric rating, for example 1 to 5.
- Single choice: exactly one answer option.
- Free text: open text field.
- Yes / No: simple approval or rejection.

Example scale question:

- Question: "The teacher explains clearly."
- Type: Scale
- Minimum: 1
- Maximum: 5
- Minimum label: "Strongly disagree"
- Maximum label: "Strongly agree"

### Publish A Module Version

A module version can only be published after it contains at least one question.

After publishing, the version is locked. This is intentional: old evaluations must remain connected to the exact question version that existed at that time. To make changes, create a new draft version.

## 7. Build A Questionnaire

### Create A Questionnaire Template

Path: Administration -> Questionnaires

A questionnaire template is the parent name, for example "Course Evaluation". The usable questionnaire is a questionnaire version.

### Create A Questionnaire Version In The Builder

Path: Administration -> Questionnaire Builder

In the Questionnaire Builder, create a draft version. Then add published module versions.

For each module, define:

- The order in the questionnaire.
- Repeat behavior:
  - Once: the module appears once.
  - Per target: the module appears once per target, for example once per teacher.

Example:

- Organisation: Once.
- Teacher / Lessons: Per target.
- Final Comment: Once.

If a course has two teachers, the teacher module appears twice:

- Teacher / Lessons - Ms Mueller
- Teacher / Lessons - Mr Schmidt

### Publish A Questionnaire Version

The "Publish version" button is only available when the questionnaire version contains at least one module.

After publishing, the questionnaire version is locked and can be selected for evaluations.

## 8. Create An Evaluation

Path: Administration -> Evaluations -> Create Evaluation

When creating an evaluation, fill in:

- Title: a clear name, for example "German B2 Course Evaluation September 2026".
- Description: optional help text.
- Location: evaluation location.
- Course: optional, but recommended for course evaluations.
- Published questionnaire version: required.
- Start: start date and time.
- End: end date and time.
- Status: Draft, Scheduled, Active, Closed, or Archived.
- Minimum answers for results: protects anonymity.

Status meaning:

- Draft: the evaluation is being prepared and is not usable by participants yet.
- Scheduled: the evaluation is ready, but not active yet.
- Active: participants can answer with a valid TAN when the date range matches.
- Closed: no more responses are accepted.
- Archived: the evaluation is finished and removed from the normal workflow.

Important: participants can only answer when the evaluation is active and the current time is between start and end.

## 9. Generate TANs

Path: Administration -> Evaluations -> TANs

In the Evaluations list, each evaluation has a "TANs" action. This opens the TAN page for that evaluation.

On the TAN page you see:

- Title, location, course, questionnaire, and date range.
- Participant link.
- TAN statistics:
  - Total TANs
  - Unused
  - Started
  - Used
  - Inactive

How to generate TANs:

1. Enter the required number of TANs.
2. Click "Generate TANs".
3. Immediately copy the displayed TANs or download them as a TXT file.
4. Send the TANs to participants.

Important: TANs are shown in plain text only once. The database stores only a hash. After reloading the page, the same TANs cannot be displayed again in plain text.

New TANs cannot be generated for closed or archived evaluations.

## 10. Send TANs To Participants

Current app status:

- TANs can be generated, copied, and downloaded as a TXT file.
- TANs are currently sent manually to participants.
- Automatic e-mail jobs are already planned in the database structure, but the full normal e-mail sending workflow is not implemented yet.

Recommended manual message:

Subject: Your TAN for the course evaluation

Text:

Hello,

please take part in the anonymous course evaluation.

Link: /evaluation  
Your TAN: [insert TAN]

Participation is anonymous. Please use the TAN only once.

Thank you.

## 11. Participant Workflow

Participants open the public evaluation page:

`/evaluation`

Flow:

1. Enter TAN.
2. The system checks the TAN, evaluation status, and date range.
3. The questionnaire is loaded dynamically from the database.
4. Participants answer the questions.
5. Participants submit the evaluation.
6. The TAN is marked as used.
7. A thank-you page is shown.

Responses are stored anonymously. No participant name and no participant user account are stored.

## 12. What Happens In The Background

When a TAN is entered, the system checks:

- Does the TAN exist?
- Is the TAN active?
- Has the TAN already been used?
- Has the TAN expired?
- Is the evaluation active?
- Is the current time between start and end?

When the evaluation is submitted:

- A response record is created.
- All answers are stored individually.
- The TAN is marked as used.
- Everything happens inside a database transaction so partial submissions are avoided.

## 13. Results And Anonymity

The minimum answer count protects anonymity.

Example:

- Minimum answers: 5
- Submitted responses: 3
- Results remain locked.

Results and reports should only become visible after the minimum answer count is reached.

## 14. Common Problems

### I Cannot Select A Questionnaire Version In The Evaluation

The questionnaire version is probably not published yet. Open the Questionnaire Builder and publish the version.

### The Questionnaire Version Cannot Be Published

The version probably does not contain a module yet. Add at least one published module version.

### There Are No Modules In The Questionnaire Builder

There is no published module version yet. Open the Question Editor, create questions, and publish the module version.

### Participants See "This Evaluation Is Not Active"

Check:

- Evaluation status must be "Active".
- Start date must not be in the future.
- End date must not be in the past.
- TAN must be active and unused.

### A TAN Is Invalid

Possible reasons:

- TAN was entered incorrectly.
- TAN was never generated.
- There are spaces or typing mistakes.
- TAN belongs to another environment or database.

### The Teacher Module Does Not Appear Once Per Teacher

Check:

- The course has teachers assigned.
- The module is intended as a teacher module.
- In the Questionnaire Builder, "Per target" is selected.
- The evaluation has matching teacher targets.

Note: If campaign target generation is not yet visible automatically in the interface, this part should be checked or added in the next development step.

## 15. Checklist Before Starting An Evaluation

- Location is correct.
- Course is correct.
- Teachers are assigned to the course.
- All required modules are published.
- Questionnaire version is published.
- Evaluation has the correct location, course, and questionnaire.
- Start and end date are correct.
- Status is set to "Active" when participants should start.
- Enough TANs have been generated.
- TANs have been copied or downloaded.
- Participants have received the link and their TAN.

## 16. Short Version For Evaluation Managers

1. Check course and teachers.
2. Publish questionnaire.
3. Create evaluation.
4. Check status and date range.
5. In the Evaluations list, click "TANs".
6. Enter amount and generate TANs.
7. Copy TANs or download them as TXT.
8. Send `/evaluation` and TANs to participants.
9. Monitor responses.
10. After the end date, close the evaluation and review results.

