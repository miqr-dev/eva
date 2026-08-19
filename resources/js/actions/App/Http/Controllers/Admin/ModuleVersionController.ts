import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ModuleVersionController::store
 * @see app/Http/Controllers/Admin/ModuleVersionController.php:20
 * @route '/admin/api/module-versions'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/module-versions',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ModuleVersionController::store
 * @see app/Http/Controllers/Admin/ModuleVersionController.php:20
 * @route '/admin/api/module-versions'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleVersionController::store
 * @see app/Http/Controllers/Admin/ModuleVersionController.php:20
 * @route '/admin/api/module-versions'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ModuleVersionController::store
 * @see app/Http/Controllers/Admin/ModuleVersionController.php:20
 * @route '/admin/api/module-versions'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ModuleVersionController::store
 * @see app/Http/Controllers/Admin/ModuleVersionController.php:20
 * @route '/admin/api/module-versions'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\ModuleVersionController::publish
 * @see app/Http/Controllers/Admin/ModuleVersionController.php:73
 * @route '/admin/api/module-versions/{module_version}/publish'
 */
export const publish = (args: { module_version: number | { id: number } } | [module_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: publish.url(args, options),
    method: 'patch',
})

publish.definition = {
    methods: ["patch"],
    url: '/admin/api/module-versions/{module_version}/publish',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\ModuleVersionController::publish
 * @see app/Http/Controllers/Admin/ModuleVersionController.php:73
 * @route '/admin/api/module-versions/{module_version}/publish'
 */
publish.url = (args: { module_version: number | { id: number } } | [module_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module_version: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { module_version: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    module_version: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        module_version: typeof args.module_version === 'object'
                ? args.module_version.id
                : args.module_version,
                }

    return publish.definition.url
            .replace('{module_version}', parsedArgs.module_version.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleVersionController::publish
 * @see app/Http/Controllers/Admin/ModuleVersionController.php:73
 * @route '/admin/api/module-versions/{module_version}/publish'
 */
publish.patch = (args: { module_version: number | { id: number } } | [module_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: publish.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\ModuleVersionController::publish
 * @see app/Http/Controllers/Admin/ModuleVersionController.php:73
 * @route '/admin/api/module-versions/{module_version}/publish'
 */
    const publishForm = (args: { module_version: number | { id: number } } | [module_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: publish.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ModuleVersionController::publish
 * @see app/Http/Controllers/Admin/ModuleVersionController.php:73
 * @route '/admin/api/module-versions/{module_version}/publish'
 */
        publishForm.patch = (args: { module_version: number | { id: number } } | [module_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: publish.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    publish.form = publishForm
const ModuleVersionController = { store, publish }

export default ModuleVersionController