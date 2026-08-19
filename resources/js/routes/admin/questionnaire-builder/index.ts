import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/verwaltung/fragebogen-builder',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
const questionnaireBuilder = {
    index: Object.assign(index, index),
}

export default questionnaireBuilder