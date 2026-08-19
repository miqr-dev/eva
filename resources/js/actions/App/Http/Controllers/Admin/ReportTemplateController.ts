import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::index
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:15
 * @route '/admin/api/report-templates'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/api/report-templates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::index
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:15
 * @route '/admin/api/report-templates'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::index
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:15
 * @route '/admin/api/report-templates'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::index
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:15
 * @route '/admin/api/report-templates'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::index
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:15
 * @route '/admin/api/report-templates'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::index
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:15
 * @route '/admin/api/report-templates'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::index
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:15
 * @route '/admin/api/report-templates'
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
* @see \App\Http\Controllers\Admin\ReportTemplateController::store
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:28
 * @route '/admin/api/report-templates'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/report-templates',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::store
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:28
 * @route '/admin/api/report-templates'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::store
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:28
 * @route '/admin/api/report-templates'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::store
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:28
 * @route '/admin/api/report-templates'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::store
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:28
 * @route '/admin/api/report-templates'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::show
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:38
 * @route '/admin/api/report-templates/{report_template}'
 */
export const show = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/api/report-templates/{report_template}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::show
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:38
 * @route '/admin/api/report-templates/{report_template}'
 */
show.url = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report_template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { report_template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    report_template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report_template: typeof args.report_template === 'object'
                ? args.report_template.id
                : args.report_template,
                }

    return show.definition.url
            .replace('{report_template}', parsedArgs.report_template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::show
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:38
 * @route '/admin/api/report-templates/{report_template}'
 */
show.get = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::show
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:38
 * @route '/admin/api/report-templates/{report_template}'
 */
show.head = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::show
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:38
 * @route '/admin/api/report-templates/{report_template}'
 */
    const showForm = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::show
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:38
 * @route '/admin/api/report-templates/{report_template}'
 */
        showForm.get = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::show
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:38
 * @route '/admin/api/report-templates/{report_template}'
 */
        showForm.head = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\ReportTemplateController::update
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:47
 * @route '/admin/api/report-templates/{report_template}'
 */
export const update = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/api/report-templates/{report_template}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::update
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:47
 * @route '/admin/api/report-templates/{report_template}'
 */
update.url = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report_template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { report_template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    report_template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report_template: typeof args.report_template === 'object'
                ? args.report_template.id
                : args.report_template,
                }

    return update.definition.url
            .replace('{report_template}', parsedArgs.report_template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::update
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:47
 * @route '/admin/api/report-templates/{report_template}'
 */
update.put = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::update
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:47
 * @route '/admin/api/report-templates/{report_template}'
 */
update.patch = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::update
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:47
 * @route '/admin/api/report-templates/{report_template}'
 */
    const updateForm = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::update
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:47
 * @route '/admin/api/report-templates/{report_template}'
 */
        updateForm.put = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::update
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:47
 * @route '/admin/api/report-templates/{report_template}'
 */
        updateForm.patch = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\ReportTemplateController::destroy
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:56
 * @route '/admin/api/report-templates/{report_template}'
 */
export const destroy = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/api/report-templates/{report_template}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::destroy
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:56
 * @route '/admin/api/report-templates/{report_template}'
 */
destroy.url = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report_template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { report_template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    report_template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report_template: typeof args.report_template === 'object'
                ? args.report_template.id
                : args.report_template,
                }

    return destroy.definition.url
            .replace('{report_template}', parsedArgs.report_template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ReportTemplateController::destroy
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:56
 * @route '/admin/api/report-templates/{report_template}'
 */
destroy.delete = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::destroy
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:56
 * @route '/admin/api/report-templates/{report_template}'
 */
    const destroyForm = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ReportTemplateController::destroy
 * @see app/Http/Controllers/Admin/ReportTemplateController.php:56
 * @route '/admin/api/report-templates/{report_template}'
 */
        destroyForm.delete = (args: { report_template: number | { id: number } } | [report_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const ReportTemplateController = { index, store, show, update, destroy }

export default ReportTemplateController