import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/verwaltung/fragen',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\QuestionEditorController::__invoke
 * @see app/Http/Controllers/Admin/QuestionEditorController.php:15
 * @route '/verwaltung/fragen'
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
const questions = {
    index: Object.assign(index, index),
}

export default questions