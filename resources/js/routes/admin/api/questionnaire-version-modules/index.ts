import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::store
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:20
 * @route '/admin/api/questionnaire-version-modules'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/questionnaire-version-modules',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::store
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:20
 * @route '/admin/api/questionnaire-version-modules'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::store
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:20
 * @route '/admin/api/questionnaire-version-modules'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::store
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:20
 * @route '/admin/api/questionnaire-version-modules'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::store
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:20
 * @route '/admin/api/questionnaire-version-modules'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:75
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
export const update = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/api/questionnaire-version-modules/{questionnaire_version_module}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:75
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
update.url = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { questionnaire_version_module: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { questionnaire_version_module: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    questionnaire_version_module: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        questionnaire_version_module: typeof args.questionnaire_version_module === 'object'
                ? args.questionnaire_version_module.id
                : args.questionnaire_version_module,
                }

    return update.definition.url
            .replace('{questionnaire_version_module}', parsedArgs.questionnaire_version_module.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:75
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
update.put = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:75
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
update.patch = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:75
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
    const updateForm = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:75
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
        updateForm.put = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:75
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
        updateForm.patch = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::destroy
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:105
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
export const destroy = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/api/questionnaire-version-modules/{questionnaire_version_module}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::destroy
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:105
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
destroy.url = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { questionnaire_version_module: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { questionnaire_version_module: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    questionnaire_version_module: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        questionnaire_version_module: typeof args.questionnaire_version_module === 'object'
                ? args.questionnaire_version_module.id
                : args.questionnaire_version_module,
                }

    return destroy.definition.url
            .replace('{questionnaire_version_module}', parsedArgs.questionnaire_version_module.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::destroy
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:105
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
destroy.delete = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::destroy
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:105
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
    const destroyForm = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionModuleController::destroy
 * @see app/Http/Controllers/Admin/QuestionnaireVersionModuleController.php:105
 * @route '/admin/api/questionnaire-version-modules/{questionnaire_version_module}'
 */
        destroyForm.delete = (args: { questionnaire_version_module: number | { id: number } } | [questionnaire_version_module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const questionnaireVersionModules = {
    store: Object.assign(store, store),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default questionnaireVersionModules