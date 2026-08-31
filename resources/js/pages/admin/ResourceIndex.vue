<script setup lang="ts">
import { Head, Link, router, useHttp, usePage } from '@inertiajs/vue3';
import { computed, nextTick, ref, watch } from 'vue';

import AppIcon from '@/components/AppIcon.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import benchmarkGroups from '@/routes/admin/api/benchmark-groups';
import courses from '@/routes/admin/api/courses';
import emailTemplates from '@/routes/admin/api/email-templates';
import evaluationCampaigns from '@/routes/admin/api/evaluation-campaigns';
import modules from '@/routes/admin/api/modules';
import organizationUnits from '@/routes/admin/api/organization-units';
import questionnaireTemplates from '@/routes/admin/api/questionnaire-templates';
import reportTemplates from '@/routes/admin/api/report-templates';
import teachers from '@/routes/admin/api/teachers';
import users from '@/routes/admin/api/users';
import { show as campaignTans } from '@/routes/admin/evaluation-campaigns/tans';
import type { Auth } from '@/types/auth';

defineOptions({ layout: AdminLayout, inheritAttrs: false });

type ResourceKey =
    | 'organisationseinheiten'
    | 'benutzer'
    | 'kurse'
    | 'lehrende'
    | 'frageboegen'
    | 'module'
    | 'evaluationen'
    | 'berichtsvorlagen'
    | 'benchmarks'
    | 'email-vorlagen';

type DataRecord = {
    id: number;
    [key: string]: unknown;
};

type Option = {
    value: number | string;
    label: string;
};

type CourseOption = Option & {
    organization_unit_id: number | null;
};

type OptionGroup = {
    label: string;
    options: Option[];
};

type OptionEntry = Option | OptionGroup;

type PageOptions = {
    organizationUnits: OptionGroup[];
    organizationUnitParents: Option[];
    users: Option[];
    roles: Option[];
    permissions: Option[];
    courses: CourseOption[];
    teachers: Option[];
    questionnaireVersions: Option[];
};

type FormValue = string | number | boolean | number[] | null;
type FormPayload = Record<string, FormValue>;

type FieldDefinition = {
    name: string;
    label: string;
    type:
        | 'text'
        | 'email'
        | 'password'
        | 'number'
        | 'textarea'
        | 'select'
        | 'multiselect'
        | 'checkbox'
        | 'datetime-local';
    options?: OptionEntry[];
    optionsKey?: keyof PageOptions;
    placeholder?: string;
    default?: FormValue;
    nullable?: boolean;
    required?: boolean;
    requiredOnCreate?: boolean;
    help?: string;
};

type ColumnDefinition = {
    label: string;
    path: string;
    format?: 'boolean' | 'date' | 'list' | 'status';
};

type ResourceApi = {
    store: () => { url: string; method: 'post' };
    update: (id: number) => { url: string; method: 'put' | 'patch' };
    destroy: (id: number) => { url: string; method: 'delete' };
};

type ResourceDefinition = {
    title: string;
    singular: string;
    description: string;
    columns: ColumnDefinition[];
    fields: FieldDefinition[];
    api: ResourceApi;
};

type ApiResourceResponse = {
    data: DataRecord;
};

const props = defineProps<{
    resourceKey: ResourceKey;
    records: DataRecord[];
    options: PageOptions;
}>();

const page = usePage<{ auth: Auth }>();

const organizationTypes: Option[] = [
    { value: 'institution', label: 'Bundesland' },
    { value: 'faculty', label: 'Fakultät' },
    { value: 'department', label: 'Standort' },
    { value: 'program', label: 'Studiengang' },
    { value: 'other', label: 'Sonstiger Standort' },
];

const campaignStatuses: Option[] = [
    { value: 'draft', label: 'Entwurf' },
    { value: 'scheduled', label: 'Geplant' },
    { value: 'active', label: 'Aktiv' },
    { value: 'closed', label: 'Geschlossen' },
    { value: 'archived', label: 'Archiviert' },
];

const definitions: Record<ResourceKey, ResourceDefinition> = {
    organisationseinheiten: {
        title: 'Standorte',
        singular: 'Standort',
        description: 'Bundesländer und zugehörige Standorte verwalten.',
        api: organizationUnits,
        columns: [
            { label: 'Name', path: 'name' },
            { label: 'Typ', path: 'type', format: 'status' },
            { label: 'Bundesland', path: 'parent.name' },
            { label: 'Benutzer', path: 'users_count' },
            { label: 'Aktiv', path: 'is_active', format: 'boolean' },
        ],
        fields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            {
                name: 'parent_id',
                label: 'Bundesland',
                type: 'select',
                optionsKey: 'organizationUnitParents',
                nullable: true,
                placeholder: 'Kein Bundesland',
            },
            {
                name: 'type',
                label: 'Typ',
                type: 'select',
                options: organizationTypes,
                default: 'department',
                required: true,
            },
            {
                name: 'sort_order',
                label: 'Sortierreihenfolge',
                type: 'number',
                default: 0,
            },
            {
                name: 'is_active',
                label: 'Standort ist aktiv',
                type: 'checkbox',
                default: true,
            },
        ],
    },
    benutzer: {
        title: 'Benutzer',
        singular: 'Benutzer',
        description:
            'Zugänge, Rollen und direkte Berechtigungen der Plattform verwalten.',
        api: users,
        columns: [
            { label: 'Name', path: 'name' },
            { label: 'E-Mail-Adresse', path: 'email' },
            { label: 'Standort', path: 'organization_unit.name' },
            { label: 'Rollen', path: 'roles', format: 'list' },
            { label: 'Aktiv', path: 'is_active', format: 'boolean' },
        ],
        fields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            {
                name: 'email',
                label: 'E-Mail-Adresse',
                type: 'email',
                required: true,
            },
            {
                name: 'password',
                label: 'Passwort',
                type: 'password',
                requiredOnCreate: true,
                help: 'Beim Bearbeiten leer lassen, um das bestehende Passwort beizubehalten.',
            },
            {
                name: 'organization_unit_id',
                label: 'Standort',
                type: 'select',
                optionsKey: 'organizationUnits',
                nullable: true,
                placeholder: 'Kein Standort',
            },
            {
                name: 'role_ids',
                label: 'Rollen',
                type: 'multiselect',
                optionsKey: 'roles',
                default: [],
                help: 'Mehrfachauswahl mit Strg.',
            },
            {
                name: 'permission_ids',
                label: 'Direkte Berechtigungen',
                type: 'multiselect',
                optionsKey: 'permissions',
                default: [],
                help: 'Nur für Ausnahmen zusätzlich zu den Rollen verwenden.',
            },
            {
                name: 'is_active',
                label: 'Benutzerkonto ist aktiv',
                type: 'checkbox',
                default: true,
            },
        ],
    },
    kurse: {
        title: 'Kurse',
        singular: 'Kurs',
        description:
            'Lehrveranstaltungen mit Zeitraum, Standort und Lehrenden pflegen.',
        api: courses,
        columns: [
            { label: 'Kurs', path: 'name' },
            { label: 'Code', path: 'code' },
            { label: 'Standort', path: 'organization_unit.name' },
            { label: 'Lehrende', path: 'teachers', format: 'list' },
            { label: 'Aktiv', path: 'is_active', format: 'boolean' },
        ],
        fields: [
            { name: 'name', label: 'Kursname', type: 'text', required: true },
            { name: 'code', label: 'Kurscode', type: 'text', required: true },
            {
                name: 'organization_unit_id',
                label: 'Standort',
                type: 'select',
                optionsKey: 'organizationUnits',
                required: true,
                placeholder: 'Standort wählen',
            },
            {
                name: 'starts_at',
                label: 'Beginn',
                type: 'datetime-local',
                nullable: true,
            },
            {
                name: 'ends_at',
                label: 'Ende',
                type: 'datetime-local',
                nullable: true,
            },
            {
                name: 'teacher_ids',
                label: 'Lehrende',
                type: 'multiselect',
                optionsKey: 'teachers',
                default: [],
                help: 'Mehrfachauswahl mit Strg.',
            },
            {
                name: 'is_active',
                label: 'Kurs ist aktiv',
                type: 'checkbox',
                default: true,
            },
        ],
    },
    lehrende: {
        title: 'Lehrende',
        singular: 'Lehrperson',
        description:
            'Lehrpersonen mit Benutzerkonto, Standort und Kursen verknüpfen.',
        api: teachers,
        columns: [
            { label: 'Name', path: 'name' },
            { label: 'E-Mail-Adresse', path: 'email' },
            { label: 'Standort', path: 'organization_unit.name' },
            { label: 'Kurse', path: 'courses', format: 'list' },
            { label: 'Aktiv', path: 'is_active', format: 'boolean' },
        ],
        fields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            { name: 'email', label: 'E-Mail-Adresse', type: 'email' },
            {
                name: 'user_id',
                label: 'Verknüpftes Benutzerkonto',
                type: 'select',
                optionsKey: 'users',
                nullable: true,
                placeholder: 'Kein Benutzerkonto',
            },
            {
                name: 'organization_unit_id',
                label: 'Standort',
                type: 'select',
                optionsKey: 'organizationUnits',
                required: true,
                placeholder: 'Standort wählen',
            },
            {
                name: 'course_ids',
                label: 'Kurse',
                type: 'multiselect',
                optionsKey: 'courses',
                default: [],
                help: 'Mehrfachauswahl mit Strg.',
            },
            {
                name: 'is_active',
                label: 'Lehrperson ist aktiv',
                type: 'checkbox',
                default: true,
            },
        ],
    },
    frageboegen: {
        title: 'Fragebögen',
        singular: 'Fragebogenvorlage',
        description:
            'Wiederverwendbare Vorlagen als Ausgangspunkt für Evaluationen verwalten.',
        api: questionnaireTemplates,
        columns: [
            { label: 'Name', path: 'name' },
            { label: 'Beschreibung', path: 'description' },
            { label: 'Versionen', path: 'versions_count' },
            { label: 'Erstellt von', path: 'creator.name' },
            { label: 'Aktiv', path: 'is_active', format: 'boolean' },
        ],
        fields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            {
                name: 'description',
                label: 'Beschreibung',
                type: 'textarea',
                nullable: true,
            },
            {
                name: 'is_active',
                label: 'Fragebogenvorlage ist aktiv',
                type: 'checkbox',
                default: true,
            },
        ],
    },
    module: {
        title: 'Module',
        singular: 'Modul',
        description:
            'Wiederverwendbare Fragenblöcke für Fragebogenversionen organisieren.',
        api: modules,
        columns: [
            { label: 'Name', path: 'name' },
            { label: 'Beschreibung', path: 'description' },
            { label: 'Versionen', path: 'versions_count' },
            { label: 'Erstellt von', path: 'creator.name' },
            { label: 'Aktiv', path: 'is_active', format: 'boolean' },
        ],
        fields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            {
                name: 'description',
                label: 'Beschreibung',
                type: 'textarea',
                nullable: true,
            },
            {
                name: 'is_active',
                label: 'Modul ist aktiv',
                type: 'checkbox',
                default: true,
            },
        ],
    },
    evaluationen: {
        title: 'Evaluationen',
        singular: 'Evaluation',
        description:
            'Evaluationszeiträume planen und mit Kursen sowie Fragebögen verbinden.',
        api: evaluationCampaigns,
        columns: [
            { label: 'Titel', path: 'title' },
            { label: 'Kurs', path: 'course.name' },
            { label: 'Status', path: 'status', format: 'status' },
            { label: 'Beginn', path: 'starts_at', format: 'date' },
            { label: 'TANs', path: 'tans_count' },
            { label: 'Antworten', path: 'responses_count' },
        ],
        fields: [
            { name: 'title', label: 'Titel', type: 'text', required: true },
            {
                name: 'description',
                label: 'Beschreibung',
                type: 'textarea',
                nullable: true,
            },
            {
                name: 'organization_unit_id',
                label: 'Standort',
                type: 'select',
                optionsKey: 'organizationUnits',
                required: true,
                placeholder: 'Standort wählen',
            },
            {
                name: 'course_id',
                label: 'Kurs',
                type: 'select',
                optionsKey: 'courses',
                nullable: true,
                placeholder: 'Kein Kurs',
            },
            {
                name: 'questionnaire_version_id',
                label: 'Veröffentlichte Fragebogenversion',
                type: 'select',
                optionsKey: 'questionnaireVersions',
                required: true,
                placeholder: 'Fragebogenversion wählen',
            },
            {
                name: 'starts_at',
                label: 'Beginn',
                type: 'datetime-local',
                nullable: true,
            },
            {
                name: 'ends_at',
                label: 'Ende',
                type: 'datetime-local',
                nullable: true,
            },
            {
                name: 'status',
                label: 'Status',
                type: 'select',
                options: campaignStatuses,
                default: 'draft',
            },
        ],
    },
    berichtsvorlagen: {
        title: 'Berichtsvorlagen',
        singular: 'Berichtsvorlage',
        description:
            'Aufbau und wiederverwendbare Grundlagen für Ergebnisberichte verwalten.',
        api: reportTemplates,
        columns: [
            { label: 'Name', path: 'name' },
            { label: 'Beschreibung', path: 'description' },
            { label: 'Abschnitte', path: 'sections_count' },
            { label: 'Erstellt von', path: 'creator.name' },
            { label: 'Aktiv', path: 'is_active', format: 'boolean' },
        ],
        fields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            {
                name: 'description',
                label: 'Beschreibung',
                type: 'textarea',
                nullable: true,
            },
            {
                name: 'is_active',
                label: 'Berichtsvorlage ist aktiv',
                type: 'checkbox',
                default: true,
            },
        ],
    },
    benchmarks: {
        title: 'Benchmarks',
        singular: 'Benchmark-Gruppe',
        description:
            'Vergleichsgruppen für organisationsübergreifende Auswertungen definieren.',
        api: benchmarkGroups,
        columns: [
            { label: 'Name', path: 'name' },
            { label: 'Vergleichsebene', path: 'scope_type', format: 'status' },
            { label: 'Standort', path: 'organization_unit.name' },
            { label: 'Datenstände', path: 'snapshots_count' },
            { label: 'Aktiv', path: 'is_active', format: 'boolean' },
        ],
        fields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            {
                name: 'scope_type',
                label: 'Vergleichsebene',
                type: 'select',
                options: [
                    { value: 'global', label: 'Gesamtes System' },
                    {
                        value: 'organization_unit',
                        label: 'Standort',
                    },
                ],
                default: 'organization_unit',
                required: true,
            },
            {
                name: 'organization_unit_id',
                label: 'Standort',
                type: 'select',
                optionsKey: 'organizationUnits',
                nullable: true,
                placeholder: 'Kein Standort',
            },
            {
                name: 'description',
                label: 'Beschreibung',
                type: 'textarea',
                nullable: true,
            },
            {
                name: 'is_active',
                label: 'Benchmark-Gruppe ist aktiv',
                type: 'checkbox',
                default: true,
            },
        ],
    },
    'email-vorlagen': {
        title: 'E-Mail-Vorlagen',
        singular: 'E-Mail-Vorlage',
        description:
            'Texte für Einladungen, Erinnerungen und Ergebnisbenachrichtigungen pflegen.',
        api: emailTemplates,
        columns: [
            { label: 'Name', path: 'name' },
            { label: 'Typ', path: 'type', format: 'status' },
            { label: 'Betreff', path: 'subject' },
            { label: 'Aktiv', path: 'is_active', format: 'boolean' },
        ],
        fields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            {
                name: 'type',
                label: 'Typ',
                type: 'select',
                options: [
                    { value: 'invitation', label: 'Einladung' },
                    { value: 'reminder', label: 'Erinnerung' },
                    { value: 'results', label: 'Ergebnisbenachrichtigung' },
                ],
                default: 'invitation',
                required: true,
            },
            {
                name: 'subject',
                label: 'Betreff',
                type: 'text',
                required: true,
            },
            {
                name: 'body',
                label: 'Inhalt',
                type: 'textarea',
                required: true,
                help: 'Platzhalter wie {{ course.name }} können im Text verwendet werden.',
            },
            {
                name: 'is_active',
                label: 'E-Mail-Vorlage ist aktiv',
                type: 'checkbox',
                default: true,
            },
        ],
    },
};

const localRecords = ref<DataRecord[]>([...props.records]);
const search = ref('');
const editorOpen = ref(false);
const deleteCandidate = ref<DataRecord | null>(null);
const editingRecord = ref<DataRecord | null>(null);
const generalError = ref('');
const successMessage = ref('');
let successTimer: ReturnType<typeof setTimeout> | null = null;
let isPreparingForm = false;

const definition = computed(() => definitions[props.resourceKey]);
const form = useHttp<FormPayload, ApiResourceResponse>({});
const deleteRequest = useHttp<Record<string, never>, unknown>({});

const filteredRecords = computed(() => {
    const term = search.value.trim().toLocaleLowerCase('de');

    if (!term) {
        return localRecords.value;
    }

    return localRecords.value.filter((record) =>
        JSON.stringify(record).toLocaleLowerCase('de').includes(term),
    );
});

watch(
    () => props.records,
    (records) => {
        localRecords.value = [...records];
    },
);

watch(
    () => (props.resourceKey === 'evaluationen' ? form.organization_unit_id : null),
    (organizationUnitId, previousOrganizationUnitId) => {
        if (
            isPreparingForm ||
            previousOrganizationUnitId === undefined ||
            organizationUnitId === previousOrganizationUnitId
        ) {
            return;
        }

        form.course_id = null;
    },
);

watch(
    () => props.resourceKey,
    () => {
        search.value = '';
        editorOpen.value = false;
        deleteCandidate.value = null;
        generalError.value = '';
    },
);

function fieldOptions(field: FieldDefinition): OptionEntry[] {
    if (
        props.resourceKey === 'evaluationen' &&
        field.name === 'course_id'
    ) {
        const organizationUnitId = form.organization_unit_id;

        return organizationUnitId
            ? props.options.courses.filter(
                  (course) =>
                      course.organization_unit_id === organizationUnitId,
              )
            : [];
    }

    if (field.optionsKey) {
        return props.options[field.optionsKey];
    }

    return field.options ?? [];
}

function isOptionGroup(option: OptionEntry): option is OptionGroup {
    return 'options' in option;
}

function optionKey(option: OptionEntry): number | string {
    return isOptionGroup(option) ? `group-${option.label}` : option.value;
}

function relationIds(record: DataRecord, relation: string): number[] {
    const value = record[relation];

    if (!Array.isArray(value)) {
        return [];
    }

    return value
        .map((item) => {
            if (typeof item === 'object' && item !== null && 'id' in item) {
                return Number(item.id);
            }

            return null;
        })
        .filter((id): id is number => id !== null);
}

function valueForField(field: FieldDefinition, record?: DataRecord): FormValue {
    if (!record) {
        if (field.default !== undefined) {
            return Array.isArray(field.default)
                ? [...field.default]
                : field.default;
        }

        if (field.type === 'multiselect') {
            return [];
        }

        if (field.type === 'checkbox') {
            return true;
        }

        return field.nullable || field.type === 'select' ? null : '';
    }

    const relationFields: Record<string, string> = {
        role_ids: 'roles',
        permission_ids: 'permissions',
        teacher_ids: 'teachers',
        course_ids: 'courses',
    };

    if (relationFields[field.name]) {
        return relationIds(record, relationFields[field.name]);
    }

    if (field.type === 'password') {
        return '';
    }

    const value = record[field.name];

    if (field.type === 'datetime-local' && typeof value === 'string') {
        return value.slice(0, 16);
    }

    if (
        typeof value === 'string' ||
        typeof value === 'number' ||
        typeof value === 'boolean' ||
        value === null
    ) {
        return value;
    }

    return field.default ?? '';
}

function prepareForm(record?: DataRecord): void {
    isPreparingForm = true;
    form.clearErrors();
    generalError.value = '';

    for (const field of definition.value.fields) {
        form[field.name] = valueForField(field, record);
    }

    void nextTick(() => {
        isPreparingForm = false;
    });
}

function openCreate(): void {
    editingRecord.value = null;
    prepareForm();
    editorOpen.value = true;
}

function openEdit(record: DataRecord): void {
    editingRecord.value = record;
    prepareForm(record);
    editorOpen.value = true;
}

function closeEditor(): void {
    if (form.processing) {
        return;
    }

    editorOpen.value = false;
    editingRecord.value = null;
    generalError.value = '';
}

function normalizedPayload(): FormPayload {
    return Object.fromEntries(
        definition.value.fields.map((field) => {
            const value = form[field.name];

            if (field.nullable && value === '') {
                return [field.name, null];
            }

            return [field.name, value];
        }),
    );
}

async function submit(): Promise<void> {
    generalError.value = '';
    form.transform(() => normalizedPayload());

    const route = editingRecord.value
        ? definition.value.api.update(editingRecord.value.id)
        : definition.value.api.store();

    await form.submit(route, {
        onSuccess: () => {
            editorOpen.value = false;
            editingRecord.value = null;
            showSuccess(
                `${definition.value.singular} wurde erfolgreich gespeichert.`,
            );
            router.reload({ only: ['records', 'options'] });
        },
        onHttpException: () => {
            generalError.value =
                'Der Datensatz konnte nicht gespeichert werden. Bitte prüfen Sie die Eingaben.';

            return false;
        },
        onNetworkError: () => {
            generalError.value =
                'Die Verbindung zum Server ist fehlgeschlagen. Bitte versuchen Sie es erneut.';

            return false;
        },
    });
}

async function confirmDelete(): Promise<void> {
    if (!deleteCandidate.value) {
        return;
    }

    generalError.value = '';

    await deleteRequest.submit(
        definition.value.api.destroy(deleteCandidate.value.id),
        {
            onSuccess: () => {
                deleteCandidate.value = null;
                showSuccess(
                    `${definition.value.singular} wurde erfolgreich gelöscht.`,
                );
                router.reload({ only: ['records', 'options'] });
            },
            onHttpException: () => {
                generalError.value =
                    'Der Datensatz kann nicht gelöscht werden, da er noch verwendet wird.';
                deleteCandidate.value = null;

                return false;
            },
            onNetworkError: () => {
                generalError.value =
                    'Die Verbindung zum Server ist fehlgeschlagen. Bitte versuchen Sie es erneut.';
                deleteCandidate.value = null;

                return false;
            },
        },
    );
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

function fieldError(name: string): string | null {
    const error = form.errors[name] ?? form.errors[`${name}.0`];

    if (!error) {
        return null;
    }

    return Array.isArray(error) ? String(error[0]) : String(error);
}

function stringValue(value: FormValue): string | number {
    return typeof value === 'string' || typeof value === 'number' ? value : '';
}

function updateTextField(name: string, event: Event): void {
    const target = event.target as HTMLInputElement | HTMLTextAreaElement;
    form[name] = target.value;
}

function getValue(record: DataRecord, path: string): unknown {
    return path.split('.').reduce<unknown>((value, segment) => {
        if (typeof value !== 'object' || value === null) {
            return null;
        }

        return (value as Record<string, unknown>)[segment];
    }, record);
}

function translateValue(value: string): string {
    const translations: Record<string, string> = {
        active: 'Aktiv',
        archived: 'Archiviert',
        closed: 'Geschlossen',
        department: 'Fachbereich',
        draft: 'Entwurf',
        faculty: 'Fakultät',
        global: 'Gesamtes System',
        institution: 'Institution',
        invitation: 'Einladung',
        organization_unit: 'Standort',
        other: 'Sonstige Einheit',
        program: 'Studiengang',
        reminder: 'Erinnerung',
        results: 'Ergebnisbenachrichtigung',
        scheduled: 'Geplant',
    };

    return translations[value] ?? value;
}

function formatValue(
    value: unknown,
    format?: ColumnDefinition['format'],
): string {
    if (value === null || value === undefined || value === '') {
        return 'Nicht angegeben';
    }

    if (format === 'boolean') {
        return value ? 'Aktiv' : 'Inaktiv';
    }

    if (format === 'date' && typeof value === 'string') {
        return new Intl.DateTimeFormat('de-DE', {
            dateStyle: 'medium',
        }).format(new Date(value));
    }

    if (format === 'list' && Array.isArray(value)) {
        const labels = value.map((item) => {
            if (typeof item !== 'object' || item === null) {
                return String(item);
            }

            const object = item as Record<string, unknown>;

            return String(object.name ?? object.title ?? object.label ?? '');
        });

        return labels.filter(Boolean).join(', ') || 'Keine';
    }

    if (format === 'status' && typeof value === 'string') {
        return translateValue(value);
    }

    return String(value);
}

function isSelf(record: DataRecord): boolean {
    return (
        props.resourceKey === 'benutzer' &&
        page.props.auth.user?.id === record.id
    );
}
</script>

<template>
    <Head :title="definition.title" />

    <div class="mx-auto max-w-[1600px]">
        <div
            class="flex flex-col justify-between gap-5 xl:flex-row xl:items-end"
        >
            <div>
                <p
                    class="text-xs font-semibold tracking-[0.2em] text-teal-600 uppercase"
                >
                    Verwaltung
                </p>
                <h1
                    class="mt-2 text-2xl font-semibold tracking-tight text-slate-900"
                >
                    {{ definition.title }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                    {{ definition.description }}
                </p>
            </div>

            <button
                type="button"
                class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-teal-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800"
                @click="openCreate"
            >
                <AppIcon name="plus" class="h-4 w-4" />
                {{ definition.singular }} anlegen
            </button>
        </div>

        <div
            v-if="successMessage"
            class="mt-6 rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-medium text-teal-800"
        >
            {{ successMessage }}
        </div>

        <div
            v-if="generalError"
            class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800"
        >
            {{ generalError }}
        </div>

        <div
            class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
        >
            <p class="text-sm text-slate-500">
                {{ localRecords.length }} Einträge
            </p>

            <label class="relative block w-full sm:max-w-xs">
                <span class="sr-only">Einträge durchsuchen</span>
                <input
                    v-model="search"
                    type="search"
                    placeholder="Suchen"
                    class="h-10 w-full rounded-lg border border-slate-300 bg-white pr-4 pl-9 text-sm transition outline-none placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                />
                <AppIcon
                    name="search"
                    class="pointer-events-none absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                />
            </label>
        </div>

        <section
            class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th
                                v-for="column in definition.columns"
                                :key="column.path"
                                class="px-5 py-3 text-xs font-semibold tracking-wide whitespace-nowrap text-slate-500 uppercase"
                            >
                                {{ column.label }}
                            </th>
                            <th
                                class="px-5 py-3 text-right text-xs font-semibold tracking-wide text-slate-500 uppercase"
                            >
                                Details
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr
                            v-for="record in filteredRecords"
                            :key="record.id"
                            class="transition hover:bg-slate-50/70"
                        >
                            <td
                                v-for="(
                                    column, columnIndex
                                ) in definition.columns"
                                :key="column.path"
                                class="max-w-xs px-5 py-3.5"
                                :class="
                                    columnIndex === 0
                                        ? 'font-medium text-slate-800'
                                        : 'text-slate-600'
                                "
                            >
                                <span
                                    v-if="columnIndex === 0"
                                    class="flex items-center gap-2.5"
                                >
                                    <AppIcon
                                        name="ring"
                                        class="h-4 w-4 shrink-0 text-teal-600"
                                    />
                                    <span class="line-clamp-2">
                                        {{
                                            formatValue(
                                                getValue(record, column.path),
                                                column.format,
                                            )
                                        }}
                                    </span>
                                </span>
                                <span
                                    v-else-if="
                                        column.format === 'status' ||
                                        column.format === 'boolean'
                                    "
                                    class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold"
                                    :class="
                                        column.format === 'boolean' &&
                                        !getValue(record, column.path)
                                            ? 'bg-slate-100 text-slate-600'
                                            : 'bg-teal-50 text-teal-700'
                                    "
                                >
                                    {{
                                        formatValue(
                                            getValue(record, column.path),
                                            column.format,
                                        )
                                    }}
                                </span>
                                <span v-else class="line-clamp-2">
                                    {{
                                        formatValue(
                                            getValue(record, column.path),
                                            column.format,
                                        )
                                    }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div
                                    class="flex items-center justify-end gap-1"
                                >
                                    <Link
                                        v-if="
                                            props.resourceKey === 'evaluationen'
                                        "
                                        :href="campaignTans(record.id)"
                                        class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-teal-700 transition hover:bg-teal-50"
                                    >
                                        TANs
                                    </Link>
                                    <button
                                        type="button"
                                        title="Bearbeiten"
                                        class="flex h-8 w-8 items-center justify-center rounded-full text-amber-500 transition hover:bg-amber-50"
                                        @click="openEdit(record)"
                                    >
                                        <AppIcon
                                            name="pencil"
                                            class="h-4 w-4"
                                        />
                                    </button>
                                    <button
                                        type="button"
                                        class="flex h-8 w-8 items-center justify-center rounded-full text-red-500 transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-30"
                                        :disabled="isSelf(record)"
                                        :title="
                                            isSelf(record)
                                                ? 'Das eigene Konto kann nicht gelöscht werden.'
                                                : 'Eintrag löschen'
                                        "
                                        @click="deleteCandidate = record"
                                    >
                                        <AppIcon
                                            name="x-circle"
                                            class="h-4 w-4"
                                        />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredRecords.length === 0">
                            <td
                                :colspan="definition.columns.length + 1"
                                class="px-6 py-16 text-center"
                            >
                                <p class="font-medium text-slate-700">
                                    Keine Einträge gefunden
                                </p>
                                <p class="mt-1 text-sm text-slate-500">
                                    Passen Sie die Suche an oder legen Sie einen
                                    neuen Eintrag an.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div
        v-if="editorOpen"
        class="fixed inset-0 z-[60] flex items-end justify-center bg-slate-950/50 p-0 backdrop-blur-sm sm:items-center sm:p-6"
    >
        <section
            role="dialog"
            aria-modal="true"
            class="max-h-[95vh] w-full max-w-2xl overflow-y-auto rounded-t-2xl bg-white shadow-2xl sm:rounded-2xl"
        >
            <div
                class="sticky top-0 z-10 flex items-start justify-between gap-6 border-b border-slate-200 bg-white px-6 py-5 sm:px-8"
            >
                <div>
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-teal-600 uppercase"
                    >
                        {{
                            editingRecord
                                ? 'Eintrag bearbeiten'
                                : 'Neuer Eintrag'
                        }}
                    </p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-950">
                        {{ definition.singular }}
                    </h2>
                </div>
                <button
                    type="button"
                    class="rounded-lg p-2 text-xl leading-none text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                    aria-label="Dialog schließen"
                    @click="closeEditor"
                >
                    ×
                </button>
            </div>

            <form class="space-y-5 px-6 py-6 sm:px-8" @submit.prevent="submit">
                <div
                    v-if="generalError"
                    class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                >
                    {{ generalError }}
                </div>

                <template v-for="field in definition.fields" :key="field.name">
                    <label
                        v-if="field.type === 'checkbox'"
                        class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3"
                    >
                        <input
                            v-model="form[field.name]"
                            type="checkbox"
                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500"
                        />
                        <span>
                            <span
                                class="block text-sm font-medium text-slate-800"
                            >
                                {{ field.label }}
                            </span>
                            <span
                                v-if="field.help"
                                class="mt-1 block text-xs leading-5 text-slate-500"
                            >
                                {{ field.help }}
                            </span>
                        </span>
                    </label>

                    <label v-else class="block">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            {{ field.label }}
                            <span
                                v-if="
                                    field.required ||
                                    (field.requiredOnCreate && !editingRecord)
                                "
                                class="text-red-500"
                            >
                                *
                            </span>
                        </span>

                        <textarea
                            v-if="field.type === 'textarea'"
                            :value="stringValue(form[field.name])"
                            rows="4"
                            :required="field.required"
                            :placeholder="field.placeholder"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                            @input="updateTextField(field.name, $event)"
                        />

                        <select
                            v-else-if="field.type === 'select'"
                            v-model="form[field.name]"
                            :required="field.required"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        >
                            <option
                                v-if="field.placeholder || field.nullable"
                                :value="null"
                            >
                                {{ field.placeholder ?? 'Bitte auswählen' }}
                            </option>
                            <template
                                v-for="option in fieldOptions(field)"
                                :key="optionKey(option)"
                            >
                                <optgroup
                                    v-if="isOptionGroup(option)"
                                    :label="option.label"
                                >
                                    <option
                                        v-for="childOption in option.options"
                                        :key="childOption.value"
                                        :value="childOption.value"
                                    >
                                        {{ childOption.label }}
                                    </option>
                                </optgroup>
                                <option v-else :value="option.value">
                                    {{ option.label }}
                                </option>
                            </template>
                        </select>

                        <select
                            v-else-if="field.type === 'multiselect'"
                            v-model="form[field.name]"
                            multiple
                            class="min-h-32 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        >
                            <template
                                v-for="option in fieldOptions(field)"
                                :key="optionKey(option)"
                            >
                                <optgroup
                                    v-if="isOptionGroup(option)"
                                    :label="option.label"
                                >
                                    <option
                                        v-for="childOption in option.options"
                                        :key="childOption.value"
                                        :value="childOption.value"
                                        class="rounded px-2 py-1.5"
                                    >
                                        {{ childOption.label }}
                                    </option>
                                </optgroup>
                                <option
                                    v-else
                                    :value="option.value"
                                    class="rounded px-2 py-1.5"
                                >
                                    {{ option.label }}
                                </option>
                            </template>
                        </select>

                        <input
                            v-else
                            :value="stringValue(form[field.name])"
                            :type="field.type"
                            :required="
                                field.required ||
                                (field.requiredOnCreate && !editingRecord)
                            "
                            :placeholder="field.placeholder"
                            :min="field.type === 'number' ? 0 : undefined"
                            class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                            @input="updateTextField(field.name, $event)"
                        />

                        <span
                            v-if="field.help"
                            class="mt-1.5 block text-xs leading-5 text-slate-500"
                        >
                            {{ field.help }}
                        </span>
                        <span
                            v-if="fieldError(field.name)"
                            class="mt-1.5 block text-sm text-red-600"
                        >
                            {{ fieldError(field.name) }}
                        </span>
                    </label>
                </template>

                <div
                    class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end"
                >
                    <button
                        type="button"
                        class="h-11 rounded-xl border border-slate-200 px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        @click="closeEditor"
                    >
                        Abbrechen
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="h-11 rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{
                            form.processing
                                ? 'Wird gespeichert...'
                                : 'Speichern'
                        }}
                    </button>
                </div>
            </form>
        </section>
    </div>

    <div
        v-if="deleteCandidate"
        class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/50 p-6 backdrop-blur-sm"
    >
        <section
            role="alertdialog"
            aria-modal="true"
            class="w-full max-w-md rounded-2xl bg-white p-7 shadow-2xl"
        >
            <div
                class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600"
            >
                <AppIcon name="x-circle" class="h-6 w-6" />
            </div>
            <h2 class="mt-5 text-xl font-semibold text-slate-900">
                {{ definition.singular }} löschen?
            </h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Dieser Vorgang kann nicht rückgängig gemacht werden. Verknüpfte
                Datensätze können das Löschen verhindern.
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    class="h-11 rounded-xl border border-slate-200 px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    @click="deleteCandidate = null"
                >
                    Abbrechen
                </button>
                <button
                    type="button"
                    :disabled="deleteRequest.processing"
                    class="h-11 rounded-xl bg-red-600 px-5 text-sm font-semibold text-white hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
                    @click="confirmDelete"
                >
                    {{
                        deleteRequest.processing
                            ? 'Wird gelöscht...'
                            : 'Endgültig löschen'
                    }}
                </button>
            </div>
        </section>
    </div>
</template>
