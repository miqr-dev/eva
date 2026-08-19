import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::index
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:15
 * @route '/admin/api/email-templates'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/api/email-templates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::index
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:15
 * @route '/admin/api/email-templates'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::index
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:15
 * @route '/admin/api/email-templates'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::index
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:15
 * @route '/admin/api/email-templates'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::index
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:15
 * @route '/admin/api/email-templates'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::index
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:15
 * @route '/admin/api/email-templates'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::index
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:15
 * @route '/admin/api/email-templates'
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
* @see \App\Http\Controllers\Admin\EmailTemplateController::store
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:26
 * @route '/admin/api/email-templates'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/email-templates',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::store
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:26
 * @route '/admin/api/email-templates'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::store
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:26
 * @route '/admin/api/email-templates'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::store
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:26
 * @route '/admin/api/email-templates'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::store
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:26
 * @route '/admin/api/email-templates'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::show
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:33
 * @route '/admin/api/email-templates/{email_template}'
 */
export const show = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/api/email-templates/{email_template}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::show
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:33
 * @route '/admin/api/email-templates/{email_template}'
 */
show.url = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { email_template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { email_template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    email_template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        email_template: typeof args.email_template === 'object'
                ? args.email_template.id
                : args.email_template,
                }

    return show.definition.url
            .replace('{email_template}', parsedArgs.email_template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::show
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:33
 * @route '/admin/api/email-templates/{email_template}'
 */
show.get = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::show
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:33
 * @route '/admin/api/email-templates/{email_template}'
 */
show.head = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::show
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:33
 * @route '/admin/api/email-templates/{email_template}'
 */
    const showForm = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::show
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:33
 * @route '/admin/api/email-templates/{email_template}'
 */
        showForm.get = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::show
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:33
 * @route '/admin/api/email-templates/{email_template}'
 */
        showForm.head = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\EmailTemplateController::update
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:40
 * @route '/admin/api/email-templates/{email_template}'
 */
export const update = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/api/email-templates/{email_template}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::update
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:40
 * @route '/admin/api/email-templates/{email_template}'
 */
update.url = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { email_template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { email_template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    email_template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        email_template: typeof args.email_template === 'object'
                ? args.email_template.id
                : args.email_template,
                }

    return update.definition.url
            .replace('{email_template}', parsedArgs.email_template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::update
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:40
 * @route '/admin/api/email-templates/{email_template}'
 */
update.put = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::update
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:40
 * @route '/admin/api/email-templates/{email_template}'
 */
update.patch = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::update
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:40
 * @route '/admin/api/email-templates/{email_template}'
 */
    const updateForm = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::update
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:40
 * @route '/admin/api/email-templates/{email_template}'
 */
        updateForm.put = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::update
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:40
 * @route '/admin/api/email-templates/{email_template}'
 */
        updateForm.patch = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\EmailTemplateController::destroy
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:49
 * @route '/admin/api/email-templates/{email_template}'
 */
export const destroy = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/api/email-templates/{email_template}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::destroy
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:49
 * @route '/admin/api/email-templates/{email_template}'
 */
destroy.url = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { email_template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { email_template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    email_template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        email_template: typeof args.email_template === 'object'
                ? args.email_template.id
                : args.email_template,
                }

    return destroy.definition.url
            .replace('{email_template}', parsedArgs.email_template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EmailTemplateController::destroy
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:49
 * @route '/admin/api/email-templates/{email_template}'
 */
destroy.delete = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::destroy
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:49
 * @route '/admin/api/email-templates/{email_template}'
 */
    const destroyForm = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\EmailTemplateController::destroy
 * @see app/Http/Controllers/Admin/EmailTemplateController.php:49
 * @route '/admin/api/email-templates/{email_template}'
 */
        destroyForm.delete = (args: { email_template: number | { id: number } } | [email_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const emailTemplates = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default emailTemplates