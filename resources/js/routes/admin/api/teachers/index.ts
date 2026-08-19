import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\TeacherController::index
 * @see app/Http/Controllers/Admin/TeacherController.php:17
 * @route '/admin/api/teachers'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/api/teachers',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TeacherController::index
 * @see app/Http/Controllers/Admin/TeacherController.php:17
 * @route '/admin/api/teachers'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TeacherController::index
 * @see app/Http/Controllers/Admin/TeacherController.php:17
 * @route '/admin/api/teachers'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TeacherController::index
 * @see app/Http/Controllers/Admin/TeacherController.php:17
 * @route '/admin/api/teachers'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TeacherController::index
 * @see app/Http/Controllers/Admin/TeacherController.php:17
 * @route '/admin/api/teachers'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TeacherController::index
 * @see app/Http/Controllers/Admin/TeacherController.php:17
 * @route '/admin/api/teachers'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TeacherController::index
 * @see app/Http/Controllers/Admin/TeacherController.php:17
 * @route '/admin/api/teachers'
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
* @see \App\Http\Controllers\Admin\TeacherController::store
 * @see app/Http/Controllers/Admin/TeacherController.php:29
 * @route '/admin/api/teachers'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/teachers',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TeacherController::store
 * @see app/Http/Controllers/Admin/TeacherController.php:29
 * @route '/admin/api/teachers'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TeacherController::store
 * @see app/Http/Controllers/Admin/TeacherController.php:29
 * @route '/admin/api/teachers'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\TeacherController::store
 * @see app/Http/Controllers/Admin/TeacherController.php:29
 * @route '/admin/api/teachers'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TeacherController::store
 * @see app/Http/Controllers/Admin/TeacherController.php:29
 * @route '/admin/api/teachers'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\TeacherController::show
 * @see app/Http/Controllers/Admin/TeacherController.php:43
 * @route '/admin/api/teachers/{teacher}'
 */
export const show = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/api/teachers/{teacher}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TeacherController::show
 * @see app/Http/Controllers/Admin/TeacherController.php:43
 * @route '/admin/api/teachers/{teacher}'
 */
show.url = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { teacher: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { teacher: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    teacher: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        teacher: typeof args.teacher === 'object'
                ? args.teacher.id
                : args.teacher,
                }

    return show.definition.url
            .replace('{teacher}', parsedArgs.teacher.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TeacherController::show
 * @see app/Http/Controllers/Admin/TeacherController.php:43
 * @route '/admin/api/teachers/{teacher}'
 */
show.get = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\TeacherController::show
 * @see app/Http/Controllers/Admin/TeacherController.php:43
 * @route '/admin/api/teachers/{teacher}'
 */
show.head = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\TeacherController::show
 * @see app/Http/Controllers/Admin/TeacherController.php:43
 * @route '/admin/api/teachers/{teacher}'
 */
    const showForm = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\TeacherController::show
 * @see app/Http/Controllers/Admin/TeacherController.php:43
 * @route '/admin/api/teachers/{teacher}'
 */
        showForm.get = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\TeacherController::show
 * @see app/Http/Controllers/Admin/TeacherController.php:43
 * @route '/admin/api/teachers/{teacher}'
 */
        showForm.head = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\TeacherController::update
 * @see app/Http/Controllers/Admin/TeacherController.php:52
 * @route '/admin/api/teachers/{teacher}'
 */
export const update = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/api/teachers/{teacher}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\TeacherController::update
 * @see app/Http/Controllers/Admin/TeacherController.php:52
 * @route '/admin/api/teachers/{teacher}'
 */
update.url = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { teacher: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { teacher: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    teacher: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        teacher: typeof args.teacher === 'object'
                ? args.teacher.id
                : args.teacher,
                }

    return update.definition.url
            .replace('{teacher}', parsedArgs.teacher.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TeacherController::update
 * @see app/Http/Controllers/Admin/TeacherController.php:52
 * @route '/admin/api/teachers/{teacher}'
 */
update.put = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\TeacherController::update
 * @see app/Http/Controllers/Admin/TeacherController.php:52
 * @route '/admin/api/teachers/{teacher}'
 */
update.patch = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\TeacherController::update
 * @see app/Http/Controllers/Admin/TeacherController.php:52
 * @route '/admin/api/teachers/{teacher}'
 */
    const updateForm = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TeacherController::update
 * @see app/Http/Controllers/Admin/TeacherController.php:52
 * @route '/admin/api/teachers/{teacher}'
 */
        updateForm.put = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\TeacherController::update
 * @see app/Http/Controllers/Admin/TeacherController.php:52
 * @route '/admin/api/teachers/{teacher}'
 */
        updateForm.patch = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\TeacherController::destroy
 * @see app/Http/Controllers/Admin/TeacherController.php:67
 * @route '/admin/api/teachers/{teacher}'
 */
export const destroy = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/api/teachers/{teacher}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\TeacherController::destroy
 * @see app/Http/Controllers/Admin/TeacherController.php:67
 * @route '/admin/api/teachers/{teacher}'
 */
destroy.url = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { teacher: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { teacher: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    teacher: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        teacher: typeof args.teacher === 'object'
                ? args.teacher.id
                : args.teacher,
                }

    return destroy.definition.url
            .replace('{teacher}', parsedArgs.teacher.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TeacherController::destroy
 * @see app/Http/Controllers/Admin/TeacherController.php:67
 * @route '/admin/api/teachers/{teacher}'
 */
destroy.delete = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\TeacherController::destroy
 * @see app/Http/Controllers/Admin/TeacherController.php:67
 * @route '/admin/api/teachers/{teacher}'
 */
    const destroyForm = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\TeacherController::destroy
 * @see app/Http/Controllers/Admin/TeacherController.php:67
 * @route '/admin/api/teachers/{teacher}'
 */
        destroyForm.delete = (args: { teacher: number | { id: number } } | [teacher: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const teachers = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default teachers