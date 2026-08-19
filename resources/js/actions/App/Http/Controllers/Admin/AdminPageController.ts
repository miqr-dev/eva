import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\AdminPageController::__invoke
 * @see app/Http/Controllers/Admin/AdminPageController.php:52
 * @route '/verwaltung/{resource}'
 */
const AdminPageController = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AdminPageController.url(args, options),
    method: 'get',
})

AdminPageController.definition = {
    methods: ["get","head"],
    url: '/verwaltung/{resource}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AdminPageController::__invoke
 * @see app/Http/Controllers/Admin/AdminPageController.php:52
 * @route '/verwaltung/{resource}'
 */
AdminPageController.url = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { resource: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    resource: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        resource: args.resource,
                }

    return AdminPageController.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AdminPageController::__invoke
 * @see app/Http/Controllers/Admin/AdminPageController.php:52
 * @route '/verwaltung/{resource}'
 */
AdminPageController.get = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AdminPageController.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\AdminPageController::__invoke
 * @see app/Http/Controllers/Admin/AdminPageController.php:52
 * @route '/verwaltung/{resource}'
 */
AdminPageController.head = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: AdminPageController.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\AdminPageController::__invoke
 * @see app/Http/Controllers/Admin/AdminPageController.php:52
 * @route '/verwaltung/{resource}'
 */
    const AdminPageControllerForm = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: AdminPageController.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\AdminPageController::__invoke
 * @see app/Http/Controllers/Admin/AdminPageController.php:52
 * @route '/verwaltung/{resource}'
 */
        AdminPageControllerForm.get = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: AdminPageController.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\AdminPageController::__invoke
 * @see app/Http/Controllers/Admin/AdminPageController.php:52
 * @route '/verwaltung/{resource}'
 */
        AdminPageControllerForm.head = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: AdminPageController.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    AdminPageController.form = AdminPageControllerForm
export default AdminPageController