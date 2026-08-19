<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

import AppIcon from '@/components/AppIcon.vue';
import { store as submitTan } from '@/routes/evaluation/tan';

const tanForm = useForm({
    tan_code: '',
});

function errorFor(name: string): string | null {
    const errors = tanForm.errors as Record<string, unknown>;
    const error = errors[name];

    if (!error) {
        return null;
    }

    return Array.isArray(error) ? String(error[0]) : String(error);
}

function submit(): void {
    tanForm.submit(submitTan());
}
</script>

<template>
    <Head title="TAN eingeben" />

    <main
        class="flex min-h-screen items-center justify-center bg-gray-100 px-5 py-10"
    >
        <div class="w-full max-w-md">
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
                <p
                    class="text-xs font-semibold tracking-[0.22em] text-teal-600 uppercase"
                >
                    Evaluation
                </p>
                <h1
                    class="mt-3 text-2xl font-semibold tracking-tight text-slate-900"
                >
                    TAN eingeben
                </h1>
                <p class="mt-3 text-sm leading-6 text-slate-500">
                    Geben Sie Ihre TAN ein, um die Evaluation anonym zu starten.
                    Es wird kein Benutzerkonto benötigt.
                </p>

                <form class="mt-7 space-y-5" @submit.prevent="submit">
                    <label class="block">
                        <span
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            TAN
                        </span>
                        <input
                            v-model="tanForm.tan_code"
                            required
                            autofocus
                            autocomplete="one-time-code"
                            class="h-12 w-full rounded-lg border border-slate-300 px-4 text-lg font-semibold tracking-[0.16em] uppercase transition outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                            placeholder="A8K9-PQ22"
                        />
                        <span
                            v-if="errorFor('tan_code')"
                            class="mt-2 block text-sm font-medium text-red-600"
                        >
                            {{ errorFor('tan_code') }}
                        </span>
                    </label>

                    <button
                        type="submit"
                        :disabled="tanForm.processing"
                        class="h-12 w-full rounded-lg bg-teal-700 px-5 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{
                            tanForm.processing
                                ? 'TAN wird geprüft...'
                                : 'Evaluation starten'
                        }}
                    </button>
                </form>
            </section>

            <p class="mt-6 text-center text-xs text-slate-400">
                eva Evaluationsplattform
            </p>
        </div>
    </main>
</template>
