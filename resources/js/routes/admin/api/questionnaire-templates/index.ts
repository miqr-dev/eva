import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::index
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:15
 * @route '/admin/api/questionnaire-templates'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/api/questionnaire-templates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::index
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:15
 * @route '/admin/api/questionnaire-templates'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::index
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:15
 * @route '/admin/api/questionnaire-templates'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::index
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:15
 * @route '/admin/api/questionnaire-templates'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::index
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:15
 * @route '/admin/api/questionnaire-templates'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::index
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:15
 * @route '/admin/api/questionnaire-templates'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::index
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:15
 * @route '/admin/api/questionnaire-templates'
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
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::store
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:28
 * @route '/admin/api/questionnaire-templates'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/questionnaire-templates',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::store
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:28
 * @route '/admin/api/questionnaire-templates'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::store
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:28
 * @route '/admin/api/questionnaire-templates'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::store
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:28
 * @route '/admin/api/questionnaire-templates'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::store
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:28
 * @route '/admin/api/questionnaire-templates'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::show
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:41
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
export const show = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/api/questionnaire-templates/{questionnaire_template}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::show
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:41
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
show.url = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { questionnaire_template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { questionnaire_template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    questionnaire_template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        questionnaire_template: typeof args.questionnaire_template === 'object'
                ? args.questionnaire_template.id
                : args.questionnaire_template,
                }

    return show.definition.url
            .replace('{questionnaire_template}', parsedArgs.questionnaire_template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::show
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:41
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
show.get = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::show
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:41
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
show.head = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::show
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:41
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
    const showForm = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::show
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:41
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
        showForm.get = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::show
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:41
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
        showForm.head = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::update
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:51
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
export const update = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/api/questionnaire-templates/{questionnaire_template}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::update
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:51
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
update.url = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { questionnaire_template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { questionnaire_template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    questionnaire_template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        questionnaire_template: typeof args.questionnaire_template === 'object'
                ? args.questionnaire_template.id
                : args.questionnaire_template,
                }

    return update.definition.url
            .replace('{questionnaire_template}', parsedArgs.questionnaire_template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::update
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:51
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
update.put = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::update
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:51
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
update.patch = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::update
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:51
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
    const updateForm = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::update
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:51
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
        updateForm.put = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::update
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:51
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
        updateForm.patch = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::destroy
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:62
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
export const destroy = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/api/questionnaire-templates/{questionnaire_template}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::destroy
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:62
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
destroy.url = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { questionnaire_template: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { questionnaire_template: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    questionnaire_template: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        questionnaire_template: typeof args.questionnaire_template === 'object'
                ? args.questionnaire_template.id
                : args.questionnaire_template,
                }

    return destroy.definition.url
            .replace('{questionnaire_template}', parsedArgs.questionnaire_template.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::destroy
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:62
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
destroy.delete = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::destroy
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:62
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
    const destroyForm = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireTemplateController::destroy
 * @see app/Http/Controllers/Admin/QuestionnaireTemplateController.php:62
 * @route '/admin/api/questionnaire-templates/{questionnaire_template}'
 */
        destroyForm.delete = (args: { questionnaire_template: number | { id: number } } | [questionnaire_template: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const questionnaireTemplates = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default questionnaireTemplates