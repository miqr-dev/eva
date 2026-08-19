import organizationUnits from './organization-units'
import users from './users'
import courses from './courses'
import teachers from './teachers'
import questionnaireTemplates from './questionnaire-templates'
import modules from './modules'
import evaluationCampaigns from './evaluation-campaigns'
import reportTemplates from './report-templates'
import benchmarkGroups from './benchmark-groups'
import emailTemplates from './email-templates'
import moduleVersions from './module-versions'
import questionnaireVersions from './questionnaire-versions'
import questionnaireVersionModules from './questionnaire-version-modules'
import moduleSections from './module-sections'
import questions from './questions'
const api = {
    organizationUnits: Object.assign(organizationUnits, organizationUnits),
users: Object.assign(users, users),
courses: Object.assign(courses, courses),
teachers: Object.assign(teachers, teachers),
questionnaireTemplates: Object.assign(questionnaireTemplates, questionnaireTemplates),
modules: Object.assign(modules, modules),
evaluationCampaigns: Object.assign(evaluationCampaigns, evaluationCampaigns),
reportTemplates: Object.assign(reportTemplates, reportTemplates),
benchmarkGroups: Object.assign(benchmarkGroups, benchmarkGroups),
emailTemplates: Object.assign(emailTemplates, emailTemplates),
moduleVersions: Object.assign(moduleVersions, moduleVersions),
questionnaireVersions: Object.assign(questionnaireVersions, questionnaireVersions),
questionnaireVersionModules: Object.assign(questionnaireVersionModules, questionnaireVersionModules),
moduleSections: Object.assign(moduleSections, moduleSections),
questions: Object.assign(questions, questions),
}

export default api