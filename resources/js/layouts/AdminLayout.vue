<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { destroy } from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import { dashboard } from '@/routes';
import { index as questionnaireBuilder } from '@/routes/admin/questionnaire-builder';
import { index as questionEditor } from '@/routes/admin/questions';
import { index as resourceIndex } from '@/routes/admin/resources';
import type { Auth } from '@/types/auth';

type NavigationItem = {
    label: string;
    shortLabel: string;
    resource?: string;
    questionEditor?: boolean;
    questionnaireBuilder?: boolean;
};

const page = usePage<{ auth: Auth }>();
const mobileNavigationOpen = ref(false);

const navigation: NavigationItem[] = [
    { label: 'Übersicht', shortLabel: 'Ü' },
    {
        label: 'Standorte',
        shortLabel: 'S',
        resource: 'organisationseinheiten',
    },
    { label: 'Benutzer', shortLabel: 'B', resource: 'benutzer' },
    { label: 'Kurse', shortLabel: 'K', resource: 'kurse' },
    { label: 'Lehrende', shortLabel: 'L', resource: 'lehrende' },
    { label: 'Fragebögen', shortLabel: 'F', resource: 'frageboegen' },
    {
        label: 'Fragebogen-Builder',
        shortLabel: 'B',
        questionnaireBuilder: true,
    },
    { label: 'Module', shortLabel: 'M', resource: 'module' },
    { label: 'Frageneditor', shortLabel: '?', questionEditor: true },
    { label: 'Evaluationen', shortLabel: 'E', resource: 'evaluationen' },
    {
        label: 'Berichtsvorlagen',
        shortLabel: 'R',
        resource: 'berichtsvorlagen',
    },
    { label: 'Benchmarks', shortLabel: 'V', resource: 'benchmarks' },
    {
        label: 'E-Mail-Vorlagen',
        shortLabel: '@',
        resource: 'email-vorlagen',
    },
];

const currentUser = computed(() => page.props.auth.user);

function hrefFor(item: NavigationItem) {
    if (item.questionEditor) {
        return questionEditor();
    }

    if (item.questionnaireBuilder) {
        return questionnaireBuilder();
    }

    return item.resource ? resourceIndex(item.resource) : dashboard();
}

function isActive(item: NavigationItem): boolean {
    const href = hrefFor(item).url;

    return item.resource || item.questionEditor || item.questionnaireBuilder
        ? page.url.startsWith(href)
        : page.url === href;
}
</script>

<template>
    <div class="min-h-screen bg-slate-100 text-slate-900">
        <div
            v-if="mobileNavigationOpen"
            class="fixed inset-0 z-40 bg-slate-950/45 backdrop-blur-sm lg:hidden"
            @click="mobileNavigationOpen = false"
        />

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-800 bg-slate-950 text-slate-100 transition-transform duration-200 lg:translate-x-0"
            :class="
                mobileNavigationOpen ? 'translate-x-0' : '-translate-x-full'
            "
        >
            <div
                class="flex h-20 items-center justify-between border-b border-white/10 px-6"
            >
                <Link
                    :href="dashboard()"
                    class="flex items-center gap-3"
                    @click="mobileNavigationOpen = false"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-400 text-lg font-bold text-slate-950"
                    >
                        E
                    </span>
                    <span>
                        <span class="block text-lg font-semibold">eva</span>
                        <span class="block text-xs text-slate-400">
                            Evaluationsverwaltung
                        </span>
                    </span>
                </Link>

                <button
                    type="button"
                    class="rounded-lg p-2 text-slate-400 hover:bg-white/10 hover:text-white lg:hidden"
                    aria-label="Navigation schließen"
                    @click="mobileNavigationOpen = false"
                >
                    <span class="text-xl leading-none">×</span>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
                <Link
                    v-for="item in navigation"
                    :key="item.label"
                    :href="hrefFor(item)"
                    class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition"
                    :class="
                        isActive(item)
                            ? 'bg-sky-400 text-slate-950 shadow-lg shadow-sky-950/20'
                            : 'text-slate-300 hover:bg-white/8 hover:text-white'
                    "
                    @click="mobileNavigationOpen = false"
                >
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs font-bold"
                        :class="
                            isActive(item)
                                ? 'bg-slate-950/10'
                                : 'bg-white/8 text-slate-400 group-hover:text-white'
                        "
                    >
                        {{ item.shortLabel }}
                    </span>
                    {{ item.label }}
                </Link>
            </nav>

            <div class="border-t border-white/10 p-4">
                <div class="mb-3 rounded-xl bg-white/5 px-4 py-3">
                    <p class="truncate text-sm font-medium">
                        {{ currentUser?.name }}
                    </p>
                    <p class="mt-1 truncate text-xs text-slate-400">
                        {{ currentUser?.email }}
                    </p>
                </div>
                <Link
                    :href="destroy()"
                    method="delete"
                    as="button"
                    class="w-full rounded-xl border border-white/10 px-4 py-2.5 text-left text-sm font-medium text-slate-300 transition hover:border-white/20 hover:bg-white/8 hover:text-white"
                >
                    Abmelden
                </Link>
            </div>
        </aside>

        <div class="lg:pl-72">
            <header
                class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white/90 px-5 backdrop-blur sm:px-8 lg:px-10"
            >
                <button
                    type="button"
                    class="rounded-xl border border-slate-200 p-2.5 text-slate-600 hover:bg-slate-50 lg:hidden"
                    aria-label="Navigation öffnen"
                    @click="mobileNavigationOpen = true"
                >
                    <span class="block h-0.5 w-5 bg-current" />
                    <span class="mt-1.5 block h-0.5 w-5 bg-current" />
                    <span class="mt-1.5 block h-0.5 w-5 bg-current" />
                </button>

                <div class="ml-auto text-right">
                    <p class="text-sm font-medium text-slate-800">
                        {{ currentUser?.name }}
                    </p>
                    <p class="text-xs text-slate-500">
                        Angemeldet als Administration
                    </p>
                </div>
            </header>

            <main class="px-5 py-8 sm:px-8 lg:px-10 lg:py-10">
                <slot />
            </main>
        </div>
    </div>
</template>
