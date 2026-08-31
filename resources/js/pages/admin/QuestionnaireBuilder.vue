<script setup lang="ts">
import { Head, Link, useHttp } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

import AdminLayout from '@/layouts/AdminLayout.vue';
import questionnaireTemplates from '@/routes/admin/api/questionnaire-templates';
import questionnaireVersionModules from '@/routes/admin/api/questionnaire-version-modules';
import questionnaireVersions from '@/routes/admin/api/questionnaire-versions';
import { index as questionEditor } from '@/routes/admin/questions';
import { index as resourceIndex } from '@/routes/admin/resources';

defineOptions({ layout: AdminLayout, inheritAttrs: false });

type AvailableModuleVersion = {
    id: number;
    module_id: number;
    module_name: string | null;
    version_number: number;
    title: string;
    description: string | null;
    status: string;
    target_type: string;
    default_language: string;
};

type ModuleLink = {
    id: number;
    questionnaire_version_id: number;
    module_version_id: number;
    sort_order: number;
    repeat_mode: string;
    module_version: AvailableModuleVersion;
};

type QuestionnaireVersion = {
    id: number;
    questionnaire_template_id: number;
    version_number: number;
    title: string;
    description: string | null;
    status: string;
    default_language: string;
    min_answers_to_show_results: number;
    published_at: string | null;
    modules: ModuleLink[];
};

type QuestionnaireTemplate = {
    id: number;
    name: string;
    description: string | null;
    is_active: boolean;
    versions: QuestionnaireVersion[];
};

type ApiResponse<T> = {
    data: T;
};

const props = defineProps<{
    templates: QuestionnaireTemplate[];
    availableModuleVersions: AvailableModuleVersion[];
}>();

const localTemplates = ref<QuestionnaireTemplate[]>(
    JSON.parse(JSON.stringify(props.templates)) as QuestionnaireTemplate[],
);
const selectedTemplateId = ref<number | null>(
    localTemplates.value[0]?.id ?? null,
);
const selectedVersionId = ref<number | null>(null);
const selectedModuleVersionId = ref<number | null>(
    props.availableModuleVersions[0]?.id ?? null,
);
const selectedRepeatMode = ref('once');
const successMessage = ref('');
const generalError = ref('');
let successTimer: ReturnType<typeof setTimeout> | null = null;

const templateForm = useHttp<
    { name: string; description: string | null; is_active: boolean },
    ApiResponse<QuestionnaireTemplate>
>({
    name: '',
    description: null,
    is_active: true,
});

const versionForm = useHttp<
    {
        questionnaire_template_id: number | null;
        source_version_id: number | null;
        title: string;
        description: string | null;
        default_language: string;
    },
    ApiResponse<QuestionnaireVersion>
>({
    questionnaire_template_id: null,
    source_version_id: null,
    title: '',
    description: null,
    default_language: 'de',
});

const moduleLinkForm = useHttp<
    {
        questionnaire_version_id: number | null;
        module_version_id: number | null;
        repeat_mode: string;
        sort_order: number | null;
    },
    ApiResponse<ModuleLink>
>({
    questionnaire_version_id: null,
    module_version_id: null,
    repeat_mode: 'once',
    sort_order: null,
});

const deleteForm = useHttp<Record<string, never>, unknown>({});
const publishForm = useHttp<
    Record<string, never>,
    ApiResponse<QuestionnaireVersion>
>({});

const selectedTemplate = computed(
    () =>
        localTemplates.value.find(
            (template) => template.id === selectedTemplateId.value,
        ) ?? null,
);

const selectedVersion = computed(
    () =>
        selectedTemplate.value?.versions.find(
            (version) => version.id === selectedVersionId.value,
        ) ?? null,
);

const isDraft = computed(() => selectedVersion.value?.status === 'draft');

const sortedModuleLinks = computed(() =>
    [...(selectedVersion.value?.modules ?? [])].sort(
        (left, right) => left.sort_order - right.sort_order,
    ),
);

const publishDisabledReason = computed(() => {
    if (!selectedVersion.value || !isDraft.value) {
        return null;
    }

    if (sortedModuleLinks.value.length === 0) {
        return props.availableModuleVersions.length === 0
            ? 'Es gibt noch keine veröffentlichten Modulversionen. Veröffentlichen Sie zuerst eine Modulversion im Frageneditor.'
            : 'Fügen Sie mindestens eine veröffentlichte Modulversion hinzu.';
    }

    return null;
});

const selectableModuleVersions = computed(() => {
    const usedIds = new Set(
        (selectedVersion.value?.modules ?? []).map(
            (moduleLink) => moduleLink.module_version_id,
        ),
    );

    return props.availableModuleVersions.filter(
        (moduleVersion) => !usedIds.has(moduleVersion.id),
    );
});

watch(
    () => props.templates,
    (templates) => {
        localTemplates.value = JSON.parse(
            JSON.stringify(templates),
        ) as QuestionnaireTemplate[];
        selectPreferredVersion();
    },
);

watch(selectedTemplateId, () => {
    selectPreferredVersion();
});

watch(selectedVersionId, () => {
    syncVersionForm();
});

selectPreferredVersion();

function selectPreferredVersion(): void {
    const versions = selectedTemplate.value?.versions ?? [];
    const draft = versions.find((version) => version.status === 'draft');
    selectedVersionId.value = draft?.id ?? versions[0]?.id ?? null;
    syncVersionForm();
}

function syncVersionForm(): void {
    versionForm.clearErrors();
    versionForm.questionnaire_template_id = selectedTemplate.value?.id ?? null;
    versionForm.source_version_id = selectedVersion.value?.id ?? null;
    versionForm.title =
        selectedVersion.value?.title ?? selectedTemplate.value?.name ?? '';
    versionForm.description =
        selectedVersion.value?.description ??
        selectedTemplate.value?.description ??
        null;
    versionForm.default_language =
        selectedVersion.value?.default_language ?? 'de';
}

function showSuccess(message: string): void {
    successMessage.value = message;

    if (successTimer) {
        clearTimeout(successTimer);
    }

    successTimer = setTimeout(() => {
        successMessage.value = '';
    }, 3500);
}

function requestFailed(): false {
    generalError.value =
        'Die Änderung konnte nicht gespeichert werden. Bitte prüfen Sie die Eingaben.';

    return false;
}

function statusLabel(status: string): string {
    return (
        {
            archived: 'Archiviert',
            draft: 'Entwurf',
            published: 'Veröffentlicht',
        }[status] ?? status
    );
}

function repeatModeLabel(repeatMode: string): string {
    return (
        {
            once: 'Einmal',
            per_target: 'Pro Zielperson',
        }[repeatMode] ?? repeatMode
    );
}

function targetTypeLabel(targetType: string): string {
    return (
        {
            course: 'Kurs',
            none: 'Kein Ziel',
            organization: 'Standort',
            teacher: 'Lehrperson',
        }[targetType] ?? targetType
    );
}

function moduleLabel(moduleVersion: AvailableModuleVersion): string {
    return `${moduleVersion.module_name ?? 'Modul'} · ${moduleVersion.title} · Version ${moduleVersion.version_number}`;
}

function replaceVersion(version: QuestionnaireVersion): void {
    const template = localTemplates.value.find(
        (item) => item.id === version.questionnaire_template_id,
    );

    if (!template) {
        return;
    }

    const index = template.versions.findIndex((item) => item.id === version.id);

    if (index >= 0) {
        template.versions[index] = version;
    } else {
        template.versions.unshift(version);
    }

    template.versions.sort(
        (left, right) => right.version_number - left.version_number,
    );
    selectedTemplateId.value = template.id;
    selectedVersionId.value = version.id;
}

function replaceModuleLink(moduleLink: ModuleLink): void {
    if (!selectedVersion.value) {
        return;
    }

    const index = selectedVersion.value.modules.findIndex(
        (item) => item.id === moduleLink.id,
    );

    if (index >= 0) {
        selectedVersion.value.modules[index] = moduleLink;
    } else {
        selectedVersion.value.modules.push(moduleLink);
    }

    selectedVersion.value.modules.sort(
        (left, right) => left.sort_order - right.sort_order,
    );
}

async function createTemplate(): Promise<void> {
    generalError.value = '';

    await templateForm.submit(questionnaireTemplates.store(), {
        onSuccess: (response) => {
            const template = { ...response.data, versions: [] };
            localTemplates.value.push(template);
            localTemplates.value.sort((left, right) =>
                left.name.localeCompare(right.name, 'de'),
            );
            selectedTemplateId.value = template.id;
            templateForm.resetAndClearErrors();
            templateForm.is_active = true;
            showSuccess('Die Fragebogenvorlage wurde angelegt.');
        },
        onHttpException: requestFailed,
        onNetworkError: requestFailed,
    });
}

async function createDraftVersion(): Promise<void> {
    if (!selectedTemplate.value) {
        return;
    }

    generalError.value = '';
    versionForm.questionnaire_template_id = selectedTemplate.value.id;
    versionForm.source_version_id = selectedVersion.value?.id ?? null;

    await versionForm.submit(questionnaireVersions.store(), {
        onSuccess: (response) => {
            replaceVersion(response.data);
            showSuccess('Die Entwurfsversion wurde angelegt.');
        },
        onHttpException: requestFailed,
        onNetworkError: requestFailed,
    });
}

async function saveVersion(): Promise<void> {
    if (!selectedVersion.value) {
        return;
    }

    await versionForm.submit(
        questionnaireVersions.update(selectedVersion.value.id),
        {
            onSuccess: (response) => {
                replaceVersion(response.data);
                showSuccess('Die Version wurde gespeichert.');
            },
            onHttpException: requestFailed,
            onNetworkError: requestFailed,
        },
    );
}

async function publishVersion(): Promise<void> {
    if (!selectedVersion.value) {
        return;
    }

    generalError.value = '';

    await publishForm.submit(
        questionnaireVersions.publish(selectedVersion.value.id),
        {
            onSuccess: (response) => {
                replaceVersion(response.data);
                showSuccess('Die Version wurde veröffentlicht.');
            },
            onHttpException: requestFailed,
            onNetworkError: requestFailed,
        },
    );
}

async function addModule(): Promise<void> {
    if (!selectedVersion.value || !selectedModuleVersionId.value) {
        return;
    }

    moduleLinkForm.questionnaire_version_id = selectedVersion.value.id;
    moduleLinkForm.module_version_id = selectedModuleVersionId.value;
    moduleLinkForm.repeat_mode = selectedRepeatMode.value;
    moduleLinkForm.sort_order = selectedVersion.value.modules.length;

    await moduleLinkForm.submit(questionnaireVersionModules.store(), {
        onSuccess: (response) => {
            replaceModuleLink(response.data);
            selectedModuleVersionId.value =
                selectableModuleVersions.value.find(
                    (moduleVersion) =>
                        moduleVersion.id !== response.data.module_version_id,
                )?.id ?? null;
            showSuccess('Das Modul wurde hinzugefügt.');
        },
        onHttpException: requestFailed,
        onNetworkError: requestFailed,
    });
}

async function updateModuleLink(
    moduleLink: ModuleLink,
    attributes: { repeat_mode?: string; sort_order?: number },
): Promise<void> {
    if (!selectedVersion.value) {
        return;
    }

    moduleLinkForm.questionnaire_version_id = selectedVersion.value.id;
    moduleLinkForm.module_version_id = moduleLink.module_version_id;
    moduleLinkForm.repeat_mode =
        attributes.repeat_mode ?? moduleLink.repeat_mode;
    moduleLinkForm.sort_order = attributes.sort_order ?? moduleLink.sort_order;

    await moduleLinkForm.submit(
        questionnaireVersionModules.update(moduleLink.id),
        {
            onSuccess: (response) => {
                replaceModuleLink(response.data);
            },
            onHttpException: requestFailed,
            onNetworkError: requestFailed,
        },
    );
}

async function removeModule(moduleLink: ModuleLink): Promise<void> {
    if (!selectedVersion.value) {
        return;
    }

    await deleteForm.submit(
        questionnaireVersionModules.destroy(moduleLink.id),
        {
            onSuccess: () => {
                selectedVersion.value!.modules = selectedVersion
                    .value!.modules.filter((item) => item.id !== moduleLink.id)
                    .map((item, index) => ({ ...item, sort_order: index }));
                showSuccess('Das Modul wurde entfernt.');
            },
            onHttpException: requestFailed,
            onNetworkError: requestFailed,
        },
    );
}
</script>

<template>
    <Head title="Fragebogen-Builder" />

    <div class="mx-auto max-w-[1600px]">
        <div
            class="flex flex-col justify-between gap-5 xl:flex-row xl:items-end"
        >
            <div>
                <p
                    class="text-xs font-semibold tracking-[0.2em] text-teal-600 uppercase"
                >
                    Fragebogen-Builder
                </p>
                <h1
                    class="mt-2 text-3xl font-semibold tracking-tight text-slate-900"
                >
                    Fragebögen aus Modulen zusammenstellen
                </h1>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    Bauen Sie veröffentlichbare Fragebogenversionen aus
                    bestehenden Modulversionen. Module können einmalig oder pro
                    Zielperson, zum Beispiel pro Lehrperson, wiederholt werden.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <Link
                    :href="questionEditor()"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-teal-200 hover:text-teal-700"
                >
                    Module bearbeiten
                </Link>
                <Link
                    :href="resourceIndex('frageboegen')"
                    class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800"
                >
                    Vorlagen verwalten
                </Link>
            </div>
        </div>

        <div
            v-if="successMessage"
            class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700"
        >
            {{ successMessage }}
        </div>
        <div
            v-if="generalError"
            class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700"
        >
            {{ generalError }}
        </div>

        <div class="mt-7 grid gap-6 xl:grid-cols-[340px_1fr]">
            <aside class="space-y-5">
                <section
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <h2 class="font-semibold text-slate-900">Neue Vorlage</h2>
                    <form
                        class="mt-4 space-y-3"
                        @submit.prevent="createTemplate"
                    >
                        <label class="block">
                            <span
                                class="mb-1.5 block text-sm font-medium text-slate-700"
                            >
                                Name
                            </span>
                            <input
                                v-model="templateForm.name"
                                required
                                class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                                placeholder="Kursbewertung"
                            />
                        </label>
                        <label class="block">
                            <span
                                class="mb-1.5 block text-sm font-medium text-slate-700"
                            >
                                Beschreibung
                            </span>
                            <textarea
                                v-model="templateForm.description"
                                rows="3"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                            />
                        </label>
                        <button
                            type="submit"
                            :disabled="templateForm.processing"
                            class="h-11 w-full rounded-xl bg-teal-700 px-4 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            Vorlage anlegen
                        </button>
                    </form>
                </section>

                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="font-semibold text-slate-900">Vorlagen</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Wählen Sie eine Vorlage und danach eine Version.
                        </p>
                    </div>
                    <div class="max-h-[560px] overflow-y-auto p-2">
                        <button
                            v-for="template in localTemplates"
                            :key="template.id"
                            type="button"
                            class="w-full rounded-xl px-3 py-3 text-left transition"
                            :class="
                                selectedTemplateId === template.id
                                    ? 'bg-teal-50 text-teal-900'
                                    : 'hover:bg-slate-50'
                            "
                            @click="selectedTemplateId = template.id"
                        >
                            <span class="block font-medium">
                                {{ template.name }}
                            </span>
                            <span class="mt-1 block text-xs text-slate-500">
                                {{ template.versions.length }} Versionen
                            </span>
                        </button>
                    </div>
                </section>
            </aside>

            <main class="space-y-6">
                <section
                    v-if="!selectedTemplate"
                    class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"
                >
                    <h2 class="font-semibold text-slate-900">
                        Noch keine Vorlage ausgewählt
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Legen Sie links eine Vorlage an oder wählen Sie eine
                        vorhandene Vorlage aus.
                    </p>
                </section>

                <template v-else>
                    <section
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                    >
                        <div
                            class="flex flex-col justify-between gap-4 lg:flex-row lg:items-start"
                        >
                            <div>
                                <p
                                    class="text-xs font-semibold tracking-[0.18em] text-teal-600 uppercase"
                                >
                                    Vorlage
                                </p>
                                <h2
                                    class="mt-1 text-2xl font-semibold text-slate-900"
                                >
                                    {{ selectedTemplate.name }}
                                </h2>
                                <p
                                    v-if="selectedTemplate.description"
                                    class="mt-2 text-sm leading-6 text-slate-500"
                                >
                                    {{ selectedTemplate.description }}
                                </p>
                            </div>

                            <button
                                type="button"
                                class="rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700 disabled:cursor-not-allowed disabled:opacity-60"
                                :disabled="versionForm.processing"
                                @click="createDraftVersion"
                            >
                                Neue Entwurfsversion
                            </button>
                        </div>

                        <div
                            v-if="selectedTemplate.versions.length > 0"
                            class="mt-5 flex flex-wrap gap-2"
                        >
                            <button
                                v-for="version in selectedTemplate.versions"
                                :key="version.id"
                                type="button"
                                class="rounded-full border px-3 py-1.5 text-sm font-medium transition"
                                :class="
                                    selectedVersionId === version.id
                                        ? 'border-teal-300 bg-teal-50 text-teal-700'
                                        : 'border-slate-200 text-slate-600 hover:border-teal-200 hover:text-teal-700'
                                "
                                @click="selectedVersionId = version.id"
                            >
                                Version {{ version.version_number }} ·
                                {{ statusLabel(version.status) }}
                            </button>
                        </div>
                    </section>

                    <section
                        v-if="!selectedVersion"
                        class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"
                    >
                        <h2 class="font-semibold text-slate-900">
                            Keine Version vorhanden
                        </h2>
                        <p class="mt-2 text-sm text-slate-500">
                            Erstellen Sie eine Entwurfsversion, um Module
                            hinzuzufügen.
                        </p>
                    </section>

                    <template v-else>
                        <section
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                        >
                            <div
                                class="flex flex-col justify-between gap-4 lg:flex-row lg:items-start"
                            >
                                <div>
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold"
                                        :class="
                                            isDraft
                                                ? 'bg-amber-100 text-amber-700'
                                                : 'bg-emerald-100 text-emerald-700'
                                        "
                                    >
                                        {{
                                            statusLabel(selectedVersion.status)
                                        }}
                                    </span>
                                    <h2
                                        class="mt-3 text-xl font-semibold text-slate-900"
                                    >
                                        Version
                                        {{ selectedVersion.version_number }}
                                    </h2>
                                </div>
                                <div
                                    v-if="isDraft"
                                    class="flex flex-col gap-2 lg:items-end"
                                >
                                    <button
                                        type="button"
                                        :disabled="
                                            publishForm.processing ||
                                            Boolean(publishDisabledReason)
                                        "
                                        class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60"
                                        @click="publishVersion"
                                    >
                                        Version veröffentlichen
                                    </button>
                                    <p
                                        v-if="publishDisabledReason"
                                        class="max-w-sm text-left text-xs text-slate-500 lg:text-right"
                                    >
                                        {{ publishDisabledReason }}
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="!isDraft"
                                class="mt-5 rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-800"
                            >
                                Veröffentlichte Versionen sind gesperrt. Für
                                Änderungen erstellen Sie eine neue
                                Entwurfsversion.
                            </div>

                            <form
                                class="mt-5 grid gap-4 lg:grid-cols-2"
                                @submit.prevent="saveVersion"
                            >
                                <label class="block lg:col-span-2">
                                    <span
                                        class="mb-1.5 block text-sm font-medium text-slate-700"
                                    >
                                        Titel
                                    </span>
                                    <input
                                        v-model="versionForm.title"
                                        :disabled="!isDraft"
                                        required
                                        class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100 disabled:bg-slate-50 disabled:text-slate-500"
                                    />
                                </label>
                                <label class="block lg:col-span-2">
                                    <span
                                        class="mb-1.5 block text-sm font-medium text-slate-700"
                                    >
                                        Beschreibung
                                    </span>
                                    <textarea
                                        v-model="versionForm.description"
                                        :disabled="!isDraft"
                                        rows="3"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100 disabled:bg-slate-50 disabled:text-slate-500"
                                    />
                                </label>
                                <label class="block">
                                    <span
                                        class="mb-1.5 block text-sm font-medium text-slate-700"
                                    >
                                        Standardsprache
                                    </span>
                                    <input
                                        v-model="versionForm.default_language"
                                        :disabled="!isDraft"
                                        required
                                        maxlength="10"
                                        class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100 disabled:bg-slate-50 disabled:text-slate-500"
                                    />
                                </label>
                                <div v-if="isDraft" class="lg:col-span-2">
                                    <button
                                        type="submit"
                                        :disabled="versionForm.processing"
                                        class="rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        Version speichern
                                    </button>
                                </div>
                            </form>
                        </section>

                        <section
                            v-if="isDraft"
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                        >
                            <h2 class="font-semibold text-slate-900">
                                Modul hinzufügen
                            </h2>
                            <div
                                class="mt-4 grid gap-4 lg:grid-cols-[1fr_220px_auto]"
                            >
                                <label class="block">
                                    <span
                                        class="mb-1.5 block text-sm font-medium text-slate-700"
                                    >
                                        Veröffentlichte Modulversion
                                    </span>
                                    <select
                                        v-model="selectedModuleVersionId"
                                        class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                                    >
                                        <option
                                            v-for="moduleVersion in selectableModuleVersions"
                                            :key="moduleVersion.id"
                                            :value="moduleVersion.id"
                                        >
                                            {{ moduleLabel(moduleVersion) }}
                                        </option>
                                    </select>
                                </label>
                                <label class="block">
                                    <span
                                        class="mb-1.5 block text-sm font-medium text-slate-700"
                                    >
                                        Wiederholung
                                    </span>
                                    <select
                                        v-model="selectedRepeatMode"
                                        class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                                    >
                                        <option value="once">Einmal</option>
                                        <option value="per_target">
                                            Pro Zielperson
                                        </option>
                                    </select>
                                </label>
                                <button
                                    type="button"
                                    :disabled="
                                        moduleLinkForm.processing ||
                                        !selectedModuleVersionId
                                    "
                                    class="self-end rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700 disabled:cursor-not-allowed disabled:opacity-60"
                                    @click="addModule"
                                >
                                    Hinzufügen
                                </button>
                            </div>
                            <p
                                v-if="selectableModuleVersions.length === 0"
                                class="mt-3 text-sm text-slate-500"
                            >
                                Es gibt keine weiteren veröffentlichten
                                Modulversionen. Prüfen Sie im Frageneditor, ob
                                mindestens eine Modulversion veröffentlicht ist.
                            </p>
                        </section>

                        <section
                            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                        >
                            <div
                                class="flex flex-col justify-between gap-3 border-b border-slate-200 px-5 py-4 lg:flex-row lg:items-center"
                            >
                                <div>
                                    <h2 class="font-semibold text-slate-900">
                                        Module dieser Version
                                    </h2>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Reihenfolge und Wiederholungsmodus
                                        bestimmen den späteren Fragebogenablauf.
                                    </p>
                                </div>
                                <span
                                    class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-600"
                                >
                                    {{ sortedModuleLinks.length }} Module
                                </span>
                            </div>

                            <div class="divide-y divide-slate-100">
                                <article
                                    v-for="(
                                        moduleLink, index
                                    ) in sortedModuleLinks"
                                    :key="moduleLink.id"
                                    class="grid gap-4 px-5 py-4 lg:grid-cols-[1fr_180px_auto]"
                                >
                                    <div>
                                        <h3 class="font-medium text-slate-900">
                                            {{
                                                moduleLink.module_version
                                                    .module_name
                                            }}
                                        </h3>
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{
                                                moduleLink.module_version.title
                                            }}
                                            · Version
                                            {{
                                                moduleLink.module_version
                                                    .version_number
                                            }}
                                            · Ziel:
                                            {{
                                                targetTypeLabel(
                                                    moduleLink.module_version
                                                        .target_type,
                                                )
                                            }}
                                        </p>
                                        <p
                                            v-if="
                                                moduleLink.module_version
                                                    .description
                                            "
                                            class="mt-2 text-sm leading-6 text-slate-500"
                                        >
                                            {{
                                                moduleLink.module_version
                                                    .description
                                            }}
                                        </p>
                                    </div>

                                    <label>
                                        <span
                                            class="mb-1.5 block text-sm font-medium text-slate-700"
                                        >
                                            Wiederholung
                                        </span>
                                        <select
                                            :value="moduleLink.repeat_mode"
                                            :disabled="!isDraft"
                                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100 disabled:bg-slate-50 disabled:text-slate-500"
                                            @change="
                                                updateModuleLink(moduleLink, {
                                                    repeat_mode: (
                                                        $event.target as HTMLSelectElement
                                                    ).value,
                                                })
                                            "
                                        >
                                            <option value="once">Einmal</option>
                                            <option value="per_target">
                                                Pro Zielperson
                                            </option>
                                        </select>
                                        <span
                                            class="mt-1 block text-xs text-slate-500"
                                        >
                                            {{
                                                repeatModeLabel(
                                                    moduleLink.repeat_mode,
                                                )
                                            }}
                                        </span>
                                    </label>

                                    <div
                                        v-if="isDraft"
                                        class="flex items-end gap-2 lg:justify-end"
                                    >
                                        <button
                                            type="button"
                                            :disabled="index === 0"
                                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-teal-200 hover:text-teal-700 disabled:cursor-not-allowed disabled:opacity-40"
                                            @click="
                                                updateModuleLink(moduleLink, {
                                                    sort_order: index - 1,
                                                })
                                            "
                                        >
                                            Hoch
                                        </button>
                                        <button
                                            type="button"
                                            :disabled="
                                                index ===
                                                sortedModuleLinks.length - 1
                                            "
                                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-teal-200 hover:text-teal-700 disabled:cursor-not-allowed disabled:opacity-40"
                                            @click="
                                                updateModuleLink(moduleLink, {
                                                    sort_order: index + 1,
                                                })
                                            "
                                        >
                                            Runter
                                        </button>
                                        <button
                                            type="button"
                                            class="rounded-lg px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                                            @click="removeModule(moduleLink)"
                                        >
                                            Entfernen
                                        </button>
                                    </div>
                                </article>

                                <div
                                    v-if="sortedModuleLinks.length === 0"
                                    class="px-5 py-12 text-center"
                                >
                                    <h3 class="font-medium text-slate-800">
                                        Noch keine Module
                                    </h3>
                                    <p class="mt-2 text-sm text-slate-500">
                                        Fügen Sie veröffentlichte Module hinzu,
                                        um den Fragebogen zusammenzustellen.
                                        Modulversionen werden im Frageneditor
                                        veröffentlicht.
                                    </p>
                                </div>
                            </div>
                        </section>
                    </template>
                </template>
            </main>
        </div>
    </div>
</template>
