import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import tan from './tan'
import form from './form'
/**
* @see \App\Http\Controllers\Evaluation\FormController::finished
 * @see app/Http/Controllers/Evaluation/FormController.php:67
 * @route '/evaluation/finished'
 */
export const finished = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: finished.url(options),
    method: 'get',
})

finished.definition = {
    methods: ["get","head"],
    url: '/evaluation/finished',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Evaluation\FormController::finished
 * @see app/Http/Controllers/Evaluation/FormController.php:67
 * @route '/evaluation/finished'
 */
finished.url = (options?: RouteQueryOptions) => {
    return finished.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Evaluation\FormController::finished
 * @see app/Http/Controllers/Evaluation/FormController.php:67
 * @route '/evaluation/finished'
 */
finished.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: finished.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Evaluation\FormController::finished
 * @see app/Http/Controllers/Evaluation/FormController.php:67
 * @route '/evaluation/finished'
 */
finished.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: finished.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Evaluation\FormController::finished
 * @see app/Http/Controllers/Evaluation/FormController.php:67
 * @route '/evaluation/finished'
 */
    const finishedForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: finished.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Evaluation\FormController::finished
 * @see app/Http/Controllers/Evaluation/FormController.php:67
 * @route '/evaluation/finished'
 */
        finishedForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: finished.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Evaluation\FormController::finished
 * @see app/Http/Controllers/Evaluation/FormController.php:67
 * @route '/evaluation/finished'
 */
        finishedForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: finished.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    finished.form = finishedForm
const evaluation = {
    tan: Object.assign(tan, tan),
form: Object.assign(form, form),
finished: Object.assign(finished, finished),
}

export default evaluation