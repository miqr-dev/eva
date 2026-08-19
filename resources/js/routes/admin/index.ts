import questions from './questions'
import questionnaireBuilder from './questionnaire-builder'
import evaluationCampaigns from './evaluation-campaigns'
import resources from './resources'
import api from './api'
const admin = {
    questions: Object.assign(questions, questions),
questionnaireBuilder: Object.assign(questionnaireBuilder, questionnaireBuilder),
evaluationCampaigns: Object.assign(evaluationCampaigns, evaluationCampaigns),
resources: Object.assign(resources, resources),
api: Object.assign(api, api),
}

export default admin