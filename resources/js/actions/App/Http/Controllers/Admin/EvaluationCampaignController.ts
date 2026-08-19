import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::index
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:15
 * @route '/admin/api/evaluation-campaigns'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/api/evaluation-campaigns',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::index
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:15
 * @route '/admin/api/evaluation-campaigns'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::index
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:15
 * @route '/admin/api/evaluation-campaigns'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::index
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:15
 * @route '/admin/api/evaluation-campaigns'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::index
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:15
 * @route '/admin/api/evaluation-campaigns'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::index
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:15
 * @route '/admin/api/evaluation-campaigns'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::index
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:15
 * @route '/admin/api/evaluation-campaigns'
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
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::store
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:28
 * @route '/admin/api/evaluation-campaigns'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/evaluation-campaigns',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::store
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:28
 * @route '/admin/api/evaluation-campaigns'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::store
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:28
 * @route '/admin/api/evaluation-campaigns'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::store
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:28
 * @route '/admin/api/evaluation-campaigns'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::store
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:28
 * @route '/admin/api/evaluation-campaigns'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:45
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
export const show = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/api/evaluation-campaigns/{evaluation_campaign}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:45
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
show.url = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { evaluation_campaign: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { evaluation_campaign: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    evaluation_campaign: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        evaluation_campaign: typeof args.evaluation_campaign === 'object'
                ? args.evaluation_campaign.id
                : args.evaluation_campaign,
                }

    return show.definition.url
            .replace('{evaluation_campaign}', parsedArgs.evaluation_campaign.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:45
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
show.get = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:45
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
show.head = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:45
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
    const showForm = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:45
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
        showForm.get = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::show
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:45
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
        showForm.head = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::update
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:61
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
export const update = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/api/evaluation-campaigns/{evaluation_campaign}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::update
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:61
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
update.url = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { evaluation_campaign: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { evaluation_campaign: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    evaluation_campaign: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        evaluation_campaign: typeof args.evaluation_campaign === 'object'
                ? args.evaluation_campaign.id
                : args.evaluation_campaign,
                }

    return update.definition.url
            .replace('{evaluation_campaign}', parsedArgs.evaluation_campaign.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::update
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:61
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
update.put = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::update
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:61
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
update.patch = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::update
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:61
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
    const updateForm = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::update
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:61
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
        updateForm.put = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::update
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:61
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
        updateForm.patch = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::destroy
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:76
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
export const destroy = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/api/evaluation-campaigns/{evaluation_campaign}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::destroy
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:76
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
destroy.url = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { evaluation_campaign: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { evaluation_campaign: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    evaluation_campaign: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        evaluation_campaign: typeof args.evaluation_campaign === 'object'
                ? args.evaluation_campaign.id
                : args.evaluation_campaign,
                }

    return destroy.definition.url
            .replace('{evaluation_campaign}', parsedArgs.evaluation_campaign.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::destroy
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:76
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
destroy.delete = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::destroy
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:76
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
    const destroyForm = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\EvaluationCampaignController::destroy
 * @see app/Http/Controllers/Admin/EvaluationCampaignController.php:76
 * @route '/admin/api/evaluation-campaigns/{evaluation_campaign}'
 */
        destroyForm.delete = (args: { evaluation_campaign: number | { id: number } } | [evaluation_campaign: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const EvaluationCampaignController = { index, store, show, update, destroy }

export default EvaluationCampaignController