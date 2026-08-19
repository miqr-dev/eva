<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';

import { store } from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import AppIcon from '@/components/AppIcon.vue';

defineOptions({ inheritAttrs: false });
</script>

<template>
    <Head title="Anmelden" />

    <main
        class="flex min-h-screen items-center justify-center bg-gray-100 px-5 py-10 sm:px-6"
    >
        <div class="w-full max-w-sm">
            <div class="mb-8 flex items-center justify-center gap-2.5">
                <span
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-600 text-white"
                >
                    <AppIcon name="ring" class="h-4.5 w-4.5" />
                </span>
                <span
                    class="text-xl font-semibold tracking-tight text-slate-900"
                >
                    eva
                </span>
            </div>

            <section
                class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm"
            >
                <div class="text-center">
                    <h2 class="text-xl font-semibold text-slate-900">
                        Willkommen zurück
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Melden Sie sich mit Ihrem Administrationskonto an.
                    </p>
                </div>

                <Form
                    v-bind="store.form()"
                    class="mt-8 flex flex-col gap-5"
                    #default="{ errors, processing }"
                >
                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-medium text-slate-700">
                            E-Mail-Adresse
                        </span>
                        <input
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            autofocus
                            class="h-11 rounded-lg border border-slate-300 bg-white px-4 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        />
                        <span v-if="errors.email" class="text-sm text-red-600">
                            {{ errors.email }}
                        </span>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-medium text-slate-700">
                            Passwort
                        </span>
                        <input
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="h-11 rounded-lg border border-slate-300 bg-white px-4 text-sm transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                        />
                        <span
                            v-if="errors.password"
                            class="text-sm text-red-600"
                        >
                            {{ errors.password }}
                        </span>
                    </label>

                    <label
                        class="flex items-center gap-3 text-sm text-slate-600"
                    >
                        <input
                            name="remember"
                            type="checkbox"
                            value="1"
                            class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500"
                        />
                        Angemeldet bleiben
                    </label>

                    <button
                        type="submit"
                        :disabled="processing"
                        class="mt-1 h-11 rounded-lg bg-teal-700 px-5 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{ processing ? 'Anmeldung läuft...' : 'Anmelden' }}
                    </button>
                </Form>
            </section>

            <p class="mt-6 text-center text-xs text-slate-400">
                eva Evaluationsplattform
            </p>
        </div>
    </main>
</template>
