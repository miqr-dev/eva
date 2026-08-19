import Auth from './Auth'
import Evaluation from './Evaluation'
import DashboardController from './DashboardController'
import Admin from './Admin'
const Controllers = {
    Auth: Object.assign(Auth, Auth),
Evaluation: Object.assign(Evaluation, Evaluation),
DashboardController: Object.assign(DashboardController, DashboardController),
Admin: Object.assign(Admin, Admin),
}

export default Controllers