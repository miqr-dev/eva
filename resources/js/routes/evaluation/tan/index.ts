import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Evaluation\TanController::create
 * @see app/Http/Controllers/Evaluation/TanController.php:15
 * @route '/evaluation'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/evaluation',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Evaluation\TanController::create
 * @see app/Http/Controllers/Evaluation/TanController.php:15
 * @route '/evaluation'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Evaluation\TanController::create
 * @see app/Http/Controllers/Evaluation/TanController.php:15
 * @route '/evaluation'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Evaluation\TanController::create
 * @see app/Http/Controllers/Evaluation/TanController.php:15
 * @route '/evaluation'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Evaluation\TanController::create
 * @see app/Http/Controllers/Evaluation/TanController.php:15
 * @route '/evaluation'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Evaluation\TanController::create
 * @see app/Http/Controllers/Evaluation/TanController.php:15
 * @route '/evaluation'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Evaluation\TanController::create
 * @see app/Http/Controllers/Evaluation/TanController.php:15
 * @route '/evaluation'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\Evaluation\TanController::store
 * @see app/Http/Controllers/Evaluation/TanController.php:20
 * @route '/evaluation/tan'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/evaluation/tan',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Evaluation\TanController::store
 * @see app/Http/Controllers/Evaluation/TanController.php:20
 * @route '/evaluation/tan'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Evaluation\TanController::store
 * @see app/Http/Controllers/Evaluation/TanController.php:20
 * @route '/evaluation/tan'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Evaluation\TanController::store
 * @see app/Http/Controllers/Evaluation/TanController.php:20
 * @route '/evaluation/tan'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Evaluation\TanController::store
 * @see app/Http/Controllers/Evaluation/TanController.php:20
 * @route '/evaluation/tan'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const tan = {
    create: Object.assign(create, create),
store: Object.assign(store, store),
}

export default tan