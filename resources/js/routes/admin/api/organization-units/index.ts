import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::index
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:15
 * @route '/admin/api/organization-units'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/api/organization-units',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::index
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:15
 * @route '/admin/api/organization-units'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::index
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:15
 * @route '/admin/api/organization-units'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::index
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:15
 * @route '/admin/api/organization-units'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::index
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:15
 * @route '/admin/api/organization-units'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::index
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:15
 * @route '/admin/api/organization-units'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::index
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:15
 * @route '/admin/api/organization-units'
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
* @see \App\Http\Controllers\Admin\OrganizationUnitController::store
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:29
 * @route '/admin/api/organization-units'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/organization-units',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::store
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:29
 * @route '/admin/api/organization-units'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::store
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:29
 * @route '/admin/api/organization-units'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::store
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:29
 * @route '/admin/api/organization-units'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::store
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:29
 * @route '/admin/api/organization-units'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::show
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:36
 * @route '/admin/api/organization-units/{organization_unit}'
 */
export const show = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/api/organization-units/{organization_unit}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::show
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:36
 * @route '/admin/api/organization-units/{organization_unit}'
 */
show.url = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { organization_unit: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { organization_unit: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    organization_unit: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        organization_unit: typeof args.organization_unit === 'object'
                ? args.organization_unit.id
                : args.organization_unit,
                }

    return show.definition.url
            .replace('{organization_unit}', parsedArgs.organization_unit.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::show
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:36
 * @route '/admin/api/organization-units/{organization_unit}'
 */
show.get = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::show
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:36
 * @route '/admin/api/organization-units/{organization_unit}'
 */
show.head = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::show
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:36
 * @route '/admin/api/organization-units/{organization_unit}'
 */
    const showForm = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::show
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:36
 * @route '/admin/api/organization-units/{organization_unit}'
 */
        showForm.get = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::show
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:36
 * @route '/admin/api/organization-units/{organization_unit}'
 */
        showForm.head = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\OrganizationUnitController::update
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:45
 * @route '/admin/api/organization-units/{organization_unit}'
 */
export const update = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/api/organization-units/{organization_unit}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::update
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:45
 * @route '/admin/api/organization-units/{organization_unit}'
 */
update.url = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { organization_unit: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { organization_unit: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    organization_unit: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        organization_unit: typeof args.organization_unit === 'object'
                ? args.organization_unit.id
                : args.organization_unit,
                }

    return update.definition.url
            .replace('{organization_unit}', parsedArgs.organization_unit.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::update
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:45
 * @route '/admin/api/organization-units/{organization_unit}'
 */
update.put = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::update
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:45
 * @route '/admin/api/organization-units/{organization_unit}'
 */
update.patch = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::update
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:45
 * @route '/admin/api/organization-units/{organization_unit}'
 */
    const updateForm = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::update
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:45
 * @route '/admin/api/organization-units/{organization_unit}'
 */
        updateForm.put = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::update
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:45
 * @route '/admin/api/organization-units/{organization_unit}'
 */
        updateForm.patch = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\OrganizationUnitController::destroy
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:54
 * @route '/admin/api/organization-units/{organization_unit}'
 */
export const destroy = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/api/organization-units/{organization_unit}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::destroy
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:54
 * @route '/admin/api/organization-units/{organization_unit}'
 */
destroy.url = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { organization_unit: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { organization_unit: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    organization_unit: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        organization_unit: typeof args.organization_unit === 'object'
                ? args.organization_unit.id
                : args.organization_unit,
                }

    return destroy.definition.url
            .replace('{organization_unit}', parsedArgs.organization_unit.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::destroy
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:54
 * @route '/admin/api/organization-units/{organization_unit}'
 */
destroy.delete = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::destroy
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:54
 * @route '/admin/api/organization-units/{organization_unit}'
 */
    const destroyForm = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\OrganizationUnitController::destroy
 * @see app/Http/Controllers/Admin/OrganizationUnitController.php:54
 * @route '/admin/api/organization-units/{organization_unit}'
 */
        destroyForm.delete = (args: { organization_unit: number | { id: number } } | [organization_unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const organizationUnits = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default organizationUnits