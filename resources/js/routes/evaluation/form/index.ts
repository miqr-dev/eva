import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Evaluation\FormController::show
 * @see app/Http/Controllers/Evaluation/FormController.php:18
 * @route '/evaluation/form/{session}'
 */
export const show = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/evaluation/form/{session}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Evaluation\FormController::show
 * @see app/Http/Controllers/Evaluation/FormController.php:18
 * @route '/evaluation/form/{session}'
 */
show.url = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { session: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    session: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        session: args.session,
                }

    return show.definition.url
            .replace('{session}', parsedArgs.session.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Evaluation\FormController::show
 * @see app/Http/Controllers/Evaluation/FormController.php:18
 * @route '/evaluation/form/{session}'
 */
show.get = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Evaluation\FormController::show
 * @see app/Http/Controllers/Evaluation/FormController.php:18
 * @route '/evaluation/form/{session}'
 */
show.head = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Evaluation\FormController::show
 * @see app/Http/Controllers/Evaluation/FormController.php:18
 * @route '/evaluation/form/{session}'
 */
    const showForm = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Evaluation\FormController::show
 * @see app/Http/Controllers/Evaluation/FormController.php:18
 * @route '/evaluation/form/{session}'
 */
        showForm.get = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Evaluation\FormController::show
 * @see app/Http/Controllers/Evaluation/FormController.php:18
 * @route '/evaluation/form/{session}'
 */
        showForm.head = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\Evaluation\FormController::store
 * @see app/Http/Controllers/Evaluation/FormController.php:40
 * @route '/evaluation/submit/{session}'
 */
export const store = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/evaluation/submit/{session}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Evaluation\FormController::store
 * @see app/Http/Controllers/Evaluation/FormController.php:40
 * @route '/evaluation/submit/{session}'
 */
store.url = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { session: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    session: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        session: args.session,
                }

    return store.definition.url
            .replace('{session}', parsedArgs.session.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Evaluation\FormController::store
 * @see app/Http/Controllers/Evaluation/FormController.php:40
 * @route '/evaluation/submit/{session}'
 */
store.post = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Evaluation\FormController::store
 * @see app/Http/Controllers/Evaluation/FormController.php:40
 * @route '/evaluation/submit/{session}'
 */
    const storeForm = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Evaluation\FormController::store
 * @see app/Http/Controllers/Evaluation/FormController.php:40
 * @route '/evaluation/submit/{session}'
 */
        storeForm.post = (args: { session: string | number } | [session: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
const form = {
    show: Object.assign(show, show),
store: Object.assign(store, store),
}

export default form