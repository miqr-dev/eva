import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:20
 * @route '/verwaltung/evaluationen/{evaluation_campaign}/tans'
 */
export const show = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/verwaltung/evaluationen/{evaluation_campaign}/tans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:20
 * @route '/verwaltung/evaluationen/{evaluation_campaign}/tans'
 */
show.url = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{evaluation_campaign}', parsedArgs.evaluation_campaign.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:20
 * @route '/verwaltung/evaluationen/{evaluation_campaign}/tans'
 */
show.get = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:20
 * @route '/verwaltung/evaluationen/{evaluation_campaign}/tans'
 */
show.head = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:20
 * @route '/verwaltung/evaluationen/{evaluation_campaign}/tans'
 */
    const showForm = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:20
 * @route '/verwaltung/evaluationen/{evaluation_campaign}/tans'
 */
        showForm.get = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignTanController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignTanController.php:20
 * @route '/verwaltung/evaluationen/{evaluation_campaign}/tans'
 */
        showForm.head = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const tans = {
    show: Object.assign(show, show),
}

export default tans