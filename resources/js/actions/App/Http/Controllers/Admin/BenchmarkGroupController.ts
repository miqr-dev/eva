import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::index
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:15
 * @route '/admin/api/benchmark-groups'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/api/benchmark-groups',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::index
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:15
 * @route '/admin/api/benchmark-groups'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::index
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:15
 * @route '/admin/api/benchmark-groups'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::index
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:15
 * @route '/admin/api/benchmark-groups'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::index
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:15
 * @route '/admin/api/benchmark-groups'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::index
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:15
 * @route '/admin/api/benchmark-groups'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::index
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:15
 * @route '/admin/api/benchmark-groups'
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
/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::store
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:28
 * @route '/admin/api/benchmark-groups'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/benchmark-groups',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::store
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:28
 * @route '/admin/api/benchmark-groups'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::store
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:28
 * @route '/admin/api/benchmark-groups'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::store
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:28
 * @route '/admin/api/benchmark-groups'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::store
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:28
 * @route '/admin/api/benchmark-groups'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::show
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:35
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
export const show = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/api/benchmark-groups/{benchmark_group}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::show
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:35
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
show.url = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { benchmark_group: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { benchmark_group: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    benchmark_group: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        benchmark_group: typeof args.benchmark_group === 'object'
                ? args.benchmark_group.id
                : args.benchmark_group,
                }

    return show.definition.url
            .replace('{benchmark_group}', parsedArgs.benchmark_group.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::show
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:35
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
show.get = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::show
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:35
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
show.head = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::show
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:35
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
    const showForm = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::show
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:35
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
        showForm.get = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::show
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:35
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
        showForm.head = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::update
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:44
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
export const update = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/api/benchmark-groups/{benchmark_group}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::update
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:44
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
update.url = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { benchmark_group: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { benchmark_group: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    benchmark_group: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        benchmark_group: typeof args.benchmark_group === 'object'
                ? args.benchmark_group.id
                : args.benchmark_group,
                }

    return update.definition.url
            .replace('{benchmark_group}', parsedArgs.benchmark_group.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::update
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:44
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
update.put = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::update
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:44
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
update.patch = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::update
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:44
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
    const updateForm = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::update
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:44
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
        updateForm.put = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::update
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:44
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
        updateForm.patch = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::destroy
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:53
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
export const destroy = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/api/benchmark-groups/{benchmark_group}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::destroy
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:53
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
destroy.url = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { benchmark_group: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { benchmark_group: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    benchmark_group: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        benchmark_group: typeof args.benchmark_group === 'object'
                ? args.benchmark_group.id
                : args.benchmark_group,
                }

    return destroy.definition.url
            .replace('{benchmark_group}', parsedArgs.benchmark_group.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::destroy
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:53
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
destroy.delete = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::destroy
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:53
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
    const destroyForm = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\BenchmarkGroupController::destroy
 * @see app/Http/Controllers/Admin/BenchmarkGroupController.php:53
 * @route '/admin/api/benchmark-groups/{benchmark_group}'
 */
        destroyForm.delete = (args: { benchmark_group: number | { id: number } } | [benchmark_group: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const BenchmarkGroupController = { index, store, show, update, destroy }

export default BenchmarkGroupController