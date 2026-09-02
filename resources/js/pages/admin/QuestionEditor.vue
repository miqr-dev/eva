<script setup lang="ts">
import { Head, Link, useHttp } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

import AdminLayout from '@/layouts/AdminLayout.vue';
import moduleSections from '@/routes/admin/api/module-sections';
import moduleVersions from '@/routes/admin/api/module-versions';
import questions from '@/routes/admin/api/questions';
import { index as resourceIndex } from '@/routes/admin/resources';

defineOptions({ layout: AdminLayout, inheritAttrs: false });

type QuestionOption = {
    id: number;
    option_text: string;
    value: string;
    sort_order: number;
};

type Question = {
    id: number;
    module_section_id: number;
    question_text: string;
    question_type: string;
    scale_min: number | null;
    scale_max: number | null;
    scale_min_label: string | null;
    scale_max_label: string | null;
    scale_labels: (string | null)[] | null;
    is_required: boolean;
    sort_order: number;
    options: QuestionOption[];
};

type ModuleSection = {
    id: number;
    module_version_id: number;
    title: string;
    description: string | null;
    sort_order: number;
    questions: Question[];
};

type ModuleVersion = {
    id: number;
    module_id: number;
    version_number: number;
    title: string;
    description: string | null;
    status: string;
    default_language: string;
    target_type: string;
    target_role_id: number | null;
    target_role: { id: number; name: string } | null;
    published_at: string | null;
    sections: ModuleSection[];
};

type TeacherRoleOption = {
    id: number;
    name: string;
};

type EditorModule = {
    id: number;
    name: string;
    description: string | null;
    is_active: boolean;
    versions: ModuleVersion[];
};

type ApiResponse<T> = {
    data: T;
};

type DeleteTarget =
    | { kind: 'section'; item: ModuleSection }
    | { kind: 'question'; item: Question };

const DEFAULT_FIVE_POINT_SCALE_LABELS = [
    'Stimme nicht zu',
    'Stimme überwiegend nicht zu',
    'Neutral',
    'Stimme überwiegend zu',
    'Stimme zu',
];

const props = defineProps<{
    modules: EditorModule[];
    teacherRoles: TeacherRoleOption[];
}>();

const localModules = ref<EditorModule[]>(
    JSON.parse(JSON.stringify(props.modules)) as EditorModule[],
);
const selectedModuleId = ref<number | null>(localModules.value[0]?.id ?? null);
const selectedVersionId = ref<number | null>(null);
const sectionEditorOpen = ref(false);
const questionEditorOpen = ref(false);
const editingSection = ref<ModuleSection | null>(null);
const editingQuestion = ref<Question | null>(null);
const deleteTarget = ref<DeleteTarget | null>(null);
const successMessage = ref('');
const generalError = ref('');
let successTimer: ReturnType<typeof setTimeout> | null = null;

const cloneForm = useHttp<
    {
        module_id: number | null;
        source_version_id: number | null;
        target_type: string;
        target_role_id: number | null;
    },
    ApiResponse<ModuleVersion>
>({
    module_id: null,
    source_version_id: null,
    target_type: 'none',
    target_role_id: null,
});

const targetForm = useHttp<
    { target_type: string; target_role_id: number | null },
    ApiResponse<ModuleVersion>
>({
    target_type: 'none',
    target_role_id: null,
});

const descriptionForm = useHttp<
    { description: string | null },
    ApiResponse<ModuleVersion>
>({
    description: null,
});

const publishForm = useHttp<Record<string, never>, ApiResponse<ModuleVersion>>(
    {},
);

const sectionForm = useHttp<
    {
        module_version_id: number | null;
        title: string;
        description: string | null;
    },
    ApiResponse<ModuleSection>
>({
    module_version_id: null,
    title: '',
    description: null,
});

const questionForm = useHttp<
    {
        module_section_id: number | null;
        question_text: string;
        question_type: string;
        scale_min: number | null;
        scale_max: number | null;
        scale_labels: string[];
        is_required: boolean;
        options: string[];
    },
    ApiResponse<Question>
>({
    module_section_id: null,
    question_text: '',
    question_type: 'scale',
    scale_min: 1,
    scale_max: 5,
    scale_labels: [...DEFAULT_FIVE_POINT_SCALE_LABELS],
    is_required: false,
    options: [],
});

const deleteForm = useHttp<Record<string, never>, unknown>({});

const selectedModule = computed(
    () =>
        localModules.value.find(
            (module) => module.id === selectedModuleId.value,
        ) ?? null,
);

const selectedVersion = computed(
    () =>
        selectedModule.value?.versions.find(
            (version) => version.id === selectedVersionId.value,
        ) ?? null,
);

const isDraft = computed(() => selectedVersion.value?.status === 'draft');

const totalQuestions = computed(
    () =>
        selectedVersion.value?.sections.reduce(
            (total, section) => total + section.questions.length,
            0,
        ) ?? 0,
);

const publishDisabledReason = computed(() => {
    if (!selectedVersion.value || !isDraft.value) {
        return null;
    }

    if (totalQuestions.value === 0) {
        return 'Zum Veröffentlichen braucht diese Modulversion mindestens eine Frage.';
    }

    return null;
});

const usesOptions = computed(() =>
    ['single_choice'].includes(questionForm.question_type),
);

const usesScale = computed(() => questionForm.question_type === 'scale');

const questionTypes = [
    { value: 'scale', label: 'Skala' },
    { value: 'single_choice', label: 'Einfachauswahl' },
    { value: 'free_text', label: 'Freitext' },
    { value: 'yes_no', label: 'Ja / Nein' },
];

const targetTypeOptions = [
    { value: 'none', label: 'Kein Ziel' },
    { value: 'course', label: 'Kurs' },
    { value: 'organization', label: 'Standort' },
    { value: 'teacher', label: 'Lehrperson' },
];

watch(
    selectedModuleId,
    () => {
        selectPreferredVersion();
        closeEditors();
    },
    { immediate: true },
);

watch(
    selectedVersionId,
    () => {
        cloneForm.target_type = selectedVersion.value?.target_type ?? 'none';
        cloneForm.target_role_id =
            selectedVersion.value?.target_role_id ?? null;
        targetForm.clearErrors();
        targetForm.target_type = selectedVersion.value?.target_type ?? 'none';
        targetForm.target_role_id =
            selectedVersion.value?.target_role_id ?? null;
        descriptionForm.clearErrors();
        descriptionForm.description = selectedVersion.value?.description ?? null;
    },
    { immediate: true },
);

watch(
    () => questionForm.question_type,
    (type) => {
        if (type === 'single_choice') {
            if (questionForm.options.length < 2) {
                questionForm.options = ['Option 1', 'Option 2'];
            }

            return;
        }

        questionForm.options = [];
    },
);

function selectPreferredVersion(): void {
    const versions = selectedModule.value?.versions ?? [];
    const draft = versions.find((version) => version.status === 'draft');
    selectedVersionId.value = draft?.id ?? versions[0]?.id ?? null;
}

function statusLabel(status: string): string {
    return (
        {
            draft: 'Entwurf',
            published: 'Veröffentlicht',
            archived: 'Archiviert',
        }[status] ?? status
    );
}

function targetLabel(target: string): string {
    return (
        targetTypeOptions.find((option) => option.value === target)?.label ??
        target
    );
}

function questionTypeLabel(type: string): string {
    return questionTypes.find((item) => item.value === type)?.label ?? type;
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

async function createDraft(): Promise<void> {
    if (!selectedModule.value) {
        return;
    }

    generalError.value = '';
    cloneForm.module_id = selectedModule.value.id;
    cloneForm.source_version_id = selectedVersion.value?.id ?? null;

    await cloneForm.submit(moduleVersions.store(), {
        onSuccess: (response) => {
            selectedModule.value?.versions.unshift(response.data);
            selectedVersionId.value = response.data.id;
            showSuccess('Die neue Entwurfsversion wurde angelegt.');
        },
        onHttpException: requestFailed,
        onNetworkError: requestFailed,
    });
}

function replaceSelectedVersion(version: ModuleVersion): void {
    if (!selectedModule.value) {
        return;
    }

    const index = selectedModule.value.versions.findIndex(
        (item) => item.id === version.id,
    );

    if (index >= 0) {
        selectedModule.value.versions[index] = version;
    } else {
        selectedModule.value.versions.unshift(version);
    }

    selectedVersionId.value = version.id;
}

async function updateTargetType(targetType: string): Promise<void> {
    targetForm.target_type = targetType;

    if (targetType !== 'teacher') {
        targetForm.target_role_id = null;
    }

    await saveTarget();
}

async function updateTargetRole(targetRoleId: string): Promise<void> {
    targetForm.target_role_id = targetRoleId ? Number(targetRoleId) : null;
    await saveTarget();
}

async function saveTarget(): Promise<void> {
    if (!selectedVersion.value || !isDraft.value) {
        return;
    }

    generalError.value = '';

    await targetForm.submit(moduleVersions.update(selectedVersion.value.id), {
        onSuccess: (response) => {
            replaceSelectedVersion(response.data);
            showSuccess('Das Ziel wurde gespeichert.');
        },
        onHttpException: requestFailed,
        onNetworkError: requestFailed,
    });
}

async function saveDescription(): Promise<void> {
    if (!selectedVersion.value || !isDraft.value) {
        return;
    }

    generalError.value = '';

    await descriptionForm.submit(
        moduleVersions.update(selectedVersion.value.id),
        {
            onSuccess: (response) => {
                replaceSelectedVersion(response.data);
                showSuccess('Die Einleitung wurde gespeichert.');
            },
            onHttpException: requestFailed,
            onNetworkError: requestFailed,
        },
    );
}

async function publishVersion(): Promise<void> {
    if (!selectedVersion.value || publishDisabledReason.value) {
        return;
    }

    generalError.value = '';

    await publishForm.submit(moduleVersions.publish(selectedVersion.value.id), {
        onSuccess: (response) => {
            replaceSelectedVersion(response.data);
            showSuccess('Die Modulversion wurde veröffentlicht.');
        },
        onHttpException: requestFailed,
        onNetworkError: requestFailed,
    });
}

function openCreateSection(): void {
    if (!selectedVersion.value) {
        return;
    }

    editingSection.value = null;
    sectionForm.resetAndClearErrors();
    sectionForm.module_version_id = selectedVersion.value.id;
    sectionForm.title = '';
    sectionForm.description = null;
    sectionEditorOpen.value = true;
}

function openEditSection(section: ModuleSection): void {
    editingSection.value = section;
    sectionForm.clearErrors();
    sectionForm.module_version_id = section.module_version_id;
    sectionForm.title = section.title;
    sectionForm.description = section.description;
    sectionEditorOpen.value = true;
}

async function saveSection(): Promise<void> {
    const route = editingSection.value
        ? moduleSections.update(editingSection.value.id)
        : moduleSections.store();

    await sectionForm.submit(route, {
        onSuccess: (response) => {
            if (!selectedVersion.value) {
                return;
            }

            if (editingSection.value) {
                const index = selectedVersion.value.sections.findIndex(
                    (section) => section.id === editingSection.value?.id,
                );

                if (index >= 0) {
                    selectedVersion.value.sections[index] = response.data;
                }
            } else {
                selectedVersion.value.sections.push(response.data);
            }

            sectionEditorOpen.value = false;
            editingSection.value = null;
            showSuccess('Der Abschnitt wurde gespeichert.');
        },
        onHttpException: requestFailed,
        onNetworkError: requestFailed,
    });
}

function scalePointCount(min: number, max: number): number {
    return Math.max(max - min + 1, 0);
}

function resolveScaleLabels(question: Question): (string | null)[] {
    if (question.scale_labels && question.scale_labels.length > 0) {
        return question.scale_labels;
    }

    const min = question.scale_min ?? 1;
    const max = question.scale_max ?? min;
    const labels: (string | null)[] = Array.from(
        { length: scalePointCount(min, max) },
        () => null,
    );

    if (labels.length > 0) {
        labels[0] = question.scale_min_label;
        labels[labels.length - 1] = question.scale_max_label;
    }

    return labels;
}

function onScaleRangeChange(): void {
    const min = questionForm.scale_min ?? 1;
    const max = questionForm.scale_max ?? min;
    const pointCount = scalePointCount(min, max);
    const current = questionForm.scale_labels;

    if (pointCount === 5 && current.every((label) => !label)) {
        questionForm.scale_labels = [...DEFAULT_FIVE_POINT_SCALE_LABELS];

        return;
    }

    questionForm.scale_labels = Array.from(
        { length: pointCount },
        (_, index) => current[index] ?? '',
    );
}

function resetQuestionForm(sectionId: number): void {
    questionForm.clearErrors();
    questionForm.module_section_id = sectionId;
    questionForm.question_text = '';
    questionForm.question_type = 'scale';
    questionForm.scale_min = 1;
    questionForm.scale_max = 5;
    questionForm.scale_labels = [...DEFAULT_FIVE_POINT_SCALE_LABELS];
    questionForm.is_required = false;
    questionForm.options = [];
}

function openCreateQuestion(section: ModuleSection): void {
    editingQuestion.value = null;
    resetQuestionForm(section.id);
    questionEditorOpen.value = true;
}

function openEditQuestion(question: Question): void {
    editingQuestion.value = question;
    questionForm.clearErrors();
    questionForm.module_section_id = question.module_section_id;
    questionForm.question_text = question.question_text;
    questionForm.question_type = question.question_type;
    questionForm.scale_min = question.scale_min;
    questionForm.scale_max = question.scale_max;
    questionForm.scale_labels = resolveScaleLabels(question).map(
        (label) => label ?? '',
    );
    questionForm.is_required = question.is_required;
    questionForm.options = question.options.map((option) => option.option_text);
    questionEditorOpen.value = true;
}

async function saveQuestion(): Promise<void> {
    const route = editingQuestion.value
        ? questions.update(editingQuestion.value.id)
        : questions.store();

    await questionForm.submit(route, {
        onSuccess: (response) => {
            if (!selectedVersion.value) {
                return;
            }

            for (const section of selectedVersion.value.sections) {
                section.questions = section.questions.filter(
                    (question) => question.id !== editingQuestion.value?.id,
                );
            }

            const targetSection = selectedVersion.value.sections.find(
                (section) => section.id === response.data.module_section_id,
            );

            if (targetSection) {
                targetSection.questions.push(response.data);
                targetSection.questions.sort(
                    (left, right) => left.sort_order - right.sort_order,
                );
            }

            questionEditorOpen.value = false;
            editingQuestion.value = null;
            showSuccess('Die Frage wurde gespeichert.');
        },
        onHttpException: requestFailed,
        onNetworkError: requestFailed,
    });
}

function addOption(): void {
    questionForm.options.push(`Option ${questionForm.options.length + 1}`);
}

function removeOption(index: number): void {
    if (questionForm.options.length <= 2) {
        return;
    }

    questionForm.options.splice(index, 1);
}

async function confirmDelete(): Promise<void> {
    if (!deleteTarget.value || !selectedVersion.value) {
        return;
    }

    const target = deleteTarget.value;
    const route =
        target.kind === 'section'
            ? moduleSections.destroy(target.item.id)
            : questions.destroy(target.item.id);

    await deleteForm.submit(route, {
        onSuccess: () => {
            if (target.kind === 'section') {
                selectedVersion.value!.sections =
                    selectedVersion.value!.sections.filter(
                        (section) => section.id !== target.item.id,
                    );
            } else {
                for (const section of selectedVersion.value!.sections) {
                    section.questions = section.questions.filter(
                        (question) => question.id !== target.item.id,
                    );
                }
            }

            deleteTarget.value = null;
            showSuccess(
                target.kind === 'section'
                    ? 'Der Abschnitt wurde gelöscht.'
                    : 'Die Frage wurde gelöscht.',
            );
        },
        onHttpException: requestFailed,
        onNetworkError: requestFailed,
    });
}

function closeEditors(): void {
    sectionEditorOpen.value = false;
    questionEditorOpen.value = false;
    editingSection.value = null;
    editingQuestion.value = null;
    generalError.value = '';
}

function errorFor(name: string): string | null {
    const questionErrors = questionForm.errors as Record<string, unknown>;
    const sectionErrors = sectionForm.errors as Record<string, unknown>;
    const error =
        questionErrors[name] ??
        sectionErrors[name] ??
        questionErrors[`${name}.0`];

    if (!error) {
        return null;
    }

    return Array.isArray(error) ? String(error[0]) : String(error);
}
</script>

<template>
    <div>
        <Head title="Frageneditor" />

        <div class="mx-auto max-w-[1500px]">
            <div
                class="flex flex-col justify-between gap-5 xl:flex-row xl:items-end"
            >
                <div>
                    <p
                        class="text-xs font-semibold tracking-[0.2em] text-teal-600 uppercase"
                    >
                        Fragebögen
                    </p>
                    <h1
                        class="mt-2 text-3xl font-semibold tracking-tight text-slate-900"
                    >
                        Frageneditor
                    </h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                        Fragen werden in Abschnitten einer Modulversion
                        gepflegt. Veröffentlichte Versionen bleiben unverändert
                        und können als neuer Entwurf kopiert werden.
                    </p>
                </div>

                <Link
                    :href="resourceIndex('module')"
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Module verwalten
                </Link>
            </div>

            <div
                v-if="successMessage"
                class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
            >
                {{ successMessage }}
            </div>

            <div
                v-if="generalError"
                class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800"
            >
                {{ generalError }}
            </div>

            <section
                v-if="localModules.length === 0"
                class="mt-7 rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"
            >
                <h2 class="text-lg font-semibold text-slate-900">
                    Noch keine Module vorhanden
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Legen Sie zuerst ein Modul an. Danach kann hier die erste
                    Entwurfsversion mit Abschnitten und Fragen erstellt werden.
                </p>
                <Link
                    :href="resourceIndex('module')"
                    class="mt-6 inline-flex h-11 items-center rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white hover:bg-teal-800"
                >
                    Erstes Modul anlegen
                </Link>
            </section>

            <template v-else>
                <section
                    class="mt-7 flex flex-wrap items-end gap-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <label class="block w-56">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Modul
                        </span>
                        <select
                            v-model="selectedModuleId"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        >
                            <option
                                v-for="module in localModules"
                                :key="module.id"
                                :value="module.id"
                            >
                                {{ module.name }}
                            </option>
                        </select>
                    </label>

                    <label class="block w-56">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Modulversion
                        </span>
                        <select
                            v-model="selectedVersionId"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        >
                            <option
                                v-if="selectedModule?.versions.length === 0"
                                :value="null"
                            >
                                Noch keine Version
                            </option>
                            <option
                                v-for="version in selectedModule?.versions"
                                :key="version.id"
                                :value="version.id"
                            >
                                Version {{ version.version_number }} ·
                                {{ statusLabel(version.status) }}
                            </option>
                        </select>
                    </label>

                    <label class="block w-56">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Ziel der neuen Version
                        </span>
                        <select
                            v-model="cloneForm.target_type"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                            @change="
                                cloneForm.target_type !== 'teacher' &&
                                    (cloneForm.target_role_id = null)
                            "
                        >
                            <option
                                v-for="option in targetTypeOptions"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </label>

                    <label
                        v-if="cloneForm.target_type === 'teacher'"
                        class="block w-56"
                    >
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Rolle
                        </span>
                        <select
                            v-model="cloneForm.target_role_id"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        >
                            <option :value="null">Alle Lehrenden</option>
                            <option
                                v-for="role in props.teacherRoles"
                                :key="role.id"
                                :value="role.id"
                            >
                                {{ role.name }}
                            </option>
                        </select>
                    </label>

                    <button
                        type="button"
                        :disabled="cloneForm.processing"
                        class="h-12 rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-60"
                        @click="createDraft"
                    >
                        {{
                            cloneForm.processing
                                ? 'Entwurf wird erstellt...'
                                : selectedVersion
                                  ? 'Als Entwurf kopieren'
                                  : 'Entwurfsversion anlegen'
                        }}
                    </button>
                </section>

                <section
                    v-if="selectedVersion"
                    class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div
                        class="flex flex-col justify-between gap-5 border-b border-slate-200 px-6 py-5 lg:flex-row lg:items-center"
                    >
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h2
                                    class="text-lg font-semibold text-slate-900"
                                >
                                    {{ selectedVersion.title }}
                                </h2>
                                <span
                                    class="rounded-full px-2.5 py-1 text-xs font-semibold"
                                    :class="
                                        isDraft
                                            ? 'bg-amber-100 text-amber-700'
                                            : 'bg-emerald-100 text-emerald-700'
                                    "
                                >
                                    {{ statusLabel(selectedVersion.status) }}
                                </span>
                            </div>
                            <div
                                class="mt-2 flex flex-wrap items-center gap-2 text-sm text-slate-500"
                            >
                                <span>
                                    {{ selectedVersion.sections.length }}
                                    Abschnitte · {{ totalQuestions }} Fragen
                                </span>
                                <span>·</span>
                                <span
                                    v-if="!isDraft"
                                    class="font-medium text-slate-700"
                                >
                                    Ziel:
                                    {{ targetLabel(selectedVersion.target_type) }}
                                    <template
                                        v-if="selectedVersion.target_role"
                                    >
                                        ({{ selectedVersion.target_role.name }})
                                    </template>
                                </span>
                                <template v-else>
                                    <label class="inline-flex items-center gap-1.5">
                                        <span>Ziel:</span>
                                        <select
                                            :value="selectedVersion.target_type"
                                            class="h-8 rounded-lg border border-slate-200 bg-white px-2 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                                            @change="
                                                updateTargetType(
                                                    (
                                                        $event.target as HTMLSelectElement
                                                    ).value,
                                                )
                                            "
                                        >
                                            <option
                                                v-for="option in targetTypeOptions"
                                                :key="option.value"
                                                :value="option.value"
                                            >
                                                {{ option.label }}
                                            </option>
                                        </select>
                                    </label>
                                    <label
                                        v-if="
                                            selectedVersion.target_type ===
                                            'teacher'
                                        "
                                        class="inline-flex items-center gap-1.5"
                                    >
                                        <span>Rolle:</span>
                                        <select
                                            :value="
                                                selectedVersion.target_role_id
                                            "
                                            class="h-8 rounded-lg border border-slate-200 bg-white px-2 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                                            @change="
                                                updateTargetRole(
                                                    (
                                                        $event.target as HTMLSelectElement
                                                    ).value,
                                                )
                                            "
                                        >
                                            <option :value="null">
                                                Alle Lehrenden
                                            </option>
                                            <option
                                                v-for="role in props.teacherRoles"
                                                :key="role.id"
                                                :value="role.id"
                                            >
                                                {{ role.name }}
                                            </option>
                                        </select>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div
                            v-if="isDraft"
                            class="flex flex-col gap-2 sm:items-end"
                        >
                            <div class="flex flex-wrap gap-2">
                                <button
                                    type="button"
                                    class="h-11 rounded-xl bg-teal-600 px-5 text-sm font-semibold text-white transition hover:bg-teal-700"
                                    @click="openCreateSection"
                                >
                                    Abschnitt hinzufügen
                                </button>
                                <button
                                    type="button"
                                    :disabled="
                                        publishForm.processing ||
                                        Boolean(publishDisabledReason)
                                    "
                                    class="h-11 rounded-xl bg-emerald-600 px-5 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60"
                                    @click="publishVersion"
                                >
                                    Modulversion veröffentlichen
                                </button>
                            </div>
                            <p
                                v-if="publishDisabledReason"
                                class="max-w-sm text-left text-xs text-slate-500 sm:text-right"
                            >
                                {{ publishDisabledReason }}
                            </p>
                        </div>
                    </div>

                    <div class="border-b border-slate-200 px-6 py-5">
                        <span
                            class="mb-1.5 block text-sm font-medium text-slate-700"
                        >
                            Einleitung für Teilnehmende
                        </span>
                        <p class="mb-2 text-xs text-slate-500">
                            Wird den Teilnehmenden vor den Fragen dieses
                            Moduls angezeigt – keine Frage, sondern Begrüßung
                            und Hinweise.
                        </p>
                        <template v-if="isDraft">
                            <textarea
                                v-model="descriptionForm.description"
                                rows="3"
                                placeholder="z. B. Begrüßung und Hinweise zum Ausfüllen dieses Abschnitts."
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                            />
                            <div class="mt-3 flex justify-end">
                                <button
                                    type="button"
                                    :disabled="descriptionForm.processing"
                                    class="rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-60"
                                    @click="saveDescription"
                                >
                                    Einleitung speichern
                                </button>
                            </div>
                        </template>
                        <p
                            v-else-if="selectedVersion.description"
                            class="rounded-xl bg-slate-50 px-3 py-2 text-sm leading-6 text-slate-700"
                        >
                            {{ selectedVersion.description }}
                        </p>
                        <p v-else class="text-sm text-slate-400 italic">
                            Keine Einleitung hinterlegt.
                        </p>
                    </div>

                    <div
                        v-if="!isDraft"
                        class="border-b border-amber-200 bg-amber-50 px-6 py-4 text-sm leading-6 text-amber-800"
                    >
                        Diese Version ist veröffentlicht und deshalb
                        schreibgeschützt. Nutzen Sie „Als Entwurf kopieren“, um
                        Fragen zu ändern.
                    </div>

                    <div
                        v-if="selectedVersion.sections.length === 0"
                        class="px-6 py-14 text-center"
                    >
                        <p class="font-medium text-slate-700">
                            Diese Version enthält noch keine Abschnitte.
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Ein Abschnitt gruppiert zusammengehörige Fragen.
                        </p>
                    </div>

                    <div v-else class="space-y-5 bg-slate-50/70 p-5 sm:p-6">
                        <article
                            v-for="section in selectedVersion.sections"
                            :key="section.id"
                            class="overflow-hidden rounded-2xl border border-slate-200 bg-white"
                        >
                            <div
                                class="flex flex-col justify-between gap-4 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-start"
                            >
                                <div>
                                    <p
                                        class="text-xs font-semibold tracking-[0.16em] text-teal-600 uppercase"
                                    >
                                        Abschnitt
                                        {{ section.sort_order + 1 }}
                                    </p>
                                    <h3
                                        class="mt-1 text-base font-semibold text-slate-900"
                                    >
                                        {{ section.title }}
                                    </h3>
                                    <p
                                        v-if="section.description"
                                        class="mt-1 text-sm text-slate-500"
                                    >
                                        {{ section.description }}
                                    </p>
                                </div>

                                <div v-if="isDraft" class="flex gap-2">
                                    <button
                                        type="button"
                                        class="rounded-lg px-3 py-2 text-xs font-semibold text-teal-700 hover:bg-teal-50"
                                        @click="openEditSection(section)"
                                    >
                                        Bearbeiten
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-lg px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50"
                                        @click="
                                            deleteTarget = {
                                                kind: 'section',
                                                item: section,
                                            }
                                        "
                                    >
                                        Löschen
                                    </button>
                                </div>
                            </div>

                            <div class="divide-y divide-slate-100">
                                <div
                                    v-for="(
                                        question, index
                                    ) in section.questions"
                                    :key="question.id"
                                    class="flex flex-col justify-between gap-4 px-5 py-4 lg:flex-row lg:items-start"
                                >
                                    <div class="flex gap-4">
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-500"
                                        >
                                            {{ index + 1 }}
                                        </span>
                                        <div>
                                            <div
                                                class="flex flex-wrap items-center gap-2"
                                            >
                                                <p
                                                    class="font-medium text-slate-900"
                                                >
                                                    {{ question.question_text }}
                                                </p>
                                                <span
                                                    v-if="question.is_required"
                                                    class="rounded-full bg-red-50 px-2 py-0.5 text-[11px] font-semibold text-red-600"
                                                >
                                                    Pflichtfrage
                                                </span>
                                            </div>
                                            <p
                                                class="mt-1 text-xs font-medium text-slate-400"
                                            >
                                                {{
                                                    questionTypeLabel(
                                                        question.question_type,
                                                    )
                                                }}
                                            </p>
                                            <p
                                                v-if="
                                                    question.question_type ===
                                                    'scale'
                                                "
                                                class="mt-2 text-sm text-slate-500"
                                            >
                                                Skala
                                                {{ question.scale_min }} bis
                                                {{ question.scale_max }}:
                                                {{
                                                    resolveScaleLabels(
                                                        question,
                                                    )
                                                        .map(
                                                            (label) =>
                                                                label ||
                                                                'ohne Beschriftung',
                                                        )
                                                        .join(' → ')
                                                }}
                                            </p>
                                            <div
                                                v-if="
                                                    question.options.length > 0
                                                "
                                                class="mt-2 flex flex-wrap gap-2"
                                            >
                                                <span
                                                    v-for="option in question.options"
                                                    :key="option.id"
                                                    class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs text-slate-600"
                                                >
                                                    {{ option.option_text }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="isDraft" class="flex gap-2">
                                        <button
                                            type="button"
                                            class="rounded-lg px-3 py-2 text-xs font-semibold text-teal-700 hover:bg-teal-50"
                                            @click="openEditQuestion(question)"
                                        >
                                            Bearbeiten
                                        </button>
                                        <button
                                            type="button"
                                            class="rounded-lg px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50"
                                            @click="
                                                deleteTarget = {
                                                    kind: 'question',
                                                    item: question,
                                                }
                                            "
                                        >
                                            Löschen
                                        </button>
                                    </div>
                                </div>

                                <div
                                    v-if="section.questions.length === 0"
                                    class="px-5 py-8 text-center text-sm text-slate-500"
                                >
                                    Dieser Abschnitt enthält noch keine Fragen.
                                </div>
                            </div>

                            <div
                                v-if="isDraft"
                                class="border-t border-slate-200 bg-slate-50 px-5 py-3"
                            >
                                <button
                                    type="button"
                                    class="text-sm font-semibold text-teal-700 hover:text-teal-800"
                                    @click="openCreateQuestion(section)"
                                >
                                    + Frage hinzufügen
                                </button>
                            </div>
                        </article>
                    </div>
                </section>
            </template>
        </div>

        <div
            v-if="sectionEditorOpen"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/50 p-5 backdrop-blur-sm"
        >
            <section
                role="dialog"
                aria-modal="true"
                class="w-full max-w-xl rounded-3xl bg-white p-7 shadow-2xl"
            >
                <h2 class="text-xl font-semibold text-slate-900">
                    {{
                        editingSection
                            ? 'Abschnitt bearbeiten'
                            : 'Abschnitt hinzufügen'
                    }}
                </h2>
                <form class="mt-6 space-y-5" @submit.prevent="saveSection">
                    <label class="block">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Titel *
                        </span>
                        <input
                            v-model="sectionForm.title"
                            required
                            class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        />
                        <span
                            v-if="errorFor('title')"
                            class="mt-1.5 block text-sm text-red-600"
                        >
                            {{ errorFor('title') }}
                        </span>
                    </label>
                    <label class="block">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Beschreibung
                        </span>
                        <textarea
                            v-model="sectionForm.description"
                            rows="3"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        />
                    </label>
                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            class="h-11 rounded-xl border border-slate-200 px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                            @click="sectionEditorOpen = false"
                        >
                            Abbrechen
                        </button>
                        <button
                            type="submit"
                            :disabled="sectionForm.processing"
                            class="h-11 rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white hover:bg-teal-800 disabled:opacity-60"
                        >
                            Speichern
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <div
            v-if="questionEditorOpen"
            class="fixed inset-0 z-[60] flex items-end justify-center bg-slate-950/50 p-0 backdrop-blur-sm sm:items-center sm:p-5"
        >
            <section
                role="dialog"
                aria-modal="true"
                class="max-h-[95vh] w-full max-w-3xl overflow-y-auto rounded-t-3xl bg-white shadow-2xl sm:rounded-3xl"
            >
                <div
                    class="sticky top-0 z-10 border-b border-slate-200 bg-white px-6 py-5 sm:px-8"
                >
                    <h2 class="text-xl font-semibold text-slate-900">
                        {{
                            editingQuestion
                                ? 'Frage bearbeiten'
                                : 'Frage hinzufügen'
                        }}
                    </h2>
                </div>

                <form
                    class="space-y-5 px-6 py-6 sm:px-8"
                    @submit.prevent="saveQuestion"
                >
                    <label class="block">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Abschnitt *
                        </span>
                        <select
                            v-model="questionForm.module_section_id"
                            required
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        >
                            <option
                                v-for="section in selectedVersion?.sections"
                                :key="section.id"
                                :value="section.id"
                            >
                                {{ section.title }}
                            </option>
                        </select>
                    </label>

                    <label class="block">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Fragetext *
                        </span>
                        <textarea
                            v-model="questionForm.question_text"
                            required
                            rows="3"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        />
                        <span
                            v-if="errorFor('question_text')"
                            class="mt-1.5 block text-sm text-red-600"
                        >
                            {{ errorFor('question_text') }}
                        </span>
                    </label>

                    <label class="block">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Fragetyp *
                        </span>
                        <select
                            v-model="questionForm.question_type"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        >
                            <option
                                v-for="type in questionTypes"
                                :key="type.value"
                                :value="type.value"
                            >
                                {{ type.label }}
                            </option>
                        </select>
                    </label>

                    <div
                        v-if="usesScale"
                        class="grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:grid-cols-2"
                    >
                        <label class="block">
                            <span
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Skalenminimum *
                            </span>
                            <input
                                v-model.number="questionForm.scale_min"
                                type="number"
                                required
                                class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none focus:border-teal-500"
                                @change="onScaleRangeChange"
                            />
                        </label>
                        <label class="block">
                            <span
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Skalenmaximum *
                            </span>
                            <input
                                v-model.number="questionForm.scale_max"
                                type="number"
                                required
                                class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none focus:border-teal-500"
                                @change="onScaleRangeChange"
                            />
                        </label>
                        <div class="sm:col-span-2">
                            <p
                                class="mb-2 text-sm font-medium text-slate-700"
                            >
                                Beschriftung je Skalenpunkt
                            </p>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label
                                    v-for="(_, index) in questionForm
                                        .scale_labels"
                                    :key="index"
                                    class="block"
                                >
                                    <span
                                        class="mb-1.5 block text-xs font-medium text-slate-500"
                                    >
                                        Punkt
                                        {{ (questionForm.scale_min ?? 1) + index }}
                                    </span>
                                    <input
                                        v-model="
                                            questionForm.scale_labels[index]
                                        "
                                        class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-teal-500"
                                    />
                                </label>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="usesOptions"
                        class="rounded-2xl border border-slate-200 bg-slate-50 p-4"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-800">
                                    Antwortoptionen
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Mindestens zwei Optionen sind erforderlich.
                                </p>
                            </div>
                            <button
                                type="button"
                                class="rounded-lg bg-white px-3 py-2 text-xs font-semibold text-teal-700 shadow-sm"
                                @click="addOption"
                            >
                                Option hinzufügen
                            </button>
                        </div>
                        <div class="mt-4 space-y-3">
                            <div
                                v-for="(_, index) in questionForm.options"
                                :key="index"
                                class="flex gap-2"
                            >
                                <input
                                    v-model="questionForm.options[index]"
                                    required
                                    class="h-11 flex-1 rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:border-teal-500"
                                />
                                <button
                                    type="button"
                                    :disabled="questionForm.options.length <= 2"
                                    class="rounded-xl border border-slate-200 bg-white px-3 text-sm text-red-600 disabled:opacity-30"
                                    @click="removeOption(index)"
                                >
                                    Entfernen
                                </button>
                            </div>
                        </div>
                        <span
                            v-if="errorFor('options')"
                            class="mt-2 block text-sm text-red-600"
                        >
                            {{ errorFor('options') }}
                        </span>
                    </div>

                    <label
                        class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3"
                    >
                        <input
                            v-model="questionForm.is_required"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500"
                        />
                        <span class="text-sm font-medium text-slate-700">
                            Diese Frage ist verpflichtend
                        </span>
                    </label>

                    <div
                        class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end"
                    >
                        <button
                            type="button"
                            class="h-11 rounded-xl border border-slate-200 px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                            @click="questionEditorOpen = false"
                        >
                            Abbrechen
                        </button>
                        <button
                            type="submit"
                            :disabled="questionForm.processing"
                            class="h-11 rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white hover:bg-teal-800 disabled:opacity-60"
                        >
                            {{
                                questionForm.processing
                                    ? 'Wird gespeichert...'
                                    : 'Frage speichern'
                            }}
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <div
            v-if="deleteTarget"
            class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/50 p-5 backdrop-blur-sm"
        >
            <section
                role="alertdialog"
                aria-modal="true"
                class="w-full max-w-md rounded-3xl bg-white p-7 shadow-2xl"
            >
                <h2 class="text-xl font-semibold text-slate-900">
                    {{
                        deleteTarget.kind === 'section'
                            ? 'Abschnitt löschen?'
                            : 'Frage löschen?'
                    }}
                </h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Der Vorgang kann nicht rückgängig gemacht werden.
                    {{
                        deleteTarget.kind === 'section'
                            ? 'Alle Fragen dieses Abschnitts werden ebenfalls gelöscht.'
                            : ''
                    }}
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        class="h-11 rounded-xl border border-slate-200 px-5 text-sm font-semibold text-slate-700"
                        @click="deleteTarget = null"
                    >
                        Abbrechen
                    </button>
                    <button
                        type="button"
                        :disabled="deleteForm.processing"
                        class="h-11 rounded-xl bg-red-600 px-5 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-60"
                        @click="confirmDelete"
                    >
                        Endgültig löschen
                    </button>
                </div>
            </section>
        </div>
    </div>
</template>
