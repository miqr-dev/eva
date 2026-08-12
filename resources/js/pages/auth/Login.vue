<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';

import { store } from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';

defineOptions({ inheritAttrs: false });
</script>

<template>
    <Head title="Anmelden" />

    <main
        class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 px-5 py-10 text-slate-100 sm:px-6"
    >
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.2),transparent_34%),radial-gradient(circle_at_bottom_right,rgba(99,102,241,0.18),transparent_40%)]"
        />

        <div
            class="relative grid w-full max-w-5xl overflow-hidden rounded-3xl border border-white/10 bg-slate-900/85 shadow-2xl shadow-sky-950/40 backdrop-blur lg:grid-cols-[1.08fr_0.92fr]"
        >
            <section class="hidden flex-col justify-between p-12 lg:flex">
                <div>
                    <div
                        class="mb-10 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-400 font-bold text-slate-950"
                    >
                        E
                    </div>
                    <p
                        class="mb-3 text-sm font-semibold tracking-[0.24em] text-sky-300 uppercase"
                    >
                        Evaluationsplattform
                    </p>
                    <h1
                        class="max-w-md text-4xl leading-tight font-semibold text-white"
                    >
                        Kursfeedback zentral erfassen und wirksam auswerten.
                    </h1>
                    <p class="mt-5 max-w-md leading-7 text-slate-400">
                        Verwalten Sie Fragebögen, Evaluationen, Lehrende,
                        Berichte, Benchmarks und die Kommunikation mit
                        Teilnehmenden an einem Ort.
                    </p>
                </div>

                <p class="text-sm text-slate-500">
                    Laravel 13 · Inertia 3 · Vue 3
                </p>
            </section>

            <section class="bg-white p-7 text-slate-900 sm:p-10 lg:p-12">
                <div class="mx-auto max-w-sm">
                    <div
                        class="mb-8 flex h-11 w-11 items-center justify-center rounded-xl bg-sky-500 font-bold text-white lg:hidden"
                    >
                        E
                    </div>

                    <p
                        class="text-sm font-semibold tracking-[0.18em] text-sky-600 uppercase"
                    >
                        eva
                    </p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight">
                        Willkommen zurück
                    </h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Melden Sie sich mit Ihrem Administrationskonto an.
                    </p>

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
                                class="h-12 rounded-xl border border-slate-200 bg-white px-4 text-sm transition outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                            />
                            <span
                                v-if="errors.email"
                                class="text-sm text-red-600"
                            >
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
                                class="h-12 rounded-xl border border-slate-200 bg-white px-4 text-sm transition outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
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
                                class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                            />
                            Angemeldet bleiben
                        </label>

                        <button
                            type="submit"
                            :disabled="processing"
                            class="mt-1 h-12 rounded-xl bg-slate-950 px-5 text-sm font-semibold text-white transition hover:bg-sky-600 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {{ processing ? 'Anmeldung läuft...' : 'Anmelden' }}
                        </button>
                    </Form>
                </div>
            </section>
        </div>
    </main>
</template>
