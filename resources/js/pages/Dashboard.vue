<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import AdminLayout from '@/layouts/AdminLayout.vue';
import { index as resourceIndex } from '@/routes/admin/resources';

defineOptions({ layout: AdminLayout, inheritAttrs: false });

type Stats = {
    organizationUnits: number;
    users: number;
    courses: number;
    teachers: number;
    questionnaires: number;
    campaigns: number;
};

type Campaign = {
    id: number;
    title: string;
    status: string;
    starts_at: string | null;
    ends_at: string | null;
};

defineProps<{
    stats: Stats;
    recentCampaigns: Campaign[];
}>();

const statCards: Array<{
    key: keyof Stats;
    label: string;
    description: string;
    resource: string;
    accent: string;
}> = [
    {
        key: 'organizationUnits',
        label: 'Standorte',
        description: 'Bundesländer und Standorte',
        resource: 'organisationseinheiten',
        accent: 'bg-violet-100 text-violet-700',
    },
    {
        key: 'users',
        label: 'Benutzer',
        description: 'Konten und Berechtigungen',
        resource: 'benutzer',
        accent: 'bg-blue-100 text-blue-700',
    },
    {
        key: 'courses',
        label: 'Kurse',
        description: 'Evaluierbare Lehrveranstaltungen',
        resource: 'kurse',
        accent: 'bg-cyan-100 text-cyan-700',
    },
    {
        key: 'teachers',
        label: 'Lehrende',
        description: 'Zugeordnete Lehrpersonen',
        resource: 'lehrende',
        accent: 'bg-amber-100 text-amber-700',
    },
    {
        key: 'questionnaires',
        label: 'Fragebögen',
        description: 'Wiederverwendbare Vorlagen',
        resource: 'frageboegen',
        accent: 'bg-emerald-100 text-emerald-700',
    },
    {
        key: 'campaigns',
        label: 'Evaluationen',
        description: 'Geplante und aktive Zeiträume',
        resource: 'evaluationen',
        accent: 'bg-rose-100 text-rose-700',
    },
];

function formatDate(value: string | null): string {
    if (!value) {
        return 'Nicht festgelegt';
    }

    return new Intl.DateTimeFormat('de-DE', {
        dateStyle: 'medium',
    }).format(new Date(value));
}

function statusLabel(status: string): string {
    const labels: Record<string, string> = {
        active: 'Aktiv',
        archived: 'Archiviert',
        closed: 'Geschlossen',
        draft: 'Entwurf',
        scheduled: 'Geplant',
    };

    return labels[status] ?? status;
}
</script>

<template>
    <Head title="Übersicht" />

    <div class="mx-auto max-w-[1600px]">
        <div
            class="flex flex-col justify-between gap-5 xl:flex-row xl:items-end"
        >
            <div>
                <p
                    class="text-xs font-semibold tracking-[0.2em] text-teal-600 uppercase"
                >
                    Dashboard
                </p>
                <h1
                    class="mt-2 text-2xl font-semibold tracking-tight text-slate-900"
                >
                    Evaluationsübersicht
                </h1>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Alle zentralen Bereiche der Evaluationsplattform auf einen
                    Blick.
                </p>
            </div>
            <span
                class="w-fit rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-700"
            >
                System bereit
            </span>
        </div>

        <section
            class="mt-7 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3"
        >
            <Link
                v-for="card in statCards"
                :key="card.key"
                :href="resourceIndex(card.resource)"
                class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-200 hover:shadow-md"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            {{ card.label }}
                        </p>
                        <p
                            class="mt-3 text-3xl font-semibold tracking-tight text-slate-900"
                        >
                            {{ stats[card.key] }}
                        </p>
                        <p class="mt-2 text-sm text-slate-400">
                            {{ card.description }}
                        </p>
                    </div>
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-xl text-lg font-semibold"
                        :class="card.accent"
                    >
                        {{ card.label.charAt(0) }}
                    </span>
                </div>
                <p
                    class="mt-5 text-xs font-semibold text-teal-600 opacity-0 transition group-hover:opacity-100"
                >
                    Bereich öffnen →
                </p>
            </Link>
        </section>

        <section
            class="mt-7 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
        >
            <div
                class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5"
            >
                <div>
                    <h2 class="font-semibold text-slate-900">
                        Neueste Evaluationen
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Die zuletzt angelegten Evaluationszeiträume.
                    </p>
                </div>
                <Link
                    :href="resourceIndex('evaluationen')"
                    class="hidden text-sm font-semibold text-teal-600 hover:text-teal-700 sm:block"
                >
                    Alle anzeigen
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3.5 font-medium">Evaluation</th>
                            <th class="px-6 py-3.5 font-medium">Status</th>
                            <th class="px-6 py-3.5 font-medium">Beginn</th>
                            <th class="px-6 py-3.5 font-medium">Ende</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr
                            v-for="campaign in recentCampaigns"
                            :key="campaign.id"
                            class="hover:bg-slate-50/80"
                        >
                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ campaign.title }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="rounded-full bg-teal-50 px-2.5 py-1 text-xs font-semibold text-teal-700"
                                >
                                    {{ statusLabel(campaign.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ formatDate(campaign.starts_at) }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ formatDate(campaign.ends_at) }}
                            </td>
                        </tr>
                        <tr v-if="recentCampaigns.length === 0">
                            <td
                                colspan="4"
                                class="px-6 py-12 text-center text-slate-500"
                            >
                                Es wurden noch keine Evaluationen angelegt.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</template>
