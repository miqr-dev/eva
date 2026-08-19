import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
const QuestionnaireBuilderController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: QuestionnaireBuilderController.url(options),
    method: 'get',
})

QuestionnaireBuilderController.definition = {
    methods: ["get","head"],
    url: '/verwaltung/fragebogen-builder',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
QuestionnaireBuilderController.url = (options?: RouteQueryOptions) => {
    return QuestionnaireBuilderController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
QuestionnaireBuilderController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: QuestionnaireBuilderController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
QuestionnaireBuilderController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: QuestionnaireBuilderController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
    const QuestionnaireBuilderControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: QuestionnaireBuilderController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
        QuestionnaireBuilderControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: QuestionnaireBuilderController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\QuestionnaireBuilderController::__invoke
 * @see app/Http/Controllers/Admin/QuestionnaireBuilderController.php:17
 * @route '/verwaltung/fragebogen-builder'
 */
        QuestionnaireBuilderControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: QuestionnaireBuilderController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    QuestionnaireBuilderController.form = QuestionnaireBuilderControllerForm
export default QuestionnaireBuilderController