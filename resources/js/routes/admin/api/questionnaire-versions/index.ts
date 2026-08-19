import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::store
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:19
 * @route '/admin/api/questionnaire-versions'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/api/questionnaire-versions',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::store
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:19
 * @route '/admin/api/questionnaire-versions'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::store
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:19
 * @route '/admin/api/questionnaire-versions'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::store
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:19
 * @route '/admin/api/questionnaire-versions'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::store
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:19
 * @route '/admin/api/questionnaire-versions'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:86
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}'
 */
export const update = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/api/questionnaire-versions/{questionnaire_version}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:86
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}'
 */
update.url = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { questionnaire_version: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { questionnaire_version: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    questionnaire_version: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        questionnaire_version: typeof args.questionnaire_version === 'object'
                ? args.questionnaire_version.id
                : args.questionnaire_version,
                }

    return update.definition.url
            .replace('{questionnaire_version}', parsedArgs.questionnaire_version.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:86
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}'
 */
update.put = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:86
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}'
 */
update.patch = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:86
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}'
 */
    const updateForm = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:86
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}'
 */
        updateForm.put = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::update
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:86
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}'
 */
        updateForm.patch = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::publish
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:99
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}/publish'
 */
export const publish = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: publish.url(args, options),
    method: 'patch',
})

publish.definition = {
    methods: ["patch"],
    url: '/admin/api/questionnaire-versions/{questionnaire_version}/publish',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::publish
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:99
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}/publish'
 */
publish.url = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { questionnaire_version: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { questionnaire_version: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    questionnaire_version: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        questionnaire_version: typeof args.questionnaire_version === 'object'
                ? args.questionnaire_version.id
                : args.questionnaire_version,
                }

    return publish.definition.url
            .replace('{questionnaire_version}', parsedArgs.questionnaire_version.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::publish
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:99
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}/publish'
 */
publish.patch = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: publish.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::publish
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:99
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}/publish'
 */
    const publishForm = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: publish.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\QuestionnaireVersionController::publish
 * @see app/Http/Controllers/Admin/QuestionnaireVersionController.php:99
 * @route '/admin/api/questionnaire-versions/{questionnaire_version}/publish'
 */
        publishForm.patch = (args: { questionnaire_version: number | { id: number } } | [questionnaire_version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: publish.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    publish.form = publishForm
const questionnaireVersions = {
    store: Object.assign(store, store),
update: Object.assign(update, update),
publish: Object.assign(publish, publish),
}

export default questionnaireVersions