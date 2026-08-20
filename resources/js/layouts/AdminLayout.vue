<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { destroy } from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import AppIcon from '@/components/AppIcon.vue';
import type { IconName } from '@/components/AppIcon.vue';
import { dashboard } from '@/routes';
import { index as questionnaireBuilder } from '@/routes/admin/questionnaire-builder';
import { index as questionEditor } from '@/routes/admin/questions';
import { index as resourceIndex } from '@/routes/admin/resources';
import type { Auth } from '@/types/auth';

type NavigationItem = {
    label: string;
    shortLabel: string;
    icon: IconName;
    resource?: string;
    questionEditor?: boolean;
    questionnaireBuilder?: boolean;
};

const page = usePage<{ auth: Auth }>();
const mobileNavigationOpen = ref(false);
const profileOpen = ref(false);

const navigation: NavigationItem[] = [
    { label: 'Übersicht', shortLabel: 'Übersicht', icon: 'home' },
    {
        label: 'Standorte',
        shortLabel: 'Standorte',
        icon: 'building',
        resource: 'organisationseinheiten',
    },
    {
        label: 'Benutzer',
        shortLabel: 'Benutzer',
        icon: 'users',
        resource: 'benutzer',
    },
    {
        label: 'Lehrende',
        shortLabel: 'Lehrende',
        icon: 'graduation-cap',
        resource: 'lehrende',
    },
    { label: 'Kurse', shortLabel: 'Kurse', icon: 'layers', resource: 'kurse' },
    {
        label: 'Fragebögen',
        shortLabel: 'Fragebögen',
        icon: 'clipboard-list',
        resource: 'frageboegen',
    },
    {
        label: 'Module',
        shortLabel: 'Module',
        icon: 'puzzle',
        resource: 'module',
    },
    {
        label: 'Fragebogen-Builder',
        shortLabel: 'Builder',
        icon: 'layout',
        questionnaireBuilder: true,
    },
    {
        label: 'Frageneditor',
        shortLabel: 'Fragen',
        icon: 'help-circle',
        questionEditor: true,
    },
    {
        label: 'Evaluationen',
        shortLabel: 'Evaluationen',
        icon: 'calendar-check',
        resource: 'evaluationen',
    },
    {
        label: 'Berichtsvorlagen',
        shortLabel: 'Berichte',
        icon: 'bar-chart',
        resource: 'berichtsvorlagen',
    },
    {
        label: 'Benchmarks',
        shortLabel: 'Benchmarks',
        icon: 'scale',
        resource: 'benchmarks',
    },
    {
        label: 'E-Mail-Vorlagen',
        shortLabel: 'E-Mails',
        icon: 'mail',
        resource: 'email-vorlagen',
    },
];

const topNavigation = navigation.filter((item) => item.label !== 'Übersicht');

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
    <div class="flex min-h-screen bg-gray-100 text-slate-900">
        <aside
            class="group fixed inset-y-0 left-0 z-40 hidden w-16 flex-col gap-1 overflow-x-hidden overflow-y-auto bg-slate-950 py-4 transition-[width] duration-200 hover:w-64 lg:flex"
        >
            <Link
                v-for="item in navigation"
                :key="item.label"
                :href="hrefFor(item)"
                :title="item.label"
                class="mx-auto flex h-10 w-10 shrink-0 items-center gap-3 rounded-xl px-2.5 transition-all group-hover:mx-3 group-hover:w-auto"
                :class="
                    isActive(item)
                        ? 'bg-teal-600/15 text-teal-400'
                        : 'text-slate-500 hover:bg-white/5 hover:text-slate-200'
                "
            >
                <AppIcon :name="item.icon" class="h-5 w-5 shrink-0" />
                <span
                    class="hidden text-sm font-medium whitespace-nowrap group-hover:inline"
                >
                    {{ item.label }}
                </span>
            </Link>
        </aside>

        <div
            v-if="mobileNavigationOpen"
            class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm lg:hidden"
            @click="mobileNavigationOpen = false"
        />

        <aside
            v-if="mobileNavigationOpen"
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-950 text-slate-100 lg:hidden"
        >
            <div
                class="flex h-16 items-center justify-between border-b border-white/10 px-5"
            >
                <Link
                    :href="dashboard()"
                    class="flex items-center gap-2.5"
                    @click="mobileNavigationOpen = false"
                >
                    <img
                        src="/images/logo-mark.png"
                        alt="eva"
                        class="h-8 w-8 shrink-0 rounded-full"
                    />
                    <span class="text-lg font-semibold">eva</span>
                </Link>
                <button
                    type="button"
                    class="rounded-lg p-2 text-slate-400 hover:bg-white/10 hover:text-white"
                    aria-label="Navigation schließen"
                    @click="mobileNavigationOpen = false"
                >
                    <span class="text-xl leading-none">×</span>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <Link
                    v-for="item in navigation"
                    :key="item.label"
                    :href="hrefFor(item)"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition"
                    :class="
                        isActive(item)
                            ? 'bg-teal-600/15 text-teal-400'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white'
                    "
                    @click="mobileNavigationOpen = false"
                >
                    <AppIcon :name="item.icon" class="h-5 w-5 shrink-0" />
                    {{ item.label }}
                </Link>
            </nav>

            <div class="border-t border-white/10 p-3">
                <Link
                    :href="destroy()"
                    method="delete"
                    as="button"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm font-medium text-slate-300 transition hover:bg-white/5 hover:text-white"
                >
                    <AppIcon name="logout" class="h-5 w-5 shrink-0" />
                    Abmelden
                </Link>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col lg:pl-16">
            <header
                class="sticky top-0 z-30 border-b border-slate-200 bg-white"
            >
                <div class="flex h-16 items-center gap-4 px-4 sm:px-6">
                    <button
                        type="button"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-50 lg:hidden"
                        aria-label="Navigation öffnen"
                        @click="mobileNavigationOpen = true"
                    >
                        <span class="block h-0.5 w-5 bg-current" />
                        <span class="mt-1.5 block h-0.5 w-5 bg-current" />
                        <span class="mt-1.5 block h-0.5 w-5 bg-current" />
                    </button>

                    <Link
                        :href="dashboard()"
                        class="hidden shrink-0 items-center gap-2.5 lg:flex"
                    >
                        <img
                            src="/images/logo-mark.png"
                            alt="eva"
                            class="h-8 w-8 shrink-0 rounded-full"
                        />
                        <span
                            class="text-lg font-semibold tracking-tight text-slate-900"
                        >
                            eva
                        </span>
                    </Link>

                    <nav
                        class="hidden flex-1 items-center gap-5 overflow-x-auto pl-1 text-xs font-semibold tracking-wide whitespace-nowrap text-slate-500 uppercase md:flex"
                    >
                        <Link
                            v-for="item in topNavigation"
                            :key="item.label"
                            :href="hrefFor(item)"
                            class="shrink-0 border-b-2 py-[19px] transition"
                            :class="
                                isActive(item)
                                    ? 'border-teal-600 text-slate-900'
                                    : 'border-transparent hover:text-slate-700'
                            "
                        >
                            {{ item.shortLabel }}
                        </Link>
                    </nav>

                    <div class="ml-auto flex shrink-0 items-center gap-1">
                        <button
                            type="button"
                            class="hidden items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50 sm:flex"
                        >
                            <AppIcon name="search" class="h-4 w-4" />
                            Suche
                        </button>
                        <button
                            type="button"
                            class="hidden items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50 sm:flex"
                        >
                            <AppIcon
                                name="help-circle"
                                class="h-4 w-4 text-teal-600"
                            />
                            Hilfe
                        </button>
                        <button
                            type="button"
                            class="rounded-full p-2.5 text-slate-400 hover:bg-slate-50 hover:text-slate-600"
                            aria-label="Benachrichtigungen"
                        >
                            <AppIcon name="bell" class="h-5 w-5" />
                        </button>

                        <div class="relative">
                            <button
                                type="button"
                                class="flex items-center gap-2 rounded-full p-1 text-slate-500 hover:bg-slate-50"
                                aria-label="Konto"
                                @click="profileOpen = !profileOpen"
                            >
                                <AppIcon name="user-circle" class="h-7 w-7" />
                            </button>

                            <div
                                v-if="profileOpen"
                                class="fixed inset-0 z-40"
                                @click="profileOpen = false"
                            />

                            <div
                                v-if="profileOpen"
                                class="absolute right-0 z-50 mt-2 w-64 rounded-xl border border-slate-200 bg-white py-2 shadow-lg"
                            >
                                <div
                                    class="border-b border-slate-100 px-4 py-3"
                                >
                                    <p
                                        class="truncate text-sm font-semibold text-slate-900"
                                    >
                                        {{ currentUser?.name }}
                                    </p>
                                    <p
                                        class="mt-0.5 truncate text-xs text-slate-500"
                                    >
                                        {{ currentUser?.email }}
                                    </p>
                                </div>
                                <Link
                                    :href="destroy()"
                                    method="delete"
                                    as="button"
                                    class="flex w-full items-center gap-2.5 px-4 py-2.5 text-left text-sm text-slate-600 hover:bg-slate-50"
                                >
                                    <AppIcon
                                        name="logout"
                                        class="h-4 w-4 text-slate-400"
                                    />
                                    Abmelden
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8">
                <slot />
            </main>

            <footer
                class="border-t border-slate-200 px-6 py-4 text-center text-xs text-slate-400"
            >
                eva Evaluationsplattform · Angemeldet als
                {{ currentUser?.name }}
            </footer>
        </div>
    </div>
</template>
