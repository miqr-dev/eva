<script setup lang="ts">
import { Head, Link, useHttp } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import AdminLayout from '@/layouts/AdminLayout.vue';
import { store as generateTans } from '@/routes/admin/api/evaluation-campaigns/tans';
import { index as resourceIndex } from '@/routes/admin/resources';

defineOptions({ layout: AdminLayout, inheritAttrs: false });

type RelatedRecord = {
    id: number;
    name?: string;
    title?: string;
    code?: string;
    version_number?: number;
};

type Campaign = {
    id: number;
    title: string;
    description: string | null;
    status: string;
    starts_at: string | null;
    ends_at: string | null;
    min_answers_to_show_results: number;
    organization_unit: RelatedRecord | null;
    course: RelatedRecord | null;
    questionnaire_version: RelatedRecord | null;
    targets_count?: number;
    responses_count?: number;
    tans_count?: number;
};

type TanStats = {
    total: number;
    unused: number;
    started: number;
    used: number;
    inactive: number;
};

type GenerateTansResponse = {
    message: string;
    tans: string[];
    stats: TanStats;
    evaluation_url: string;
    expires_at: string | null;
};

const props = defineProps<{
    campaign: Campaign;
    tanStats: TanStats;
    evaluationUrl: string;
}>();

const localStats = ref<TanStats>({ ...props.tanStats });
const generatedTans = ref<string[]>([]);
const successMessage = ref('');
const generalError = ref('');
const copyMessage = ref('');
let copyTimer: ReturnType<typeof setTimeout> | null = null;

const generationForm = useHttp<{ amount: number }, GenerateTansResponse>({
    amount: 20,
});

const tanText = computed(() => generatedTans.value.join('\n'));
const generationDisabledReason = computed(() => {
    if (['closed', 'archived'].includes(props.campaign.status)) {
        return 'Für geschlossene oder archivierte Evaluationen können keine TANs erzeugt werden.';
    }

    return null;
});

const stats = computed(() => [
    { label: 'TANs gesamt', value: localStats.value.total },
    { label: 'Unbenutzt', value: localStats.value.unused },
    { label: 'Gestartet', value: localStats.value.started },
    { label: 'Verwendet', value: localStats.value.used },
    { label: 'Inaktiv', value: localStats.value.inactive },
]);

function statusLabel(status: string): string {
    const labels: Record<string, string> = {
        draft: 'Entwurf',
        scheduled: 'Geplant',
        active: 'Aktiv',
        closed: 'Geschlossen',
        archived: 'Archiviert',
    };

    return labels[status] ?? status;
}

function formatDateTime(value: string | null): string {
    if (!value) {
        return 'Nicht angegeben';
    }

    return new Intl.DateTimeFormat('de-DE', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
}

function questionnaireLabel(): string {
    const questionnaireVersion = props.campaign.questionnaire_version;

    if (!questionnaireVersion) {
        return 'Nicht angegeben';
    }

    const title = questionnaireVersion.title ?? questionnaireVersion.name ?? '';
    const version = questionnaireVersion.version_number
        ? `Version ${questionnaireVersion.version_number}`
        : null;

    return [title, version].filter(Boolean).join(' · ');
}

function fieldError(name: string): string | null {
    const errors = generationForm.errors as Record<string, unknown>;
    const error = errors[name];

    if (!error) {
        return null;
    }

    return Array.isArray(error) ? String(error[0]) : String(error);
}

function requestFailed(): false {
    generalError.value =
        'Die TANs konnten nicht erzeugt werden. Bitte prüfen Sie die Eingaben.';

    return false;
}

function showCopyMessage(message: string): void {
    copyMessage.value = message;

    if (copyTimer) {
        clearTimeout(copyTimer);
    }

    copyTimer = setTimeout(() => {
        copyMessage.value = '';
    }, 3000);
}

async function generate(): Promise<void> {
    if (generationDisabledReason.value) {
        return;
    }

    generalError.value = '';
    successMessage.value = '';
    generatedTans.value = [];

    await generationForm.submit(generateTans(props.campaign.id), {
        onSuccess: (response) => {
            generatedTans.value = response.tans;
            localStats.value = response.stats;
            successMessage.value = response.message;
        },
        onHttpException: requestFailed,
        onNetworkError: requestFailed,
    });
}

async function copyTans(): Promise<void> {
    if (!tanText.value) {
        return;
    }

    if (!navigator.clipboard) {
        showCopyMessage('Kopieren wird in diesem Browser nicht unterstützt.');

        return;
    }

    await navigator.clipboard.writeText(tanText.value);
    showCopyMessage('TANs wurden in die Zwischenablage kopiert.');
}

function downloadTans(): void {
    if (!tanText.value) {
        return;
    }

    const blob = new Blob([`${tanText.value}\n`], {
        type: 'text/plain;charset=utf-8',
    });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');

    link.href = url;
    link.download = `tans-evaluation-${props.campaign.id}.txt`;
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(url);
}
</script>

<template>
    <Head :title="`TANs · ${props.campaign.title}`" />

    <div class="mx-auto max-w-6xl">
        <div
            class="flex flex-col justify-between gap-5 xl:flex-row xl:items-end"
        >
            <div>
                <p
                    class="text-xs font-semibold tracking-[0.2em] text-sky-600 uppercase"
                >
                    Evaluationen
                </p>
                <h1
                    class="mt-2 text-3xl font-semibold tracking-tight text-slate-950"
                >
                    TANs erzeugen
                </h1>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    Erzeugen Sie TANs für diese Evaluation. Die TANs werden nur
                    einmal im Klartext angezeigt; in der Datenbank wird nur der
                    Hash gespeichert.
                </p>
            </div>

            <Link
                :href="resourceIndex('evaluationen')"
                class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                Zurück zu Evaluationen
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

        <section class="mt-7 grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div
                class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2"
            >
                <div
                    class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
                >
                    <div>
                        <span
                            class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700"
                        >
                            {{ statusLabel(props.campaign.status) }}
                        </span>
                        <h2 class="mt-4 text-2xl font-semibold text-slate-950">
                            {{ props.campaign.title }}
                        </h2>
                        <p
                            v-if="props.campaign.description"
                            class="mt-2 text-sm leading-6 text-slate-500"
                        >
                            {{ props.campaign.description }}
                        </p>
                    </div>
                </div>

                <dl class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <dt class="text-xs font-semibold text-slate-500">
                            Standort
                        </dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{
                                props.campaign.organization_unit?.name ??
                                'Nicht angegeben'
                            }}
                        </dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <dt class="text-xs font-semibold text-slate-500">
                            Kurs
                        </dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{
                                props.campaign.course?.name ?? 'Nicht angegeben'
                            }}
                        </dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <dt class="text-xs font-semibold text-slate-500">
                            Fragebogen
                        </dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{ questionnaireLabel() }}
                        </dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <dt class="text-xs font-semibold text-slate-500">
                            Zeitraum
                        </dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{ formatDateTime(props.campaign.starts_at) }}
                            bis
                            {{ formatDateTime(props.campaign.ends_at) }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div
                class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
            >
                <h2 class="text-lg font-semibold text-slate-950">
                    Teilnehmer-Link
                </h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Teilnehmer öffnen diese Seite und geben dort ihre TAN ein.
                </p>
                <a
                    :href="props.evaluationUrl"
                    target="_blank"
                    class="mt-4 block rounded-xl bg-slate-50 p-4 text-sm font-medium break-all text-sky-700 transition hover:bg-sky-50"
                >
                    {{ props.evaluationUrl }}
                </a>
            </div>
        </section>

        <section class="mt-5 grid grid-cols-2 gap-4 lg:grid-cols-5">
            <div
                v-for="item in stats"
                :key="item.label"
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >
                <p class="text-xs font-semibold text-slate-500">
                    {{ item.label }}
                </p>
                <p class="mt-2 text-3xl font-semibold text-slate-950">
                    {{ item.value }}
                </p>
            </div>
        </section>

        <section
            class="mt-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            <div
                class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between"
            >
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">
                        Neue TANs
                    </h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Wählen Sie die Anzahl der Teilnehmer aus. Nach dem
                        Erzeugen kopieren oder laden Sie die TANs direkt
                        herunter.
                    </p>
                </div>

                <form
                    class="flex flex-col gap-3 sm:flex-row sm:items-start"
                    @submit.prevent="generate"
                >
                    <label class="block">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Anzahl
                        </span>
                        <input
                            v-model.number="generationForm.amount"
                            type="number"
                            min="1"
                            max="500"
                            required
                            class="h-11 w-36 rounded-xl border border-slate-200 px-4 text-sm transition outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                        />
                        <span
                            v-if="fieldError('amount')"
                            class="mt-1.5 block max-w-xs text-sm text-red-600"
                        >
                            {{ fieldError('amount') }}
                        </span>
                    </label>

                    <button
                        type="submit"
                        :disabled="
                            generationForm.processing ||
                            Boolean(generationDisabledReason)
                        "
                        class="mt-7 h-11 rounded-xl bg-slate-950 px-5 text-sm font-semibold text-white transition hover:bg-sky-600 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{
                            generationForm.processing
                                ? 'TANs werden erzeugt...'
                                : 'TANs erzeugen'
                        }}
                    </button>
                </form>
            </div>

            <p
                v-if="generationDisabledReason"
                class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800"
            >
                {{ generationDisabledReason }}
            </p>
        </section>

        <section
            v-if="generatedTans.length > 0"
            class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm"
        >
            <div
                class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between"
            >
                <div>
                    <p
                        class="text-xs font-semibold tracking-[0.18em] text-amber-700 uppercase"
                    >
                        Nur einmal sichtbar
                    </p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-950">
                        {{ generatedTans.length }} neue TANs
                    </h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-amber-900">
                        Diese TANs werden nach dem Neuladen der Seite nicht
                        erneut angezeigt. Bitte jetzt kopieren oder
                        herunterladen.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button
                        type="button"
                        class="h-11 rounded-xl bg-slate-950 px-5 text-sm font-semibold text-white transition hover:bg-sky-600"
                        @click="copyTans"
                    >
                        Alle kopieren
                    </button>
                    <button
                        type="button"
                        class="h-11 rounded-xl border border-amber-300 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-amber-100"
                        @click="downloadTans"
                    >
                        Als TXT laden
                    </button>
                </div>
            </div>

            <p
                v-if="copyMessage"
                class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
            >
                {{ copyMessage }}
            </p>

            <textarea
                readonly
                :value="tanText"
                rows="10"
                class="mt-5 w-full rounded-xl border border-amber-200 bg-white p-4 font-mono text-sm tracking-[0.12em] text-slate-900 outline-none"
            />
        </section>
    </div>
</template>
