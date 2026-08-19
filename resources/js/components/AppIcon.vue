<script setup lang="ts">
import { computed } from 'vue';

export type IconName =
    | 'home'
    | 'building'
    | 'users'
    | 'graduation-cap'
    | 'layers'
    | 'clipboard-list'
    | 'puzzle'
    | 'layout'
    | 'help-circle'
    | 'calendar-check'
    | 'bar-chart'
    | 'scale'
    | 'mail'
    | 'search'
    | 'bell'
    | 'user-circle'
    | 'pencil'
    | 'x-circle'
    | 'ring'
    | 'chevron-right'
    | 'logout'
    | 'plus';

const props = withDefaults(defineProps<{ name: IconName; class?: string }>(), {
    class: 'h-5 w-5',
});

const paths: Record<IconName, string[]> = {
    home: ['M4 11.5 12 4l8 7.5', 'M6 10v9h12v-9', 'M10 19v-5h4v5'],
    building: [
        'M6 21V5a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v16',
        'M16 21V11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v10',
        'M6 21h14',
        'M8.5 7.5h1M11.5 7.5h1M8.5 11h1M11.5 11h1M8.5 14.5h1M11.5 14.5h1',
    ],
    users: [
        'M9 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z',
        'M3.5 20a5.5 5.5 0 0 1 11 0',
        'M16.5 8.5a2.5 2.5 0 1 0 0-5',
        'M15 20a5 5 0 0 1 5.5-4.97',
    ],
    'graduation-cap': [
        'm3 9 9-4.5L21 9l-9 4.5L3 9Z',
        'M7 11.5V16c0 1.1 2.24 2 5 2s5-.9 5-2v-4.5',
        'M21 9v6',
    ],
    layers: [
        'm3 9 9-4.5L21 9l-9 4.5L3 9Z',
        'm3 12.5 9 4.5 9-4.5',
        'm3 16 9 4.5 9-4.5',
    ],
    'clipboard-list': [
        'M9 4h6a1 1 0 0 1 1 1v1H8V5a1 1 0 0 1 1-1Z',
        'M6 6h12v14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V6Z',
        'M9 11h6M9 14h6M9 17h3',
    ],
    puzzle: [
        'M9 4.5h4a1 1 0 0 1 1 1v2.3a1.7 1.7 0 0 0 2.9 1.2 1.7 1.7 0 0 1 2.9 1.2v3.3a1 1 0 0 1-1 1h-2.3a1.7 1.7 0 0 0-1.2 2.9 1.7 1.7 0 0 1-1.2 2.9H11a1 1 0 0 1-1-1v-2.3a1.7 1.7 0 0 0-2.9-1.2A1.7 1.7 0 0 1 4.5 14v-3.9a1 1 0 0 1 1-1h2.3A1.7 1.7 0 0 0 9 6.9V4.5Z',
    ],
    layout: [
        'M4 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v3H4V5Z',
        'M4 10.5h6V19H5a1 1 0 0 1-1-1v-7.5Z',
        'M12 10.5h8V18a1 1 0 0 1-1 1h-7v-8.5Z',
    ],
    'help-circle': [
        'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z',
        'M9.5 9.3a2.5 2.5 0 1 1 3.7 2.2c-.9.5-1.2 1-1.2 1.9',
        'M12 16.5h.01',
    ],
    'calendar-check': [
        'M5 5.5A1.5 1.5 0 0 1 6.5 4h11A1.5 1.5 0 0 1 19 5.5V19a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5.5Z',
        'M5 9.5h14',
        'M8 3v3M16 3v3',
        'm9 14 2 2 4-4',
    ],
    'bar-chart': ['M4 20V10M4 20h16M9 20V6M14 20v-8M19 20V4'],
    scale: [
        'M12 3v18',
        'M12 5.5 6 8',
        'M12 5.5 18 8',
        'm3 9 3-1 3 1-3 7-3-7Z',
        'm15 9 3-1 3 1-3 7-3-7Z',
        'M5 20h14',
    ],
    mail: [
        'M4 6.5A1.5 1.5 0 0 1 5.5 5h13A1.5 1.5 0 0 1 20 6.5v11a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 17.5v-11Z',
        'm4.5 6 7.5 6.5L19.5 6',
    ],
    search: ['M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14Z', 'm20 20-4.6-4.6'],
    bell: [
        'M6 10a6 6 0 1 1 12 0c0 4 1.5 5.5 1.5 5.5H4.5S6 14 6 10Z',
        'M9.5 18a2.5 2.5 0 0 0 5 0',
    ],
    'user-circle': [
        'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z',
        'M8.5 18a3.75 3.75 0 0 1 7 0',
        'M12 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z',
    ],
    pencil: ['m14.5 5.5 4 4L8 20H4v-4L14.5 5.5Z', 'm12.5 7.5 4 4'],
    'x-circle': [
        'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z',
        'm9.5 9.5 5 5m0-5-5 5',
    ],
    ring: ['M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z'],
    'chevron-right': ['m9.5 6 6 6-6 6'],
    logout: [
        'M9 4.5H6a1.5 1.5 0 0 0-1.5 1.5v12A1.5 1.5 0 0 0 6 19.5h3',
        'M14 8l4 4-4 4',
        'M18 12H9.5',
    ],
    plus: ['M12 5v14M5 12h14'],
};

const items = computed(() => paths[props.name]);
</script>

<template>
    <svg
        :class="props.class"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.75"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
    >
        <path v-for="(d, index) in items" :key="index" :d="d" />
    </svg>
</template>
