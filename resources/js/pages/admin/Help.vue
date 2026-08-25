<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppIcon from '@/components/AppIcon.vue';
import type { IconName } from '@/components/AppIcon.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout, inheritAttrs: false });

type Phase = 'stamm' | 'fragen' | 'fbogen' | 'eval';

type Step = {
    number: number;
    phase: Phase;
    icon: IconName;
    title: string;
    route: string;
    actions: string[];
};

const phases: Record<Phase, { label: string; badge: string; border: string; icon: string }> = {
    stamm: {
        label: 'Stammdaten',
        badge: 'bg-slate-100 text-slate-700',
        border: 'border-t-slate-500',
        icon: 'text-slate-500',
    },
    fragen: {
        label: 'Fragen',
        badge: 'bg-indigo-50 text-indigo-700',
        border: 'border-t-indigo-500',
        icon: 'text-indigo-500',
    },
    fbogen: {
        label: 'Fragebogen',
        badge: 'bg-emerald-50 text-emerald-700',
        border: 'border-t-emerald-500',
        icon: 'text-emerald-500',
    },
    eval: {
        label: 'Evaluation',
        badge: 'bg-violet-50 text-violet-700',
        border: 'border-t-violet-500',
        icon: 'text-violet-500',
    },
};

const steps: Step[] = [
    {
        number: 1,
        phase: 'stamm',
        icon: 'building',
        title: 'Standort anlegen',
        route: 'Verwaltung → Standorte',
        actions: ['Neuer Eintrag → Name', 'Speichern'],
    },
    {
        number: 2,
        phase: 'stamm',
        icon: 'users',
        title: 'Benutzer, Lehrende, Kurse',
        route: 'Verwaltung → Benutzer / Lehrende / Kurse',
        actions: ['Je Bereich: Neuer Eintrag ausfüllen', 'Speichern'],
    },
    {
        number: 3,
        phase: 'fragen',
        icon: 'puzzle',
        title: 'Modul anlegen',
        route: 'Verwaltung → Module',
        actions: [
            'Neuer Eintrag → Name, Beschreibung',
            'Speichern (legt Entwurf Version 1 an)',
        ],
    },
    {
        number: 4,
        phase: 'fragen',
        icon: 'help-circle',
        title: 'Fragen erfassen',
        route: 'Verwaltung → Frageneditor',
        actions: [
            'Modul wählen → Abschnitt(e) und Fragen anlegen',
            'Modulversion veröffentlichen',
        ],
    },
    {
        number: 5,
        phase: 'fbogen',
        icon: 'clipboard-list',
        title: 'Fragebogen-Vorlage anlegen',
        route: 'Verwaltung → Fragebögen',
        actions: ['Neue Vorlage → Name, Beschreibung', 'Speichern'],
    },
    {
        number: 6,
        phase: 'fbogen',
        icon: 'layout',
        title: 'Fragebogen bauen',
        route: 'Verwaltung → Fragebogen-Builder',
        actions: [
            'Entwurfsversion anlegen',
            'Module verknüpfen, sortieren',
            'Version veröffentlichen',
        ],
    },
    {
        number: 7,
        phase: 'eval',
        icon: 'calendar-check',
        title: 'Evaluation anlegen',
        route: 'Verwaltung → Evaluationen',
        actions: [
            'Standort, Kurs und veröffentlichte Fragebogen-Version wählen',
            'Titel, Zeitraum → Speichern',
        ],
    },
    {
        number: 8,
        phase: 'eval',
        icon: 'ring',
        title: 'TANs generieren',
        route: 'Evaluation → TANs',
        actions: [
            'Anzahl eingeben → Erzeugen',
            'Codes einmalig im Klartext sichern',
        ],
    },
];
</script>

<template>
    <Head title="Hilfe" />

    <div class="mx-auto max-w-[1600px]">
        <div>
            <p
                class="text-xs font-semibold tracking-[0.2em] text-teal-600 uppercase"
            >
                Hilfe
            </p>
            <h1
                class="mt-2 text-2xl font-semibold tracking-tight text-slate-900"
            >
                Ablauf einer Evaluation: von Standort bis TAN
            </h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                Die acht Schritte, mit denen eine Evaluation vorbereitet wird
                – in derselben Reihenfolge, in der die Punkte auch in
                Sidebar und Topbar erscheinen.
            </p>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <span
                v-for="(phase, key) in phases"
                :key="key"
                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                :class="phase.badge"
            >
                {{ phase.label }}
            </span>
        </div>

        <div
            class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4"
        >
            <div
                v-for="step in steps"
                :key="step.number"
                class="flex flex-col gap-3 rounded-2xl border border-slate-200 border-t-4 bg-white p-5 shadow-sm"
                :class="phases[step.phase].border"
            >
                <div class="flex items-center gap-2.5">
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-sm font-bold"
                        :class="phases[step.phase].badge"
                    >
                        {{ step.number }}
                    </span>
                    <AppIcon
                        :name="step.icon"
                        class="h-4 w-4 shrink-0"
                        :class="phases[step.phase].icon"
                    />
                    <span
                        class="text-[11px] font-semibold tracking-wide text-slate-400 uppercase"
                    >
                        {{ phases[step.phase].label }}
                    </span>
                </div>

                <h2 class="text-base font-semibold text-slate-900">
                    {{ step.title }}
                </h2>

                <p
                    class="border-b border-slate-100 pb-2.5 font-mono text-xs text-slate-400"
                >
                    {{ step.route }}
                </p>

                <ul class="space-y-1.5 text-sm text-slate-600">
                    <li
                        v-for="action in step.actions"
                        :key="action"
                        class="flex gap-2"
                    >
                        <span
                            class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-slate-300"
                        />
                        {{ action }}
                    </li>
                </ul>
            </div>
        </div>

        <p class="mt-6 text-xs text-slate-400 italic">
            Hinweis: Die Reihenfolge der Punkte in Sidebar und Topbar folgt
            exakt dieser Prozessfolge.
        </p>
    </div>
</template>
