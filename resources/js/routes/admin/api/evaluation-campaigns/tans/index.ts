import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::store
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:38
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}/tans'
 */
export const store = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/evaluation-campaigns/{evaluation_campaign}/tans',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::store
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:38
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}/tans'
 */
store.url = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { evaluation_campaign: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { evaluation_campaign: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    evaluation_campaign: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        evaluation_campaign: typeof args.evaluation_campaign === 'object'
                ? args.evaluation_campaign.id
                : args.evaluation_campaign,
                }

    return store.definition.url
            .replace('{evaluation_campaign}', parsedArgs.evaluation_campaign.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::store
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:38
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}/tans'
 */
store.post = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::store
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:38
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}/tans'
 */
    const storeForm = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::store
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:38
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}/tans'
 */
        storeForm.post = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
const tans = {
    store: Object.assign(store, store),
}

export default tans