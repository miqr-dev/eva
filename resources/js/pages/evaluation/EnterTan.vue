<script setup lang="ts">
import { Head, useHttp } from '@inertiajs/vue3';

import { store as submitTan } from '@/routes/evaluation/tan';

const tanForm = useHttp<{ tan_code: string }, unknown>({
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

async function submit(): Promise<void> {
    await tanForm.submit(submitTan());
}
</script>

<template>
    <Head title="TAN eingeben" />

    <main
        class="flex min-h-screen items-center justify-center bg-slate-950 px-5 py-10 text-slate-100"
    >
        <section
            class="w-full max-w-xl rounded-3xl bg-white p-8 text-slate-900 shadow-2xl"
        >
            <p
                class="text-xs font-semibold tracking-[0.22em] text-sky-600 uppercase"
            >
                Evaluation
            </p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight">
                TAN eingeben
            </h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Geben Sie Ihre TAN ein, um die Evaluation anonym zu starten. Es
                wird kein Benutzerkonto benötigt.
            </p>

            <form class="mt-7 space-y-5" @submit.prevent="submit">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">
                        TAN
                    </span>
                    <input
                        v-model="tanForm.tan_code"
                        required
                        autofocus
                        autocomplete="one-time-code"
                        class="h-12 w-full rounded-xl border border-slate-200 px-4 text-lg font-semibold tracking-[0.16em] uppercase transition outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
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
                    class="h-12 w-full rounded-xl bg-slate-950 px-5 text-sm font-semibold text-white transition hover:bg-sky-600 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    {{
                        tanForm.processing
                            ? 'TAN wird geprüft...'
                            : 'Evaluation starten'
                    }}
                </button>
            </form>
        </section>
    </main>
</template>
