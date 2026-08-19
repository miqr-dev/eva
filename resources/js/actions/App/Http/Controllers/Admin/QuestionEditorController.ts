import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
const QuestionEditorController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: QuestionEditorController.url(options),
    method: 'get',
})

QuestionEditorController.definition = {
    methods: ["get","head"],
    url: '/verwaltung/fragen',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
QuestionEditorController.url = (options?: RouteQueryOptions) => {
    return QuestionEditorController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
QuestionEditorController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: QuestionEditorController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
QuestionEditorController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: QuestionEditorController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
    const QuestionEditorControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: QuestionEditorController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
        QuestionEditorControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: QuestionEditorController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
        QuestionEditorControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: QuestionEditorController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    QuestionEditorController.form = QuestionEditorControllerForm
export default QuestionEditorController