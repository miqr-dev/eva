import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ModuleSectionController::store
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:17
 * @route '/admin/api/module-sections'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/module-sections',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ModuleSectionController::store
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:17
 * @route '/admin/api/module-sections'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleSectionController::store
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:17
 * @route '/admin/api/module-sections'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ModuleSectionController::store
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:17
 * @route '/admin/api/module-sections'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ModuleSectionController::store
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:17
 * @route '/admin/api/module-sections'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\ModuleSectionController::update
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:35
 * @route '/admin/api/module-sections/{module_section}'
 */
export const update = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/api/module-sections/{module_section}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\ModuleSectionController::update
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:35
 * @route '/admin/api/module-sections/{module_section}'
 */
update.url = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module_section: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { module_section: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    module_section: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        module_section: typeof args.module_section === 'object'
                ? args.module_section.id
                : args.module_section,
                }

    return update.definition.url
            .replace('{module_section}', parsedArgs.module_section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleSectionController::update
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:35
 * @route '/admin/api/module-sections/{module_section}'
 */
update.put = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\ModuleSectionController::update
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:35
 * @route '/admin/api/module-sections/{module_section}'
 */
update.patch = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\ModuleSectionController::update
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:35
 * @route '/admin/api/module-sections/{module_section}'
 */
    const updateForm = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ModuleSectionController::update
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:35
 * @route '/admin/api/module-sections/{module_section}'
 */
        updateForm.put = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\ModuleSectionController::update
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:35
 * @route '/admin/api/module-sections/{module_section}'
 */
        updateForm.patch = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\ModuleSectionController::destroy
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:47
 * @route '/admin/api/module-sections/{module_section}'
 */
export const destroy = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/api/module-sections/{module_section}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\ModuleSectionController::destroy
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:47
 * @route '/admin/api/module-sections/{module_section}'
 */
destroy.url = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module_section: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { module_section: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    module_section: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        module_section: typeof args.module_section === 'object'
                ? args.module_section.id
                : args.module_section,
                }

    return destroy.definition.url
            .replace('{module_section}', parsedArgs.module_section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleSectionController::destroy
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:47
 * @route '/admin/api/module-sections/{module_section}'
 */
destroy.delete = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\ModuleSectionController::destroy
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:47
 * @route '/admin/api/module-sections/{module_section}'
 */
    const destroyForm = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ModuleSectionController::destroy
 * @see app/Http/Controllers/Admin/ModuleSectionController.php:47
 * @route '/admin/api/module-sections/{module_section}'
 */
        destroyForm.delete = (args: { module_section: number | { id: number } } | [module_section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const moduleSections = {
    store: Object.assign(store, store),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default moduleSections